<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use App\Http\Requests\RegisteredStoreRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

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
    public function store(RegisteredStoreRequest $request): RedirectResponse
    {
        // Los datos ya vienen validados y limpios desde el FormRequest
        $user = User::create([
            'name' => $request->validated()['name'],
            'surname' => $request->validated()['surname'],
            'idNumber' => $request->validated()['idNumber'],
            'birthdate' => $request->validated()['birthdate'],
            'gender' => $request->validated()['gender'],
            'country' => $request->validated()['country'],
            'province' => $request->validated()['province'],
            'city' => $request->validated()['city'],
            'address' => $request->validated()['address'],
            'phone' => $request->validated()['phone'],
            'email' => $request->validated()['email'],
            'password' => Hash::make($request->validated()['password']),
        ]);

        // Asignar el rol de user por defecto
        $user->assignRole('user');

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
