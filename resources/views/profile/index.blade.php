<x-app-layout>
    <div class="content-wrapper">
        <H3 class="title-form">
            @if (Auth::user()->genero == 'Masculino')
                Bienvenido, {{ Auth::user()->name }}
            @elseif(Auth::user()->genero == 'Femenino')
                Bienvenida, {{ Auth::user()->name }}
            @else
                Hola, {{ Auth::user()->name }}
            @endif
            </H1>

            <!-- Para nuevos usuarios -->
            @if (Auth::user()->is_new_user)
                <div class="welcome-guide">
                    <h2>¡Bienvenido a nuestro sistema de turnos online!</h2>
                    <div class="guide-steps">
                        <div class="step">
                            <i class="icon-user-check"></i>
                            <p>Complete su perfil para agilizar la solicitud de turnos</p>
                            <a href="{{ route('profile.show') }}" class="btn-step-action">Completar perfil</a>
                        </div>
                        <div class="step">
                            <i class="icon-calendar"></i>
                            <p>Solicite su primer turno médico</p>
                            <a href="{{ route('appointments.create') }}" class="btn-step-action">Sacar turno</a>
                        </div>
                        <div class="step">
                            <i class="icon-bell"></i>
                            <p>Configure sus preferencias de notificaciones</p>
                            <a href="{{ route('profile.notifications') }}" class="btn-step-action">Configurar</a>
                        </div>
                    </div>
                </div>
            @endif


            <div class="section-container-profile">
                <div class="card-profile centrado-total">
                    <h1><b>Seccion Informacion</b></h1>
                    <i class="bi bi-person-bounding-box"></i>
                    <a href="{{ route('profile.edit') }}">Ver mis datos</a>
                </div>
                <div class="card-profile centrado-total">
                    <h1><b>Seccion Turnos</b></h1>
                    <i class="bi bi-calendar3"></i>
                    <a href="{{ route('profile.edit') }}">Reservar turno</a>
                </div>

                <!-- Notificaciones importantes -->
                <div class="card-profile centrado-total">
                    <h1><b>Mi historial</b></h1>
                    <i class="bi bi-journal-text"></i>
                    <a href="{{ route('profile.historial') }}">Ver historial</a>
                </div>
            </div>
    </div>
</x-app-layout>
