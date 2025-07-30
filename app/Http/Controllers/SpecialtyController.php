<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use App\Http\Requests\SpecialtyStoreRequest;
use App\Http\Requests\SpecialtyUpdateRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SpecialtyController extends Controller
{
    public function index()
    {
        $specialties = Especialidad::all();
        return view('specialties/index', compact('specialties'));
    }
    public function create()
    {
        return view('specialties/create');
    }
    public function store(SpecialtyStoreRequest $request)
    {
        try {
            Especialidad::create($request->validated());

            session()->flash('success', [
                'title' => 'Creado!',
                'text' => 'Nueva specialty creada con éxito.',
                'icon' => 'success',
            ]);
        } catch (Exception $e) {
            return back()->withErrors('Error al crear la specialty: ' . $e->getMessage());
        }

        return redirect()->route('specialty.index');
    }

    public function show($id)
    {
        try {
            $specialty = Especialidad::findOrFail($id);
            return view('specialties/show', compact('specialty'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('specialty.index')->withErrors('Especialidad no encontrada.');
        }
    }
    public function edit($id)
    {
        try {
            $specialty = Especialidad::findOrFail($id);
            return view('specialties/edit', compact('specialty'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('specialty.index')->withErrors('Especialidad no encontrada.');
        }
    }

    public function update(SpecialtyUpdateRequest $request, $id)
    {
        try {
            $specialty = Especialidad::findOrFail($id);
            $specialty->update($request->validated());

            session()->flash('success', [
                'title' => 'Actualizado!',
                'text' => 'Especialidad actualizada con éxito.',
                'icon' => 'success',
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('specialty.index')->withErrors('Especialidad no encontrada.');
        } catch (Exception $e) {
            return back()->withErrors('Error al actualizar la specialty: ' . $e->getMessage());
        }

        return redirect()->route('specialty.index');
    }


    public function destroy($id)
    {
        try {
            $specialty = Especialidad::findOrFail($id);

            // Evitar eliminar si tiene medicos asociados
            if ($specialty->medicos()->exists()) {
                session()->flash('error', [
                    'title' => 'Error!',
                    'text' => 'No se puede eliminar una specialty con médicos asociados.',
                    'icon' => 'error',
                ]);
                return redirect()->back();
            }
            // Eliminar la specialty

            $specialty->delete();

            session()->flash('success', [
                'title' => 'Eliminado!',
                'text' => 'Especialidad eliminada con éxito.',
                'icon' => 'success',
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('specialty.index')->withErrors('Especialidad no encontrada.');
        } catch (Exception $e) {
            return back()->withErrors('Error al eliminar la specialty: ' . $e->getMessage());
        }

        return redirect()->route('specialty.index');
    }
}
