<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">Turnos.com</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('reserva.create') }}">Reservar Turno</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Administración <i class="bi bi-chevron-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <p>Seccion Usuarios</p>
                        <hr>
                        <li><a class="dropdown-item2" href="{{ route('usuario.index') }}"><i class="bi bi-eye"></i> Ver
                                usuarios</a></li>
                        <br>
                        <p>Seccion Equipo</p>
                        <hr>
                        <li><a class="dropdown-item2" href="{{ route('equipo.index') }}"><i class="bi bi-eye"></i> Ver
                                equipo</a></li>
                        <li><a class="dropdown-item2" href="{{ route('equipo.create') }}"><i
                                    class="bi bi-person-fill-add"></i> Agregar equipo</a></li>
                        <li><a class="dropdown-item2" href="{{ route('especialidad.index') }}"><i
                                    class="bi bi-heart-pulse-fill"></i> Especialidades</a></li>
                        <br>
                        <p>Seccion Turnos</p>
                        <hr>
                        <li><a class="dropdown-item2" href="{{ route('turnos.index') }}"><i class="bi bi-eye"></i> Ver
                                Turnos</a></li>
                        <li><a class="dropdown-item2" href="{{ route('turnos.create') }}"><i class="bi bi-clock"></i>
                                Crear Turnos</a></li>
                        <br>
                        <p>Seccion Reservas</p>
                        <hr>
                        <li><a class="dropdown-item2" href="{{ route('reservas.index') }}"><i class="bi bi-eye"></i> Ver
                                reservas</a></li>

                    </ul>
                </li>

                @if (auth()->check())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Mi perfil <i class="bi bi-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('profile.index') }}">Mi perfil</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <button type="submit" class="dropdown-item" style="background: red; ">Cerrar
                                        sesión</button>
                                </form>
                        </ul>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Iniciar sesión
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('login') }}">Iniciar sesión</a></li>
                            <li><a class="dropdown-item" href="{{ route('register') }}">Registrarse</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
