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

    public function updateAdmin(Request $request)
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

        return redirect()->route('profile.index')->with('success', 'Perfil actualizado correctamente.');
    }

    public function historial()
    {
        // implementar la lógica para mostrar el historial de reservas del usuario
        // Por ejemplo, btener las reservas del usuario autenticado y pasarlas a la vista
        if (Auth::check()) {
            $totalReservas = Reserva::where('user_id', Auth::user()->id)->count(); // Cuenta todas las reservas
            $reservas = Reserva::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(10);
            return view('profile.historial', compact('reservas', 'totalReservas'));
        } else {
            return 'Usuario no autenticado';
        }
    }

    public function show($id)
    {
        $reserva = Reserva::findOrfail($id); // Obtiene el usuario autenticado
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

        // Actualizar campos editables (excluyendo dni)
        $user->fill($request->only([
            'name',
            'surname',
            'birthdate',
            'genero',
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
