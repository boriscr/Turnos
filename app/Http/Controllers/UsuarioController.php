<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Medico;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class UsuarioController extends Controller
{
    public function index()
    {
        return view('usuarios/index');
    }

    public function show($id)
    {
        $usuario = User::find($id);
        if (!$usuario) {
            return redirect()->route('usuario.index')->with('error', 'Usuario no encontrado.');
        };
        return view('usuarios/show', compact('usuario'));
    }
    //Edit admin controller
    public function edit($id)
    {
        $usuario = User::find($id);
        if (!$usuario) {
            return redirect()->route('usuario.index')->with('error', 'Usuario no encontrado.');
        };
        return view('usuarios/edit', compact('usuario'));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'digits_between:7,8', Rule::unique('users', 'dni')->ignore($id)],
            'birthdate' => ['required', 'date'],
            'genero' => ['required', 'string', 'max:255', 'in:Masculino,Femenino,No binario,otro,Prefiero no decir'],
            'country' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:15', 'unique:' . User::class . ',phone,' . $id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class . ',email,' . $id],
            'estado' => ['required', 'boolean'],
            'role' => ['required', 'string', 'in:admin,medico,user'],

        ]);

        return DB::transaction(function () use ($validated, $id) {
            $user = User::findOrFail($id);
            $originalRole = $user->getRoleNames()->first(); // Más seguro que $user->role

            // Actualiza los campos del usuario
            $user->fill(Arr::except($validated, 'role'));
            $user->save();

            // Asegura que el usuario tiene el rol actualizado
            if (!$user->hasRole('medico') && $validated['role'] === 'medico') {
                $user->syncRoles(['medico']);
            }

            // Si ahora es médico y no tiene perfil relacionado
            if ($user->hasRole('medico') && !$user->medico) {
                $medico = Medico::create([
                    'user_id' => $user->id,
                    'nombre' => $user->name,
                    'apellido' => $user->surname,
                    'dni' => $user->dni,
                    'email' => $user->email,
                    'telefono' => $user->phone,
                    'estado' => $user->estado,
                    'role' => 'medico',
                ]);

                return redirect()->route('medico.edit', $medico->id)->with([
                    'success' => 'Perfil profesional creado. Complete los datos específicos.',
                    'nuevoMedico' => true,
                ]);
            }

            // Si ya no tiene rol de médico, eliminar relación
            if ($originalRole === 'medico' && $validated['role'] === 'user') {
                $user->syncRoles(['user']);
                $user->medico()->delete();
            }

            return redirect()->route('usuario.index')->with('success', 'Usuario actualizado correctamente.');
        });
    }

    public function destroy($id)
    {
        $usuario = User::find($id);
        if (!$usuario) {
            return redirect()->route('usuario.index')->with('error', 'Usuario no encontrado.');
        };
        $usuario->delete();
        return redirect()->route('usuario.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
