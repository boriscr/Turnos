<x-body.body>
    <div class="main" style="color: #000000">
        <div class="box-presentacion bg-light p-5 rounded shadow-sm text-center">
            <h1>Turnos Online</h1>
            <h2>Bienvenido a la plataforma de gestión de turnos online</h2>
            <p>En esta plataforma podrás gestionar tus turnos de manera fácil y rápida.</p>
            @if (auth()->check())
                @auth
                    <div class="d-flex justify-content-center mt-4">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary me-2">Ir a Mi perfil</a>
                        <a href="{{ route('logout') }}" class="btn btn-secondary">Cerrar sesión</a>
                    </div>
                @endauth
            @else
                <p>Para comenzar, por favor inicia sesión o regístrate si aún no tienes una cuenta.</p>
                <div class="d-flex justify-content-center mt-4">
                    <a href="{{ route('login') }}" class="btn btn-primary me-2">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="btn btn-secondary">Registrarse</a>
                </div>
            @endif
        </div>
    </div>
</x-body.body>
