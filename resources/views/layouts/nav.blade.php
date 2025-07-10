<nav class="main-nav">
    <div class="nav-header">
        <a href="{{ route('home') }}"><img src="{{asset('images/app-icon.ico')}}" alt="icon" class="nav-icon"></a>
        <a class="nav-brand" href="{{ route('home') }}">{{ config('app.name', 'Laravel') }}</a>
        <button type="button" class="nav-toggle" aria-label="Toggle menu" aria-expanded="false">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <div class="nav-collapse">
        <ul class="nav-list">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('disponible.create') }}">Reservar Turno</a>
            </li>
            @role('admin')
                <li class="nav-item dropdown">
                    <button class="nav-link dropdown-toggle" aria-expanded="false" aria-haspopup="true">
                        Administración <i class="bi bi-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li class="dropdown-section">
                            <span>Sección Usuarios</span>
                            <a class="dropdown-item" href="{{ route('usuario.index') }}">
                                <i class="bi bi-eye"></i> Ver usuarios
                            </a>
                        </li>

                        <li class="dropdown-section">
                            <span>Sección Medico</span>
                            <a class="dropdown-item" href="{{ route('medico.index') }}">
                                <i class="bi bi-eye"></i> Ver medico
                            </a>
                            <a class="dropdown-item" href="{{ route('medico.create') }}">
                                <i class="bi bi-person-fill-add"></i> Agregar medico
                            </a>
                            <a class="dropdown-item" href="{{ route('especialidad.index') }}">
                                <i class="bi bi-heart-pulse-fill"></i> Especialidades
                            </a>
                        </li>

                        <li class="dropdown-section">
                            <span>Sección Turnos</span>
                            <a class="dropdown-item" href="{{ route('turnos.index') }}">
                                <i class="bi bi-eye"></i> Ver Turnos
                            </a>
                            <a class="dropdown-item" href="{{ route('turnos.create') }}">
                                <i class="bi bi-clock"></i> Crear Turnos
                            </a>
                        </li>

                        <li class="dropdown-section">
                            <span>Sección Reservas</span>
                            <a class="dropdown-item" href="{{ route('reservas.index') }}">
                                <i class="bi bi-eye"></i> Ver reservas
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('settings.edit') }}">Settings</a>
                </li>
            @endrole
            @if (auth()->check())
                <li class="nav-item dropdown">
                    <button class="nav-link dropdown-toggle" aria-expanded="false" aria-haspopup="true">
                        Mi perfil <i class="bi bi-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('profile.index') }}">Mi perfil</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button type="submit" class="dropdown-item logout-btn">Cerrar sesión</button>
                            </form>
                        </li>
                    </ul>
                </li>
            @else
                <li class="nav-item dropdown">
                    <button class="nav-link dropdown-toggle" aria-expanded="false" aria-haspopup="true">
                        Iniciar sesión
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('login') }}">Iniciar sesión</a></li>
                        <li><a class="dropdown-item" href="{{ route('register') }}">Registrarse</a></li>
                    </ul>
                </li>
            @endif
        </ul>
    </div>
</nav>
