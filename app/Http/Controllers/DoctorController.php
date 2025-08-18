<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Specialty;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\DoctorStoreRequest;
use App\Http\Requests\DoctorUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::all();
        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        $specialties = Specialty::all();
        return view('doctors.create', compact('specialties'));
    }

    public function store(DoctorStoreRequest $request)
    {
        try {

            Doctor::create([
                ...$request->validated(),
                'create_by' => Auth::id(),
                'update_by' => Auth::id(),
            ]);
            session()->flash('success', [
                'title' => 'Creado!',
                'text'  => 'Nuevo doctor creado con éxito.',
                'icon'  => 'success',
            ]);
        } catch (\Exception $e) {
            Log::error('Error al crear nuevo doctor', ['error' => $e->getMessage()]);

            session()->flash('error', [
                'title' => 'Error!',
                'text'  => 'Ocurrió un error al crear datos del doctor.',
                'icon'  => 'error',
            ]);
        }

        return redirect()->route('doctor.index');
    }

    //ok
    public function show($id)
    {
        try {
            $doctor = Doctor::findOrFail($id);
            return view('doctors.show', compact('doctor'));
        } catch (ModelNotFoundException $e) {
            Log::error('Médico no encontrado', ['id' => $id]);

            session()->flash('error', [
                'title' => 'Error!',
                'text'  => 'Médico no encontrado.',
                'icon'  => 'error',
            ]);
            return back();
        } catch (\Exception $e) {
            Log::error('Error al mostrar datos del doctor', ['id' => $id, 'error' => $e->getMessage()]);
            return back();
        }
    }

    public function edit($id)
    {
        try {
            $doctors = Doctor::findOrFail($id);
            $specialties = Specialty::all();
            return view('doctors.edit', compact('doctors', 'specialties'));
        } catch (ModelNotFoundException $e) {
            Log::error('Médico no encontrado', ['id' => $id]);

            session()->flash('error', [
                'title' => 'Error!',
                'text'  => 'Médico no encontrado.',
                'icon'  => 'error',
            ]);
            return back();
        } catch (\Exception $e) {
            Log::error('Error al editar datos del doctor', ['id' => $id, 'error' => $e->getMessage()]);
            return back();
        }
    }

    public function update(DoctorUpdateRequest $request, $id)
    {
        try {
            $doctor = Doctor::findOrFail($id);
            $doctor->update([
                ...$request->validated(), // Spread operator para los datos validados
                'update_by' => Auth::id(), // Asignar el usuario que actualiza
            ]);
            session()->flash('success', [
                'title' => 'Actualizado!',
                'text'  => 'Datos actualizados con éxito.',
                'icon'  => 'success',
            ]);
        } catch (ModelNotFoundException $e) {
            Log::error('Doctor no encontrado', ['id' => $id]);

            session()->flash('error', [
                'title' => 'Error!',
                'text'  => 'Médico no encontrado.',
                'icon'  => 'error',
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar datos del doctor', ['id' => $id, 'error' => $e->getMessage()]);

            session()->flash('error', [
                'title' => 'Error!',
                'text'  => 'Ocurrió un error al actualizar los datos.',
                'icon'  => 'error',
            ]);
        }

        return redirect()->route('doctor.index');
    }

    public function destroy($id)
    {
        try {
            $doctor = Doctor::findOrFail($id);
            $doctor->delete();
            session()->flash('success', [
                'title' => 'Eliminado!',
                'text'  => 'Médico eliminado con éxito.',
                'icon'  => 'success',
            ]);
            Log::info('Médico eliminado', ['id' => $id]);
            return redirect()->route('doctor.index');
        } catch (ModelNotFoundException $e) {
            Log::error('Médico no encontrado', ['id' => $id]);
            session()->flash('error', [
                'title' => 'Error!',
                'text'  => 'Médico no encontrado.',
                'icon'  => 'error',
            ]);
            return redirect()->route('doctor.index');
        } catch (\Exception $e) {
            Log::error('Error al eliminar doctor', ['id' => $id, 'error' => $e->getMessage()]);
            session()->flash('error', [
                'title' => 'Error!',
                'text'  => 'Ocurrió un error al eliminar el doctor.',
                'icon'  => 'error',
            ]);
            return redirect()->route('doctor.index');
        }
    }
}
