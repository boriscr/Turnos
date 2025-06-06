<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Asegúrate de importar el modelo Usuario
use App\Models\TurnoDisponible;
use App\Models\Equipo; // Asegúrate de importar el modelo Equipo

use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::all();

        return view('usuarios/index', compact('usuarios'));
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
            'dni' => ['required', 'integer', 'digits_between:7,8', 'unique:' . User::class . ',dni,' . $id],
            'birthdate' => ['required', 'date'],
            'genero' => ['required', 'string', 'max:255', 'in:Masculino,Femenino,No binario,otro,Prefiero no decir'],
            'country' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:15', 'unique:' . User::class . ',phone,' . $id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class . ',email,' . $id],
            'estado' => ['required', 'boolean'],
            'role' => ['required', 'string', 'max:25', 'in:user,equipo,admin'],
        ]);

        return DB::transaction(function () use ($validated, $request, $id) {
            $user = User::findOrFail($id);
            $originalRole = $user->role;

            // Actualizar usuario
            $user->fill($validated);
            $user->save();

            // Manejo del equipo médico
            if ($request->role === 'equipo') {
                if (!$user->equipo) {
                    $equipo = Equipo::create([
                        'user_id' => $user->id,
                        'nombre' => $user->name,
                        'apellido' => $user->surname,
                        'dni' => $user->dni,
                        'email' => $user->email,
                        'telefono' => $user->phone,
                        'estado' => $user->estado,
                        'role' => 'equipo', // Asignar el rol de equipo
                        // No es necesario guardar el role aquí si ya está en users
                    ]);

                    // Redirigir a edición del equipo recién creado
                    $nuevoEquipo = true;
                    return redirect()->route('equipo.edit', $equipo->id)
                        ->with([
                            'success' => 'Perfil profesional creado. Complete los datos específicos.',
                            'nuevoEquipo' => $nuevoEquipo
                        ]);
                }
            } elseif ($originalRole === 'equipo' && $request->role !== 'equipo') {
                $user->equipo()->delete();
            }

            return redirect()->route('usuario.index')
                ->with('success', 'Usuario actualizado correctamente.');
        });
    }

    public function destroy($id)
    {
        $usuario = User::find($id);
        if (!$usuario) {
            return redirect()->route('usuario.index')->with('error', 'Usuario no encontrado.');
        };

        // Aquí puedes manejar la lógica para eliminar un usuario específico
        // Por ejemplo, eliminarlo de la base de datos
        $usuario->delete();
        return redirect()->route('usuario.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
