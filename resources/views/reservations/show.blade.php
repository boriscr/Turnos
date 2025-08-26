<x-app-layout>
    @if (isset($reservation))
        <div class="content-wrapper">
            <h1>{{ __('medical.titles.details') }}</h1>
            <div class="section-container full-center">
                <div class="card">
                    <h2>{{ __('medical.titles.reserved_appointment_details') }}</h2>
                    <p><b><i class="bi bi-activity"></i>{{ __('reservation.title_name') }}:
                        </b>{{ $reservation->availableAppointment->appointment->name }} <a
                            href="{{ route('appointments.show', $reservation->availableAppointment->appointment->id) }}">
                            <i class="bi bi-eye">{{ __('button.view') }}</i>
                        </a></p>
                    <p><b><i class="bi bi-geo-alt-fill"></i>{{ __('contact.address') }}:
                        </b>{{ $reservation->availableAppointment->appointment->address }}</p>
                    <p><b><i class="bi bi-heart-pulse-fill"></i>{{ __('specialty.title') }}:
                        </b>{{ $reservation->availableAppointment->doctor->specialty->name }}<a
                            href="{{ route('specialty.show', $reservation->availableAppointment->doctor->specialty->id) }}">
                            <i class="bi bi-eye">{{ __('button.view') }}</i>
                        </a></p>
                    </p>
                    <p><b><i class="bi bi-person-check-fill"></i>{{ __('medical.doctor') }}:
                        </b>{{ $reservation->availableAppointment->doctor->name . ' ' . $reservation->availableAppointment->doctor->surname }}
                        <a href="{{ route('doctor.show', $reservation->availableAppointment->doctor_id) }}">
                            <i class="bi bi-eye">{{ __('button.view') }}</i>
                        </a>
                    </p>
                    <p><b><i class="bi bi-clock-fill"></i>{{ __('appointment.schedule.time') }}:
                        </b>{{ \Carbon\Carbon::parse($reservation->availableAppointment->time)->format('H:i') }}</p>
                    <p><b><i class="bi bi-calendar-check-fill"></i>{{ __('appointment.date.date') }}:
                        </b>{{ \Carbon\Carbon::parse($reservation->availableAppointment->date)->format('d/m/Y') }}</p>
                    <p><b><i class="bi bi-clipboard"></i>{{ __('medical.status') }}: </b>
                        </b>
                        <span
                            class="{{ $reservation->asistencia === null ? 'btn-default' : ($reservation->asistencia === true ? 'btn-success' : 'btn-danger') }}">
                            {{ $reservation->asistencia === null ? __('button.search.pending') : ($reservation->asistencia === true ? __('button.search.assisted') : __('button.search.not_attendance')) }}
                        </span>
                    </p>
                    <p><b><i class="bi bi-calendar-date"></i>{{ __('medical.creation_date') }}:
                        </b>{{ $reservation->created_at }}</p>
                    <p><b><i class="bi bi-calendar-date"></i>{{ __('medical.update_date') }}:
                        </b>{{ $reservation->updated_at }}</p>
                </div>

                <div class="card">
                    <h2>{{ __('medical.titles.patient_data') }}</h2>
                    <p><b><i class="bi bi-person-fill"></i>{{ __('medical.patient') }}: </b>
                        {{ $reservation->user->name . ' ' . $reservation->user->surname }}
                        <a href="{{ route('user.show', $reservation->user->id) }}"><i
                                class="bi bi-eye">{{ __('button.view') }}</i></a>
                    </p>
                    <p><b><i class="bi bi-fingerprint"></i>{{ __('contact.idNumber') }}:
                        </b>{{ $reservation->user->idNumber }}</p>
                    <p><b><i class="bi bi-clipboard"></i>{{ __('medical.status') }}:
                        </b>
                        <span class="{{ $reservation->user->status ? 'btn-success' : 'btn-danger' }}">
                            {{ $reservation->user->status ? __('medical.active') : __('medical.inactive') }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="opciones full-center">
                <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST"
                    style="display:inline;" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-delete delete-btn">
                        <i class="bi bi-trash-fill">{{ __('button.delete') }}</i>
                    </button>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
