<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Especialidad;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\EquipoStoreRequest;
use App\Http\Requests\EquipoUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EquipoController extends Controller
{
    public function index()
    {
        $equipos = Equipo::all();
        return view('equipos.index', compact('equipos'));
    }

    public function create()
    {
        $especialidades = Especialidad::all();
        return view('equipos.create', compact('especialidades'));
    }

    public function store(EquipoStoreRequest $request)
    {
        try {
            Equipo::create($request->validated());

            session()->flash('success', [
                'title' => 'Creado!',
                'text'  => 'Nuevo equipo creado con éxito.',
                'icon'  => 'success',
            ]);
        } catch (\Exception $e) {
            Log::error('Error al crear equipo', ['error' => $e->getMessage()]);

            session()->flash('error', [
                'title' => 'Error!',
                'text'  => 'Ocurrió un error al crear el equipo.',
                'icon'  => 'error',
            ]);
        }

        return redirect()->route('equipo.index');
    }

    public function getPorEspecialidad($id)
    {
        try {
            $equipos = Equipo::where('especialidad_id', $id)
                ->where('estado', 1)
                ->get();

            return response()->json($equipos);
        } catch (\Exception $e) {
            Log::error('Error al obtener equipos por especialidad', ['especialidad_id' => $id, 'error' => $e->getMessage()]);
            return response()->json([], 500);
        }
    }

    public function show($id)
    {
        try {
            $equipo = Equipo::findOrFail($id);
            return view('equipos.show', compact('equipo'));
        } catch (ModelNotFoundException $e) {
            Log::error('Equipo no encontrado', ['id' => $id]);

            session()->flash('error', [
                'title' => 'Error!',
                'text'  => 'Equipo no encontrado.',
                'icon'  => 'error',
            ]);
            return back();
        } catch (\Exception $e) {
            Log::error('Error al mostrar equipo', ['id' => $id, 'error' => $e->getMessage()]);
            return back();
        }
    }

    public function edit($id)
    {
        try {
            $equipo = Equipo::findOrFail($id);
            $especialidades = Especialidad::all();
            return view('equipos.edit', compact('equipo', 'especialidades'));
        } catch (ModelNotFoundException $e) {
            Log::error('Equipo no encontrado', ['id' => $id]);

            session()->flash('error', [
                'title' => 'Error!',
                'text'  => 'Equipo no encontrado.',
                'icon'  => 'error',
            ]);
            return back();
        } catch (\Exception $e) {
            Log::error('Error al editar equipo', ['id' => $id, 'error' => $e->getMessage()]);
            return back();
        }
    }

    public function update(EquipoUpdateRequest $request, $id)
    {
        try {
            $equipo = Equipo::findOrFail($id);
            $equipo->update($request->validated());

            session()->flash('success', [
                'title' => 'Actualizado!',
                'text'  => 'Datos actualizados con éxito.',
                'icon'  => 'success',
            ]);
        } catch (ModelNotFoundException $e) {
            Log::error('Equipo no encontrado', ['id' => $id]);

            session()->flash('error', [
                'title' => 'Error!',
                'text'  => 'Equipo no encontrado.',
                'icon'  => 'error',
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar equipo', ['id' => $id, 'error' => $e->getMessage()]);

            session()->flash('error', [
                'title' => 'Error!',
                'text'  => 'Ocurrió un error al actualizar los datos.',
                'icon'  => 'error',
            ]);
        }

        return redirect()->route('equipo.index');
    }

    public function destroy($id)
    {
        try {
            $equipo = Equipo::findOrFail($id);
            $equipo->delete();

            return back()->with('success', [
                'title' => 'Eliminado!',
                'text'  => 'Equipo eliminado con éxito.',
                'icon'  => 'success',
            ]);
        } catch (ModelNotFoundException $e) {
            Log::error('Equipo no encontrado', ['id' => $id]);

            return back()->with('error', [
                'title' => 'Error!',
                'text'  => 'Equipo no encontrado.',
                'icon'  => 'error',
            ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar equipo', ['id' => $id, 'error' => $e->getMessage()]);

            return back()->with('error', [
                'title' => 'Error!',
                'text'  => 'Ocurrió un error al eliminar el equipo.',
                'icon'  => 'error',
            ]);
        }
    }
}
