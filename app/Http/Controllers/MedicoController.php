<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\Especialidad;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\MedicoStoreRequest;
use App\Http\Requests\MedicoUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MedicoController extends Controller
{
    public function index()
    {
        $medicos = Medico::all();
        return view('medicos.index', compact('medicos'));
    }

    public function create()
    {
        $specialties = Especialidad::all();
        return view('medicos.create', compact('specialties'));
    }

    public function store(MedicoStoreRequest $request)
    {
        try {
            Medico::create($request->validated());

            session()->flash('success', [
                'title' => 'Creado!',
                'text'  => 'Nuevo médico creado con éxito.',
                'icon'  => 'success',
            ]);
        } catch (\Exception $e) {
            Log::error('Error al crear nuevo médico', ['error' => $e->getMessage()]);

            session()->flash('error', [
                'title' => 'Error!',
                'text'  => 'Ocurrió un error al crear datos del médico.',
                'icon'  => 'error',
            ]);
        }

        return redirect()->route('medico.index');
    }

    //ok
    public function show($id)
    {
        try {
            $medico = Medico::findOrFail($id);
            return view('medicos.show', compact('medico'));
        } catch (ModelNotFoundException $e) {
            Log::error('Médico no encontrado', ['id' => $id]);

            session()->flash('error', [
                'title' => 'Error!',
                'text'  => 'Médico no encontrado.',
                'icon'  => 'error',
            ]);
            return back();
        } catch (\Exception $e) {
            Log::error('Error al mostrar datos del médico', ['id' => $id, 'error' => $e->getMessage()]);
            return back();
        }
    }

    public function edit($id)
    {
        try {
            $medicos = Medico::findOrFail($id);
            $specialties = Especialidad::all();
            return view('medicos.edit', compact('medicos', 'specialties'));
        } catch (ModelNotFoundException $e) {
            Log::error('Médico no encontrado', ['id' => $id]);

            session()->flash('error', [
                'title' => 'Error!',
                'text'  => 'Médico no encontrado.',
                'icon'  => 'error',
            ]);
            return back();
        } catch (\Exception $e) {
            Log::error('Error al editar datos del médico', ['id' => $id, 'error' => $e->getMessage()]);
            return back();
        }
    }

    public function update(MedicoUpdateRequest $request, $id)
    {
        try {
            $medico = Medico::findOrFail($id);
            $medico->update($request->validated());

            session()->flash('success', [
                'title' => 'Actualizado!',
                'text'  => 'Datos actualizados con éxito.',
                'icon'  => 'success',
            ]);
        } catch (ModelNotFoundException $e) {
            Log::error('Medico no encontrado', ['id' => $id]);

            session()->flash('error', [
                'title' => 'Error!',
                'text'  => 'Médico no encontrado.',
                'icon'  => 'error',
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar datos del médico', ['id' => $id, 'error' => $e->getMessage()]);

            session()->flash('error', [
                'title' => 'Error!',
                'text'  => 'Ocurrió un error al actualizar los datos.',
                'icon'  => 'error',
            ]);
        }

        return redirect()->route('medico.index');
    }

    public function destroy($id)
    {
        try {
            $medico = Medico::findOrFail($id);
            $medico->delete();
            session()->flash('success', [
                'title' => 'Eliminado!',
                'text'  => 'Médico eliminado con éxito.',
                'icon'  => 'success',
            ]);
            Log::info('Médico eliminado', ['id' => $id]);
            return redirect()->route('medico.index');
        } catch (ModelNotFoundException $e) {
            Log::error('Médico no encontrado', ['id' => $id]);
            session()->flash('error', [
                'title' => 'Error!',
                'text'  => 'Médico no encontrado.',
                'icon'  => 'error',
            ]);
            return redirect()->route('medico.index');
        } catch (\Exception $e) {
            Log::error('Error al eliminar medico', ['id' => $id, 'error' => $e->getMessage()]);
            session()->flash('error', [
                'title' => 'Error!',
                'text'  => 'Ocurrió un error al eliminar el médico.',
                'icon'  => 'error',
            ]);
            return redirect()->route('medico.index');
        }
    }
}
