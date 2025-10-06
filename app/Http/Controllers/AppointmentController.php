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
use Illuminate\Support\Facades\DB;

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
        // Aumentar temporalmente el tiempo de ejecución
        set_time_limit(60);

        // Decodificar datos una sola vez
        $timeSlots = json_decode($request->available_time_slots, true) ?? [];
        $available_dates = json_decode($request->selected_dates, true) ?? [];

        if (empty($available_dates)) {
            return back()->with('error', 'Debe seleccionar al menos una fecha');
        }

        // Verificar especialidad y doctor activos
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

        // Determinar el tipo de appointment
        $tipoAppointment = $request->appointment_type ?? 'single_slot';

        // Filtrar fechas que están dentro del límite
        $resultadoFiltrado = $this->filtrarFechasDentroDeLimite($available_dates, $timeSlots, $tipoAppointment);

        $fechasParaCrear = $resultadoFiltrado['fechas_crear'];
        $fechasExcluidas = $resultadoFiltrado['fechas_excluir'];
        $totalCombinaciones = $resultadoFiltrado['total_combinaciones'];

        // Si no hay fechas para crear después del filtro
        if (empty($fechasParaCrear)) {
            return back()->with(
                'error',
                "Todas las fechas seleccionadas exceden el límite. " .
                    "Máximo permitido: 10,000 combinaciones.\n" .
                    "Seleccionaste: " . count($available_dates) . " fechas × " .
                    (empty($timeSlots) ? 1 : count($timeSlots)) . " horarios = " .
                    number_format(count($available_dates) * (empty($timeSlots) ? 1 : count($timeSlots))) . " registros."
            );
        }

        // Crear el appointment con solo las fechas que están dentro del límite
        $appointment = Appointment::create([
            ...$request->validated(),
            'available_time_slots' => !empty($timeSlots) ? $timeSlots : null,
            'available_dates' => $fechasParaCrear, // Solo las fechas que se crearán
        ]);

        // Crear disponibilidades en lote solo con las fechas permitidas
        $this->crearDisponibilidadesEnLote($appointment, $fechasParaCrear, $tipoAppointment, $request, $timeSlots);

        // Mostrar mensaje informativo si se excluyeron algunas fechas
        if (!empty($fechasExcluidas)) {
            session()->flash('info', [
                'title' => 'Creación parcial',
                'html' => $this->generarMensajeFechasExcluidas($fechasParaCrear, $fechasExcluidas, $timeSlots, $totalCombinaciones),
                'icon' => 'info'
            ]);
        } else {
            session()->flash('success', [
                'title' => 'Creado!',
                'text' => 'El turno ha sido creado correctamente.',
                'icon' => 'success'
            ]);
        }

        return redirect()->route('appointments.index');
    }

    private function filtrarFechasDentroDeLimite($available_dates, $timeSlots, $tipoAppointment)
    {
        $limiteMaximo = 10000;
        $timeSlotsCount = ($tipoAppointment === 'multi_slot' && !empty($timeSlots)) ? count($timeSlots) : 1;

        // Calcular cuántas fechas podemos aceptar
        $fechasPermitidas = min(count($available_dates), floor($limiteMaximo / $timeSlotsCount));

        // Tomar solo las primeras fechas permitidas
        $fechasParaCrear = array_slice($available_dates, 0, $fechasPermitidas);
        $fechasExcluidas = array_slice($available_dates, $fechasPermitidas);

        $totalCombinaciones = count($available_dates) * $timeSlotsCount;

        return [
            'fechas_crear' => $fechasParaCrear,
            'fechas_excluir' => $fechasExcluidas,
            'total_combinaciones' => $totalCombinaciones,
            'fechas_permitidas' => $fechasPermitidas,
            'fechas_excluidas_count' => count($fechasExcluidas)
        ];
    }

    private function generarMensajeFechasExcluidas($fechasParaCrear, $fechasExcluidas, $timeSlots, $totalCombinaciones)
    {
        $timeSlotsCount = empty($timeSlots) ? 1 : count($timeSlots);
        $fechasCreadasCount = count($fechasParaCrear);
        $fechasExcluidasCount = count($fechasExcluidas);

        // Formatear algunas fechas excluidas para mostrar (máximo 5)
        $fechasEjemplo = array_slice($fechasExcluidas, 0, 5);
        $fechasFormateadas = array_map(function ($fecha) {
            return \Carbon\Carbon::parse($fecha)->format('d/m/Y');
        }, $fechasEjemplo);

        $masFechas = $fechasExcluidasCount > 5 ? " y " . ($fechasExcluidasCount - 5) . " fechas más" : "";

        return "
    <div class='text-left'>
        <p><strong>Se creó el turno parcialmente:</strong></p>
        <ul>
            <li><strong>Fechas creadas:</strong> {$fechasCreadasCount}</li>
            <li><strong>Fechas excluidas:</strong> {$fechasExcluidasCount}</li>
            <li><strong>Combinaciones totales:</strong> " . number_format($totalCombinaciones) . "</li>
        </ul>
        <p><strong>Fechas que no se crearon:</strong> " . implode(', ', $fechasFormateadas) . "{$masFechas}</p>
        <p class='text-sm text-muted'>Solo se crearon las primeras {$fechasCreadasCount} fechas para no exceder el límite de 10,000 combinaciones.</p>
    </div>";
    }

    private function calcularTotalRegistros($available_dates, $timeSlots, $appointmentType)
    {
        $totalFechas = count($available_dates);

        if ($appointmentType === 'multi_slot' && !empty($timeSlots)) {
            return $totalFechas * count($timeSlots);
        }

        return $totalFechas;
    }

    private function crearDisponibilidadesEnLote($appointment, $available_dates, $tipoAppointment, $request, $timeSlots = [])
    {
        $lotes = [];
        $now = now();
        $doctor_id = $request->doctor_id;
        $specialty_id = $request->specialty_id;
        $appointment_id = $appointment->id;

        // Pre-calcular valores comunes
        $isMultiSlot = ($tipoAppointment === 'multi_slot' && !empty($timeSlots));
        $availableSpotsSingle = intval($request->number_of_reservations);
        $startTimeNormalized = $request->start_time ? \Carbon\Carbon::parse($request->start_time)->format('H:i:s') : null;

        foreach ($available_dates as $date) {
            $fechaFormateada = \Carbon\Carbon::parse($date)->format('Y-m-d');

            if ($isMultiSlot) {
                // Multi slot: crear un registro por cada horario
                foreach ($timeSlots as $time) {
                    $horaNormalizada = \Carbon\Carbon::parse($time)->format('H:i:s');

                    $lotes[] = [
                        'appointment_id' => $appointment_id,
                        'doctor_id' => $doctor_id,
                        'specialty_id' => $specialty_id,
                        'date' => $fechaFormateada,
                        'time' => $horaNormalizada,
                        'available_spots' => 1,
                        'reserved_spots' => 0,
                        'created_at' => $now,
                        'updated_at' => $now
                    ];

                    // Insertar en chunks de 100
                    if (count($lotes) >= 100) {
                        AvailableAppointment::insert($lotes);
                        $lotes = [];
                    }
                }
            } else {
                // Single slot: un solo registro por fecha
                $lotes[] = [
                    'appointment_id' => $appointment_id,
                    'doctor_id' => $doctor_id,
                    'specialty_id' => $specialty_id,
                    'date' => $fechaFormateada,
                    'time' => $startTimeNormalized,
                    'available_spots' => $availableSpotsSingle,
                    'reserved_spots' => 0,
                    'created_at' => $now,
                    'updated_at' => $now
                ];

                // Insertar en chunks de 100
                if (count($lotes) >= 100) {
                    AvailableAppointment::insert($lotes);
                    $lotes = [];
                }
            }
        }

        // Insertar lotes restantes
        if (!empty($lotes)) {
            AvailableAppointment::insert($lotes);
        }
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
        // Aumentar temporalmente el tiempo de ejecución
        set_time_limit(60);

        // Decodificar datos una sola vez
        $timeSlots = json_decode($request->available_time_slots, true) ?? [];
        $available_dates = json_decode($request->selected_dates, true) ?? [];

        if (empty($available_dates)) {
            return back()->with('error', 'Debe seleccionar al menos una fecha')->withInput();
        }

        $tipoAppointment = $request->appointment_type ?? 'single_slot';

        // Validación específica para horarios
        if ($tipoAppointment === 'multi_slot') {
            if (empty($timeSlots)) {
                return back()->withErrors(['available_time_slots' => 'Para reservas por hora, debe seleccionar al menos un horario'])->withInput();
            }
        }

        $appointment = Appointment::findOrFail($id);

        // FILTRAR FECHAS: Aplicar límites como en el store
        $resultadoFiltrado = $this->filtrarFechasDentroDeLimite($available_dates, $timeSlots, $tipoAppointment);

        $fechasParaActualizar = $resultadoFiltrado['fechas_crear'];
        $fechasExcluidas = $resultadoFiltrado['fechas_excluir'];
        $totalCombinaciones = $resultadoFiltrado['total_combinaciones'];

        // Si no hay fechas para actualizar después del filtro
        if (empty($fechasParaActualizar)) {
            return back()->with(
                'error',
                "Todas las fechas seleccionadas exceden el límite. " .
                    "Máximo permitido: 10,000 combinaciones.\n" .
                    "Seleccionaste: " . count($available_dates) . " fechas × " .
                    (empty($timeSlots) ? 1 : count($timeSlots)) . " horarios = " .
                    number_format($totalCombinaciones) . " registros."
            );
        }

        // 1. Construir nuevas combinaciones solo con fechas permitidas
        $nuevasCombinaciones = $this->construirCombinaciones(
            $fechasParaActualizar, // Solo fechas permitidas
            $tipoAppointment,
            $timeSlots,
            $request->start_time
        );

        // 2. Obtener IDs de disponibilidades con reservas en una sola consulta
        $disponibilidadesConReservas = AvailableAppointment::where('appointment_id', $appointment->id)
            ->where('reserved_spots', '>', 0)
            ->get();

        // 3. Verificar conflictos de forma optimizada (con fechas filtradas)
        $conflictos = $this->verificarConflictosOptimizado(
            $disponibilidadesConReservas,
            $nuevasCombinaciones
        );

        if (!empty($conflictos)) {
            return $this->mostrarErrorConflictos($conflictos);
        }

        // 4. Actualizar el appointment principal con solo fechas permitidas
        $appointment->update([
            ...$request->validated(),
            'available_time_slots' => !empty($timeSlots) ? $timeSlots : null,
            'available_dates' => $fechasParaActualizar, // Solo fechas permitidas
            'status' => $request->status ?? $appointment->status,
        ]);

        // 5. Procesar disponibilidades de forma optimizada
        $this->procesarDisponibilidades(
            $appointment,
            $nuevasCombinaciones,
            $disponibilidadesConReservas,
            $tipoAppointment,
            $request
        );

        // Mostrar mensaje informativo si se excluyeron algunas fechas
        if (!empty($fechasExcluidas)) {
            session()->flash('info', [
                'title' => 'Actualización parcial',
                'html' => $this->generarMensajeFechasExcluidasUpdate($fechasParaActualizar, $fechasExcluidas, $timeSlots, $totalCombinaciones),
                'icon' => 'info'
            ]);
        } else {
            session()->flash('success', [
                'title' => 'Actualizado!',
                'text' => 'El turno ha sido actualizado correctamente.',
                'icon' => 'success'
            ]);
        }

        return redirect()->route('appointments.index');
    }



    private function generarMensajeFechasExcluidasUpdate($fechasParaActualizar, $fechasExcluidas, $timeSlots, $totalCombinaciones)
    {
        $timeSlotsCount = empty($timeSlots) ? 1 : count($timeSlots);
        $fechasActualizadasCount = count($fechasParaActualizar);
        $fechasExcluidasCount = count($fechasExcluidas);

        // Formatear algunas fechas excluidas para mostrar (máximo 5)
        $fechasEjemplo = array_slice($fechasExcluidas, 0, 5);
        $fechasFormateadas = array_map(function ($fecha) {
            return \Carbon\Carbon::parse($fecha)->format('d/m/Y');
        }, $fechasEjemplo);

        $masFechas = $fechasExcluidasCount > 5 ? " y " . ($fechasExcluidasCount - 5) . " fechas más" : "";

        return "
    <div class='text-left'>
        <p><strong>Se actualizó el turno parcialmente:</strong></p>
        <ul>
            <li><strong>Fechas actualizadas:</strong> {$fechasActualizadasCount}</li>
            <li><strong>Fechas excluidas:</strong> {$fechasExcluidasCount}</li>
            <li><strong>Combinaciones totales seleccionadas:</strong> " . number_format($totalCombinaciones) . "</li>
        </ul>
        <p><strong>Fechas que no se actualizaron:</strong> " . implode(', ', $fechasFormateadas) . "{$masFechas}</p>
        <p class='text-sm text-muted'>Solo se actualizaron las primeras {$fechasActualizadasCount} fechas para no exceder el límite de 10,000 combinaciones.</p>
    </div>";
    }

    private function construirCombinaciones($available_dates, $tipoAppointment, $timeSlots, $start_time)
    {
        $combinaciones = [];

        foreach ($available_dates as $date) {
            $fechaFormateada = \Carbon\Carbon::parse($date)->format('Y-m-d');

            if ($tipoAppointment === 'multi_slot' && !empty($timeSlots)) {
                foreach ($timeSlots as $time) {
                    $horaNormalizada = \Carbon\Carbon::parse($time)->format('H:i:s');
                    $combinaciones[] = [
                        'date' => $fechaFormateada,
                        'time' => $horaNormalizada,
                        'key' => $fechaFormateada . '_' . $horaNormalizada
                    ];
                }
            } else {
                $horaNormalizada = $start_time ? \Carbon\Carbon::parse($start_time)->format('H:i:s') : '';
                $combinaciones[] = [
                    'date' => $fechaFormateada,
                    'time' => $horaNormalizada,
                    'key' => $fechaFormateada . '_' . $horaNormalizada
                ];
            }
        }

        return $combinaciones;
    }

    private function verificarConflictosOptimizado($disponibilidadesConReservas, $nuevasCombinaciones)
    {
        if ($disponibilidadesConReservas->isEmpty()) {
            return [];
        }

        // Crear mapa de nuevas combinaciones para búsqueda rápida
        $mapaNuevasCombinaciones = [];
        foreach ($nuevasCombinaciones as $combinacion) {
            $mapaNuevasCombinaciones[$combinacion['key']] = true;
        }

        // Obtener todos los IDs de disponibilidades que podrían tener conflictos
        $idsDisponibilidades = $disponibilidadesConReservas->pluck('id')->toArray();

        // Una sola consulta para todas las reservas pendientes
        $reservasPendientes = Reservation::whereIn('available_appointment_id', $idsDisponibilidades)
            ->whereNull('asistencia')
            ->get()
            ->groupBy('available_appointment_id');

        $conflictos = [];

        foreach ($disponibilidadesConReservas as $disponibilidad) {
            try {
                $fechaDisponibilidad = \Carbon\Carbon::parse($disponibilidad->date)->format('Y-m-d');
                $horaDisponibilidad = $disponibilidad->time ? \Carbon\Carbon::parse($disponibilidad->time)->format('H:i:s') : '';
                $key = $fechaDisponibilidad . '_' . $horaDisponibilidad;

                // Verificar si se está eliminando esta disponibilidad
                if (!isset($mapaNuevasCombinaciones[$key])) {
                    $reservations = $reservasPendientes->get($disponibilidad->id, collect());

                    if ($reservations->count() > 0) {
                        $fechaFormateada = \Carbon\Carbon::parse($disponibilidad->date)->format('d/m/Y');
                        $horaFormateada = $disponibilidad->time ? \Carbon\Carbon::parse($disponibilidad->time)->format('H:i') : 'Todo el día';

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

        return $conflictos;
    }

    private function mostrarErrorConflictos($conflictos)
    {
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

    private function procesarDisponibilidades($appointment, $nuevasCombinaciones, $disponibilidadesConReservas, $tipoAppointment, $request)
    {
        // Eliminar solo disponibilidades sin reservas
        AvailableAppointment::where('appointment_id', $appointment->id)
            ->where('reserved_spots', 0)
            ->delete();

        // Crear mapa de existentes con reservas para búsqueda rápida
        $mapaExistentesConReservas = [];
        foreach ($disponibilidadesConReservas as $existente) {
            $fechaExistente = \Carbon\Carbon::parse($existente->date)->format('Y-m-d');
            $horaExistente = $existente->time ? \Carbon\Carbon::parse($existente->time)->format('H:i:s') : '';
            $mapaExistentesConReservas[$fechaExistente . '_' . $horaExistente] = $existente;
        }

        // Procesar en lotes para mejor performance
        $lotes = [];
        foreach ($nuevasCombinaciones as $combinacion) {
            $availableSpots = ($tipoAppointment === 'single_slot') ? intval($request->number_of_reservations) : 1;
            $key = $combinacion['date'] . '_' . ($combinacion['time'] ?: '');

            if (isset($mapaExistentesConReservas[$key])) {
                // Actualizar existente
                $existente = $mapaExistentesConReservas[$key];
                $newAvailableSpots = max($availableSpots - $existente->reserved_spots, 0);

                $existente->update([
                    'doctor_id' => $request->doctor_id,
                    'available_spots' => $newAvailableSpots,
                    'specialty_id' => $request->specialty_id,
                ]);
            } else {
                // Crear nueva para insertar en lote
                $lotes[] = [
                    'appointment_id' => $appointment->id,
                    'doctor_id' => $request->doctor_id,
                    'specialty_id' => $request->specialty_id,
                    'date' => $combinacion['date'],
                    'time' => $combinacion['time'],
                    'available_spots' => $availableSpots,
                    'reserved_spots' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        // Insertar en lote para mejor performance
        if (!empty($lotes)) {
            // Insertar en chunks para evitar límites de MySQL
            foreach (array_chunk($lotes, 100) as $chunk) {
                AvailableAppointment::insert($chunk);
            }
        }
    }

    public function destroy($id)
    {
        set_time_limit(30);

        try {
            // 1. Verificar reservas pendientes en una sola consulta optimizada
            $tieneReservasPendientes = DB::table('reservations')
                ->join('available_appointments', 'reservations.available_appointment_id', '=', 'available_appointments.id')
                ->where('available_appointments.appointment_id', $id)
                ->whereNull('reservations.asistencia')
                ->exists();

            if ($tieneReservasPendientes) {
                session()->flash('error', [
                    'title' => 'Error!',
                    'text' => 'No se puede eliminar el turno porque tiene reservas pendientes.',
                    'icon' => 'error',
                ]);
                return redirect()->route('appointments.index');
            }

            // 2. Usar transacción para atomicidad
            DB::transaction(function () use ($id) {
                // 3. Eliminar disponibilidades con DELETE directo (más rápido que Eloquent)
                AvailableAppointment::where('appointment_id', $id)->delete();

                // 4. Eliminar el appointment
                Appointment::where('id', $id)->delete();
            });

            session()->flash('success', [
                'title' => 'Eliminado!',
                'text' => 'El turno ha sido eliminado correctamente.',
                'icon' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Error eliminando appointment ' . $id . ': ' . $e->getMessage());

            session()->flash('error', [
                'title' => 'Error!',
                'text' => 'Ocurrió un error al eliminar el turno.',
                'icon' => 'error',
            ]);
        }

        return redirect()->route('appointments.index');
    }
}
