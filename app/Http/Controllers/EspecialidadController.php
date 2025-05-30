<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EspecialidadUpdateRequest;
use App\Models\Especialidad;
use App\Models\Equipo;

class EspecialidadController extends Controller
{
    public function index()
    {
        $especialidades = Especialidad::all();
        return view('especialidades/index', compact('especialidades'));
    }
    public function create()
    {
        return view('especialidades/create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:especialidads,nombre',
            'descripcion' => 'nullable|string|max:255',
            'estado' => 'sometimes|boolean',
        ]);
        $especialidad = new Especialidad();
        $especialidad->nombre = trim($validated['nombre']);
        $especialidad->descripcion = $validated['descripcion'] ? trim($validated['descripcion']) : null;
        $especialidad->estado = $request->boolean('estado', false);

        $especialidad->save();

        session()->flash('success', [
            'title' => 'Creado!',
            'text' => 'Nueva especialidad creada con éxito.',
            'icon' => 'success',
        ]);

        return back();
    }

    public function show($id)
    {
        $especialidad = Especialidad::findOrFail($id);
        return view('especialidades/show', compact('especialidad'));
    }
    public function edit($id)
    {
        $especialidad = Especialidad::findOrFail($id);
        return view('especialidades/edit', compact('especialidad'));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:especialidads,nombre,' . $id,
            'descripcion' => 'nullable|string|max:255',
            'estado' => 'sometimes|boolean',
        ]);
        // Validar el ID de la especialidad
        $especialidad = Especialidad::findOrFail($id);
        // Actualizar los campos de la especialidad
        $especialidad->nombre = trim($validated['nombre']);
        $especialidad->descripcion = $validated['descripcion'] ? trim($validated['descripcion']) : null;
        $especialidad->estado = $request->boolean('estado', false);

        // Guardar los cambios en la base de datos
        $especialidad->save();

        session()->flash('success', [
            'title' => 'Actualizado!',
            'text' => 'Especialidad actualizada con éxito.',
            'icon' => 'success',
        ]);

        return redirect()->route('especialidad.index');
    }

    
    public function destroy($id)
    {
        $especialidad = Especialidad::findOrFail($id);
        $especialidad->delete();

        session()->flash('success', [
            'title' => 'Eliminado!',
            'text' => 'Especialidad eliminada con éxito.',
            'icon' => 'success',
        ]);

        return redirect()->route('especialidad.index');
    }

    //View lista del equipo con la especialidad
    public function listaEquipo($id)
    {
        $equipo = Equipo::with('especialidad')->where('especialidad_id', $id)->get();

        return view('especialidades/list', compact('equipo'));
    }
}
