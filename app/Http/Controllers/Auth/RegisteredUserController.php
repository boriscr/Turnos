<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $disabledNav = true;
        return view('auth.register', compact('disabledNav'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'idNumber' => ['required', 'string', 'min:7', 'max:8', 'unique:' . User::class],
            'birthdate' => ['required', 'date'],
            'gender' => ['required', 'string', 'max:255', 'in:Masculino,Femenino,No binario,Otro,Prefiero no decir'],
            'country' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string','min:9', 'max:15', 'unique:' . User::class],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults()
                    ->min(12)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'messages' => [
                'password.required' => 'La contraseña es obligatoria',
                'password.confirmed' => 'Las contraseñas no coinciden',
                'password.min' => 'La contraseña debe tener al menos :min caracteres',
                'password.letters' => 'La contraseña debe contener letras',
                'password.mixed' => 'La contraseña debe contener mayúsculas y minúsculas',
                'password.numbers' => 'La contraseña debe contener números',
                'password.symbols' => 'La contraseña debe contener símbolos',
                'password.uncompromised' => 'Esta contraseña ha aparecido en filtraciones de datos',
            ]
        ]);

        $user = User::create([
            'name' => trim($request->name),
            'surname' => trim($request->surname),
            'idNumber' => trim($request->idNumber),
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'country' => $request->country,
            'province' => trim($request->province),
            'city' => trim($request->city),
            'address' => trim($request->address),
            'phone' => trim($request->phone),
            'email' => trim($request->email),
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole('user'); // Asignar el rol de usuario por defecto

        event(new Registered($user));

        Auth::login($user);
        session()->flash('success', [
            'title' => 'Registro exitoso',
            'message' => '¡Bienvenido/a! Tu cuenta ha sido creada exitosamente.',
            'icon' => 'success',
            'timer' => 3000,
        ]);
        return redirect(route('home', absolute: false));
    }
}
