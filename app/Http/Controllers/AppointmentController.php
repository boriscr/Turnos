<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentStoreRequest;
use App\Http\Requests\AppointmentUpdateRequest;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\Specialty;
use App\Models\Doctor;
use App\Models\AvailableAppointment;
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
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
        return view('appointments/index', compact('appointments'));
    }
    public function create()
    {
        $specialties = Specialty::select('id', 'name', 'status')
            ->where('status', 1)
            ->orderBy('name')
            ->get();
        return view('appointments/create', compact('specialties'));
    }

    //Usado desde el formulario Create Appointments para cargar especialidad seleccionada (fetch API) segun los doctores
    public function getBySpecialty($id)
    {
        try {
            $doctors = Doctor::where('specialty_id', $id)
                ->select('id', 'name', 'surname')
                ->where('status', 1)
                ->orderBy('name')
                ->get();

            return response()->json($doctors);
        } catch (\Exception $e) {
            Log::error('Error al obtener doctores por especialidad', ['specialty_id' => $id, 'error' => $e->getMessage()]);
            return response()->json([], 500);
        }
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
        // Crear el appointment solo si las especialidades y doctor tienen el status activo
        $specialty = Specialty::where('id', $request->specialty_id)->where('status', 1)->first();
        $doctor = Doctor::where('id', $request->doctor_id)->where('status', 1)->first();
        if (!$specialty || !$doctor) {
            session()->flash('error', [
                'title' => 'Inactivos!',
                'text' => 'La especialidad o el médico seleccionado no están activos.',
                'icon' => 'error'
            ]);
            return back()->withInput();
        }
        // Crear el appointment
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
        $timeSlots = json_decode($request->available_time_slots, true);
        $available_dates = json_decode($request->selected_dates, true);

        if (empty($available_dates)) {
            return back()->with('error', 'Debe seleccionar al menos una fecha')->withInput();
        }

        // Determinar el tipo de appointment
        $tipoAppointment = $request->appointment_type ?? 'single_slot';

        // Validación específica para horarios
        if ($tipoAppointment === 'multi_slot') {
            if (empty($request->available_time_slots) || !json_decode($request->available_time_slots)) {
                return back()->withErrors(['available_time_slots' => 'Para reservas por hora, debe seleccionar al menos un horario'])->withInput();
            }
        }

        // Obtener el appointment a actualizar
        $appointment = Appointment::findOrFail($id);

        // PRIMERO: Construir las NUEVAS combinaciones ANTES de verificar
        $nuevasCombinaciones = [];

        foreach ($available_dates as $date) {
            if ($tipoAppointment === 'multi_slot' && $request->available_time_slots) {
                // Appointment con división horaria
                $horarios = json_decode($request->available_time_slots, true);
                foreach ($horarios as $time) {
                    $horaNormalizada = \Carbon\Carbon::parse($time)->format('H:i:s');
                    $nuevasCombinaciones[] = [
                        'date' => \Carbon\Carbon::parse($date)->format('Y-m-d'), // Formatear igual que la BD
                        'time' => $horaNormalizada,
                        'key' => $date . '_' . $horaNormalizada
                    ];
                }
            } else {
                // Appointment sin horarios específicos
                $horaNormalizada = $request->start_time ? \Carbon\Carbon::parse($request->start_time)->format('H:i:s') : '';
                $nuevasCombinaciones[] = [
                    'date' => \Carbon\Carbon::parse($date)->format('Y-m-d'), // Formatear igual que la BD
                    'time' => $horaNormalizada,
                    'key' => $date . '_' . $horaNormalizada
                ];
            }
        }

        // SEGUNDO: Obtener disponibilidades existentes con reservas
        $disponibilidadesConReservas = AvailableAppointment::where('appointment_id', $appointment->id)
            ->where('reserved_spots', '>', 0)
            ->get();

        // TERCERO: Verificar solo las que se están ELIMINANDO
        $conflictos = [];

        foreach ($disponibilidadesConReservas as $disponibilidad) {
            try {
                $fechaDisponibilidad = \Carbon\Carbon::parse($disponibilidad->date)->format('Y-m-d');
                $horaDisponibilidad = $disponibilidad->time ? \Carbon\Carbon::parse($disponibilidad->time)->format('H:i:s') : '';

                // Verificar si esta disponibilidad existe en las NUEVAS combinaciones
                $existeEnNuevas = collect($nuevasCombinaciones)->first(function ($nueva) use ($fechaDisponibilidad, $horaDisponibilidad) {
                    $horaNueva = $nueva['time'] ?: '';
                    return $nueva['date'] === $fechaDisponibilidad && $horaNueva === $horaDisponibilidad;
                });

                // Solo verificar conflictos si NO existe en las nuevas (se está eliminando)
                if (!$existeEnNuevas) {
                    $reservations = Reservation::where('available_appointment_id', $disponibilidad->id)
                        ->whereNull('asistencia')
                        ->get();

                    // Si hay reservas pendientes, agregar a conflictos
                    if ($reservations->count() > 0) {
                        $fechaFormateada = \Carbon\Carbon::parse($disponibilidad->date)->format('d/m/Y');
                        $horaFormateada = \Carbon\Carbon::parse($disponibilidad->time)->format('H:i');
                        $conflictos[] = [
                            'fecha' => $fechaFormateada,
                            'hora' => $horaFormateada,
                            'cantidad_reservas' => $reservations->count(),
                            'disponibilidad_id' => $disponibilidad->id
                        ];
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error verificando disponibilidad: ' . $e->getMessage());
                continue;
            }
        }

        // CUARTO: Si hay conflictos, mostrar error
        if (!empty($conflictos)) {
            $html = '
        <div class="text-left">
            <p class="text-danger">No es posible eliminar fechas que contienen reservas pendientes.</p>
            <div class="alert alert-warning mt-3">
                <p><strong class="text-danger">Detalles del conflicto:</strong></p>
                <ul>';

            foreach ($conflictos as $conflicto) {
                $html .= '<li><strong>Fecha:</strong> ' . $conflicto['fecha'] . ' ' . $conflicto['hora'] . ' - <strong>Reservas pendientes:</strong> ' . $conflicto['cantidad_reservas'] . '</li>';
            }

            $html .= '
                </ul>
                <p><strong class="text-danger">Recomendaciones:</strong></p>
                <ul class="text-danger">
                    <li>1. Cambie el estado de este turno como inactivo</li>
                    <li>2. Cancele las reservas pendientes de este turno</li>
                    <li>3. Vuelva a intentarlo</li>
                    <li>Si no desea realizarlo, evite eliminar fechas/horarios con reservas pendientes</li>
                </ul>
            </div>
        </div>';

            session()->flash('error', [
                'title' => 'Error!',
                'html' => $html,
                'icon' => 'error'
            ]);
            return back();
        }

        // QUINTO: Si NO hay conflictos, proceder con la actualización
        $appointment->update([
            ...$request->validated(),
            'available_time_slots' => $timeSlots ? $timeSlots : null,
            'available_dates' => $available_dates,
            'status' => $request->status ?? $appointment->status,
            'update_by' => Auth::id(),
        ]);

        // SEXTO: Eliminar solo las disponibilidades SIN reservas
        AvailableAppointment::where('appointment_id', $appointment->id)
            ->where('reserved_spots', 0)
            ->delete();

        // SÉPTIMO: Procesar cada nueva combinación
        foreach ($nuevasCombinaciones as $combinacion) {
            $availableSpots = ($tipoAppointment === 'single_slot') ? intval($request->number_of_reservations) : 1;

            // Buscar si ya existe una disponibilidad con reservas para esta combinación
            $existenteConReservas = $disponibilidadesConReservas->first(function ($item) use ($combinacion) {
                $fechaExistente = \Carbon\Carbon::parse($item->date)->format('Y-m-d');
                $horaExistente = $item->time ? \Carbon\Carbon::parse($item->time)->format('H:i:s') : '';
                $horaComparacion = $combinacion['time'] ?: '';

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

        session()->flash('success', [
            'title' => 'Actualizado!',
            'text' => 'El turno ha sido actualizado correctamente.',
            'icon' => 'success'
        ]);

        return redirect()->route('appointments.index');
    }

    function destroy($id)
    {
        $availableAppointments = AvailableAppointment::where('appointment_id', $id)->get();
        //Verificar si existen reservas asociadas a las disponibilidades del appointment en la tabla reservations con asistencia null
        foreach ($availableAppointments as $disponibilidad) {
            $tieneReservas = Reservation::where('available_appointment_id', $disponibilidad->id)
                ->whereNull('asistencia')
                ->exists();
            if ($tieneReservas) {
                session()->flash('error', [
                    'title' => 'Error!',
                    'text' => 'No se puede eliminar el turno porque tiene reservas pendientes.',
                    'icon' => 'error',
                ]);
                return redirect()->route('appointments.index');
            }
        }
        // Si no hay reservas pendientes, proceder a eliminar
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
}
