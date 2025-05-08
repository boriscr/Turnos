<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        return view('usuarios/usuario-index');
    }

    public function create()
    {
        return view('usuarios/usuario-create');
    }

    public function store(Request $request)
    {
        // Aquí puedes manejar la lógica para almacenar un nuevo usuario
        // Por ejemplo, validar los datos y guardarlos en la base de datos
    }

    public function show($id)
    {
        // Aquí puedes manejar la lógica para mostrar un usuario específico
    }

    public function edit($id)
    {
        // Aquí puedes manejar la lógica para editar un usuario específico
    }

    public function update(Request $request, $id)
    {
        // Aquí puedes manejar la lógica para actualizar un usuario específico
    }

    public function destroy($id)
    {
        // Aquí puedes manejar la lógica para eliminar un usuario específico
    }
}
