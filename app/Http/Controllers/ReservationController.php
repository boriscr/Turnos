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
use Illuminate\Http\RedirectResponse;
use App\Models\AppointmentHistory;

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
                        $userQuery->Where('idNumber', 'like', "%$search%");
                    });
                });
            })
            ->when(in_array($reservaFiltro, ['assisted', 'pending', 'not_attendance']), function ($query) use ($reservaFiltro) {
                switch ($reservaFiltro) {
                    case 'assisted':
                        $query->where('status', '=', 'assisted');
                        break;
                    case 'pending':
                        $query->where('status', '=', 'pending');
                        break;
                    case 'not_attendance':
                        $query->where('status', '=', 'not_attendance');
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

    public function actualizarstatus(Request $request, Reservation $reservation)
    {
        DB::transaction(function () use ($reservation, $request) {
            $estadoAnterior = $reservation->status;

            // Determinar el nuevo estado basado en el request
            if ($request->filled('status')) {
                $nuevoEstado = $request->status; // Usar directamente el valor del enum
            } else {
                // Comportamiento de toggle para el nuevo sistema enum
                if ($estadoAnterior === 'pending') {
                    $nuevoEstado = 'not_attendance';
                } else if ($estadoAnterior === 'assisted') {
                    $nuevoEstado = 'not_attendance';
                } else { // not_attendance
                    $nuevoEstado = 'assisted';
                }
            }

            $reservation->status = $nuevoEstado;
            $reservation->save();

            if ($reservation->user) {
                $this->gestionarFaltas($reservation, $estadoAnterior, $nuevoEstado);
            }

            // Actualizar el historial de citas
            $appointmentHistory = AppointmentHistory::where('reservation_id', $reservation->id)->first();
            if ($appointmentHistory) {
                $appointmentHistory->update([
                    'status' => $nuevoEstado === 'assisted' ? 'assisted' : 'not_attendance',
                    'updated_at' => now(),
                ]);
            }
        });

        return back()->with('success', 'Estado de status actualizado correctamente');
    }

    // php artisan schedule:work
    public function checkStatusAutomatically()
    {
        Log::info('Iniciando verificación automática de status');

        $settings = Setting::where('group', 'appointments')->pluck('value', 'key');
        $hora_status = (int) ($settings['assists.verification_interval'] ?? 1);
        $now = Carbon::now();
        $verificationTime = $now->copy()->subHours($hora_status);

        Log::info("Parámetros - Hora status: {$hora_status}, Now: {$now}, Verification Time: {$verificationTime}");

        $actualizadas = 0;

        // ✅ OPTIMIZACIÓN: Usar chunk para evitar memory leaks con muchas reservas
        Reservation::with(['availableAppointment.appointment', 'user'])
            ->where('status', 'pending')
            ->whereHas('availableAppointment', function ($query) use ($now, $verificationTime) {
                $query->where(function ($q) use ($now, $verificationTime) {
                    $q->where('date', '<', $now->toDateString())
                        ->orWhere(function ($q2) use ($now, $verificationTime) {
                            $q2->where('date', $now->toDateString())
                                ->where('time', '<=', $verificationTime->toTimeString());
                        });
                });
            })
            ->chunk(100, function ($reservations) use (&$actualizadas) {
                foreach ($reservations as $reservation) {
                    try {
                        DB::transaction(function () use ($reservation, &$actualizadas) {
                            $estadoAnterior = $reservation->status;
                            $reservation->status = 'not_attendance';
                            $reservation->save();

                            if (
                                $reservation->user &&
                                $reservation->availableAppointment &&
                                $reservation->availableAppointment->appointment &&
                                $reservation->availableAppointment->appointment->status === true
                            ) {
                                $reservation->user->increment('faults');
                            }

                            $appointmentHistory = AppointmentHistory::where('reservation_id', $reservation->id)->first();
                            if ($appointmentHistory) {
                                $appointmentHistory->update([
                                    'status' => 'not_attendance',
                                    'updated_at' => now(),
                                ]);
                            }

                            $actualizadas++;
                        });
                    } catch (\Exception $e) {
                        Log::error("Error procesando reserva {$reservation->id}: " . $e->getMessage());
                    }
                }
            });

        Log::info("Verificación automática completada. Reservas actualizadas: {$actualizadas}");

        return [
            'message' => 'Estados verificados automáticamente',
            'reservas_actualizadas' => $actualizadas
        ];
    }


    protected function gestionarFaltas(Reservation $reservation, $estadoAnterior, $nuevoEstado)
    {
        // Si el nuevo estado es "No Asistió" y antes no lo era
        if ($nuevoEstado === 'not_attendance' && $estadoAnterior !== 'not_attendance') {
            $reservation->user->increment('faults');
        }

        // Si el nuevo estado es "Asistió" y antes era "No Asistió"
        if ($nuevoEstado === 'assisted' && $estadoAnterior === 'not_attendance') {
            if ($reservation->user->faults > 0) {
                $reservation->user->decrement('faults');
            }
        }
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
                $query->where('status', '=', 'pending');
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
            // Simular procesamiento que podría causar condiciones de carrera
            usleep(rand(2000000, 4000000)); // Retardo aleatorio entre 2-4 segundos
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
                $available = AvailableAppointment::where('id', $request->appointment_id)
                    ->firstOrFail();
                //$reservation = Reservation::with('availableAppointment')->findOrFail($available->id);
                $reservation = Reservation::where('available_appointment_id', $available->id)->firstOrFail();
                $this->storeAppointmentHistoryStatus($reservation);
                return $this->successResponse('Reserva exitosa', 'Turno reservado correctamente.');
            });
        } catch (\Exception $e) {
            return $this->errorResponse('Error en la reserva', $e->getMessage());
        }
    }
    protected function storeAppointmentHistoryStatus($reservation): void
    {
        // Verificar si ya existe un historial para esta reservación
        $appointmentHistory = AppointmentHistory::where('reservation_id', $reservation->id)
            ->first();

        // Obtener la cita disponible
        $availableAppointment = AvailableAppointment::with(['appointment', 'doctor', 'specialty'])
            ->find($reservation->available_appointment_id);

        if (!$appointmentHistory) {
            // Crear nuevo historial
            AppointmentHistory::create([
                'appointment_id' => $availableAppointment->appointment_id ?? null,
                'appointment_name' => $availableAppointment->appointment->name ?? 'Desconocido',
                'reservation_id' => $reservation->id,
                'user_id' => $reservation->user_id,
                'doctor_name' => $availableAppointment ?
                    ($availableAppointment->doctor->name . ' ' . $availableAppointment->doctor->surname) :
                    'Doctor no disponible',
                'specialty' => $availableAppointment->specialty->name ?? 'Desconocida',
                'appointment_date' => $availableAppointment->date ?? $reservation->date,
                'appointment_time' => $availableAppointment->time ?? $reservation->time,
                'status' => 'pending',
            ]);
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
                $query->where('status', '=', 'pending');
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
        return redirect()->route('myAppointments.index');
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



    /**
     * Elimina una reserva (solo para administradores)
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $reservation = Reservation::with('availableAppointment')->findOrFail($id);

            if (!$reservation->availableAppointment) {
                throw new \Exception('La cita disponible asociada no fue encontrada');
            }

            DB::transaction(function () use ($reservation) {
                // Actualizar el estado en appointment_histories
                $this->updateAppointmentHistoryStatus($reservation);
                // Actualizar cupos y eliminar reserva
                $this->updateAppointmentSpots($reservation->availableAppointment);
                $reservation->delete();
            });

            $this->flashSuccess(
                'Reserva eliminada',
                'La reserva ha sido cancelada y los cupos se han actualizado correctamente.'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Reserva no encontrada', ['id' => $id, 'error' => $e->getMessage()]);
            $this->flashError('Error!', 'Reserva no encontrada.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar reserva', ['id' => $id, 'error' => $e->getMessage()]);
            $this->flashError('Error!', 'Ocurrió un error al eliminar la reserva.');
        }

        return redirect()->route('reservations.index');
    }

    /**
     * Actualiza los cupos disponibles de la cita
     */
    protected function updateAppointmentSpots(AvailableAppointment $availableAppointment): void
    {
        $availableAppointment->available_spots++;
        $availableAppointment->reserved_spots = max(0, $availableAppointment->reserved_spots - 1);
        $availableAppointment->save();
    }
    /**
     * Funcion para cambiar el status de historial de appointment
     */
    protected function updateAppointmentHistoryStatus($reservation): void
    {
        // Verificar si ya existe un historial para esta reservación
        $appointmentHistory = AppointmentHistory::where('reservation_id', $reservation->id)
            ->first();
        if ($appointmentHistory) {
            // Actualizar status del historial
            $appointmentHistory->update([
                'status' => 'cancelled_by_admin',
                'cancelled_by' => Auth::id(),
                'cancelled_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Flash message de éxito
     */
    protected function flashSuccess(string $title, string $text): void
    {
        session()->flash('success', [
            'title' => $title,
            'text' => $text,
            'icon' => 'success'
        ]);
    }

    /**
     * Flash message de error
     */
    protected function flashError(string $title, string $text): void
    {
        session()->flash('error', [
            'title' => $title,
            'text' => $text,
            'icon' => 'error'
        ]);
    }
}
