<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users/index', compact('users'));
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('user.index')->with('error', 'Usuario no encontrado.');
        };
        return view('users/show', compact('user'));
    }
    //Edit admin controller
    public function edit($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'Usuario no encontrado.');
        };
        return view('users/edit', compact('user'));
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validated();
        $originalRole = $user->getRoleNames()->first();
        $newRole = $validated['role'];

        DB::transaction(function () use ($user, $validated, $originalRole, $newRole) {
            // Actualizar campos del usuario (excepto rol)
            $user->fill(Arr::except($validated, 'role'));
            $user->save();

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
