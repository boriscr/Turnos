<x-app-layout>
    @if (isset($reserva))
        <div class="content-wrapper">
            <h3 class="title-form">Detalles</h3>
            <div class="section-container full-center">
                <div class="card">
                    <H1>Paciente</H1>
                    <p><b> Paciente: </b> {{ $reserva->user->name . ' ' . $reserva->user->surname }}
                        <a href="{{ route('user.show', $reserva->user->id) }}"><i class="bi bi-eye"></i></a>
                    </p>
                    <p><b> DNI: </b>{{ $reserva->user->idNumber }}</p>
                    <p><b> Activo: </b>{{ $reserva->user->status ? 'Si' : 'No' }}</p>
                    <p><b> Hora del turno:
                        </b>{{ \Carbon\Carbon::parse($reserva->turnoDisponible->hora)->format('H:i') }}</p>
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
                        </b>{{ $reserva->turnoDisponible->doctor->name . ' ' . $reserva->turnoDisponible->doctor->surname }}
                        <a href="{{ route('doctor.create', $reserva->turnoDisponible->doctor->id) }}"><i
                                class="bi bi-eye"></i></a>
                    </p>
                    <p><b> DNI: </b>{{ $reserva->turnoDisponible->doctor->idNumber }}</p>
                    <p><b> Activo: </b>{{ $reserva->turnoDisponible->doctor->status ? 'Si' : 'No' }}</p>
                    <p><b>Perfil de user:</b>
                        @if ($reserva->turnoDisponible->doctor->user_id)
                            <a href="{{ route('user.show', $reserva->turnoDisponible->doctor->user_id) }}">
                                Ver <i class="bi bi-eye"></i>
                            </a>
                        @else
                            No tiene un perfil de user
                        @endif
                    </p>
                    <p><b>Perfil de doctor:</b>
                        <a href="{{ route('doctor.create', $reserva->turnoDisponible->doctor_id) }}">
                            Ver <i class="bi bi-eye"></i>
                        </a>
                    </p>

                    <p><b> Specialty: </b>{{ $reserva->turnoDisponible->doctor->specialty->name }}</p>
                    <p><b> Fecha de creación: </b>{{ $reserva->created_at }}</p>
                    <p><b> Fecha de última actualizacion: </b>{{ $reserva->updated_at }}</p>
                </div>
            </div>

            <div class="opciones full-center">
                <form action="{{ route('availableAppointments.destroy', $reserva->id) }}" method="POST" style="display:inline;"
                    class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-delete delete-btn">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
