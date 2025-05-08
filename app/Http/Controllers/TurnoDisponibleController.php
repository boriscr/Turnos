<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;
use App\Models\TurnoDisponible;
use App\Models\Turno;
use App\Models\Especialidad;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;

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
                return response()->json(['success' => false, 'message' => 'No hay cupos disponibles']);
            }
            // Verificar si la fecha y hora son válidas
            $hoy = now()->format('Y-m-d');
            $horaActual = now()->format('H:i:s');
            if ($turno->fecha < $hoy || ($turno->fecha == $hoy && $turno->hora < $horaActual)) {
                return response()->json(['success' => false, 'message' => 'La fecha y hora seleccionadas ya han pasado']);
            }
            // Reservar el turno
            // Aquí puedes agregar la lógica para asociar el turno reservado al usuario
            // Por ejemplo, podrías crear un registro en una tabla de reservas
            $turno->cupos_disponibles -= 1;
            $turno->cupos_reservados += 1; // Aumentar los cupos reservados
            // Guardar el turno actualizado
            $turno->save();
            // Aquí puedes guardar la reserva en la base de datos
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
            session()->flash('success',[
                'title'=>'Turno reservado',
                'text'=>'El turno ha sido reservado con éxito',
                'icon'=>'success',
            ]);
            return back();
        }
    }
}
