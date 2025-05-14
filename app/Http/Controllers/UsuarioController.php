<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Asegúrate de importar el modelo Usuario
class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios= User::all();

        return view('usuarios/index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios/create');
    }

    public function store(Request $request)
    {
        // Aquí puedes manejar la lógica para almacenar un nuevo usuario
        // Por ejemplo, validar los datos y guardarlos en la base de datos
    }

    public function show($id)
    {
        $usuario= User::find($id);
        if (!$usuario) {
            return redirect()->route('usuario.index')->with('error', 'Usuario no encontrado.');
        };
        return view('usuarios/show', compact('usuario'));
    }
//Edit admin controller
    public function edit($id)
    {
        $usuario= User::find($id);
        if (!$usuario) {
            return redirect()->route('usuario.index')->with('error', 'Usuario no encontrado.');
        };
        return view('usuarios/edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $usuario= User::find($id);
        if (!$usuario) {
            return redirect()->route('usuario.index')->with('error', 'Usuario no encontrado.');
        };

        // Aquí puedes manejar la lógica para actualizar un usuario específico
        // Por ejemplo, validar los datos y guardarlos en la base de datos
        $usuario->update($request->all());
        return redirect()->route('usuario.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $usuario= User::find($id);
        if (!$usuario) {
            return redirect()->route('usuario.index')->with('error', 'Usuario no encontrado.');
        };

        // Aquí puedes manejar la lógica para eliminar un usuario específico
        // Por ejemplo, eliminarlo de la base de datos
        $usuario->delete();
        return redirect()->route('usuario.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
