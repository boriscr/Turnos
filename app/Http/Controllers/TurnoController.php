<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Turno;
use App\Models\Especialidad;
use App\Models\Equipo;
use App\Models\TurnoDisponible;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TurnoController extends Controller
{
    public function index()
    {
        $turnos = Turno::all();
        return view('turnos/index', compact('turnos'));
    }
    public function create()
    {
        $especialidades = Especialidad::all();
        $equipos = Equipo::all();

        return view('turnos/create', compact('especialidades', 'equipos'));
    }
    public function store(Request $request)
    {
        // Decodificar fechas seleccionadas
        $fechas = json_decode($request->selected_dates, true);

        if (empty($fechas)) {
            Log::info('Fechas recibidas:', ['fechas' => $request->selected_dates]);
            Log::info('Decodificadas:', ['decoded' => json_decode($request->selected_dates, true)]);

            return back()->with('error', 'Debe seleccionar al menos una fecha');
        }

        // Crear el turno principal
        $turno = Turno::create([
            'nombre' => trim($request->nombre),
            'descripcion' => trim($request->descripcion),
            'especialidad_id' => $request->especialidad_id,
            'equipo_id' => $request->equipo_id,
            'turno' => $request->turno, // Asignar el turno si se proporciona
            'cantidad_turnos' => trim($request->cantidad),
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'horarios_disponibles' => $request->horarios_disponibles,
            'user_id' => Auth::id(),
            'user_id_update' => Auth::id(),
            'fechas_disponibles' => $fechas,
            'estado' => $request->estado,
        ]);

        // Crear los turnos disponibles para cada fecha

        // Crear disponibilidad por fecha y horario
        foreach ($fechas as $fecha) {

            if ($request->horarios_disponibles) {
                // Caso CON horarios (vienen en JSON)
                $horarios = json_decode($request->horarios_disponibles, true);

                foreach ($horarios as $hora) {
                    TurnoDisponible::create([
                        'turno_id' => $turno->id,
                        'equipo_id' => $request->equipo_id,
                        'fecha' => $fecha,
                        'hora' => $hora,
                        'cupos_disponibles' => 1, // 1 cupo por horario individual
                    ]);
                }
            } else {
                // Caso SIN horarios (por día)
                TurnoDisponible::create([
                    'turno_id' => $turno->id,
                    'equipo_id' => $request->equipo_id,
                    'fecha' => $fecha,
                    'hora' => $request->hora_inicio, // sin hora específica
                    'cupos_disponibles' => $request->cantidad, // cupo total por fecha
                ]);
            }
        }

        return redirect()->route('turnos.index')->with('success', 'Turno creado exitosamente');
    }

    public function show($id)
    {
        // Obtener el turno específico
        $turno = Turno::findOrFail($id);
        // Obtener el turno disponible relacionado con el equipo de ese turno
        $turnoDisponibles = TurnoDisponible::where('equipo_id', $turno->equipo_id)
            ->orderByDesc('fecha')
            ->orderByDesc('hora')
            ->paginate(10);

        return view('turnos.show', compact('turno', 'turnoDisponibles'));
    }
    public function search(Request $request)
    {
        // Si se presionó "Mostrar Todo", ignoramos todos los filtros
        if ($request->has('mostrar_todo')) {
            $turnoDisponibles = TurnoDisponible::with(['turno', 'reservas.user'])
                ->orderByDesc('fecha')
                ->orderByDesc('hora')
                ->paginate(10);

            return view('disponibles.index', [
                'turnoDisponibles' => $turnoDisponibles,
                'reservaFiltro' => 'todos',
                'fechaFiltro' => 'todos',
                'search' => null,
                'fechaInicio' => null,
                'fechaFin' => null
            ]);
        }

        // Procesamiento normal de filtros
        $search = $request->input('search');
        $reservaFiltro = $request->input('reserva', 'reservados');
        $fechaFiltro = $request->input('fecha', 'hoy');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        $hoy = now()->format('Y-m-d');
        $manana = now()->addDay()->format('Y-m-d'); // Nueva variable para mañana

        $query = TurnoDisponible::with(['turno', 'reservas.user'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('reservas.user', function ($userQuery) use ($search) {
                    $userQuery->where('dni', 'like', "%$search%")
                        ->orWhere('name', 'like', "%$search%")
                        ->orWhere('surname', 'like', "%$search%");
                });
            })
            ->when($reservaFiltro !== 'todos', function ($query) use ($reservaFiltro) {
                switch ($reservaFiltro) {
                    case 'reservados':
                        $query->where('cupos_reservados', '>=', 1);
                        break;
                    case 'sin_reserva':
                        $query->where('cupos_reservados', 0);
                        break;
                }
            });

        // Manejo de fechas
        if ($fechaFiltro === 'personalizado') {
            if ($fechaInicio && $fechaFin) {
                $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
            } elseif ($fechaInicio) {
                $query->whereDate('fecha', '>=', $fechaInicio);
            } elseif ($fechaFin) {
                $query->whereDate('fecha', '<=', $fechaFin);
            }
        } elseif ($fechaFiltro !== 'todos') {
            switch ($fechaFiltro) {
                case 'hoy':
                    $query->whereDate('fecha', $hoy);
                    break;
                case 'anteriores':
                    $query->whereDate('fecha', '<', $hoy);
                    break;
                case 'futuros':
                    // Cambiado para mostrar solo mañana
                    $query->whereDate('fecha', $manana);
                    break;
            }
        }

        $turnoDisponibles = $query
            ->orderByDesc('fecha')
            ->orderByDesc('hora')
            ->paginate(10);

        return view('disponibles.index', compact('turnoDisponibles', 'reservaFiltro', 'fechaFiltro', 'search', 'fechaInicio', 'fechaFin'));
    }

    //Editar Turno
    public function edit($id)
    {
        $turno = Turno::with(['especialidad', 'equipo', 'disponibilidades'])->findOrFail($id);
        $especialidades = Especialidad::where('estado', 1)->get();

        // Procesar horarios_disponibles para la vista
        $horarios_disponibles = $turno->horarios_disponibles;

        // Verificar si es un array JSON (horario2)
        $horariosArray = json_decode($turno->horarios_disponibles, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($horariosArray)) {
            $horarios_disponibles = json_encode($horariosArray);
        }

        return view('turnos.edit', [
            'turno' => $turno,
            'especialidades' => $especialidades,
            'especialidad_id' => $turno->especialidad_id,
            'equipo_id' => $turno->equipo_id,
            'equipo_nombre' => $turno->equipo->nombre ?? 'Equipo no disponible',
            'nombre' => $turno->nombre,
            'descripcion' => $turno->descripcion,
            'cantidad' => $turno->cantidad_turnos,
            'inicio' => $turno->hora_inicio ? Carbon::parse($turno->hora_inicio)->format('H:i') : null,
            'fin' => $turno->hora_fin ? Carbon::parse($turno->hora_fin)->format('H:i') : null,
            'turnoTipo' => $turno->turno,
            'fechas' => json_encode($turno->fechas_disponibles),

            'horarios_disponibles' => $horarios_disponibles
            
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validación básica
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'especialidad_id' => 'required|exists:'.Especialidad::TABLE.',id',
            'equipo_id' => 'required|exists:equipos,id',
            'cantidad' => 'required|integer|min:1',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
            'selected_dates' => 'required'
        ]);

        // Decodificar fechas seleccionadas
        $fechas = json_decode($request->selected_dates, true);

        if (empty($fechas)) {
            return back()->with('error', 'Debe seleccionar al menos una fecha')->withInput();
        }

        // Validación específica para horarios
        if ($request->turno === 'horario2') {
            if (empty($request->horarios_disponibles) || !json_decode($request->horarios_disponibles)) {
                return back()->withErrors(['horarios_disponibles' => 'Para turnos por hora, debe seleccionar al menos un horario'])->withInput();
            }
        }

        // Resto del código de actualización...


        // Obtener el turno a actualizar
        $turno = Turno::findOrFail($id);

        // Actualizar el turno principal
        $turno->nombre = trim($request->nombre);
        $turno->descripcion = trim($request->descripcion);
        $turno->especialidad_id = $request->especialidad_id;
        $turno->equipo_id = $request->equipo_id;
        $turno->turno = $request->turno;
        $turno->cantidad_turnos = trim($request->cantidad);
        $turno->hora_inicio = $request->hora_inicio;
        $turno->hora_fin = $request->hora_fin;
        $turno->horarios_disponibles = $request->horarios_disponibles;
        $turno->user_id_update = Auth::id();
        $turno->fechas_disponibles = $fechas;
        $turno->estado = $request->estado ?? $turno->estado;

        $turno->save();

        // Eliminar disponibilidades existentes

        // Crear nuevas disponibilidades según la configuración
        foreach ($fechas as $fecha) {
            if ($request->horarios_disponibles && json_decode($request->horarios_disponibles)) {
                $horarios = json_decode($request->horarios_disponibles, true);

                foreach ($horarios as $hora) {
                    TurnoDisponible::updateOrCreate(
                        ['turno_id' => $turno->id, 'equipo_id' => $request->equipo_id, 'fecha' => $fecha, 'hora' => $hora],
                        ['cupos_disponibles' => 1]
                    );
                }
            } else {
                TurnoDisponible::updateOrCreate(
                    ['turno_id' => $turno->id, 'equipo_id' => $request->equipo_id, 'fecha' => $fecha, 'hora' => $request->hora_inicio],
                    ['cupos_disponibles' => $request->cantidad]
                );
            }
        }


        return redirect()->route('turnos.index')->with('success', 'Turno actualizado correctamente');
    }

    function destroy($id)
    {
        $turno = Turno::findOrFail($id);
        $turno->disponibilidades()->delete(); // Eliminar disponibilidades asociadas
        $turno->delete(); // Eliminar el turno

        return redirect()->route('turnos.index')->with('success', 'Turno eliminado correctamente');
    }
}
