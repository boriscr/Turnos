<?php

namespace App\Http\Controllers;

use App\Models\AppointmentHistory;
use App\Models\AppointmentHistoryArchive;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AppointmentHistoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Solo el admin puede ver todos los historiales
        if (!$user->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        // Obtener todos los historiales de citas listados en 10 páginas

        $appointmentHistory = AppointmentHistory::orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('appointmentHistories.index', compact('appointmentHistory'));
    }

    public function show($id)
    {
        $appointmentHistoryId = AppointmentHistory::with(['user','appointment', 'doctor', 'reservation'])->findOrFail($id);
        return view('appointmentHistories.show', compact('appointmentHistoryId'));
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized action.');
        }

        if (!$id) {
            return redirect()->back()->with('error', 'ID de cita no especificado.');
        }

        $appointment = AppointmentHistory::where('id', $id)
            ->first();

        if (!$appointment) {
            return redirect()->back()->with('error', 'Cita no encontrada.');
        }

        // 🔒 Verificación de fecha en el back
        $fechaHoraReserva = $appointment->appointment_date
            ->copy()
            ->setTimeFrom($appointment->appointment_time);

        if ($fechaHoraReserva->toDateString() >= now()->toDateString()) {
            return redirect()->back()->with('error', 'Solo se pueden eliminar citas a partir del día siguiente.');
        }

        // Autorización vía policy. Elimina solo si es admin
        $this->authorize('delete', $appointment);

        DB::transaction(function () use ($appointment) {
            AppointmentHistoryArchive::create($appointment->toArray());
            $appointment->delete();
        });

        return redirect()->back()->with('success', 'Historial eliminado correctamente.');
    }
}
