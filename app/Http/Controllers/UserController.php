<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::select('id', 'name', 'surname', 'idNumber', 'status', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('users/index', compact('users'));
    }
    //busqueda de usuarios por dni o email
    public function search(Request $request)
    {
        $query = $request->input('search');
        $users = User::where('idNumber', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->paginate(10);
        return view('users/index', compact('users'));
    }

    public function show($id)
    {
        $user = User::find($id);
        $user = User::with(['country', 'state', 'city'])->find($id);
        if (!$user) {
            return redirect()->route('user.index')->with('error', 'Usuario no encontrado.');
        };
        return view('users/show', compact('user'));
    }
    //Edit admin controller
    public function edit($id)
    {
        $user = User::find($id);
        // Cargar datos para los selects
        $countries = DB::table('countries')->orderBy('name')->get();
        $states = DB::table('states')->where('country_id', $user->country_id)->orderBy('name')->get();
        $cities = DB::table('cities')->where('state_id', $user->state_id)->orderBy('name')->get();
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'Usuario no encontrado.');
        };
        return view('users/edit', compact('user', 'countries', 'states', 'cities'));
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
                'updated_by' => Auth::id()
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
        // Si no hay cambio de rol, no hacer nada
        if ($originalRole === $newRole) {
            return;
        }

        // Actualizar rol
        $user->syncRoles([$newRole]);

        // Manejar transición a doctor
        if ($newRole === 'doctor' && !$user->doctor) {
            $this->createDoctorProfile($user);
        }

        // Manejar transición desde doctor
        if ($originalRole === 'doctor' && $newRole !== 'doctor') {
            $user->doctor()->delete();
        }
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
            'role' => 'doctor',
        ]);
    }

    /**
     * Determina la respuesta de redirección apropiada
     */

    private function getRedirectResponse(User $user, ?string $originalRole, string $newRole): RedirectResponse
    {
        // Si se convirtió en doctor, redirigir para completar perfil
        if ($originalRole !== 'doctor' && $newRole === 'doctor' && $user->doctor) {
            return redirect()->route('doctor.edit', $user->doctor->id)->with([
                'success' => 'Perfil profesional creado. Complete los datos específicos.',
                'nuevoMedico' => true,
            ]);
        }

        // Redirección normal
        return redirect()->route('user.index')->with(
            'success',
            'Usuario actualizado correctamente.'
        );
    }

    public function destroy($id)
    {
        $usuario = User::find($id);
        if (!$usuario) {
            return redirect()->route('user.index')->with('error', 'Usuario no encontrado.');
        };
        $usuario->delete();
        return redirect()->route('user.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
