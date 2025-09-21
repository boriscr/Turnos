<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentStoreRequest;
use App\Http\Requests\AppointmentUpdateRequest;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\Specialty;
use App\Models\Doctor;
use App\Models\AvailableAppointment;
use App\Models\AppointmentHistory;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::select('id', 'name', 'address', 'specialty_id', 'doctor_id', 'shift', 'status')
            ->with(['specialty' => function ($query) {
                $query->select('id', 'name');
            }, 'doctor' => function ($query) {
                $query->select('id', 'name');
            }])
            ->paginate(10);
        return view('appointments/index', compact('appointments'));
    }
    public function create()
    {
        $specialties = Specialty::all();
        return view('appointments/create', compact('specialties'));
    }
    public function store(AppointmentStoreRequest $request)
    {
        $timeSlots = json_decode($request->available_time_slots, true);        // Decodificar available_dates seleccionadas
        $available_dates = json_decode($request->selected_dates, true);

        if (empty($available_dates)) {
            Log::info('Fechas recibidas:', ['available_dates' => $request->selected_dates]);
            Log::info('Decodificadas:', ['decoded' => json_decode($request->selected_dates, true)]);

            return back()->with('error', 'Debe seleccionar al menos una fecha');
        }

        $appointment = Appointment::create([
            ...$request->validated(),
            'create_by' => Auth::id(),
            'update_by' => Auth::id(),
            'available_time_slots' => $timeSlots,
            'available_dates' => $available_dates,
        ]);

        // Crear los appointments disponibles para cada date

        // Crear disponibilidad por date y horario
        foreach ($available_dates as $date) {

            if ($request->available_time_slots) {
                // Caso CON horarios (vienen en JSON)
                $horarios = json_decode($request->available_time_slots, true);

                foreach ($horarios as $time) {
                    AvailableAppointment::create([
                        'appointment_id' => $appointment->id,
                        'doctor_id' => $request->doctor_id,
                        'specialty_id' => $request->specialty_id,
                        'date' => $date,
                        'time' => $time,
                        'available_spots' => 1, // 1 cupo por horario individual
                    ]);
                }
            } else {
                // Caso SIN horarios (por día)
                AvailableAppointment::create([
                    'appointment_id' => $appointment->id,
                    'doctor_id' => $request->doctor_id,
                    'specialty_id' => $request->specialty_id,
                    'date' => $date,
                    'time' => $request->start_time, // sin time específica
                    'available_spots' => $request->number_of_reservations, // cupo total por date
                ]);
            }
        }
        session()->flash('success', [
            'title' => 'Creado!',
            'text' => 'El turno ha sido creado correctamente.',
            'icon' => 'success'
        ]);
        return redirect()->route('appointments.index');
    }

    public function show($id)
    {
        $appointment = Appointment::findOrFail($id);
        return view('appointments.show', compact('appointment'));
    }


    //Editar Appointment
    public function edit($id)
    {
        $appointment = Appointment::with(['specialty', 'doctor', 'disponibilidades'])->findOrFail($id);
        $specialties = Specialty::where('status', 1)->get();

        // Procesar available_time_slots para la vista
        $available_time_slots = $appointment->available_time_slots;

        // Verificar si es un array JSON (multi_slot)
        $availableTimeSlotsValue = is_string($appointment->available_time_slots) ? $appointment->available_time_slots : '';
        $horariosArray = json_decode($availableTimeSlotsValue, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($horariosArray)) {
            $available_time_slots = json_encode($horariosArray);
        }

        //dd($appointment,$specialties,json_encode($appointment->available_dates),$available_time_slots);
        return view('appointments.edit', [
            'appointment' => $appointment,
            'specialties' => $specialties,
            'available_dates' => json_encode($appointment->available_dates),
            'availableTimeSlots' => $available_time_slots
        ]);
    }


    public function update(AppointmentUpdateRequest $request, $id)
    {
        // Decodificar available_dates seleccionadas
        $timeSlots = json_decode($request->available_time_slots, true);        // Decodificar available_dates seleccionadas
        $available_dates = json_decode($request->selected_dates, true);
        if (empty($available_dates)) {
            return back()->with('error', 'Debe seleccionar al menos una fecha')->withInput();
        }

        // Determinar el tipo de appointment basado en los radio buttons
        $tipoAppointment = $request->appointment_type ?? 'single_slot';

        // Validación específica para horarios
        if ($tipoAppointment === 'multi_slot') {
            if (empty($request->available_time_slots) || !json_decode($request->available_time_slots)) {
                return back()->withErrors(['available_time_slots' => 'Para reservas por hora, debe seleccionar al menos un horario'])->withInput();
            }
        }

        // Obtener el appointment a actualizar
        $appointment = Appointment::findOrFail($id);
        $appointment->update([
            ...$request->validated(), // Spread operator para los datos validados
            'available_time_slots' => $timeSlots ? $timeSlots : null,
            'available_dates' => $available_dates,
            'status' => $request->status ?? $appointment->status,
            'update_by' => Auth::id(), // Asignar el usuario que actualiza
        ]);

        // Preparar nuevas combinaciones fecha-hora basadas en el tipo de appointment
        $nuevasCombinaciones = [];

        foreach ($available_dates as $date) {
            if ($tipoAppointment === 'multi_slot' && $request->available_time_slots) {
                // Appointment con división horaria
                $horarios = json_decode($request->available_time_slots, true);
                foreach ($horarios as $time) {
                    // Normalizar formato de hora
                    $horaNormalizada = \Carbon\Carbon::parse($time)->format('H:i:s');
                    $nuevasCombinaciones[] = [
                        'date' => $date,
                        'time' => $time,
                        'key' => $date . '_' . $horaNormalizada
                    ];
                }
            } else {
                // Appointment sin horarios específicos
                $horaNormalizada = $request->start_time ? \Carbon\Carbon::parse($request->start_time)->format('H:i:s') : '';
                $nuevasCombinaciones[] = [
                    'date' => $date,
                    'time' => $request->start_time ?? null,
                    'key' => $date . '_' . $horaNormalizada
                ];
            }
        }

        // Enfoque más robusto: eliminar todas las disponibilidades existentes y recrear
        // Solo si no tienen reservas, o manejar las reservas de manera inteligente

        // Primero, obtener todas las disponibilidades con reservas
        $disponibilidadesConReservas = AvailableAppointment::where('appointment_id', $appointment->id)
            ->where('reserved_spots', '>', 0)
            ->get();

        // Eliminar solo las disponibilidades sin reservas
        AvailableAppointment::where('appointment_id', $appointment->id)
            ->where('reserved_spots', 0)
            ->delete();

        // Procesar cada nueva combinación
        foreach ($nuevasCombinaciones as $combinacion) {
            $availableSpots = ($tipoAppointment === 'single_slot') ? intval($request->number_of_reservations) : 1;

            // Buscar si ya existe una disponibilidad con reservas para esta combinación
            $existenteConReservas = $disponibilidadesConReservas->first(function ($item) use ($combinacion) {
                $fechaExistente = \Carbon\Carbon::parse($item->date)->format('Y-m-d');
                $horaExistente = $item->time ? \Carbon\Carbon::parse($item->time)->format('H:i:s') : '';
                $horaComparacion = $combinacion['time'] ? \Carbon\Carbon::parse($combinacion['time'])->format('H:i:s') : '';

                return $fechaExistente === $combinacion['date'] && $horaExistente === $horaComparacion;
            });

            if ($existenteConReservas) {
                // Actualizar la existente que tiene reservas
                $reservedSpots = $existenteConReservas->reserved_spots;
                $newAvailableSpots = max($availableSpots - $reservedSpots, 0);

                $existenteConReservas->update([
                    'doctor_id' => $request->doctor_id,
                    'available_spots' => $newAvailableSpots,
                    'specialty_id' => $request->specialty_id,

                ]);
            } else {
                // Crear nueva disponibilidad
                AvailableAppointment::create([
                    'appointment_id' => $appointment->id,
                    'doctor_id' => $request->doctor_id,
                    'specialty_id' => $request->specialty_id,
                    'date' => $combinacion['date'],
                    'time' => $combinacion['time'],
                    'available_spots' => $availableSpots,
                    'reserved_spots' => 0
                ]);
            }
        }

        // Eliminar disponibilidades con reservas que ya no están en las nuevas combinaciones
        foreach ($disponibilidadesConReservas as $disponibilidad) {
            $fechaDisponibilidad = \Carbon\Carbon::parse($disponibilidad->date)->format('Y-m-d');
            $horaDisponibilidad = $disponibilidad->time ? \Carbon\Carbon::parse($disponibilidad->time)->format('H:i:s') : '';

            $existeEnNuevas = collect($nuevasCombinaciones)->first(function ($nueva) use ($fechaDisponibilidad, $horaDisponibilidad) {
                $horaNueva = $nueva['time'] ? \Carbon\Carbon::parse($nueva['time'])->format('H:i:s') : '';
                return $nueva['date'] === $fechaDisponibilidad && $horaNueva === $horaDisponibilidad;
            });

            // Esta disponibilidad ya no está en las nuevas, pero tiene reservas
            if (!$existeEnNuevas) {
                // Obtener las reservas asociadas y actualizar su historial a "deleted_by_admin"
                $reservations = Reservation::where('available_appointment_id', $disponibilidad->id)
                    ->get();
                foreach ($reservations as $reservation) {
                    // Verificar si ya existe un historial para esta reservación
                    $appointmentHistory = AppointmentHistory::where('reservation_id', $reservation->id)
                        ->first();
                    if ($appointmentHistory) {
                        // Actualizar status del historial
                        $appointmentHistory->update([
                            'status' => 'deleted_by_admin',
                            'cancelled_by' => Auth::id(),
                            'cancelled_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
                Log::warning("Disponibilidad eliminada tenía reservas: {$fechaDisponibilidad} {$horaDisponibilidad}");
                $disponibilidad->delete();
            }
        }

        session()->flash('success', [
            'title' => 'Actualizado!',
            'text' => 'El turno ha sido actualizado correctamente.',
            'icon' => 'success'
        ]);

        return redirect()->route('appointments.index');
    }

    function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->disponibilidades()->delete(); // Eliminar disponibilidades asociadas
        $appointment->delete(); // Eliminar el appointment
        session()->flash('success', [
            'title' => 'Eliminado!',
            'text' => 'El turno ha sido eliminado correctamente.',
            'icon' => 'success'
        ]);
        return redirect()->route('appointments.index');
    }

    //Usado desde el formulario Create Appointments
    public function getPorEspecialidad($id)
    {
        try {
            $doctors = Doctor::where('specialty_id', $id)
                ->where('status', 1)
                ->get();

            return response()->json($doctors);
        } catch (\Exception $e) {
            Log::error('Error al obtener doctores por especialidad', ['specialty_id' => $id, 'error' => $e->getMessage()]);
            return response()->json([], 500);
        }
    }
}
