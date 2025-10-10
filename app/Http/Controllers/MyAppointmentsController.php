<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Reservation;
use App\Models\AvailableAppointment;
use App\Models\Setting;
use App\Models\AppointmentHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class MyAppointmentsController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $reservations = Reservation::where('user_id', Auth::user()->id)
                ->with('availableAppointment') // Asegúrate de cargar la relación
                ->where('status','=','pending') //mostrar solo las reservas con status pendiente
                ->orderBy('id', 'desc')
                ->paginate(8);
            $appointmentHistory = AppointmentHistory::where('user_id', Auth::user()->id)
                ->with('appointment') // Asegúrate de cargar la relación
                ->orderBy('id', 'desc')
                ->paginate(10);
            $now = now(); // Fecha y time actual
            return view('myAppointments.index', compact('reservations', 'appointmentHistory', 'now'));
        } else {
            return redirect()->route('login'); // Mejor redirigir que mostrar un mensaje
        }
    }
    public function show($id)
    {
        /*enviar las reservas que existan y pertenezcan al usuario */

        $reservation = Reservation::with(['user', 'availableAppointment.doctor'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        $now = now(); // Fecha y time actual
        if (!$reservation) {
            abort(404, 'Reserva no encontrada o no autorizada.');
        }
        return view('myAppointments.show', compact('reservation', 'now'));
    }


    /*
* Eliminar reserva (turno) - solo para usuarios / pacientes
*/
    public function destroy(int $id): RedirectResponse
    {
        try {
            $reservation = Reservation::with('availableAppointment')->findOrFail($id);
            $availableAppointment = $reservation->availableAppointment;

            if (!$availableAppointment) {
                throw new \Exception('La cita disponible asociada no fue encontrada');
            }

            // Validar si la reserva puede ser cancelada
            if (!$this->canCancelReservation($availableAppointment)) {
                return redirect()->route('myAppointments.index');
            }

            DB::transaction(function () use ($reservation, $availableAppointment) {
                // Actualizar el estado en appointment_histories
                $this->updateAppointmentHistoryStatus($reservation);
                // Actualizar cupos y eliminar reserva
                $this->updateAppointmentSpots($availableAppointment);
                $reservation->delete();
            });

            $this->flashSuccess('Reserva cancelada', 'La reserva del turno ha sido cancelada correctamente.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Reserva no encontrada', ['id' => $id, 'error' => $e->getMessage()]);
            $this->flashError('Error!', 'Reserva no encontrada.');
        } catch (\Exception $e) {
            Log::error('Error al cancelar reserva', ['id' => $id, 'error' => $e->getMessage()]);
            $this->flashError('Error!', 'Ocurrió un error al cancelar la reserva.');
        }

        return redirect()->route('myAppointments.index');
    }

    /**
     * Verifica si la reserva puede ser cancelada
     */
    protected function canCancelReservation(AvailableAppointment $availableAppointment): bool
    {
        $appointmentDateTime = $this->getAppointmentDateTime($availableAppointment);

        // Verificar si la cita ya pasó
        if ($appointmentDateTime->isPast()) {
            $this->flashError('Error!', 'No puedes cancelar una reserva que ya ha pasado.');
            return false;
        }

        // Verificar límite de cancelación
        if (!$this->isWithinCancellationLimit($availableAppointment)) {
            $cancellationHours = $this->getCancellationHoursLimit();
            $this->flashError(
                'Error!',
                "No puedes cancelar la reserva. Debes cancelar con al menos {$cancellationHours} horas de anticipación."
            );
            return false;
        }

        return true;
    }

    /**
     * Obtiene la fecha y hora de la cita
     */
    protected function getAppointmentDateTime(AvailableAppointment $availableAppointment): Carbon
    {
        return Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $availableAppointment->date->format('Y-m-d') . ' ' . $availableAppointment->time->format('H:i:s')
        );
    }

    /**
     * Verifica si está dentro del límite de cancelación
     */
    protected function isWithinCancellationLimit(AvailableAppointment $availableAppointment): bool
    {
        $appointmentDateTime = $this->getAppointmentDateTime($availableAppointment);
        $cancellationHours = $this->getCancellationHoursLimit();
        $hoursRemaining = now()->diffInHours($appointmentDateTime, false);

        return $hoursRemaining >= $cancellationHours;
    }

    /**
     * Obtiene el límite de horas para cancelación desde settings
     */
    protected function getCancellationHoursLimit(): int
    {
        return Setting::where('group', 'appointments')
            ->where('key', 'cancellation_hours')
            ->value('value') ?? 2;
    }

    /**
     * Actualiza los cupos disponibles de la cita
     */
    protected function updateAppointmentSpots(AvailableAppointment $availableAppointment): void
    {
        $availableAppointment->available_spots++;
        $availableAppointment->reserved_spots = max(0, $availableAppointment->reserved_spots - 1);
        $availableAppointment->save();
    }
    /**
     * Funcion para cambiar el status de historial de appointment
     */
    protected function updateAppointmentHistoryStatus($reservation): void
    {
        // Verificar si ya existe un historial para esta reservación
        $appointmentHistory = AppointmentHistory::where('reservation_id', $reservation->id)
            ->first();
        if ($appointmentHistory) {
            // Actualizar status existente
            $appointmentHistory->update([
                'status' => 'cancelled_by_user',
                'cancelled_by' => Auth::id(),
                'cancelled_at' => now(),
            ]);
        }
    }
    /**
     * Flash message de éxito
     */
    protected function flashSuccess(string $title, string $text): void
    {
        session()->flash('success', [
            'title' => $title,
            'text' => $text,
            'icon' => 'success'
        ]);
    }

    /**
     * Flash message de error
     */
    protected function flashError(string $title, string $text): void
    {
        session()->flash('error', [
            'title' => $title,
            'text' => $text,
            'icon' => 'error'
        ]);
    }
}
