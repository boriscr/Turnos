<x-app-layout>
    @if (isset($reserva) && $reserva->turnoDisponible->turno->status === true)
        <div class="content-wrapper">
            <h3 class="title-form">Detalles</h3>
            <div class="section-container full-center">
                <div class="card">
                    <h1>Mis datos</h1>
                    <p><b> Paciente: </b> {{ Auth::user()->name . ' ' . Auth::user()->surname }}
                    </p>
                    <p><b> DNI: </b>{{ Auth::user()->idNumber }}</p>
                    <p><b> Activo: </b>{{ $reserva->turnoDisponible->doctor->status ? 'Si' : 'No' }}</p>

                </div>
                <div class="card">
                    <h1>Turno</h1>
                    <p><b> Specialty: </b>{{ $reserva->turnoDisponible->doctor->specialty->name }}</p>
                    <p><b> Hora del turno:
                        </b>{{ \Carbon\Carbon::parse($reserva->turnoDisponible->hora)->format('H:i') }}</p>
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
                        </b>{{ $reserva->turnoDisponible->doctor->name . ' ' . $reserva->turnoDisponible->doctor->surname }}
                    </p>
                    <p><b> DNI: </b>{{ $reserva->turnoDisponible->doctor->idNumber }}</p>
                    <p><b> Activo: </b>{{ $reserva->turnoDisponible->doctor->status ? 'Si' : 'No' }}</p>
                    <p><b> Specialty: </b>{{ $reserva->turnoDisponible->doctor->specialty->name }}</p>
                </div>
            </div>
        </div>
    @else
        <div class="content-wrapper">
            <h3 class="title-form">Upss...</h3>
            <div class="card-error full-center">
                <div class="card">
                    <div class="mensaje-error-box">
                        <h1>Turno No Disponible</h1>
                        <p>El turno que intentas acceder no está disponible o ha sido cancelado.</p>

                    </div>
                    <div class="icono-error-box full-center">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <h2>¿Por qué sucede esto?</h2>
                    <p>Este turno ha sido marcado como inactivo por un administrador. Esto significa que puede ser
                        reprogramado o definitivamente cancelado.</p>

                    <h2>¿Qué puedes hacer?</h2>
                    <ul>
                        <li><i class="bi-caret-right-fill"></i>Verifica la información en tu historial de turnos mas
                            tarde.</li>
                        <li><i class="bi-caret-right-fill"></i>Si crees que esto es un error, puedes contactar al
                            soporte.</li>
                        <li><i class="bi-caret-right-fill"></i>Consulta nuestra <a href="/ayuda">sección de ayuda</a>
                            para más detalles.</li>
                    </ul>
                    <p>Agradecemos tu comprensión y estamos aquí para ayudarte.</p>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
