<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Gender;
use App\Models\AppointmentHistory;
use App\Models\AppointmentHistoryArchive;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Requests\ProfileUpdateRequest;
use Jenssegers\Agent\Agent;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $userId = Auth::id();

        $user = User::select('id', 'name', 'gender_id', 'email', 'faults', 'status')
            ->with('gender')
            ->findOrFail($userId);

        // Autorización primero: Si no puede verlo, ni siquiera hacemos los conteos
        $this->authorize('view', $user);

        // Conteos (mover esto a métodos en el modelo User más adelante)
        $appointmentsHistory = AppointmentHistory::where('user_id', $userId)->count();
        $historyArchive = AppointmentHistoryArchive::where('user_id', $userId)->count();

        $tellHistory = $appointmentsHistory + $historyArchive;
        $appointmentsPending = AppointmentHistory::where('user_id', $userId)->where('status', 'pending')->count();
        $appointmentsNotAttendance = AppointmentHistory::where('user_id', $userId)->where('status', 'not_attendance')->count();

        return view('profile.index', compact(
            'user',
            'appointmentsNotAttendance',
            'tellHistory',
            'appointmentsPending'
        ));
    }

    //verificar si el usuario esta autenticado y pertenese al mismo usuario
    public function edit(Request $request, $id): View|RedirectResponse
    {
        $user = User::select('id', 'name', 'surname', 'idNumber', 'birthdate', 'gender_id', 'country_id', 'state_id', 'city_id', 'address', 'phone', 'email')
            ->with(['country', 'state', 'city', 'gender'])
            ->findOrFail($id);

        $this->authorize('update', $user);

        $countries = DB::table('countries')->orderBy('name')->get();
        $states = DB::table('states')->where('country_id', $user->country_id)->orderBy('name')->get();
        $cities = DB::table('cities')->where('state_id', $user->state_id)->orderBy('name')->get();
        $genders = Gender::where('status', true)->get();
        //Traducir los géneros
        $genders->transform(function ($gender) {
            $gender->translated_name = __('genders.' . $gender->name);
            return $gender;
        });
        return view('profile.edit', compact('user', 'countries', 'states', 'cities', 'genders'));
    }

    public function historial(): View|RedirectResponse
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

    public function show($id): View|RedirectResponse
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
    /**
     * Show the user's active sessions.
     */
    public function showSessions(): View|RedirectResponse
    {
        $userId = Auth::id();
        ///$this->authorize('viewSessions', $userId);
        try {
            $sesiones = DB::table('sessions')
                ->where('user_id', $userId)
                ->orderBy('last_activity', 'desc')
                ->get();

            $agent = new Agent();

            $sesionesFormateadas = $sesiones->map(function ($sesion) use ($agent) {
                $agent->setUserAgent($sesion->user_agent);
                return [
                    'id' => $sesion->id,
                    'ip' => $sesion->ip_address,
                    'navegador' => $agent->browser() . ' en ' . $agent->platform(),
                    'ultima_actividad' => \Carbon\Carbon::createFromTimestamp($sesion->last_activity)->diffForHumans(),
                    'actual' => $sesion->id === session()->getId(),
                ];
            });
        } catch (\Exception $e) {
            return back()->withErrors('Error al cargar las sesiones: ' . $e->getMessage());
        }
        return view('profile.sessions', compact('sesionesFormateadas'));
    }
    /**
     * Delete a specific user session.
     */

    /**
     * Delete a specific user session with security checks.
     */
    public function destroySession(Request $request, $sessionId): RedirectResponse
    {
        $userId = Auth::id();
        $user = Auth::user();

        // 1. Definir una llave única para el límite de intentos (basada en el ID del usuario)
        $throttleKey = 'verify-password:' . $userId;

        // 2. Validar tipos de datos (Garantizamos que sea string)
        $request->validate([
            'password_confirmation' => 'required|string',
        ]);

        // 3. Aplicar Rate Limiting (Máximo 3 intentos por 10 minutos)
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);
            session()->flash('error', [
                'title' => 'Demasiados intentos',
                'text' => "Por seguridad, espera $minutes minutos antes de intentar de nuevo.",
                'icon' => 'error',
            ]);
            return back();
        }

        // 4. Verificar la contraseña
        if (!Hash::check($request->string('password_confirmation'), $user->password)) {
            // Incrementamos el contador de fallos
            RateLimiter::hit($throttleKey, 600);

            session()->flash('error', [
                'title' => 'Contraseña incorrecta',
                'text' => 'La clave ingresada no es válida.',
                'icon' => 'error',
            ]);
            return back();
        }

        // 5. Si la clave es correcta, limpiamos los intentos fallidos
        RateLimiter::clear($throttleKey);

        // 6. Seguridad: Evitar que el usuario cierre su propia sesión actual
        if ($sessionId === session()->getId()) {
            session()->flash('error', [
                'title' => 'Acción no permitida',
                'text' => 'No puedes cerrar tu sesión actual desde aquí.',
                'icon' => 'warning',
            ]);
            return back();
        }

        // 7. Borrado atómico y seguro
        $deleted = DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', $userId)
            ->delete();

        if ($deleted) {
            session()->flash('success', [
                'title' => 'Sesión cerrada',
                'text' => 'El dispositivo ha sido desconectado.',
                'icon' => 'success',
            ]);
        }

        return Redirect::route('profile.session');
    }

    /**
     * Show the form for changing the user's password.
     */
    public function showChangePasswordForm(): View
    {
        return view('profile.change-password');
    }
    public function deleteCountForm(): View
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
