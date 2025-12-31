<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\AppointmentHistory;
use App\Models\Gender;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserUpdateRequest;
use Jenssegers\Agent\Agent;

class UserController extends Controller
{
    public function index()
    {
        $users = User::select('id', 'name', 'surname', 'idNumber', 'status', 'created_at')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
        return view('users/index', compact('users'));
    }
    //busqueda de usuarios por dni o email
    public function search(Request $request)
    {
        $query = $request->input('search');
        $users = User::where('idNumber', 'like', "%{$query}%")
            //->orWhere('email', 'like', "%{$query}%")
            ->paginate(10);
        return view('users/index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with(['country', 'state', 'city', 'gender'])->findOrfail($id);
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'Usuario no encontrado.');
        };
        $appointmentHistory = AppointmentHistory::with(['appointment', 'reservation', 'user'])->where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        try {
            //Cargar sesiones del usuario desde la tabla sessions
            //composer require jenssegers/agent
            $sesiones = DB::table('sessions')
                ->select('id', 'user_id', 'ip_address', 'user_agent', 'last_activity')
                ->where('user_id', $id)
                ->orderBy('last_activity', 'desc')
                ->get();
            $agent = new Agent();

            $sesionesFormateadas = $sesiones->map(function ($sesion) use ($agent) {
                // Le decimos al agente que analice el string de esta sesión específica
                $agent->setUserAgent($sesion->user_agent);
                // Identificar el dispositivo
                $dispositivo = $agent->device(); // Ej: iPhone, Nexus, Asus...
                $browser = $agent->browser();    // Ej: Chrome, Safari...
                $platform = $agent->platform();  // Ej: Windows, Ubuntu, OS X...

                return [
                    'id' => $sesion->id,
                    'ip' => $sesion->ip_address,
                    // Creamos un nombre legible combinando los datos
                    'navegador' => $browser . ' en ' . $platform . ($agent->isDesktop() ? '' : ' (' . $dispositivo . ')'),
                    'ultima_actividad' => \Carbon\Carbon::createFromTimestamp($sesion->last_activity)->diffForHumans(),
                    'actual' => $sesion->id === session()->getId(),
                ];
            });
        } catch (\Exception $e) {
            return back()->withErrors('Error al cargar las sesiones: ' . $e->getMessage());
        }
        return view('users/show', compact('user', 'appointmentHistory', 'sesionesFormateadas'));
    }


    //Edit admin controller
    public function edit($id)
    {
        $user = User::with(['country', 'state', 'city', 'gender'])->findOrfail($id);
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'Usuario no encontrado.');
        };
        // Cargar datos para los selects
        $countries = DB::table('countries')->orderBy('name')->get();
        $states = DB::table('states')->where('country_id', $user->country_id)->orderBy('name')->get();
        $cities = DB::table('cities')->where('state_id', $user->state_id)->orderBy('name')->get();
        $genders = Gender::where('status', '=', true)->get();
        //Traducir los géneros
        $genders->transform(function ($gender) {
            $gender->translated_name = __('genders.' . $gender->name);
            return $gender;
        });
        return view('users/edit', compact('user', 'countries', 'states', 'cities', 'genders'));
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validated();
        $originalRole = $user->getRoleNames()->first();
        $newRole = $validated['role'];

        DB::transaction(function () use ($user, $validated, $originalRole, $newRole) {
            // Actualizar campos del usuario
            $user->update([
                ...Arr::except($validated, 'role'),
                'country_id' => $validated['country_id'],
                'state_id' => $validated['state_id'],
                'city_id' => $validated['city_id'],
            ]);

            // Manejar cambios de rol
            $this->handleRoleChange($user, $originalRole, $newRole);
        });

        // Volver a cargar relaciones si es necesario
        $user->load('doctor');

        // Redirección
        return $this->getRedirectResponse($user, $originalRole, $newRole);
    }


    /**
     * Maneja los cambios de rol y relaciones asociadas
     */
    private function handleRoleChange(User $user, ?string $originalRole, string $newRole): void
    {
        if ($originalRole === $newRole) return;

        $doctor = Doctor::where('user_id', $user->id)->first();

        if ($newRole === 'doctor' && Doctor::where('idNumber', $user->idNumber)->exists()) {
            $this->flashError('Ya existe un doctor registrado con un DNI similar.');
            return;
        }

        if ($doctor && $originalRole === 'doctor' && $newRole !== 'doctor') {
            if (Appointment::where('doctor_id', $doctor->id)->exists()) {
                $this->flashError('No se puede cambiar el rol. El doctor tiene turnos asociados.');
                return;
            }
        }

        $user->syncRoles([$newRole]);

        if ($newRole === 'doctor' && !$doctor) {
            $this->createDoctorProfile($user);
        }

        if ($doctor && $originalRole === 'doctor' && $newRole !== 'doctor') {
            $doctor->delete();
        }
    }

    private function flashError(string $message): void
    {
        session()->flash('error', [
            'title' => 'Cambio de rol inválido',
            'text'  => $message,
            'icon'  => 'error',
        ]);
    }

    /**
     * Crea el perfil de doctor asociado
     */
    private function createDoctorProfile(User $user): void
    {
        Doctor::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'surname' => $user->surname,
            'idNumber' => $user->idNumber,
            'email' => $user->email,
            'phone' => $user->phone,
            'status' => $user->status,
            'role' => 'doctor'
        ]);
    }

    /**
     * Determina la respuesta de redirección apropiada
     */

    private function getRedirectResponse(User $user, ?string $originalRole, string $newRole): RedirectResponse
    {
        // Si se convirtió en doctor, redirigir para completar perfil
        if ($originalRole !== 'doctor' && $newRole === 'doctor' && $user->doctor) {
            return redirect()->route('doctors.edit', $user->doctor->id)->with([
                'success' => 'Perfil profesional creado. Complete los datos específicos.',
                'nuevoMedico' => true,
            ]);
        }

        // Redirección normal
        return redirect()->route('users.index')->with(
            'success',
            'Usuario actualizado correctamente.'
        );
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'Usuario no encontrado.');
        };
        $originalRole = $user->getRoleNames()->first();
        // Verificar si el usuario es un doctor con citas asociadas
        if ($originalRole === 'doctor') {
            $doctor = Doctor::where('user_id', $user->id)->first();
            if ($doctor && Appointment::where('doctor_id', $doctor->id)->exists()) {
                session()->flash('error', [
                    'title' => 'Eliminación inválida',
                    'text'  => 'No se puede eliminar el usuario. El doctor tiene turnos asociados.',
                    'icon'  => 'error',
                ]);
                return redirect()->route('users.index');
            }
            // Eliminar perfil de doctor si existe
            if ($doctor) {
                $doctor->delete();
            }
        }
        // Eliminar el usuario
        if (Auth::id() == $user->id) {
            Auth::logout();
        }
        $user->delete();
        session()->flash('success', [
            'title' => 'Usuario eliminado',
            'text'  => 'El usuario ha sido eliminado correctamente.',
            'icon'  => 'success',
        ]);
        return redirect()->route('users.index');
    }


    public function destroySession(Request $request, $sessionId): RedirectResponse
    {
        $admin = Auth::user();

        // 1. Verificación de Rol (Spatie)
        if (!$admin->hasRole('admin')) {
            abort(403, 'No tienes permisos administrativos.');
        }

        // 2. Rate Limiting para la contraseña del Admin
        $throttleKey = 'admin-verify-password:' . $admin->id;
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);
            session()->flash('error', [
                'title' => 'Demasiados intentos',
                'text' => "Por seguridad, espera $minutes minutos.",
                'icon' => 'error',
            ]);
            return back();
        }

        // 3. Validar que la contraseña enviada es la del ADMINISTRADOR
        $request->validate(['password_confirmation' => 'required|string']);

        if (!Hash::check($request->password_confirmation, $admin->password)) {
            //rate limit de 10 minutos
            RateLimiter::hit($throttleKey, 600);
            session()->flash('error', [
                'title' => 'Contraseña administrativa incorrecta',
                'text' => 'La clave no coincide con tus registros de administrador.',
                'icon' => 'error',
            ]);
            return back();
        }

        RateLimiter::clear($throttleKey);

        // 4. Impedir que el admin cierre su propia sesión actual
        if ($sessionId === session()->getId()) {
            session()->flash('error', [
                'title' => 'Acción no permitida',
                'text' => 'No puedes cerrar tu propia sesión desde el panel de gestión.',
                'icon' => 'warning',
            ]);
            return back();
        }

        // 5. Borrado Seguro
        $deleted = DB::table('sessions')->where('id', $sessionId)->delete();

        if ($deleted) {
            // Auditoría básica en los logs de Laravel
            logger()->info("El administrador {$admin->email} cerró la sesión ID: {$sessionId}");

            session()->flash('success', [
                'title' => 'Sesión revocada',
                'text' => 'El dispositivo ha sido desconectado por el administrador.',
                'icon' => 'success',
            ]);
        }

        return back();
    }
}
