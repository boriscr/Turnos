<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentStoreRequest;
use App\Http\Requests\AppointmentUpdateRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\Specialty;
use App\Models\Doctor;
use App\Models\AvailableAppointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::all();
        return view('appointments/index', compact('appointments'));
    }
    public function create()
    {
        $specialties = Specialty::all();
        return view('appointments/create', compact('specialties'));

    }
    public function store(AppointmentStoreRequest $request)
    {
        // Decodificar fechas seleccionadas
        $fechas = json_decode($request->selected_dates, true);

        if (empty($fechas)) {
            Log::info('Fechas recibidas:', ['fechas' => $request->selected_dates]);
            Log::info('Decodificadas:', ['decoded' => json_decode($request->selected_dates, true)]);

            return back()->with('error', 'Debe seleccionar al menos una date');
        }

        // Crear el appointment principal
        $appointment = Appointment::create([
            'name' => trim($request->name),
            'address' => trim($request->address),
            'specialty_id' => $request->specialty_id,
            'doctor_id' => $request->doctor_id,
            'appointment' => $request->appointment, // Asignar el appointment si se proporciona
            'number_of_slots' => trim($request->cantidad),
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'available_time_slots' => $request->available_time_slots,
            'createBy' => Auth::id(),
            'updateBy' => Auth::id(),
            'available_dates' => $fechas,
            'status' => $request->status,
        ]);

        // Crear los appointments disponibles para cada date

        // Crear disponibilidad por date y horario
        foreach ($fechas as $date) {

            if ($request->available_time_slots) {
                // Caso CON horarios (vienen en JSON)
                $horarios = json_decode($request->available_time_slots, true);

                foreach ($horarios as $time) {
                    AvailableAppointment::create([
                        'appointment_id' => $appointment->id,
                        'doctor_id' => $request->doctor_id,
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
                    'date' => $date,
                    'time' => $request->start_time, // sin time específica
                    'available_spots' => $request->cantidad, // cupo total por date
                ]);
            }
        }
        session()->flash('success', [
            'title' => 'Creado!',
            'text' => 'El appointment ha sido creado correctamente.',
            'icon' => 'success'
        ]);
        return redirect()->route('appointments.index');
    }

    public function show($id)
    {
        // Obtener el appointment específico
        $appointment = Appointment::findOrFail($id);
        // Obtener el appointment disponible relacionado con el doctor de ese appointment
        $turnoDisponibles = AvailableAppointment::where('doctor_id', $appointment->doctor_id)
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->paginate(10);

        return view('appointments.show', compact('appointment', 'turnoDisponibles'));
    }


    //Editar Appointment
    public function edit($id)
    {
        $appointment = Appointment::with(['specialty', 'doctor', 'disponibilidades'])->findOrFail($id);
        $specialties = Specialty::where('status', 1)->get();

        // Procesar available_time_slots para la vista
        $available_time_slots = $appointment->available_time_slots;

        // Verificar si es un array JSON (horario2)
        $horariosArray = json_decode($appointment->available_time_slots, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($horariosArray)) {
            $available_time_slots = json_encode($horariosArray);
        }

        return view('appointments.edit', [
            'appointment' => $appointment,
            'specialties' => $specialties,
            'specialty_id' => $appointment->specialty_id,
            'doctor_id' => $appointment->doctor_id,
            'medico_nombre' => $appointment->doctor->name ?? 'Doctor no disponible',
            'name' => $appointment->name,
            'address' => $appointment->address,
            'cantidad' => $appointment->number_of_slots,
            'inicio' => $appointment->start_time ? Carbon::parse($appointment->start_time)->format('H:i') : null,
            'fin' => $appointment->end_time ? Carbon::parse($appointment->end_time)->format('H:i') : null,
            'turnoTipo' => $appointment->appointment,
            'fechas' => json_encode($appointment->available_dates),

            'available_time_slots' => $available_time_slots

        ]);
    }

    public function update(AppointmentUpdateRequest $request, $id)
    {
        // Decodificar fechas seleccionadas
        $fechas = json_decode($request->selected_dates, true);

        if (empty($fechas)) {

            return back()->with('error', 'Debe seleccionar al menos una date')->withInput();
        }

        // Validación específica para horarios
        if ($request->appointment === 'horario2') {
            if (empty($request->available_time_slots) || !json_decode($request->available_time_slots)) {
                return back()->withErrors(['available_time_slots' => 'Para appointments por time, debe seleccionar al menos un horario'])->withInput();
            }
        }

        // Obtener el appointment a actualizar
        $appointment = Appointment::findOrFail($id);

        // Actualizar el appointment principal
        $appointment->name = trim($request->name);
        $appointment->address = trim($request->address);
        $appointment->specialty_id = $request->specialty_id;
        $appointment->doctor_id = $request->doctor_id;
        $appointment->appointment = $request->appointment;
        $appointment->number_of_slots = trim($request->cantidad);
        $appointment->start_time = $request->start_time;
        $appointment->end_time = $request->end_time;
        $appointment->available_time_slots = $request->available_time_slots;
        $appointment->user_id_update = Auth::id();
        $appointment->available_dates = $fechas;
        $appointment->status = $request->status ?? $appointment->status;

        $appointment->save();

        // Eliminar disponibilidades existentes

        // Crear nuevas disponibilidades según la configuración
        foreach ($fechas as $date) {
            if ($request->available_time_slots && json_decode($request->available_time_slots)) {
                $horarios = json_decode($request->available_time_slots, true);

                foreach ($horarios as $time) {
                    AvailableAppointment::updateOrCreate(
                        ['appointment_id' => $appointment->id, 'doctor_id' => $request->doctor_id, 'date' => $date, 'time' => $time],
                        ['available_spots' => 1]
                    );
                }
            } else {
                AvailableAppointment::updateOrCreate(
                    ['appointment_id' => $appointment->id, 'doctor_id' => $request->doctor_id, 'date' => $date, 'time' => $request->start_time],
                    ['available_spots' => $request->cantidad]
                );
            }
        }

        session()->flash('success', [
            'title' => 'Actualizado!',
            'text' => 'El appointment ha sido actualizado correctamente.',
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
            'text' => 'El appointment ha sido eliminado correctamente.',
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
            Log::error('Error al obtener doctores por specialty', ['specialty_id' => $id, 'error' => $e->getMessage()]);
            return response()->json([], 500);
        }
    }
}
