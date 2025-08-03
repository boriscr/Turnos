<x-app-layout>
    <div class="content-wrapper">
        <H3 class="title-form">
            @if (Auth::user()->gender == 'Masculino')
                Bienvenido, {{ Auth::user()->name }}
            @elseif(Auth::user()->gender == 'Femenino')
                Bienvenida, {{ Auth::user()->name }}
            @else
                Hola, {{ Auth::user()->name }}
            @endif
            </H1>

            <!-- Para nuevos users -->
            @if (Auth::user()->is_new_user)
                <div class="welcome-guide">
                    <h2>¡Bienvenido a nuestro sistema de appointments online!</h2>
                    <div class="guide-steps">
                        <div class="step">
                            <i class="icon-user-check"></i>
                            <p>Complete su perfil para agilizar la solicitud de appointments</p>
                            <a href="{{ route('profile.show') }}" class="btn-step-action">Completar perfil</a>
                        </div>
                        <div class="step">
                            <i class="icon-calendar"></i>
                            <p>Solicite su primer appointment médico</p>
                            <a href="{{ route('reservations.create') }}" class="btn-step-action">Sacar appointment</a>
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
                <div class="card-profile full-center">
                    <h1><b>Seccion Informacion</b></h1>
                    <i class="bi bi-person-bounding-box"></i>
                    <a href="{{ route('profile.edit') }}">Ver mis datos</a>
                </div>
                <div class="card-profile full-center">
                    <h1><b>Seccion Appointments</b></h1>
                    <i class="bi bi-calendar3"></i>
                    <a href="{{ route('profile.edit') }}">Reservar appointment</a>
                </div>

                <!-- Notificaciones importantes -->
                <div class="card-profile full-center">
                    <h1><b>Mi historial</b></h1>
                    <i class="bi bi-journal-text"></i>
                    <a href="{{ route('profile.historial') }}">Ver historial</a>
                </div>
            </div>
    </div>
</x-app-layout>
