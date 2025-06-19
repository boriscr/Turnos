<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use App\Http\Requests\EspecialidadStoreRequest;
use App\Http\Requests\EspecialidadUpdateRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
    public function store(EspecialidadStoreRequest $request)
    {
        try {
            Especialidad::create($request->validated());

            session()->flash('success', [
                'title' => 'Creado!',
                'text' => 'Nueva especialidad creada con éxito.',
                'icon' => 'success',
            ]);
        } catch (Exception $e) {
            return back()->withErrors('Error al crear la especialidad: ' . $e->getMessage());
        }

        return redirect()->route('especialidad.index');
    }

    public function show($id)
    {
        try {
            $especialidad = Especialidad::findOrFail($id);
            return view('especialidades/show', compact('especialidad'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('especialidad.index')->withErrors('Especialidad no encontrada.');
        }
    }
    public function edit($id)
    {
        try {
            $especialidad = Especialidad::findOrFail($id);
            return view('especialidades/edit', compact('especialidad'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('especialidad.index')->withErrors('Especialidad no encontrada.');
        }
    }

    public function update(EspecialidadUpdateRequest $request, $id)
    {
        try {
            $especialidad = Especialidad::findOrFail($id);
            $especialidad->update($request->validated());

            session()->flash('success', [
                'title' => 'Actualizado!',
                'text' => 'Especialidad actualizada con éxito.',
                'icon' => 'success',
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('especialidad.index')->withErrors('Especialidad no encontrada.');
        } catch (Exception $e) {
            return back()->withErrors('Error al actualizar la especialidad: ' . $e->getMessage());
        }

        return redirect()->route('especialidad.index');
    }


    public function destroy($id)
    {
        try {
            $especialidad = Especialidad::findOrFail($id);

            // Evitar eliminar si tiene medicos asociados
            /*if ($especialidad->medicos()->exists()) {
                return redirect()->back()->withErrors('No se puede eliminar una especialidad con medicos asociados.');
            }*/
            // Eliminar la especialidad

            $especialidad->delete();

            session()->flash('success', [
                'title' => 'Eliminado!',
                'text' => 'Especialidad eliminada con éxito.',
                'icon' => 'success',
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('especialidad.index')->withErrors('Especialidad no encontrada.');
        } catch (Exception $e) {
            return back()->withErrors('Error al eliminar la especialidad: ' . $e->getMessage());
        }

        return redirect()->route('especialidad.index');
    }

    // View lista del medico con la especialidad
    /*
    public function listaEquipo($id)
    {
        try {
            $especialidad = Especialidad::findOrFail($id);
            $medicos = Medico::with('especialidad')->where('especialidad_id', $id)->get();

            return view('especialidades/list', compact('medicos', 'especialidad'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('especialidad.index')->withErrors('Especialidad no encontrada.');
        } catch (Exception $e) {
            return back()->withErrors('Error al obtener lista de médicos: ' . $e->getMessage());
        }
    }*/
}
