<x-body.body>
    @if (isset($reserva))
        <div class="content-wrapper">
            <h3 class="title-form">Detalles</h3>
            <div class="section-container">
                <div class="card">
                    <h1>Mis datos</h1>
                    <p><b> Paciente: </b> {{ Auth::user()->name . ' ' . Auth::user()->surname }}
                    </p>
                    <p><b> DNI: </b>{{ Auth::user()->dni }}</p>
                    <p><b> Activo: </b>{{ $reserva->turnoDisponible->equipo->estado ? 'Si' : 'No' }}</p>

                </div>
                <div class="card">
                    <h1>Turno</h1>
                    <p><b> Especialidad: </b>{{ $reserva->turnoDisponible->equipo->especialidad->nombre }}</p>
                    <p><b> Hora del turno:
                        </b>{{ \Carbon\Carbon::parse($reserva->turnoDisponible->hora)->format('H:m') }}</p>
                    <p><b> Fecha del turno:
                        </b>{{ \Carbon\Carbon::parse($reserva->turnoDisponible->fecha)->format('d/m/Y') }}</p>
                    <p><b> Direccion: </b>{{ $reserva->turnoDisponible->direccion }}</p>

                    <p><b> Asistencia:
                        </b>{{ $reserva->asistencia == null ? 'Pendiente' : ($reserva->asistencia == true ? 'Asistio' : 'No asistio') }}
                    </p>
                    <p><b> Fecha de creación: </b>{{ $reserva->created_at }}</p>
                    <p><b> Fecha de última actualizacion: </b>{{ $reserva->updated_at }}</p>
                </div>
                <div class="card">
                    <h1>Doctor/a</h1>
                    <p><b> Doctor/a:
                        </b>{{ $reserva->turnoDisponible->equipo->nombre . ' ' . $reserva->turnoDisponible->equipo->apellido }}
                    </p>
                    <p><b> DNI: </b>{{ $reserva->turnoDisponible->equipo->dni }}</p>
                    <p><b> Activo: </b>{{ $reserva->turnoDisponible->equipo->estado ? 'Si' : 'No' }}</p>
                    <p><b> Especialidad: </b>{{ $reserva->turnoDisponible->equipo->especialidad->nombre }}</p>
                </div>
            </div>
        </div>
    @endif
</x-body.body>
