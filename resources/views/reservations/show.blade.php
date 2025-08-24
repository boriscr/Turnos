<x-app-layout>
    @if (isset($reservation))
        <div class="content-wrapper">
            <h1>{{ __('medical.titles.details') }}</h1>
            <div class="section-container full-center">
                <div class="card">
                    <h2>{{ __('medical.titles.reserved_appointment_details') }}</h2>
                    <p><b> {{ __('medical.doctor') }}:
                        </b>{{ $reservation->availableAppointment->doctor->name . ' ' . $reservation->availableAppointment->doctor->surname }}
                        <a href="{{ route('doctor.show', $reservation->availableAppointment->doctor_id) }}">
                            <i class="bi bi-eye">{{ __('button.view') }}</i>
                        </a>
                    </p>
                    <p><b>{{ __('specialty.title') }}:
                        </b>{{ $reservation->availableAppointment->doctor->specialty->name }}</p>
                    <p><b> {{ __('appointment.schedule.time') }}:
                        </b>{{ \Carbon\Carbon::parse($reservation->availableAppointment->time)->format('H:i') }}</p>
                    <p><b> {{ __('appointment.date.date') }}:
                        </b>{{ \Carbon\Carbon::parse($reservation->availableAppointment->date)->format('d/m/Y') }}</p>
                    <p><b> {{ __('medical.status') }}:
                        </b>{{ $reservation->asistencia == null ? __('button.search.pending') : ($reservation->asistencia == true ? __('button.search.assisted') : __('button.search.not_attendance')) }}
                    </p>
                    <p><b> {{ __('medical.creation_date') }}: </b>{{ $reservation->created_at }}</p>
                    <p><b> {{ __('medical.update_date') }}: </b>{{ $reservation->updated_at }}</p>
                </div>

                <div class="card">
                    <h2>{{ __('medical.titles.patient_data') }}</h2>
                    <p><b> {{ __('medical.patient') }}: </b>
                        {{ $reservation->user->name . ' ' . $reservation->user->surname }}
                        <a href="{{ route('user.show', $reservation->user->id) }}"><i
                                class="bi bi-eye">{{ __('button.view') }}</i></a>
                    </p>
                    <p><b> {{ __('contact.idNumber') }}: </b>{{ $reservation->user->idNumber }}</p>
                    <p><b> {{ __('medical.status') }}:
                        </b>{{ $reservation->user->status ? __('medical.active') : __('medical.inactive') }}</p>
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
