<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipo;
use App\Models\Especialidad;
use Illuminate\Support\Facades\Log;

class EquipoController extends Controller
{
    public function index()
    {
        $equipos = Equipo::all();
        return view('equipos/index', compact('equipos'));
    }
    public function create()
    {
        $especialidades = Especialidad::all();
        return view('equipos/create', compact('especialidades'));
    }
    public function store(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'required|integer|unique:equipos,dni',
            'email' => 'required|email|unique:equipos,email',
            'telefono' => 'required|string|max:255',
            'especialidad' => 'required|string|max:255',
            'matricula' => 'required|string|max:255',
            'rol' => 'required|string|max:255',
            'estado' => 'sometimes|boolean',
        ]);
        //dd('Llega');
        // Crear el nuevo equipo
        $equipo = new Equipo();
        $equipo->nombre = $request->input('nombre');
        $equipo->apellido = $request->input('apellido');
        $equipo->dni = $request->input('dni');
        $equipo->email = $request->input('email');
        $equipo->telefono = $request->input('telefono');
        $equipo->especialidad_id = $request->input('especialidad');
        $equipo->matricula = $request->input('matricula');
        $equipo->role = $request->input('rol');
        $equipo->estado = $request->input('estado', 0); // Valor por defecto 0 si no existe

        // Guardar el equipo en la base de datos
        $equipo->save();
        session()->flash('success', [
            'title' => 'Creado!',
            'text' => 'Nuevo equipo creado con éxito.',
            'icon' => 'success',
        ]);
        return back();
    }
    public function list()
    {
        // Aquí puedes listar todos los equipos

    }
    public function getPorEspecialidad($id)
    {
        $equipos = Equipo::where('especialidad_id', $id)
                         ->where('estado', 1) // Solo activos
                         ->get();
    
        return response()->json($equipos);
    }
    

    public function show($id)
    {
        $equipo = Equipo::find($id);
        if (!$equipo) {
            session()->flash('error', [
                'title' => 'Error!',
                'text' => 'Equipo no encontrado.',
                'icon' => 'error',
            ]);
            Log::error('Equipo no encontrado', ['id' => $id]);
            return back();
        }
        return view('equipos/show', compact('equipo'));
    }
    public function edit($id)
    {
        $equipo = Equipo::find($id);
        if (!$equipo) {
            session()->flash('error', [
                'title' => 'Error!',
                'text' => 'Equipo no encontrado.',
                'icon' => 'error',
            ]);
            Log::error('Equipo no encontrado', ['id' => $id]);
            return back();
        }
        $especialidades = Especialidad::all();
        return view('equipos/edit', compact('equipo', 'especialidades'));
    }
    public function update(Request $request, $id)
    {
        // Validar los datos de entrada
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'required|integer|unique:equipos,dni,' . $id,
            'email' => 'required|email|unique:equipos,email,' . $id,
            'telefono' => 'required|string|max:255',
            'especialidad' => 'required|string|max:255',
            'matricula' => 'required|string|max:255',
            'rol' => 'required|string|max:255',
            'estado' => 'sometimes|boolean',
        ]);

        // Actualizar el equipo
        $equipo = Equipo::find($id);
        if (!$equipo) {
            session()->flash('error', [
                'title' => 'Error!',
                'text' => 'Equipo no encontrado.',
                'icon' => 'error',
            ]);
            Log::error('Equipo no encontrado', ['id' => $id]);
            return back();
        }
        $equipo->nombre = $request->input('nombre');
        $equipo->apellido = $request->input('apellido');
        $equipo->dni = $request->input('dni');
        $equipo->email = $request->input('email');
        $equipo->telefono = $request->input('telefono');
        $equipo->especialidad_id = $request->input('especialidad');
        $equipo->matricula = $request->input('matricula');
        $equipo->role = $request->input('rol');
        $equipo->estado = $request->input('estado', 0); // Valor por defecto 0 si no existe

        // Guardar los cambios en la base de datos
        $equipo->save();
        session()->flash('success', [
            'title' => 'Actualizado!',
            'text' => 'Datos actualizados con éxito.',
            'icon' => 'success',
        ]);
        return back();
    }
    public function destroy($id)
    {
        $equipo = Equipo::find($id);
        if (!$equipo) {
            session()->flash('error', [
                'title' => 'Error!',
                'text' => 'Equipo no encontrado.',
                'icon' => 'error',
            ]);
            Log::error('Equipo no encontrado', ['id' => $id]);
            return back();
        }
        $equipo->delete();
        session()->flash('success', [
            'title' => 'Eliminado!',
            'text' => 'Equipo eliminado con éxito.',
            'icon' => 'success',
        ]);
        return back();
    }
}
