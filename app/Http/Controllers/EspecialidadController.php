<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EspecialidadUpdateRequest;
use App\Models\Especialidad;

class EspecialidadController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:especialidads,nombre',
            'descripcion' => 'nullable|string|max:255',
            'estado' => 'sometimes|boolean',
        ]);
        $especialidad = new Especialidad();
        $especialidad->nombre = request('nombre');
        $especialidad->descripcion = $validated['descripcion'] ?? null;
        $especialidad->estado = $request->input('estado', 0); // Valor por defecto 0 si no existe
        
        $especialidad->save();

        session()->flash('success', [
            'title' => 'Creado!',
            'text' => 'Nueva especialidad creada con éxito.',
            'icon' => 'success',
        ]);

        return back();
    }
}
