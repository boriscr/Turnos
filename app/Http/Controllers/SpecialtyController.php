<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use App\Models\Doctor;
use App\Http\Requests\SpecialtyStoreRequest;
use App\Http\Requests\SpecialtyUpdateRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function index()
    {
        $specialties = Specialty::select('id', 'name', 'description', 'status')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('specialties/index', compact('specialties'));
    }

    public function search(Request $request)
    {
        $query = $request->input('search');
        $specialties = Specialty::where('name', 'like', "%{$query}%")
            ->paginate(10);
        return view('specialties/index', compact('specialties'));
    }

    public function create()
    {
        return view('specialties/create');
    }
    public function store(SpecialtyStoreRequest $request)
    {
        try {
            Specialty::create([
                ...$request->validated()
                //'created_by' => Auth::id(),
                //'updated_by' => Auth::id(),
            ]);
            session()->flash('success', [
                'title' => 'Creado!',
                'text' => 'Nueva especialidad creada con éxito.',
                'icon' => 'success',
            ]);
        } catch (Exception $e) {
            return back()->withErrors('Error al crear la especialidad: ' . $e->getMessage());
        }

        return redirect()->route('specialties.index');
    }

    public function show($id)
    {
        try {
            $specialty = Specialty::findOrFail($id);
            $doctors = Doctor::select('id', 'name', 'surname', 'idNumber', 'licenseNumber', 'status')
                ->with('specialty')
                ->where('specialty_id', $id)
                ->orderBy('updated_at', 'desc')
                ->paginate(10);
            return view('specialties/show', compact('specialty', 'doctors'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('specialties.index')->withErrors('Especialidad no encontrada.');
        }
    }
    public function edit($id)
    {
        try {
            $specialty = Specialty::findOrFail($id);
            return view('specialties/edit', compact('specialty'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('specialties.index')->withErrors('Especialidad no encontrada.');
        }
    }

    public function update(SpecialtyUpdateRequest $request, $id)
    {
        try {
            $specialty = Specialty::findOrFail($id);
            $specialty->update([
                ...$request->validated(),
                'update_by' => Auth::id(),
            ]);
            $specialty->update($request->validated());

            session()->flash('success', [
                'title' => 'Actualizado!',
                'text' => 'Especialidad actualizada con éxito.',
                'icon' => 'success',
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('specialties.index')->withErrors('Especialidad no encontrada.');
        } catch (Exception $e) {
            return back()->withErrors('Error al actualizar la especialidad: ' . $e->getMessage());
        }

        return redirect()->route('specialties.index');
    }


    public function destroy($id)
    {
        try {
            $specialty = Specialty::findOrFail($id);

            // Evitar eliminar si tiene doctors asociados
            if ($specialty->doctors()->exists()) {
                session()->flash('error', [
                    'title' => 'Error!',
                    'text' => 'No se puede eliminar una especialidad con doctores asociados.',
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
            return redirect()->route('specialties.index')->withErrors('Especialidad no encontrada.');
        } catch (Exception $e) {
            return back()->withErrors('Error al eliminar la especialidad: ' . $e->getMessage());
        }

        return redirect()->route('specialties.index');
    }
}
