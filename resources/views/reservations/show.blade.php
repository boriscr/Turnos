<x-app-layout>
    @if (isset($reservation))
        <div class="content-wrapper">
            <h3 class="title-form">Detalles</h3>
            <div class="section-container full-center">
                <div class="card">
                    <H1>Paciente</H1>
                    <p><b> Paciente: </b> {{ $reservation->user->name . ' ' . $reservation->user->surname }}
                        <a href="{{ route('user.show', $reservation->user->id) }}"><i class="bi bi-eye"></i></a>
                    </p>
                    <p><b> DNI: </b>{{ $reservation->user->idNumber }}</p>
                    <p><b> Activo: </b>{{ $reservation->user->status ? 'Si' : 'No' }}</p>
                    <p><b> Hora del appointment:
                        </b>{{ \Carbon\Carbon::parse($reservation->availableAppointment->time)->format('H:i') }}</p>
                    <p><b> Fecha del appointment:
                        </b>{{ \Carbon\Carbon::parse($reservation->availableAppointment->date)->format('d/m/Y') }}</p>
                    <p><b> Cantidad de appointment/s reservado/s: </b>{{ $reservation->availableAppointment->reserved_spots }}
                    </p>
                    <p><b> Asistencia:
                        </b>{{ $reservation->asistencia == null ? 'Pendiente' : ($reservation->asistencia == true ? 'Asistio' : 'No asistio') }}
                    </p>
                    <p><b> Fecha de creación: </b>{{ $reservation->created_at }}</p>
                    <p><b> Fecha de última actualizacion: </b>{{ $reservation->updated_at }}</p>
                </div>
                <div class="card">
                    <H1>Doctor/a</H1>
                    <p><b> Doctor/a:
                        </b>{{ $reservation->availableAppointment->doctor->name . ' ' . $reservation->availableAppointment->doctor->surname }}
                    </p>
                    <p><b> DNI: </b>{{ $reservation->availableAppointment->doctor->idNumber }}</p>
                    <p><b> Activo: </b>{{ $reservation->availableAppointment->doctor->status ? 'Si' : 'No' }}</p>
                    <p><b>Perfil de user:</b>
                        @if ($reservation->availableAppointment->doctor->user_id)
                            <a href="{{ route('user.show', $reservation->availableAppointment->doctor->user_id) }}">
                                Ver <i class="bi bi-eye"></i>
                            </a>
                        @else
                            No tiene un perfil de usuario
                        @endif
                    </p>
                    <p><b>Perfil de doctor:</b>
                        <a href="{{ route('doctor.show', $reservation->availableAppointment->doctor_id) }}">
                            Ver <i class="bi bi-eye"></i>
                        </a>
                    </p>

                    <p><b> Specialty: </b>{{ $reservation->availableAppointment->doctor->specialty->name }}</p>
                    <p><b> Fecha de creación: </b>{{ $reservation->created_at }}</p>
                    <p><b> Fecha de última actualizacion: </b>{{ $reservation->updated_at }}</p>
                </div>
            </div>

            <div class="opciones full-center">
                <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST"
                    style="display:inline;" class="delete-form">
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
