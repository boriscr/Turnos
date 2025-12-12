<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Gender;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\AppointmentHistory;
use App\Models\AppointmentHistoryArchive;

class ProfileController extends Controller
{
    public function index(): View
    {
        $user = User::select('id', 'name', 'gender_id', 'email', 'faults', 'status')
            ->with('gender')
            ->findOrFail(Auth::id());
        /*Contar el total de turnos reservados segun registros en los dos historiales*/
        $AppointmentHistory = AppointmentHistory::where('user_id', Auth::id())->count();
        $AppointmentHistoryArchive = AppointmentHistoryArchive::where('user_id', Auth::id())->count();
        $tellHistory = $AppointmentHistory + $AppointmentHistoryArchive;
        //Contar el total de turnos pendientes
        $appointmentsPending = AppointmentHistory::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->count();
        //Contar el total de turnos no asistidos
        $appointmentsNotAttendance = AppointmentHistory::where('user_id', Auth::id())
            ->where('status', 'not_attendance')
            ->count();
        // Verifica si el usuario tiene permiso para ver su perfil
        $this->authorize('view', $user);

        return view('profile.index', compact('user','appointmentsNotAttendance', 'tellHistory', 'appointmentsPending'));
    }

    //verificar si el usuario esta autenticado y pertenese al mismo usuario
    public function edit(Request $request, $id): View
    {
        $user = User::select('id', 'name', 'surname', 'idNumber', 'birthdate', 'gender_id', 'country_id', 'state_id', 'city_id', 'address', 'phone', 'email')
            ->with(['country', 'state', 'city', 'gender'])
            ->findOrFail($id);

        $this->authorize('update', $user);

        $countries = DB::table('countries')->orderBy('name')->get();
        $states = DB::table('states')->where('country_id', $user->country_id)->orderBy('name')->get();
        $cities = DB::table('cities')->where('state_id', $user->state_id)->orderBy('name')->get();
        $genders = Gender::where('status', true)->get();

        return view('profile.edit', compact('user', 'countries', 'states', 'cities', 'genders'));
    }

    public function historial()
    {
        if (Auth::check()) {
            $totalReservas = Reservation::where('user_id', Auth::user()->id)->count();
            $reservations = Reservation::where('user_id', Auth::user()->id)
                ->with('availableAppointment') // Asegúrate de cargar la relación
                ->orderBy('id', 'desc')
                ->paginate(10);

            $now = now(); // Fecha y time actual

            return view('profile.historial', compact('reservations', 'totalReservas', 'now'));
        } else {
            return redirect()->route('login'); // Mejor redirigir que mostrar un mensaje
        }
    }

    public function show($id)
    {
        $reservation = Reservation::findOrfail($id); // Obtiene el user autenticado
        if (!$reservation) {
            return redirect()->route('profile.historial')->with('error', 'Reservation no encontrada.');
        }
        if ($reservation->user_id !== Auth::id()) {
            return redirect()->route('profile.historial')->with('error', 'No tienes permiso para ver esta reservation.');
        }
        return view('profile.show', compact('reservation'));
    }



    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request, $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        // Verifica si el usuario autenticado tiene permiso para actualizar este perfil
        $this->authorize('update', $user);

        // Actualizar campos editables (excluyendo idNumber y email)
        $user->fill($request->only([
            'name',
            'surname',
            'birthdate',
            'gender_id',
            'country_id',
            'state_id',
            'city_id',
            'address',
            'phone'
        ]));

        // Si el email fue modificado, se invalida la verificación
        if ($request->filled('email') && $user->email !== $request->email) {
            $user->email = $request->email;
            $user->email_verified_at = null;
        }

        $user->save();

        session()->flash('success', [
            'title' => 'Tus datos han sido actualizados',
            'text' => 'Los cambios se han guardado correctamente',
            'icon' => 'success',
        ]);

        return Redirect::route('profile.edit', $user->id);
    }



    public function showChangePasswordForm()
    {
        return view('profile.change-password');
    }
    public function deleteCountForm()
    {
        return view('profile.deleteCountForm');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Verifica si el usuario tiene permiso para eliminar su cuenta
        $this->authorize('delete', $user);

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
