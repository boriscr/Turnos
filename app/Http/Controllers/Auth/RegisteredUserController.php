<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use App\Http\Requests\RegisteredStoreRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Nnjeim\World\World;

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
        // Cargar países directamente desde la tabla
        $countries = DB::table('countries')
            ->orderBy('name')
            ->get();

        // Encontrar Argentina
        $argentinaId = DB::table('countries')
            ->where('name', 'Argentina')
            ->value('id');

        $disabledNav = true;

        return view('auth.register', compact('disabledNav', 'countries', 'argentinaId'));
    }


    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisteredStoreRequest $request): RedirectResponse
    {
        // Pequeño delay anti-bot (0.5 - 1 segundo)
        usleep(500000); // 500,000 microsegundos = 0.5 segundos
        // Los datos ya vienen validados y limpios desde el FormRequest
        $user = User::create([
            'name' => $request->validated()['name'],
            'surname' => $request->validated()['surname'],
            'idNumber' => $request->validated()['idNumber'],
            'birthdate' => $request->validated()['birthdate'],
            'gender' => $request->validated()['gender'],
            'country_id' => $request->validated()['country_id'],
            'state_id' => $request->validated()['state_id'],
            'city_id' => $request->validated()['city_id'],
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
            'html' => '<h1>¡Holaa!</h1> 
                <h2>Tu cuenta ha sido creada exitosamente.</h2>
                <h3>Te invitamos a leer las normas de la aplicación para aprovecharla al máximo y evitar problemas.</h3>
                <a href="">Revisar ahora</a>',
            'icon' => 'success',
        ]);

        return redirect(route('profile.index', absolute: false));
    }
}
