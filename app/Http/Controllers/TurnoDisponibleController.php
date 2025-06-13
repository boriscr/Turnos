<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;
use App\Models\TurnoDisponible;
use App\Models\Turno;
use App\Models\Especialidad;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TurnoDisponibleController extends Controller
{

    public function create()
    {
        $turnoDisponible = TurnoDisponible::all();
        $turno = Turno::where('estado', 1)->get();
        $especialidades = Especialidad::where('estado', 1)->get();
        return view('reservas/create', compact('turnoDisponible', 'turno', 'especialidades'));
    }


    // 1. Obtener equipos por especialidad
    public function getEquiposPorEspecialidad($especialidad_id)
    {
        $equipos = Equipo::where('especialidad_id', $especialidad_id)
            ->where('estado', 1)
            ->get();

        return response()->json(['equipos' => $equipos]);
    }

    // 2. Obtener turnos por equipo (filtrado por fecha/hora)
    public function getTurnosPorEquipo($equipo_id)
    {
        $hoy = now()->format('Y-m-d');
        $horaActual = now()->format('H:i:s');

        $turnos = TurnoDisponible::where('equipo_id', $equipo_id)
            ->where('cupos_disponibles', '>', 0)
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
                    'hora'  => $turno->hora ? \Carbon\Carbon::parse($turno->hora)->format('H:i') : null,
                ];
            });

        return response()->json(['turnos' => $turnos]);
    }

    function reservarTurno(Request $request)
    {
        $turno = TurnoDisponible::find($request->turno_id);
        if ($turno) {
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
            return back();
        }
    }

    //Eliminar reserva y actualizar los cupos disponibles
    public function destroy($id)
    {
        try {
            // 1. Encontrar la reserva a eliminar
            $reserva = Reserva::findOrFail($id);

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

                return back()->with('success', [
                    'title' => 'Reserva eliminada!',
                    'text' => 'La reserva fue cancelada y los cupos se actualizaron correctamente.',
                    'icon' => 'success'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Reserva no encontrada', ['id' => $id, 'error' => $e->getMessage()]);

            return back()->with('error', [
                'title' => 'Error!',
                'text' => 'Reserva no encontrada.',
                'icon' => 'error'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al cancelar reserva', ['id' => $id, 'error' => $e->getMessage()]);

            return back()->with('error', [
                'title' => 'Error!',
                'text' => 'Ocurrió un error al cancelar la reserva: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }
}
