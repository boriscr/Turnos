<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\Reserva; // Asegúrate de que este modelo exista y esté correctamente configurado
use App\Models\Turno; // Asegúrate de que este modelo exista y esté correctamente configurado
class PerfilController extends Controller
{
    public function index()
    {
        return view('perfil.index');
    }

    public function edit()
    {
        return view('perfil.edit');
    }

    public function update(Request $request)
    {
        // Validate and update the user's profile here
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'integer', 'digits_between:7,8'],
            'birthdate' => ['required', 'date'],
            'genero' => ['required', 'string', 'max:255', 'in:Masculino,Femenino,No binario,otro,Prefiero no decir'],
            'country' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:15'],
            // Add other fields as necessary
        ]);

        return redirect()->route('perfil.index')->with('success', 'Perfil actualizado correctamente.');
    }

    public function historial()
    {
        // Aquí puedes implementar la lógica para mostrar el historial de reservas del usuario
        // Por ejemplo, podrías obtener las reservas del usuario autenticado y pasarlas a la vista
        if (Auth::check()) {
            $totalReservas = Reserva::where('user_id', Auth::user()->id)->count(); // Cuenta todas las reservas
            $reservas = Reserva::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(10);
            return view('perfil.historial', compact('reservas', 'totalReservas'));
        } else {
            return 'Usuario no autenticado';
        }
    }

    public function show($id)
    {
        // Aquí puedes implementar la lógica para mostrar un perfil específico
        // Por ejemplo, podrías buscar un usuario por su ID y pasarlo a la vista
        $reserva = Reserva::findOrfail($id); // Obtiene el usuario autenticado
        if (!$reserva) {
            return redirect()->route('perfil.historial')->with('error', 'Reserva no encontrada.');
        }
        if ($reserva->user_id !== Auth::id()) {
            return redirect()->route('perfil.historial')->with('error', 'No tienes permiso para ver esta reserva.');
        }
        return view('perfil.show', compact('reserva'));
    }
}
