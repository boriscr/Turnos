<x-body.body>
    @if (isset($reserva))
        <div class="content-wrapper">
            <h3 class="title-form">Detalles</h3>
            <div class="section-container">
                <div class="card">
                    <H1>Paciente</H1>
                    <p><b> Paciente: </b> {{ $reserva->user->name . ' ' . $reserva->user->surname }}
                        <a href="{{ route('usuario.show', $reserva->user->id) }}"><i class="bi bi-eye"></i></a>
                    </p>
                    <p><b> DNI: </b>{{ $reserva->user->dni }}</p>
                    <p><b> Activo: </b>{{ $reserva->user->estado ? 'Si' : 'No' }}</p>
                    <p><b> Hora del turno:
                        </b>{{ \Carbon\Carbon::parse($reserva->turnoDisponible->hora)->format('H:m') }}</p>
                    <p><b> Fecha del turno:
                        </b>{{ \Carbon\Carbon::parse($reserva->turnoDisponible->fecha)->format('d/m/Y') }}</p>
                    <p><b> Cantidad de turno/s reservado/s: </b>{{ $reserva->turnoDisponible->cupos_reservados }}</p>
                    <p><b> Asistencia:
                        </b>{{ $reserva->asistencia == null ? 'Pendiente' : ($reserva->asistencia == true ? 'Asistio' : 'No asistio') }}
                    </p>
                    <p><b> Fecha de creación: </b>{{ $reserva->created_at }}</p>
                    <p><b> Fecha de última actualizacion: </b>{{ $reserva->updated_at }}</p>
                </div>
                <div class="card">
                    <H1>Doctor/a</H1>
                    <p><b> Doctor/a:
                        </b>{{ $reserva->turnoDisponible->equipo->nombre . ' ' . $reserva->turnoDisponible->equipo->apellido }}
                        <a href="{{ route('equipo.show', $reserva->turnoDisponible->equipo->id) }}"><i
                                class="bi bi-eye"></i></a>
                    </p>
                    <p><b> DNI: </b>{{ $reserva->turnoDisponible->equipo->dni }}</p>
                    <p><b> Activo: </b>{{ $reserva->turnoDisponible->equipo->estado ? 'Si' : 'No' }}</p>
                    <p><b>Perfil de usuario:</b>
                        @if ($reserva->turnoDisponible->equipo->user_id)
                            <a href="{{ route('usuario.show', $reserva->turnoDisponible->equipo->user_id) }}">
                                Ver <i class="bi bi-eye"></i>
                            </a>
                        @else
                            No tiene un perfil de usuario
                        @endif
                    </p>
                    <p><b>Perfil de equipo:</b>
                        <a href="{{ route('equipo.show', $reserva->turnoDisponible->equipo_id) }}">
                            Ver <i class="bi bi-eye"></i>
                        </a>
                    </p>

                    <p><b> Especialidad: </b>{{ $reserva->turnoDisponible->equipo->especialidad->nombre }}</p>
                    <p><b> Fecha de creación: </b>{{ $reserva->created_at }}</p>
                    <p><b> Fecha de última actualizacion: </b>{{ $reserva->updated_at }}</p>
                </div>
            </div>

            <div class="opciones">
                <a href="{{ route('equipo.edit', $reserva->id) }}" class="btn-edit"><i
                        class="bi bi-pencil-fill">Editar</i></a>
                <form action="{{ route('equipo.destroy', $reserva->id) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn-delete delete-btn"><i class="bi bi-trash-fill"></i>
                        Eliminar</button>
                </form>
            </div>
        </div>
    @endif
    <script src="../../delete-btn.js"></script>

</x-body.body>
