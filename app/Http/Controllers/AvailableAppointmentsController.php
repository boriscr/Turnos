<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AvailableAppointment;
use App\Models\Reservation;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class AvailableAppointmentsController extends Controller
{
    public function index(Request $request, $id)
    {
        $user = Auth::user();

        // Verificar si el appointment pertenece al doctor autenticado
        if ($user->hasRole('doctor')) {
            $appointment = Appointment::findOrFail($id);

            if ($appointment->doctor_id !== $user->doctor->id) {
                session()->flash('error', [
                    'title' => 'Acceso denegado',
                    'text' => 'No tiene permiso para ver las disponibilidades de este turno.',
                    'icon' => 'error'
                ]);
                return redirect()->route('appointments.index');
            }
        }

        $availableAppointment = AvailableAppointment::with(['appointment:id,name'])
            ->select('id', 'appointment_id', 'date', 'time', 'available_spots', 'reserved_spots')
            ->where('appointment_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('availableAppointments.index', compact('availableAppointment'));
    }
}
