<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
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
