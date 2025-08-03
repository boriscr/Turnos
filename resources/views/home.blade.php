<x-app-layout>
    <div class="main full-center">
        <div class="box-presentacion">
            <h1>Appointments Online</h1>
            <h2>Bienvenido a la plataforma de gestión de appointments online</h2>
            <p>{{ config('app.mensaje_bienvenida') }}</p>
            <p>{{ config('app.mensaje_paciente') }}</p>
            @if (auth()->check())
                @auth
                    <div class="full-center">
                        <button type="button" class="primario-btn">
                            <a class="nav-link" href="{{ route('reservations.create') }}">Reservar Appointment</a>
                        </button>

                    </div>
                @endauth
            @else
                <b>Para comenzar, por favor inicia sesión o regístrate si aún no tienes una cuenta.</b>
                <div class="d-flex justify-content-center mt-4">
                    <a href="{{ route('login') }}" class="btn btn-default me-2">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="btn btn-default">Registrarse</a>
                </div>
            @endif



        </div>
    </div>
</x-app-layout>
