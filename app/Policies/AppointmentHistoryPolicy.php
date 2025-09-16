<?php

namespace App\Policies;

use App\Models\AppointmentHistory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AppointmentHistoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AppointmentHistory $appointmentHistory): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AppointmentHistory $appointmentHistory): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AppointmentHistory $appointmentHistory)
    {
        // Solo el admin puede eliminar
        return $user->hasRole('admin')
            ? Response::allow()
            : Response::deny('No estás autorizado para eliminar este historial de citas.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AppointmentHistory $appointmentHistory): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AppointmentHistory $appointmentHistory): bool
    {
        return false;
    }
}
