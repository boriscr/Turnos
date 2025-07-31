<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Setting;
use App\Models\Turno;

class ReservaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $fechaFiltro = $request->input('fecha', 'hoy'); // Valores: hoy, anteriores, futuros, personalizado
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $hoy = now()->format('Y-m-d');

        $reservas = Reserva::with(['user', 'turnoDisponible.doctor'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%$search%")
                            ->orWhere('surname', 'like', "%$search%")
                            ->orWhere('idNumber', 'like', "%$search%");
                    })
                        ->orWhereHas('turnoDisponible.doctor', function ($equipoQuery) use ($search) {
                            $equipoQuery->where('name', 'like', "%$search%")
                                ->orWhere('surname', 'like', "%$search%")
                                ->orWhere('idNumber', 'like', "%$search%");
                        });
                });
            })
            ->when($fechaFiltro, function ($query) use ($fechaFiltro, $hoy, $fechaInicio, $fechaFin) {
                $query->whereHas('turnoDisponible', function ($q) use ($fechaFiltro, $hoy, $fechaInicio, $fechaFin) {
                    switch ($fechaFiltro) {
                        case 'hoy':
                            $q->whereDate('fecha', $hoy);
                            break;
                        case 'anteriores':
                            $q->whereDate('fecha', '<', $hoy);
                            break;
                        case 'futuros':
                            $q->whereDate('fecha', '>', $hoy);
                            break;
                        case 'personalizado':
                            if ($fechaInicio && $fechaFin) {
                                $q->whereBetween('fecha', [$fechaInicio, $fechaFin]);
                            }
                            break;
                        default:
                            $q->whereDate('fecha', $hoy);
                    }
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('reservas.index', compact('reservas', 'fechaFiltro', 'fechaInicio', 'fechaFin'));
    }

    public function actualizarAsistencia(Request $request, Reserva $reserva)
    {
        DB::transaction(function () use ($reserva, $request) {
            $estadoAnterior = $reserva->asistencia;

            // Si se envía el valor específico en el request, usarlo
            if ($request->has('asistencia')) {
                $nuevoEstado = $request->asistencia == '1';
            } else {
                // Comportamiento original de toggle
                $nuevoEstado = $estadoAnterior === null ? false : !$estadoAnterior;
            }

            $reserva->asistencia = $nuevoEstado;
            $reserva->save();

            if ($reserva->user) {
                $this->gestionarFaltas($reserva, $estadoAnterior, $nuevoEstado);
            }
        });

        return back()->with('success', 'Estado de asistencia actualizado correctamente');
    }

    // Método para verificación automática (se llamará desde la tarea programada)
    // php artisan schedule:work
    public function verificarAsistenciasAutomaticamente()
    {
        //$hora = Setting::value('hora_verificacion_asistencias');
        $settings = Setting::where('group', 'turnos')->pluck('value', 'key');
        $hora_asistencia = (int) ($settings['asistencias.intervalo_verificacion'] ?? 1); // Valor por defecto
        /*
        $turnos_antelacion_reserva = $settings['turnos.antelacion_reserva'] ?? 30;
        $turnos_unidad_antelacion  = $settings['turnos.unidad_antelacion'] ?? 'dia';
*/
        $now = Carbon::now();
        $fourHoursAgo = $now->copy()->subHours($hora_asistencia);

        $reservasPendientes = Reserva::with(['turnoDisponible', 'user'])
            ->whereNull('asistencia')
            ->whereHas('turnoDisponible', function ($query) use ($now, $fourHoursAgo) {
                $query->where(function ($q) use ($now, $fourHoursAgo) {
                    $q->where('fecha', '<', $now->toDateString())
                        ->orWhere(function ($q2) use ($now, $fourHoursAgo) {
                            $q2->where('fecha', $now->toDateString())
                                ->where('hora', '<=', $fourHoursAgo->toTimeString());
                        });
                });
            })
            ->get();

        foreach ($reservasPendientes as $reserva) {
            DB::transaction(function () use ($reserva) {
                $reserva->asistencia = false;
                $reserva->save();
                $status = Turno::where('id', $reserva->turnoDisponible->turno_id)->value('status');
                if ($reserva->user && $status == true) {
                    $reserva->user->increment('faults');
                }
            });
        }

        return response()->json([
            'message' => 'Asistencias verificadas automáticamente',
            'reservas_actualizadas' => $reservasPendientes->count()
        ]);
    }

    protected function gestionarFaltas(Reserva $reserva, $estadoAnterior, $nuevoEstado)
    {
        $estadoAnterior = (bool) $estadoAnterior;
        $nuevoEstado = (bool) $nuevoEstado;

        if ($nuevoEstado == false && $estadoAnterior != false) {
            $reserva->user->increment('faults');
        } elseif ($nuevoEstado === true && $estadoAnterior === false) {
            if ($reserva->user->faults > 0) {
                $reserva->user->decrement('faults');
            }
        }
    }

    public function show($id)
    {
        $reserva = Reserva::with(['user', 'turnoDisponible.doctor'])->findOrFail($id);
        return view('reservas.show', compact('reserva'));
    }
}
