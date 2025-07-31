<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Reserva;
class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index', [
            'user' => Auth::user(),
        ]);
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

public function historial()
{
    if (Auth::check()) {
        $totalReservas = Reserva::where('user_id', Auth::user()->id)->count();
        $reservas = Reserva::where('user_id', Auth::user()->id)
                      ->with('turnoDisponible') // Asegúrate de cargar la relación
                      ->orderBy('id', 'desc')
                      ->paginate(10);
        
        $now = now(); // Fecha y hora actual
        
        return view('profile.historial', compact('reservas', 'totalReservas', 'now'));
    } else {
        return redirect()->route('login'); // Mejor redirigir que mostrar un mensaje
    }
}

    public function show($id)
    {
        $reserva = Reserva::findOrfail($id); // Obtiene el user autenticado
        if (!$reserva) {
            return redirect()->route('profile.historial')->with('error', 'Reserva no encontrada.');
        }
        if ($reserva->user_id !== Auth::id()) {
            return redirect()->route('profile.historial')->with('error', 'No tienes permiso para ver esta reserva.');
        }
        return view('profile.show', compact('reserva'));
    }



    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Actualizar campos editables (excluyendo idNumber)
        $user->fill($request->only([
            'name',
            'surname',
            'birthdate',
            'gender',
            'country',
            'province',
            'city',
            'address',
            'phone'
        ]));

        // Validación adicional para email
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Guardar los cambios
        $user->save();
        session()->flash('success', [
            'title' => 'Tus datos han sido actualizados',
            'text' => 'Los cambios se han guardado correctamente',
            'icon' => 'success',
        ]);
        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
