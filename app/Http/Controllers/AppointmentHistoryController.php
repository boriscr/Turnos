<?php

namespace App\Http\Controllers;

use App\Models\AppointmentHistory;
use App\Models\AppointmentHistoryArchive;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AppointmentHistoryController extends Controller
{
    public function destroy($id)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized action.');
        }

        if (!$id) {
            return redirect()->back()->with('error', 'ID de cita no especificado.');
        }

        $appointment = AppointmentHistory::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$appointment) {
            return redirect()->back()->with('error', 'Cita no encontrada o no pertenece al usuario.');
        }

        // 🔒 Verificación de fecha en el back
        $fechaHoraReserva = $appointment->appointment_date
            ->copy()
            ->setTimeFrom($appointment->appointment_time);

        if ($fechaHoraReserva->toDateString() >= now()->toDateString()) {
            return redirect()->back()->with('error', 'Solo se pueden eliminar citas a partir del día siguiente.');
        }

        // Autorización vía policy (si la tenés definida)
        $this->authorize('delete', $appointment);

        DB::transaction(function () use ($appointment) {
            AppointmentHistoryArchive::create($appointment->toArray());
            $appointment->delete();
        });

        return redirect()->back()->with('success', 'Historial eliminado correctamente.');
    }
}
