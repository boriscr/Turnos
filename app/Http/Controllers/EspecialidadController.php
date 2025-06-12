<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use App\Models\Equipo;
use App\Http\Requests\StoreEspecialidadRequest;
use App\Http\Requests\UpdateEspecialidadRequest;

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
    public function store(StoreEspecialidadRequest $request)
    {
        // Validar los datos de la solicitud y crear una nueva especialidad
        Especialidad::create($request->validated());

        session()->flash('success', [
            'title' => 'Creado!',
            'text' => 'Nueva especialidad creada con éxito.',
            'icon' => 'success',
        ]);

        return redirect()->route('especialidad.index');
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
    public function update(UpdateEspecialidadRequest $request, $id)
    {
        // Validar el ID de la especialidad
        $especialidad = Especialidad::findOrFail($id);
        // Actualizar los campos de la especialidad
        $especialidad->update($request->validated());

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
