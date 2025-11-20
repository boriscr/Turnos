<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\DoctorStoreRequest;
use App\Http\Requests\DoctorUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;


class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('specialty')
            ->select('id', 'name', 'surname', 'idNumber', 'specialty_id', 'role', 'status')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
        return view('doctors.index', compact('doctors'));
    }
    //busqueda de usuarios por dni o email
    public function search(Request $request)
    {
        $query = $request->input('search');
        $doctors = Doctor::where('idNumber', 'like', "%{$query}%")
            ->paginate(10);
        return view('doctors/index', compact('doctors'));
    }
    public function create()
    {
        $specialties = Specialty::select('id', 'name')->where('status', true)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('doctors.create', compact('specialties'));
    }

    public function store(DoctorStoreRequest $request)
    {
        try {

            Doctor::create([
                ...$request->validated()
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

        return redirect()->route('doctors.index');
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
            $specialties = Specialty::select('id', 'name')->where('status', true)
                ->orderBy('created_at', 'desc')
                ->get();
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
                ...$request->validated() // Spread operator para los datos validados
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

        return redirect()->route('doctors.index');
    }

    public function destroy($id)
    {
        try {
            $doctor = Doctor::findOrFail($id);
            $user = User::select('id')->where('id', $doctor->user_id)->first();
            //Verificar si existen turnos asociados al doctor antes de eliminar
            $appointment = Appointment::select('doctor_id')->where('doctor_id', $id)->exists();
            if ($appointment) {
                session()->flash('error', [
                    'title' => 'Error!',
                    'text'  => 'No se puede eliminar el médico porque tiene turnos asociados.',
                    'icon'  => 'error',
                ]);
                return redirect()->route('doctors.index');
            }
            // Si el doctor tiene un user asociado, cambiar su rol a 'user'
            if ($user) {
                //cambiar el rol del user a user
                $user->removeRole('doctor');
                $user->assignRole('user');
            }
            // Eliminar el doctor
            $doctor->delete();
            session()->flash('success', [
                'title' => 'Eliminado!',
                'text'  => 'Médico eliminado con éxito.',
                'icon'  => 'success',
            ]);
            return redirect()->route('doctors.index');
        } catch (ModelNotFoundException $e) {
            Log::error('Médico no encontrado', ['id' => $id]);
            session()->flash('error', [
                'title' => 'Error!',
                'text'  => 'Médico no encontrado.',
                'icon'  => 'error',
            ]);
            return redirect()->route('doctors.index');
        } catch (\Exception $e) {
            Log::error('Error al eliminar doctor', ['id' => $id, 'error' => $e->getMessage()]);
            session()->flash('error', [
                'title' => 'Error!',
                'text'  => 'Ocurrió un error al eliminar el doctor.',
                'icon'  => 'error',
            ]);
            return redirect()->route('doctors.index');
        }
    }
}
