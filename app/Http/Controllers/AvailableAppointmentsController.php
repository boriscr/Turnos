<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AvailableAppointment;

class AvailableAppointmentsController extends Controller
{
    public function index(Request $request)
    {
        // Si se presionó "Mostrar Todo", ignoramos todos los filtros
        if ($request->has('mostrar_todo')) {
            $turnoDisponibles = AvailableAppointment::with(['turno', 'reservations.user'])
                ->orderByDesc('date')
                ->orderByDesc('time')
                ->paginate(10);

            return view('availableAppointments.index', [
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
        $reservaFiltro = $request->input('reservation', 'reservados');
        $fechaFiltro = $request->input('date', 'hoy');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        $hoy = now()->format('Y-m-d');
        $manana = now()->addDay()->format('Y-m-d'); // Nueva variable para mañana

        $query = AvailableAppointment::with(['turno', 'reservations.user'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('reservations.user', function ($userQuery) use ($search) {
                    $userQuery->where('idNumber', 'like', "%$search%")
                        ->orWhere('name', 'like', "%$search%")
                        ->orWhere('surname', 'like', "%$search%");
                });
            })
            ->when($reservaFiltro !== 'todos', function ($query) use ($reservaFiltro) {
                switch ($reservaFiltro) {
                    case 'reservados':
                        $query->where('reserved_spots', '>=', 1);
                        break;
                    case 'sin_reserva':
                        $query->where('reserved_spots', 0);
                        break;
                }
            });

        // Manejo de fechas
        if ($fechaFiltro === 'personalizado') {
            if ($fechaInicio && $fechaFin) {
                $query->whereBetween('date', [$fechaInicio, $fechaFin]);
            } elseif ($fechaInicio) {
                $query->whereDate('date', '>=', $fechaInicio);
            } elseif ($fechaFin) {
                $query->whereDate('date', '<=', $fechaFin);
            }
        } elseif ($fechaFiltro !== 'todos') {
            switch ($fechaFiltro) {
                case 'hoy':
                    $query->whereDate('date', $hoy);
                    break;
                case 'anteriores':
                    $query->whereDate('date', '<', $hoy);
                    break;
                case 'futuros':
                    // Cambiado para mostrar solo mañana
                    $query->whereDate('date', $manana);
                    break;
            }
        }

        $turnoDisponibles = $query
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->paginate(10);

        return view('availableAppointments.index', compact('turnoDisponibles', 'reservaFiltro', 'fechaFiltro', 'search', 'fechaInicio', 'fechaFin'));
    }
}
