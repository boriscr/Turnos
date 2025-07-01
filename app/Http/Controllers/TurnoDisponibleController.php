<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use Illuminate\Http\Request;
use App\Models\TurnoDisponible;
use App\Models\Turno;
use App\Models\Especialidad;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;



class TurnoDisponibleController extends Controller
{

    public function create()
    {
        $settings = Setting::first();
        $user = Auth::user();
        $turnos_activos = Reserva::where('user_id', $user->id)
            ->whereHas('turnoDisponible', function ($query) {
                $query->whereNull('asistencia');
            })
            ->count();
        if ($user->estado == 1 && $user->faults <= $settings->faltas &&  $turnos_activos < $settings->limites && $settings->limites > 0) {
            $turnoDisponible = TurnoDisponible::all();
            $turno = Turno::where('estado', 1)->get();
            $especialidades = Especialidad::where('estado', 1)->get();
            return view('reservas/create', compact('turnoDisponible', 'turno', 'especialidades'));
        } else {
            if ($user->faults > $settings->faltas && $user->estado == 0 && $turnos_activos >= $settings->limites) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => '1. Has alcanzado el límite de faltas permitidas.<br>2. Su cuenta está inactiva.<br> Contacta al administrador.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } elseif ($user->estado == 0) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => 'Su cuenta está inactiva.<br> Contacta al administrador.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } elseif ($user->faults > $settings->faltas) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => 'Has alcanzado el límite de faltas permitidas.<br> No puedes reservar turnos.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } elseif ($turnos_activos >= $settings->limites) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => 'Has alcanzado el límite de turnos activos permitidos.<br> Asiste a los turnos solicitados antes de solicitar uno nuevo.<br> No puedes reservar más turnos.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } elseif ($settings->limites <= 0) {
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


    // 1. Obtener medicos por especialidad
    public function getMedicosPorEspecialidad($especialidad_id)
    {
        $medicos = Medico::where('especialidad_id', $especialidad_id)
            ->where('estado', 1)
            ->get();

        return response()->json(['medicos' => $medicos]);
    }

    // 2. Obtener turnos por medico (filtrado por fecha/hora)
    //Filtrado por fecha y hora configurado en settings
    // Devuelve los turnos disponibles para un médico específico, filtrando por fecha y hora
    // y aplicando la configuración de previsualización de turnos.
    // La previsualización se basa en la configuración de la ventana de tiempo definida en la base de datos.
    // La función también maneja la lógica de filtrado para mostrar solo los turnos que están disponibles
    // y que son futuros, teniendo en cuenta la fecha y hora actuales.
    // El resultado se devuelve en formato JSON, incluyendo los turnos disponibles y la configuración de
    // previsualización utilizada para la consulta.
    public function getTurnosPorEquipo($medico_id)
    {
        $hoy = now()->format('Y-m-d');
        $horaActual = now()->format('H:i:s');

        // Obtener configuración de previsualización
        $previewWindow = Setting::first();
        $previewAmount = $previewWindow->preview_window_amount ?? 30; // Valor por defecto
        $previewUnit = $previewWindow->preview_window_unit ?? 'dia'; // Valor por defecto

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
            default:
                $fechaLimite->addDays($previewAmount);
                break;
        }

        $fechaLimite = $fechaLimite->format('Y-m-d');

        $turnos = TurnoDisponible::where('medico_id', $medico_id)
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
        //Contar cuantos turnos se ha reservado el usuario
        $turnos_activos = Reserva::where('user_id', $user->id)
            ->whereHas('turnoDisponible', function ($query) {
                $query->whereNull('asistencia');
            })
            ->count();
        // Verificar si el usuario tiene permisos para reservar turnos
        $settings = Setting::first();
        if ($turno && $user->faults <= $settings->faltas && $user->estado == 1 && $turnos_activos < $settings->limites) {
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
            $hoy = now()->format('Y-m-d');
            $horaActual = now()->format('H:i:s');
            if ($turno->fecha < $hoy || ($turno->fecha == $hoy && $turno->hora < $horaActual)) {
                return response()->json(['success' => false, 'message' => 'La fecha y hora seleccionadas ya han pasado']);
            }
            // Reservar el turno
            $turno->cupos_disponibles -= 1;
            $turno->cupos_reservados += 1;
            $turno->save();
            //guardar los datos del usuario que reserva el turno
            $reserva = new Reserva();
            if (Auth::check()) {
                // Asigna los valores correctamente
                $reserva->user_id = auth::id(); // ID del usuario autenticado
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
        } else {
            if ($user->estado == 1 && $user->faults <= $settings->faltas &&  $turnos_activos >= $settings->limites && $settings->limites > 0) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => '1. Has alcanzado el límite de faltas permitidas.<br>2. Su cuenta está inactiva.<br>3. Has alcanzado el límite de turnos activos permitidos.<br> Contacta al administrador.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } elseif ($user->estado == 0) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => 'Su cuenta está inactiva.<br> Contacta al administrador.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } elseif ($user->faults > $settings->faltas) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => 'Has alcanzado el límite de faltas permitidas.<br> No puedes reservar turnos.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } elseif ($user->turnos_activos >= $settings->limites) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'html' => 'Has alcanzado el límite de turnos activos permitidos.<br> Asiste a los turnos solicitados antes de solicitar uno nuevo.<br> No puedes reservar más turnos.',
                    'icon' => 'error',
                ]);
                return redirect()->route('home');
            } elseif ($settings->limites <= 0) {
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
            // 1. Encontrar la reserva a eliminar
            $reserva = Reserva::findOrFail($id);
            $user = Auth::user();
            // 2. Obtener el turno disponible asociado
            $turnoDisponible = TurnoDisponible::find($reserva->turno_disponible_id);

            if (!$turnoDisponible) {
                throw new \Exception('Turno asociado no encontrado');
            }

            // 3. Lógica inversa a reservarTurno
            $turnoDisponible->cupos_disponibles += 1; // Aumentar cupos disponibles
            $turnoDisponible->cupos_reservados -= 1; // Disminuir cupos reservados

            // 4. Validar que no haya inconsistencias
            if ($turnoDisponible->cupos_reservados < 0) {
                $turnoDisponible->cupos_reservados = 0;
            }

            // 5. Guardar cambios y eliminar reserva
            DB::beginTransaction();

            try {
                $turnoDisponible->save();
                $reserva->delete();

                DB::commit();
                // 6. Mensaje de éxito
                /** @var \App\Models\User $user */
                $user = Auth::user();

                if ($user->hasRole('medico') || $user->hasRole('admin')) {
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
