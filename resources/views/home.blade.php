<x-app-layout>
    <div class="main">
        <div class="box-presentacion">
            <h1>Turnos Online</h1>
            <h2>Bienvenido a la plataforma de gestión de turnos online</h2>
            <p>{{ config('app.mensaje_bienvenida') }}</p>
            <p>{{ config('app.mensaje_paciente') }}</p>
            @if (auth()->check())
                @auth
                    <div class="d-flex justify-content-center mt-4">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary me-2">Ir a Mi perfil</a>
                        <a href="{{ route('logout') }}" class="btn btn-secondary">Cerrar sesión</a>
                    </div>
                @endauth
            @else
                <b>Para comenzar, por favor inicia sesión o regístrate si aún no tienes una cuenta.</b>
                <div class="d-flex justify-content-center mt-4">
                    <a href="{{ route('login') }}" class="btn btn-primary me-2">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="btn btn-secondary">Registrarse</a>
                </div>
            @endif



        </div>
    </div>
</x-app-layout>
