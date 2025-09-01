<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Setting;
use App\Models\Appointment;
use App\Models\Specialty;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use App\Models\AvailableAppointment;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ReservationStoreRequest;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $specialties = Specialty::where('status', 1)->get();

        // Si se presionó "Mostrar Todo", ignoramos todos los filtros
        if ($request->has('show_all')) {
            $reservations = Reservation::with(['availableAppointment.doctor', 'user'])
                ->orderByDesc('created_at')
                ->paginate(10);

            return view('reservations.index', [
                'specialties' => $specialties,
                'reservations' => $reservations,
                'reservaFiltro' => 'all',
                'fechaFiltro' => 'all',
                'search' => null,
                'fechaInicio' => null,
                'fechaFin' => null,
                'specialty_id' => 'all_specialties'
            ]);
        }

        // Procesamiento normal de filtros
        $search = $request->input('search');
        $reservaFiltro = $request->input('reservation', 'pending');
        $fechaInicio = $request->input('start_date');
        $fechaFin = $request->input('end_date');
        $fechaFiltro = $request->input('date', 'today'); // Añadido para capturar el filtro rápido
        $today = now()->format('Y-m-d');
        $yesterday = now()->subDay()->format('Y-m-d');
        $tomorrow = now()->addDay()->format('Y-m-d');
        $specialty_id = $request->input('specialty_id', 'all_specialties');

        $reservations = Reservation::with(['user', 'availableAppointment.doctor'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%$search%")
                            ->orWhere('surname', 'like', "%$search%")
                            ->orWhere('idNumber', 'like', "%$search%");
                    })
                        ->orWhereHas('availableAppointment.doctor', function ($equipoQuery) use ($search) {
                            $equipoQuery->where('name', 'like', "%$search%")
                                ->orWhere('surname', 'like', "%$search%")
                                ->orWhere('idNumber', 'like', "%$search%");
                        });
                });
            })
            ->when(in_array($reservaFiltro, ['assisted', 'pending', 'not_attendance']), function ($query) use ($reservaFiltro) {
                switch ($reservaFiltro) {
                    case 'assisted':
                        $query->where('asistencia', '=', 1);
                        break;
                    case 'pending':
                        $query->where('asistencia', '=', null);
                        break;
                    case 'not_attendance':
                        $query->where('asistencia', '=', 0);
                        break;
                }
            })
            ->when($specialty_id !== 'all_specialties', function ($query) use ($specialty_id) {
                $query->where('specialty_id', $specialty_id);
            })
            // LÓGICA CORREGIDA PARA FECHAS - SIN CONFLICTOS
            ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                // Solo filtro por rango personalizado si hay fechas
                $query->whereHas('availableAppointment', function ($q) use ($fechaInicio, $fechaFin) {
                    $q->whereBetween('date', [$fechaInicio, $fechaFin]);
                });
            }, function ($query) use ($fechaFiltro, $today, $yesterday, $tomorrow, $fechaInicio, $fechaFin) {
                // Solo aplicar filtros rápidos si NO hay rango personalizado
                if (!$fechaInicio && !$fechaFin) {
                    $query->whereHas('availableAppointment', function ($q) use ($fechaFiltro, $today, $yesterday, $tomorrow) {
                        switch ($fechaFiltro) {
                            case 'yesterday':
                                $q->whereDate('date', $yesterday);
                                break;
                            case 'today':
                                $q->whereDate('date', $today);
                                break;
                            case 'tomorrow':
                                $q->whereDate('date', $tomorrow);
                                break;
                            default:
                                $q->whereDate('date', $today);
                        }
                    });
                }
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('reservations.index', compact(
            'specialties',
            'reservations',
            'search',
            'reservaFiltro',
            'fechaFiltro', // Añadido para mantener el estado del botón
            'fechaInicio',
            'fechaFin',
            'specialty_id'
        ));
    }


    public function actualizarAsistencia(Request $request, Reservation $reservation)
    {
        DB::transaction(function () use ($reservation, $request) {
            $estadoAnterior = $reservation->asistencia;

            // Si se envía el valor específico en el request, usarlo
            if ($request->filled('asistencia')) {
                $nuevoEstado = $request->asistencia == '1';
            } else {
                // Comportamiento original de toggle
                $nuevoEstado = $estadoAnterior === null ? false : !$estadoAnterior;
            }

            $reservation->asistencia = $nuevoEstado;
            $reservation->save();

            if ($reservation->user) {
                $this->gestionarFaltas($reservation, $estadoAnterior, $nuevoEstado);
            }
        });

        return back()->with('success', 'Estado de asistencia actualizado correctamente');
    }
    protected function gestionarFaltas(Reservation $reservation, $estadoAnterior, $nuevoEstado)
    {
        // Detectar si el estado anterior era null
        $eraPendiente = is_null($estadoAnterior);

        $estadoAnteriorBool = (bool) $estadoAnterior;
        $nuevoEstadoBool = (bool) $nuevoEstado;

        // Si el nuevo estado es "No Asistió" (false) y antes estaba en Pendiente o en Asistió
        if ($nuevoEstadoBool === false && ($estadoAnteriorBool !== false || $eraPendiente)) {
            $reservation->user->increment('faults');
        }

        // Si el nuevo estado es "Asistió" (true) y antes era "No Asistió" (false)
        if ($nuevoEstadoBool === true && $estadoAnteriorBool === false) {
            if ($reservation->user->faults > 0) {
                $reservation->user->decrement('faults');
            }
        }
    }
    // Método para verificación automática (se llamará desde la tarea programada)
    // php artisan schedule:work
    public function verificarAsistenciasAutomaticamente()
    {
        $settings = Setting::where('group', 'appointments')->pluck('value', 'key');
        $hora_asistencia = (int) ($settings['assists.verification_interval'] ?? 1); // Valor por defecto
        $now = Carbon::now();
        $fourHoursAgo = $now->copy()->subHours($hora_asistencia);

        $reservasPendientes = Reservation::with(['availableAppointment', 'user'])
            ->whereNull('asistencia')
            ->whereHas('availableAppointment', function ($query) use ($now, $fourHoursAgo) {
                $query->where(function ($q) use ($now, $fourHoursAgo) {
                    $q->where('date', '<', $now->toDateString())
                        ->orWhere(function ($q2) use ($now, $fourHoursAgo) {
                            $q2->where('date', $now->toDateString())
                                ->where('time', '<=', $fourHoursAgo->toTimeString());
                        });
                });
            })
            ->get();

        foreach ($reservasPendientes as $reservation) {
            DB::transaction(function () use ($reservation) {
                $reservation->asistencia = false;
                $reservation->save();
                $status = Appointment::where('id', $reservation->availableAppointment->appointment_id)->value('status');
                if ($reservation->user && $status == true) {
                    $reservation->user->increment('faults');
                }
            });
        }

        return response()->json([
            'message' => 'Asistencias verificadas automáticamente',
            'reservas_actualizadas' => $reservasPendientes->count()
        ]);
    }


    public function show($id)
    {
        $reservation = Reservation::with(['user', 'availableAppointment.doctor'])->findOrFail($id);
        return view('reservations.show', compact('reservation'));
    }

    //Crear la reserva por el paciente
    public function create()
    {
        $settings = Setting::where('group', 'appointments')->pluck('value', 'key');
        $turnos_antelacion_reserva = $settings['appointments.advance_reservation'];
        $turnos_faltas_maximas = $settings['appointments.maximum_faults'];
        $turnos_horas_cancelacion = $settings['appointments.cancellation_hours'];
        $turnos_limite_diario = $settings['appointments.daily_limit'];
        $turnos_unidad_antelacion = $settings['appointments.unit_advance'];
        $user = Auth::user();
        $turnos_activos = Reservation::where('user_id', $user->id)
            ->whereHas('availableAppointment', function ($query) {
                $query->whereNull('asistencia');
            })
            ->count();
        if (
            $user->status &&
            $user->faults <= $turnos_faltas_maximas &&
            $turnos_activos < $turnos_limite_diario &&
            $turnos_limite_diario > 0
        ) {
            $availableAppointment = AvailableAppointment::all();
            $appointments = Appointment::where('status', 1)->get();
            $specialties = Specialty::where('status', 1)->get();
            return view('reservations/create', compact('availableAppointment', 'appointments', 'specialties'));
        } else {
            if (
                !$user->status &&
                $user->faults >= $turnos_faltas_maximas &&
                $turnos_activos >= $turnos_limite_diario &&
                $turnos_limite_diario > 0
            ) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => '1. Su cuenta está inactiva. <br>2. Has alcanzado el límite de faltas permitidas. <br>3. Has alcanzado el límite de reservas activas permitidos.<br> Contacta al administrador.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } elseif (!$user->status) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => 'Su cuenta está inactiva.<br> Contacta al administrador.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } elseif ($user->faults > $turnos_faltas_maximas) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => 'Has alcanzado el límite de faltas permitidas.<br> No puedes solicitar más reservas.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } elseif ($turnos_activos >= $turnos_limite_diario) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => 'Has alcanzado el límite de reservas activas permitidas.<br> Asiste a las reservas solicitadas antes de solicitar una nueva.<br> No puedes realizar más reservas.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } elseif ($turnos_limite_diario <= 0) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => 'No puedes realizar más reservas en este momento. El límite de reservas activas ha sido deshabilitado.<br>Por favor, regresa más tarde.<br>Si tenés dudas, contactá al administrador.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } else {
                session()->flash('error', [
                    'title' => 'Error al reservar',
                    'html' => 'Error al ejecutar la petición.<br> Contacta al administrador.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            }
        }
    }
    // 1. Obtener doctors por specialty
    public function getDoctorsBySpecialty($specialty_id)
    {
        $doctors = Doctor::where('specialty_id', $specialty_id)
            ->where('status', 1)
            ->get();

        return response()->json(['doctors' => $doctors]);
    }

    // 2. Obtener appointments por name
    public function getAvailableReservationByName($doctor_id)
    {
        if ($doctor_id) {
            $appointments = Appointment::where('doctor_id', $doctor_id)
                ->where('status', 1)
                ->get();

            return response()->json(['appointments' => $appointments]);
        } else {
            return response()->json([]);
        }
    }

    // 3. Obtener appointments por doctor (filtrado por date/time)
    //Filtrado por date y time configurado en settings
    // Devuelve los appointments disponibles para un doctor específico, filtrando por date y time
    // y aplicando la configuración de previsualización de appointments.
    // La previsualización se basa en la configuración de la ventana de tiempo definida en la base de datos.
    // La función también maneja la lógica de filtrado para mostrar solo los appointments que están disponibles
    // y que son futuros, teniendo en cuenta la date y time actuales.
    // El resultado se devuelve en formato JSON, incluyendo los appointments disponibles y la configuración de
    // previsualización utilizada para la consulta.
    public function getAvailableReservationByDoctor($appointment_name_id)
    {
        $now = now();

        // Obtener configuración de previsualización
        $settings = Setting::where('group', 'appointments')->pluck('value', 'key');
        $previewAmount = (int) ($settings['appointments.advance_reservation'] ?? 30);
        $previewUnit = $settings['appointments.unit_advance'] ?? 'day';

        // Calcular fecha/hora límite
        $fechaLimite = match ($previewUnit) {
            'time' => $now->copy()->addHours($previewAmount),
            'month' => $now->copy()->addMonths($previewAmount),
            'day' => $now->copy()->addDays($previewAmount),
            default => $now->copy()->addDays($previewAmount)
        };

        $appointments = AvailableAppointment::where('appointment_id', $appointment_name_id)
            ->where('available_spots', '>', 0)
            ->where(function ($query) use ($now, $fechaLimite) {
                // Para appointments futuros (mayores a ahora)
                $query->where(function ($q) use ($now) {
                    $q->whereDate('date', '>', $now->format('Y-m-d'))
                        ->orWhere(function ($q2) use ($now) {
                            $q2->whereDate('date', $now->format('Y-m-d'))
                                ->whereTime('time', '>', $now->format('H:i:s'));
                        });
                })

                    // Para appointments dentro del límite configurado
                    ->where(function ($q) use ($fechaLimite) {
                        $q->whereDate('date', '<', $fechaLimite->format('Y-m-d'))
                            ->orWhere(function ($q2) use ($fechaLimite) {
                                $q2->whereDate('date', $fechaLimite->format('Y-m-d'))
                                    ->whereTime('time', '<=', $fechaLimite->format('H:i:s'));
                            });
                    });
            })
            ->orderBy('date')
            ->orderBy('time')
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'date' => $appointment->date,
                    'time' => $appointment->time ? \Carbon\Carbon::parse($appointment->time)->format('H:i') : null,
                ];
            });

        return response()->json([
            'appointments' => $appointments,
            'preview_settings' => [
                'amount' => $previewAmount,
                'unit' => $previewUnit
            ]
        ]);
    }




    public function store(ReservationStoreRequest $request)
    {
        try {
            Log::debug('Datos recibidos:', [
                'appointment_id' => $request->appointment_id,
                'specialty_id' => $request->specialty_id,
                //'user_id' => auth()->id()
            ]);

            Log::debug('AvailableAppointment find:', [
                'result' => AvailableAppointment::find($request->appointment_id)
            ]);
            return DB::transaction(function () use ($request) {
                // 1. BLOQUEAR el registro para evitar acceso concurrente
                $availableAppointment = AvailableAppointment::where('id', $request->appointment_id)
                    ->lockForUpdate() // ← BLOQUEO PESIMISTA
                    ->firstOrFail();

                $user = Auth::user();

                // 2. Validar límites de usuario
                $userValidation = $this->validateUserReservationLimits($user);
                if (!$userValidation['can_reserve']) {
                    throw new \Exception($userValidation['message']);
                }

                // 3. Validar disponibilidad (DENTRO de la transacción)
                if ($availableAppointment->available_spots <= 0) {
                    throw new \Exception('No hay cupos disponibles para este turno.');
                }

                // 4. Validar duplicados (DENTRO de la transacción)
                $existingReservation = Reservation::where('user_id', $user->id)
                    ->where('available_appointment_id', $availableAppointment->id)
                    ->exists();

                if ($existingReservation) {
                    throw new \Exception('Ya tienes este turno reservado.');
                }

                // 5. Validar tiempo del turno
                $timeValidation = $this->validateAppointmentTime($availableAppointment);
                if (!$timeValidation['valid']) {
                    throw new \Exception($timeValidation['message']);
                }

                // 6. Validar consistencia de datos
                $consistencyValidation = $this->validateDataConsistency($availableAppointment, $request->specialty_id);
                if (!$consistencyValidation['valid']) {
                    throw new \Exception($consistencyValidation['message']);
                }

                // 7. Validar estados
                $statusValidation = $this->validateStatuses($availableAppointment, $request->specialty_id);
                if (!$statusValidation['valid']) {
                    throw new \Exception($statusValidation['message']);
                }

                // 8. REALIZAR LA RESERVA (ATÓMICA)
                $availableAppointment->decrement('available_spots');
                $availableAppointment->increment('reserved_spots');

                Reservation::create([
                    'user_id' => $user->id,
                    'available_appointment_id' => $availableAppointment->id,
                    'specialty_id' => $request->specialty_id
                ]);

                return $this->successResponse('Reserva exitosa', 'Turno reservado correctamente.');
            });
        } catch (\Exception $e) {
            return $this->errorResponse('Error en la reserva', $e->getMessage());
        }
    }

    // ==================== MÉTODOS PRIVADOS DE VALIDACIÓN ====================

    /**
     * Validar límites de reserva del usuario
     */
    private function validateUserReservationLimits($user)
    {
        $settings = Setting::where('group', 'appointments')->pluck('value', 'key');
        $maxFaults = (int) ($settings['appointments.maximum_faults'] ?? 3);
        $dailyLimit = (int) ($settings['appointments.daily_limit'] ?? 1);

        // Contar turnos activos del usuario
        $activeReservations = Reservation::where('user_id', $user->id)
            ->whereHas('availableAppointment', function ($query) {
                $query->whereNull('asistencia');
            })
            ->count();

        if (!$user->status) {
            return ['can_reserve' => false, 'message' => 'Su cuenta está inactiva.'];
        }

        if ($user->faults > $maxFaults) {
            return ['can_reserve' => false, 'message' => 'Has superado el límite de faltas permitidas.'];
        }

        if ($activeReservations >= $dailyLimit) {
            return ['can_reserve' => false, 'message' => 'Has alcanzado el límite de reservas activas.'];
        }

        return ['can_reserve' => true, 'message' => ''];
    }

    /**
     * Validar tiempo del turno
     */
    private function validateAppointmentTime($availableAppointment)
    {
        $turnoDateTime = Carbon::parse($availableAppointment->date)
            ->setTime(
                Carbon::parse($availableAppointment->time)->hour,
                Carbon::parse($availableAppointment->time)->minute,
                Carbon::parse($availableAppointment->time)->second
            );

        // Validar que no sea en el pasado
        if ($turnoDateTime->isPast()) {
            return ['valid' => false, 'message' => 'No se pueden reservar turnos en el pasado.'];
        }

        // Validar límite de tiempo configurado
        $settings = Setting::where('group', 'appointments')->pluck('value', 'key');
        $previewAmount = (int) ($settings['appointments.advance_reservation'] ?? 30);
        $previewUnit = $settings['appointments.unit_advance'] ?? 'day';

        $fechaLimite = match ($previewUnit) {
            'time' => now()->addHours($previewAmount),
            'month' => now()->addMonths($previewAmount),
            'day' => now()->addDays($previewAmount),
            default => now()->addDays($previewAmount)
        };

        if ($turnoDateTime > $fechaLimite) {
            return ['valid' => false, 'message' => "El turno excede el límite de reserva configurado."];
        }

        return ['valid' => true, 'message' => ''];
    }

    /**
     * Validar consistencia de datos
     */
    private function validateDataConsistency($availableAppointment, $specialtyId)
    {
        // Validar que la especialidad coincida
        if ($availableAppointment->specialty_id != $specialtyId) {
            return ['valid' => false, 'message' => 'La especialidad no coincide con el turno seleccionado.'];
        }

        return ['valid' => true, 'message' => ''];
    }

    /**
     * Validar estados de especialidad, doctor y turno
     */
    private function validateStatuses($availableAppointment, $specialtyId)
    {
        $specialtyStatus = Specialty::where('id', $specialtyId)->value('status');
        $doctorStatus = Doctor::where('id', $availableAppointment->doctor_id)->value('status');
        $appointmentStatus = Appointment::where('id', $availableAppointment->appointment_id)->value('status');

        if (!$specialtyStatus) {
            return ['valid' => false, 'message' => 'La especialidad seleccionada no está disponible.'];
        }

        if (!$doctorStatus) {
            return ['valid' => false, 'message' => 'El doctor asociado al turno no está disponible.'];
        }

        if (!$appointmentStatus) {
            return ['valid' => false, 'message' => 'El tipo de turno seleccionado no está disponible.'];
        }

        return ['valid' => true, 'message' => ''];
    }

    /**
     * Respuesta de éxito
     */
    private function successResponse($title, $message)
    {
        session()->flash('success', [
            'title' => $title,
            'text' => $message,
            'icon' => 'success',
        ]);
        return redirect()->route('profile.historial');
    }

    /**
     * Respuesta de error
     */
    private function errorResponse($title, $message)
    {
        session()->flash('error', [
            'title' => $title,
            'text' => $message,
            'icon' => 'error',
        ]);
        return back();
    }


    /*function store(ReservationStoreRequest $request)
    {
        $Available_appointment = AvailableAppointment::find($request->appointment_id);
        $user = Auth::user();
        //Contar cuantos appointments se ha reservado el user
        $turnos_activos = Reservation::where('user_id', $user->id)
            ->whereHas('availableAppointment', function ($query) {
                $query->whereNull('asistencia');
            })
            ->count();
        // Verificar si el user tiene permisos para reservar appointments
        $settings = Setting::where('group', 'appointments')->pluck('value', 'key');
        $turnos_faltas_maximas = $settings['appointments.maximum_faults'];
        $turnos_limite_diario = $settings['appointments.daily_limit'];

        if (
            $Available_appointment &&
            $user->faults <= $turnos_faltas_maximas &&
            $user->status &&
            $turnos_activos < $turnos_limite_diario
        ) {
            // Verificar si hay cupos disponibles
            if ($Available_appointment->available_spots <= 0) {
                session()->flash('error', [
                    'title' => 'Cupos no disponibles',
                    'text' => 'No hay cupos disponibles para el turno seleccionado. Seleccione otro horario o fecha',
                    'icon' => 'error',
                ]);
                return back();
            }
            // Verificar si la date y time son válidas
            $fechaHoraTurno = Carbon::parse($Available_appointment->date)->setTimeFrom(Carbon::parse($Available_appointment->time));
            $ahora = now();

            if ($fechaHoraTurno->lessThan($ahora)) {
                session()->flash('error', [
                    'title' => 'Fecha y hora inválidas',
                    'text' => 'La fecha y hora seleccionadas ya han pasado. Seleccione una fecha y hora futura.',
                    'icon' => 'error',
                ]);
                return back();
            } else {

                if (Auth::check()) {
                    // 1. Validar configuración de tiempo
                    $settings = Setting::where('group', 'appointments')->pluck('value', 'key');
                    $previewAmount = (int) ($settings['appointments.advance_reservation'] ?? 30);
                    $previewUnit = $settings['appointments.unit_advance'] ?? 'day';

                    $now = now();
                    $fechaLimite = match ($previewUnit) {
                        'time' => $now->copy()->addHours($previewAmount),
                        'month' => $now->copy()->addMonths($previewAmount),
                        'day' => $now->copy()->addDays($previewAmount),
                        default => $now->copy()->addDays($previewAmount)
                    };

                    // Si el campo 'date' tiene la fecha correcta y el campo 'time' tiene la hora correcta
                    //laravel incluye la fecha actual por defecto en los campos time como si fuese datetime. solucion:
                    $turnoDateTime = Carbon::parse($Available_appointment->date)
                        ->setTime(
                            Carbon::parse($Available_appointment->time)->hour,
                            Carbon::parse($Available_appointment->time)->minute,
                            Carbon::parse($Available_appointment->time)->second
                        );

                    if ($turnoDateTime <= $now) {
                        session()->flash('error', [
                            'title' => 'Turno no disponible',
                            'text' => 'El turno seleccionado ya ha pasado. Por favor, elige otro horario.',
                            'icon' => 'error',
                        ]);
                        return back();
                    }

                    if ($turnoDateTime > $fechaLimite) {
                        session()->flash('error', [
                            'title' => 'Turno fuera de rango',
                            'text' => "El turno seleccionado excede el límite de reserva configurado ($previewAmount $previewUnit).",
                            'icon' => 'error',
                        ]);
                        return back();
                    }

                    // 2. Validar disponibilidad
                    if ($Available_appointment->available_spots <= 0) {
                        session()->flash('error', [
                            'title' => 'Turno agotado',
                            'text' => 'El turno seleccionado ya no tiene disponibilidad. Por favor, elige otro horario.',
                            'icon' => 'error',
                        ]);
                        return back();
                    }

                    // 3. Validar duplicados
                    $existingReservation = Reservation::where('user_id', auth::id())
                        ->whereHas('availableAppointment', function ($query) use ($Available_appointment) {
                            $query->where('date', $Available_appointment->date)
                                ->where('time', $Available_appointment->time);
                        })
                        ->exists();

                    if ($existingReservation) {
                        session()->flash('error', [
                            'title' => 'Turno duplicado',
                            'text' => 'Ya tienes un turno reservado para esta misma fecha y hora.',
                            'icon' => 'error',
                        ]);
                        return back();
                    }

                    // 4. Validar estados y consistencia de datos (tu código original)
                    $specialtyStatus = Specialty::where('id', $request->input('specialty_id'))->value('status');
                    $doctorStatus = Doctor::where('id', $Available_appointment->appointment->doctor_id)->value('status');
                    $appointmentStatus = Appointment::where('id', $Available_appointment->appointment_id)->value('status');

                    $specialtyMatch = AvailableAppointment::where('id', $Available_appointment->id)
                        ->where('specialty_id', $request->input('specialty_id'))
                        ->where('doctor_id', $Available_appointment->doctor_id)
                        ->exists();

                    $appointmentMatch = Appointment::where('id', $Available_appointment->appointment_id)
                        ->where('specialty_id', $request->input('specialty_id'))
                        ->where('doctor_id', $Available_appointment->doctor_id)
                        ->exists();

                    if (!$specialtyMatch && !$appointmentMatch) {
                        session()->flash('error', [
                            'title' => 'Datos inconsistentes',
                            'text' => 'La especialidad seleccionada no coincide con el doctor asociado al turno. Por favor, verifica los datos.',
                            'icon' => 'error',
                        ]);
                        return back();
                    }

                    if (!$specialtyStatus || !$doctorStatus || !$appointmentStatus) {
                        if (!$specialtyStatus) {
                            session()->flash('error', [
                                'title' => 'Especialidad no disponible',
                                'text' => 'La especialidad seleccionada no está disponible. Por favor, elige otra.',
                                'icon' => 'error',
                            ]);
                            return back();
                        }
                        if (!$doctorStatus) {
                            session()->flash('error', [
                                'title' => 'Doctor no disponible',
                                'text' => 'El doctor asociado al turno seleccionado no está disponible. Por favor, elige otro.',
                                'icon' => 'error',
                            ]);
                            return back();
                        }
                        if (!$appointmentStatus) {
                            session()->flash('error', [
                                'title' => 'Turno no disponible',
                                'text' => 'El turno seleccionado no está disponible. Por favor, elige otro.',
                                'icon' => 'error',
                            ]);
                            return back();
                        }
                    }

                    // 5. Si pasa todas las validaciones, proceder con la reserva
                    $Available_appointment->available_spots -= 1;
                    $Available_appointment->reserved_spots += 1;
                    $Available_appointment->save();

                    $reservation = new Reservation();
                    $reservation->user_id = auth::id();
                    $reservation->available_appointment_id = $Available_appointment->id;
                    $reservation->specialty_id = $request->input('specialty_id');
                    $reservation->save();

                    session()->flash('success', [
                        'title' => 'Reserva exitosa',
                        'text' => 'Se ha reservado un turno con éxito',
                        'icon' => 'success',
                    ]);
                    return redirect()->route('profile.historial');
                } else {
                    return response()->json(['error' => 'Usuario no autenticado'], 401);
                }
            }
        } else {
            if (
                !$user->status &&
                $user->faults >= $turnos_faltas_maximas &&
                $turnos_activos >= $turnos_limite_diario &&
                $turnos_limite_diario > 0
            ) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => '1. Su cuenta está inactiva. <br>2. Has alcanzado el límite de faltas permitidas. <br>3. Has alcanzado el límite de reservas activas permitidos.<br> Contacta al administrador.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } elseif (!$user->status) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => 'Su cuenta está inactiva.<br> Contacta al administrador.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } elseif ($user->faults > $turnos_faltas_maximas) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => 'Has alcanzado el límite de faltas permitidas.<br> No puedes realizar más reservas.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } elseif ($user->turnos_activos >= $turnos_limite_diario) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => 'Has alcanzado el límite de reservas activas permitidas.<br> Asiste a los reservas solicitadas antes de solicitar una nueva.<br> No puedes reservar más.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } elseif ($turnos_limite_diario <= 0) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => 'No puedes solicitar más reservas en este momento. El límite de reservas activas ha sido deshabilitada.<br>Por favor, regresa más tarde.<br>Si tenés dudas, contactá al administrador.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } else {
                session()->flash('error', [
                    'title' => 'Error al reservar',
                    'html' => 'Error al ejecutar la petición.<br> Contacta al administrador.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            }
        }
    }*/

    //Permite eliminar la reserva al usuario y al administrador
    //Eliminar reservation y actualizar los cupos disponibles
    public function destroy($id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            $user = Auth::user();
            $availableAppointment = AvailableAppointment::find($reservation->available_appointment_id);

            if (!$availableAppointment) {
                throw new \Exception('Reserva asociada no encontrada');
            }

            // Verificación de si el appointment ya pasó
            $fechaHoraTurno = Carbon::parse($availableAppointment->date->format('Y-m-d') . ' ' . $availableAppointment->time->format('H:i:s'));

            if ($fechaHoraTurno->isPast()) {
                session()->flash('error', [
                    'title' => 'Error!',
                    'text' => 'No puedes cancelar una reserva que ya ha pasado.',
                    'icon' => 'error'
                ]);
                /** @var \App\Models\User $user */
                return $user->hasRole('user') ? redirect()->route('profile.historial') : redirect()->route('reservations.index');
            }
            // Verificación de límite de cancelación para pacientes
            /** @var \App\Models\User $user */
            if ($user->hasRole('user')) {
                $settings = Setting::where('group', 'appointments')->pluck('value', 'key');
                $horasLimiteCancelacion = $settings['appointments.cancellation_hours'] ?? 24;

                $horasRestantes = now()->diffInHours($fechaHoraTurno, false);

                if ($horasRestantes < $horasLimiteCancelacion) {
                    session()->flash('error', [
                        'title' => 'Error!',
                        'text' => "No puedes cancelar la reserva. Debes cancelar con al menos {$horasLimiteCancelacion} horas de anticipación.",
                        'icon' => 'error'
                    ]);
                    return redirect()->route('profile.historial');
                }
            }

            // Lógica de cancelación y actualización de cupos
            $availableAppointment->available_spots++;
            $availableAppointment->reserved_spots = max(0, $availableAppointment->reserved_spots - 1);

            if ($availableAppointment->reserved_spots < 0) {
                $availableAppointment->reserved_spots = 0;
            }

            DB::beginTransaction();

            try {
                $availableAppointment->save();
                $reservation->delete();
                DB::commit();
                if ($user->hasRole('doctor') || $user->hasRole('admin')) {
                    session()->flash('success', [
                        'title' => 'Reserva eliminada',
                        'text' => 'La reserva ha sido cancelada y los cupos se han actualizado correctamente.',
                        'icon' => 'success'
                    ]);
                    return redirect()->route('reservations.index');
                } else if ($user->hasRole('user')) {
                    session()->flash('success', [
                        'title' => 'Reserva cancelada',
                        'text' => 'La reserva del turno ha sido cancelada.',
                        'icon' => 'success'
                    ]);
                    return redirect()->route('profile.historial');
                }
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Reserva no encontrada', ['id' => $id, 'error' => $e->getMessage()]);
            session()->flash('error', [
                'title' => 'Error!',
                'text' => 'Reserva no encontrada.',
                'icon' => 'error'
            ]);
            return redirect()->route('reservations.index');
        } catch (\Exception $e) {
            Log::error('Error al cancelar reserva', ['id' => $id, 'error' => $e->getMessage()]);
            session()->flash('error', [
                'title' => 'Error!',
                'text' => 'Ocurrió un error al cancelar la reserva: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
            return redirect()->route('reservations.index');
        }
    }
}
