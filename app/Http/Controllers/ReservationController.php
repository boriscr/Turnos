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
            $user->status == 1 &&
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
                $user->faults > $turnos_faltas_maximas &&
                $user->status == 0 &&
                $turnos_activos >= $turnos_limite_diario
            ) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => '1. Has alcanzado el límite de faltas permitidas.<br>2. Su cuenta está inactiva.<br> Contacta al administrador.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } elseif ($user->status == 0) {
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
        $today = now()->format('Y-m-d');
        $horaActual = now()->format('H:i:s');

        // Obtener configuración de previsualización
        $settings = Setting::where('group', 'appointments')->pluck('value', 'key');

        $previewAmount = (int) ($settings['appointments.advance_reservation'] ?? 30); // Valor por defecto
        $previewUnit = $settings['appointments.unit_advance'] ?? 'day'; // Valor por defecto

        // Calcular date límite según configuración
        $fechaLimite = now();

        switch ($previewUnit) {
            case 'time':
                $fechaLimite->addHours($previewAmount);
                break;
            case 'month':
                $fechaLimite->addMonths($previewAmount);
                break;
            case 'day':
                $fechaLimite->addDays($previewAmount);
                break;
            default:
                $fechaLimite->addDays($previewAmount);
                break;
        }

        $fechaLimite = $fechaLimite->format('Y-m-d');
        $appointments = AvailableAppointment::where('appointment_id', $appointment_name_id)
            ->where('available_spots', '>', 0)
            ->whereDate('date', '<=', $fechaLimite) // Filtro superior
            ->where(function ($query) use ($today, $horaActual) {
                $query->whereDate('date', '>', $today)
                    ->orWhere(function ($q) use ($today, $horaActual) {
                        $q->whereDate('date', $today)
                            ->whereTime('time', '>=', $horaActual);
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

    function confirmReservation(Request $request)
    {
        $appointment = AvailableAppointment::find($request->appointment_id);
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

        if ($appointment && $user->faults <= $turnos_faltas_maximas && $user->status == 1 && $turnos_activos < $turnos_limite_diario) {
            // Verificar si hay cupos disponibles
            if ($appointment->available_spots <= 0) {
                session()->flash('error', [
                    'title' => 'Cupos no disponibles',
                    'text' => 'No hay cupos disponibles para el turno seleccionado. Seleccione otro horario o fecha',
                    'icon' => 'error',
                ]);

                return back();
            }
            // Verificar si la date y time son válidas
            $fechaHoraTurno = Carbon::parse($appointment->date)->setTimeFrom(Carbon::parse($appointment->time));
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
                    // Reservar el appointment
                    $appointment->available_spots -= 1;
                    $appointment->reserved_spots += 1;
                    $appointment->save();
                    //guardar los datos del user que reservation el appointment
                    $reservation = new Reservation();
                    // Asigna los valores correctamente
                    $reservation->user_id = auth::id(); // ID del user autenticado
                    $reservation->available_appointment_id = $appointment->id;
                    $reservation->specialty_id = $request->input('specialty_id');
                    $reservation->save();
                } else {
                    return response()->json(['error' => 'Usuario no autenticado'], 401);
                }

                session()->flash('success', [
                    'title' => 'Reserva exitosa',
                    'text' => 'Se ha reservado un turno con éxito',
                    'icon' => 'success',
                ]);
                return redirect()->route('profile.historial');
            }
        } else {
            if ($user->status == 1 && $user->faults <= $turnos_faltas_maximas &&  $turnos_activos >= $turnos_limite_diario && $turnos_limite_diario > 0) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => '1. Has alcanzado el límite de faltas permitidas.<br>2. Su cuenta está inactiva.<br>3. Has alcanzado el límite de reservas activas permitidos.<br> Contacta al administrador.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } elseif ($user->status == 0) {
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
    }

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
