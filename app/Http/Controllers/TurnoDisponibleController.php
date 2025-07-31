<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\TurnoDisponible;
use App\Models\Turno;
use App\Models\Specialty;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use Carbon\Carbon;


class TurnoDisponibleController extends Controller
{

    public function create()
    {
        $settings = Setting::where('group', 'turnos')->pluck('value', 'key');
        $turnos_antelacion_reserva = $settings['turnos.antelacion_reserva'];
        $turnos_faltas_maximas = $settings['turnos.faltas_maximas'];
        $turnos_horas_cancelacion = $settings['turnos.horas_cancelacion'];
        $turnos_limite_diario = $settings['turnos.limite_diario'];
        $turnos_unidad_antelacion = $settings['turnos.unidad_antelacion'];
        /*dd($turnos_antelacion_reserva, $turnos_faltas_maximas, $turnos_horas_cancelacion, $turnos_limite_diario, $turnos_unidad_antelacion);*/
        $user = Auth::user();
        $turnos_activos = Reserva::where('user_id', $user->id)
            ->whereHas('turnoDisponible', function ($query) {
                $query->whereNull('asistencia');
            })
            ->count();
        if ($user->status == 1 && $user->faults <= $turnos_faltas_maximas &&  $turnos_activos < $turnos_limite_diario && $turnos_limite_diario > 0) {
            $turnoDisponible = TurnoDisponible::all();
            $turno = Turno::where('status', 1)->get();
            $specialties = Specialty::where('status', 1)->get();
            return view('reservas/create', compact('turnoDisponible', 'turno', 'specialties'));
        } else {
            if ($user->faults > $turnos_faltas_maximas && $user->status == 0 && $turnos_activos >= $turnos_limite_diario) {
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
                    'html' => 'Has alcanzado el límite de faltas permitidas.<br> No puedes reservar turnos.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } elseif ($turnos_activos >= $turnos_limite_diario) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => 'Has alcanzado el límite de turnos activos permitidos.<br> Asiste a los turnos solicitados antes de solicitar uno nuevo.<br> No puedes reservar más turnos.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } elseif ($turnos_limite_diario <= 0) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => 'No puedes reservar turnos en este momento. El límite de turnos activos ha sido deshabilitado.<br>Por favor, regresa más tarde.<br>Si tenés dudas, contactá al administrador.',
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
    // 1. Obtener turnos por name
    public function getTurnosPorNombre($doctor_id)
    {
        if ($doctor_id) {
            $turnos = Turno::where('doctor_id', $doctor_id)
                ->where('status', 1)
                ->get();

            return response()->json(['turnos' => $turnos]);
        } else {
            return response()->json([]);
        }
    }
    // 2. Obtener turnos por doctor (filtrado por fecha/hora)
    //Filtrado por fecha y hora configurado en settings
    // Devuelve los turnos disponibles para un médico específico, filtrando por fecha y hora
    // y aplicando la configuración de previsualización de turnos.
    // La previsualización se basa en la configuración de la ventana de tiempo definida en la base de datos.
    // La función también maneja la lógica de filtrado para mostrar solo los turnos que están disponibles
    // y que son futuros, teniendo en cuenta la fecha y hora actuales.
    // El resultado se devuelve en formato JSON, incluyendo los turnos disponibles y la configuración de
    // previsualización utilizada para la consulta.
    public function getTurnosPorEquipo($turno_nombre_id)
    {
        $hoy = now()->format('Y-m-d');
        $horaActual = now()->format('H:i:s');

        // Obtener configuración de previsualización
        $settings = Setting::where('group', 'turnos')->pluck('value', 'key');
        /*
        $turnos_antelacion_reserva = $settings['turnos.antelacion_reserva'] ?? 30;
        $turnos_unidad_antelacion  = $settings['turnos.unidad_antelacion'] ?? 'dia';
*/
        $previewAmount = (int) ($settings['turnos.antelacion_reserva'] ?? 30); // Valor por defecto
        $previewUnit = $settings['turnos.unidad_antelacion'] ?? 'dia'; // Valor por defecto

        // Calcular fecha límite según configuración
        $fechaLimite = now();

        switch ($previewUnit) {
            case 'hora':
                $fechaLimite->addHours($previewAmount);
                break;
            case 'mes':
                $fechaLimite->addMonths($previewAmount);
                break;
            case 'dia':
                $fechaLimite->addDays($previewAmount);
                break;
            default:
                $fechaLimite->addDays($previewAmount);
                break;
        }

        $fechaLimite = $fechaLimite->format('Y-m-d');
        $turnos = TurnoDisponible::where('turno_id', $turno_nombre_id)
            ->where('cupos_disponibles', '>', 0)
            ->whereDate('fecha', '<=', $fechaLimite) // Filtro superior
            ->where(function ($query) use ($hoy, $horaActual) {
                $query->whereDate('fecha', '>', $hoy)
                    ->orWhere(function ($q) use ($hoy, $horaActual) {
                        $q->whereDate('fecha', $hoy)
                            ->whereTime('hora', '>=', $horaActual);
                    });
            })
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get()
            ->map(function ($turno) {
                return [
                    'id' => $turno->id,
                    'fecha' => $turno->fecha,
                    'hora' => $turno->hora ? \Carbon\Carbon::parse($turno->hora)->format('H:i') : null,
                ];
            });

        return response()->json([
            'turnos' => $turnos,
            'preview_settings' => [
                'amount' => $previewAmount,
                'unit' => $previewUnit
            ]
        ]);
    }

    function reservarTurno(Request $request)
    {
        $turno = TurnoDisponible::find($request->turno_id);
        $user = Auth::user();
        //Contar cuantos turnos se ha reservado el user
        $turnos_activos = Reserva::where('user_id', $user->id)
            ->whereHas('turnoDisponible', function ($query) {
                $query->whereNull('asistencia');
            })
            ->count();
        // Verificar si el user tiene permisos para reservar turnos
        $settings = Setting::where('group', 'turnos')->pluck('value', 'key');
        $turnos_faltas_maximas = $settings['turnos.faltas_maximas'];
        $turnos_limite_diario = $settings['turnos.limite_diario'];

        if ($turno && $user->faults <= $turnos_faltas_maximas && $user->status == 1 && $turnos_activos < $turnos_limite_diario) {
            // Verificar si hay cupos disponibles
            if ($turno->cupos_disponibles <= 0) {
                session()->flash('error', [
                    'title' => 'Cupos no disponibles',
                    'text' => 'No hay cupos disponibles para el turno seleccionado. Seleccione otro horario o fecha',
                    'icon' => 'error',
                ]);

                return back();
            }
            // Verificar si la fecha y hora son válidas
            $fechaHoraTurno = Carbon::parse($turno->fecha)->setTimeFrom(Carbon::parse($turno->hora));
            $ahora = now();

            if ($fechaHoraTurno->lessThan($ahora)) {
                session()->flash('error', [
                    'title' => 'Fecha y hora inválidas',
                    'text' => 'La fecha y hora seleccionadas ya han pasado. Seleccione una fecha y hora futura.',
                    'icon' => 'error',
                ]);
                return back();
            } else {
                // Reservar el turno
                $turno->cupos_disponibles -= 1;
                $turno->cupos_reservados += 1;
                $turno->save();
                //guardar los datos del user que reserva el turno
                $reserva = new Reserva();
                if (Auth::check()) {
                    // Asigna los valores correctamente
                    $reserva->user_id = auth::id(); // ID del user autenticado
                    $reserva->turno_disponible_id = $turno->id;
                    $reserva->save();
                } else {
                    return response()->json(['error' => 'Usuario no autenticado'], 401);
                }

                //return response()->json(['success' => true]);
                session()->flash('success', [
                    'title' => 'Turno reservado',
                    'text' => 'El turno ha sido reservado con éxito',
                    'icon' => 'success',
                ]);
                return redirect()->route('profile.historial');
            }
        } else {
            if ($user->status == 1 && $user->faults <= $turnos_faltas_maximas &&  $turnos_activos >= $turnos_limite_diario && $turnos_limite_diario > 0) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => '1. Has alcanzado el límite de faltas permitidas.<br>2. Su cuenta está inactiva.<br>3. Has alcanzado el límite de turnos activos permitidos.<br> Contacta al administrador.',
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
                    'html' => 'Has alcanzado el límite de faltas permitidas.<br> No puedes reservar turnos.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } elseif ($user->turnos_activos >= $turnos_limite_diario) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => 'Has alcanzado el límite de turnos activos permitidos.<br> Asiste a los turnos solicitados antes de solicitar uno nuevo.<br> No puedes reservar más turnos.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } elseif ($turnos_limite_diario <= 0) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => 'No puedes reservar turnos en este momento. El límite de turnos activos ha sido deshabilitado.<br>Por favor, regresa más tarde.<br>Si tenés dudas, contactá al administrador.',
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

    //Eliminar reserva y actualizar los cupos disponibles
    public function destroy($id)
    {
        try {
            /** @var \App\Models\Reserva $reserva */
            $reserva = Reserva::findOrFail($id);

            /** @var \App\Models\User $user */
            $user = Auth::user();

            /** @var \App\Models\TurnoDisponible $turnoDisponible */
            $turnoDisponible = TurnoDisponible::find($reserva->turno_disponible_id);

            if (!$turnoDisponible) {
                throw new \Exception('Turno asociado no encontrado');
            }

            // Verificación de si el turno ya pasó (aplica para todos los roles)
            $fechaHoraTurno = Carbon::parse($turnoDisponible->fecha->format('Y-m-d') . ' ' . $turnoDisponible->hora->format('H:i:s'));

            if ($fechaHoraTurno->isPast()) {
                session()->flash('error', [
                    'title' => 'Error!',
                    'text' => 'No puedes cancelar un turno que ya ha pasado.',
                    'icon' => 'error'
                ]);
                return $user->hasRole('user') ? redirect()->route('profile.historial') : redirect()->route('reservas.index');
            }

            // Verificación de roles con type hints
            if ($user->hasRole('user')) {
                $settings = Setting::where('group', 'turnos')->pluck('value', 'key');
                $turnos_horas_cancelacion = $settings['turnos.horas_cancelacion'];

                $horasLimiteCancelacion = $turnos_horas_cancelacion ?? 24;
                try {
                    // Opción 1: Si ya es un objeto DateTime/Carbon
                    if ($turnoDisponible->hora instanceof \DateTimeInterface) {
                        $horaTurno = Carbon::instance($turnoDisponible->hora);
                    }
                    // Opción 2: Si es un string en formato 'H:i:s'
                    elseif (is_string($turnoDisponible->hora) && preg_match('/^\d{2}:\d{2}:\d{2}$/', $turnoDisponible->hora)) {
                        $horaTurno = Carbon::createFromFormat('H:i:s', $turnoDisponible->hora);
                    }
                    // Opción 3: Si es un string en formato 'H:i'
                    elseif (is_string($turnoDisponible->hora) && preg_match('/^\d{2}:\d{2}$/', $turnoDisponible->hora)) {
                        $horaTurno = Carbon::createFromFormat('H:i', $turnoDisponible->hora);
                    }
                    // Opción 4: Si es un timestamp o formato no reconocido
                    else {
                        $horaTurno = Carbon::parse($turnoDisponible->hora);
                    }

                    // Calcular diferencia de horas
                    $horasRestantes = now()->diffInHours($fechaHoraTurno, false);

                    if ($horasRestantes < $horasLimiteCancelacion) {
                        session()->flash('error', [
                            'title' => 'Error!',
                            'text' => "No puedes cancelar el turno. Debes cancelar con al menos {$horasLimiteCancelacion} horas de anticipación.",
                            'icon' => 'error'
                        ]);
                        return redirect()->route('profile.historial');
                    }
                } catch (\Exception $e) {
                    Log::error('Error al procesar hora del turno', [
                        'hora_turno' => $turnoDisponible->hora,
                        'error' => $e->getMessage()
                    ]);

                    session()->flash('error', [
                        'title' => 'Error!',
                        'text' => 'Ocurrió un error al procesar el horario del turno.',
                        'icon' => 'error'
                    ]);
                    return redirect()->route('profile.historial');
                }
            }

            // Resto de la lógica de cancelación...
            $turnoDisponible->cupos_disponibles += 1;
            $turnoDisponible->cupos_reservados -= 1;

            if ($turnoDisponible->cupos_reservados < 0) {
                $turnoDisponible->cupos_reservados = 0;
            }

            DB::beginTransaction();

            try {
                $turnoDisponible->save();
                $reserva->delete();

                DB::commit();

                if ($user->hasRole('doctor') || $user->hasRole('admin')) {
                    session()->flash('success', [
                        'title' => 'Reserva eliminada',
                        'text' => 'La reserva ha sido cancelada y los cupos se han actualizado correctamente.',
                        'icon' => 'success'
                    ]);
                    return redirect()->route('reservas.index');
                } else if ($user->hasRole('user')) {
                    session()->flash('success', [
                        'title' => 'Reserva cancelada',
                        'text' => 'La reserva de su turno ha sido cancelada.',
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
            return redirect()->route('reservas.index');
        } catch (\Exception $e) {
            Log::error('Error al cancelar reserva', ['id' => $id, 'error' => $e->getMessage()]);
            session()->flash('error', [
                'title' => 'Error!',
                'text' => 'Ocurrió un error al cancelar la reserva: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
            return redirect()->route('reservas.index');
        }
    }
}
