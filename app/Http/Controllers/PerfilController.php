<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function index()
    {
        return view('perfil.index');
    }

    public function edit()
    {
        return view('perfil.edit');
    }

    public function update(Request $request)
    {
        // Validate and update the user's profile here
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'integer', 'digits_between:7,8'],
            'birthdate' => ['required', 'date'],
            'genero' => ['required', 'string', 'max:255', 'in:Masculino,Femenino,No binario,otro,Prefiero no decir'],
            'country' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:15'],
            // Add other fields as necessary
        ]);
        return redirect()->route('perfil.index')->with('success', 'Perfil actualizado correctamente.');
    }
}
