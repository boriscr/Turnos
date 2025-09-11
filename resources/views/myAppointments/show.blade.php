<x-app-layout>
    @if (isset($reservation) && $reservation->availableAppointment->appointment->status === true)
        <div class="content-wrapper">
            <h1>{{ __('medical.titles.details') }}</h1>
            <div class="section-container full-center">
                <div class="card">
                    <h2>{{ __('medical.titles.reserved_appointment_details') }}</h2>
                    <p><b><i class="bi bi-activity"></i>{{ __('reservation.title_name') }}:
                        </b>{{ $reservation->availableAppointment->appointment->name }}</p>
                    <p><b><i class="bi bi-geo-alt-fill"></i>{{ __('contact.address') }}:
                        </b>{{ $reservation->availableAppointment->appointment->address }}</p>
                    <p><b><i class="bi bi-heart-pulse-fill"></i>{{ __('specialty.title') }}:
                        </b>{{ $reservation->availableAppointment->doctor->specialty->name }}
                        <span
                            class="{{ $reservation->availableAppointment->doctor->specialty->status ? 'btn-success' : 'inactive' }}">
                            @if (!$reservation->availableAppointment->doctor->specialty->status)
                                <i class="bi bi-exclamation-diamond"></i>
                                {{ __('medical.inactive') }}
                            @endif
                        </span>
                    </p>
                    <p><b><i class="bi bi-person-check-fill"></i>{{ __('medical.doctor') }}:
                        </b>{{ $reservation->availableAppointment->doctor->name . ' ' . $reservation->availableAppointment->doctor->surname }}
                        <span
                            class="{{ $reservation->availableAppointment->doctor->status ? 'btn-success' : 'inactive' }}">
                            @if (!$reservation->availableAppointment->doctor->status)
                                <i class="bi bi-exclamation-diamond"></i>
                                {{ __('medical.inactive') }}
                            @else
                            @endif
                        </span>
                    </p>
                    <p><b><i class="bi bi-clock-fill"></i>{{ __('appointment.schedule.time') }}:
                        </b>{{ \Carbon\Carbon::parse($reservation->availableAppointment->time)->format('H:i') }}</p>
                    <p><b><i class="bi bi-calendar-check-fill"></i>{{ __('appointment.date.date') }}:
                        </b>{{ \Carbon\Carbon::parse($reservation->availableAppointment->date)->format('d/m/Y') }}</p>
                    <p><b><i class="bi bi-clipboard"></i>{{ __('medical.status.title') }}: </b>
                        <span
                            class="{{ $reservation->asistencia === null ? 'btn-default' : ($reservation->asistencia === true ? 'btn-success' : 'btn-danger') }}">
                            {{ $reservation->asistencia === null ? __('button.search.pending') : ($reservation->asistencia === true ? __('button.search.assisted') : __('button.search.not_attendance')) }}
                        </span>
                    </p>
                    <p><b><i class="bi bi-calendar-date"></i>{{ __('medical.creation_date') }}:
                        </b>{{ $reservation->created_at }}</p>
                </div>
                <div class="card">
                    <h2>{{ __('medical.titles.my_data') }}</h2>
                    <p><b><i class="bi bi-person-fill"></i>{{ __('contact.name_and_surname') }}: </b>
                        {{ @Auth::user()->name . ' ' . @Auth::user()->surname }}
                    </p>
                    <p><b><i class="bi bi-fingerprint"></i>{{ __('contact.idNumber') }}:
                        </b>{{ @Auth::user()->idNumber }}</p>
                    <p><b><i class="bi bi-clipboard"></i>{{ __('medical.status.title') }}:
                        </b>
                        <spam class="{{ @Auth::user()->status ? 'btn-success' : 'btn-danger' }}">
                            {{ @Auth::user()->status ? __('medical.active') : __('medical.inactive') }}</spam>
                    </p>
                </div>
                @php
                    // Correcto: Combinar solo la date (de availableAppointment->date) con la time (de availableAppointment->time)
                    $fechaHoraReserva = \Carbon\Carbon::parse(
                        $reservation->availableAppointment->date->format('Y-m-d') .
                            ' ' .
                            $reservation->availableAppointment->time->format('H:i:s'),
                    );
                @endphp
                @if ($fechaHoraReserva->gt($now))
                    <div class="options full-center">
                        <form action="{{ route('myAppointments.destroy', $reservation->id) }}" method="POST"
                            style="display:inline;" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-delete cancel-btn">
                                <i class="bi bi-x-lg">{{ __('button.cancel') }}</i>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="content-wrapper">
            <h1>{{ __('error.oops.title') }}</h1>
            <div class="error-card full-center">
                <div class="card">
                    <div class="error-box-message">
                        <h2>{{ __('error.oops.subtitle_1') }}</h2>
                        <p>{{ __('error.oops.message') }}</p>
                    </div>
                    <div class="error-box-icon full-center">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <p>{{ __('error.oops.message_1') }}</p>
                    <hr>
                    <hr>
                    <h2>{{ __('error.oops.subtitle_2') }}</h2>
                    <p>{{ __('error.oops.message_2') }}</p>
                    <h2>{{ __('error.oops.subtitle_3') }}</h2>
                    <ul>
                        <li><i class="bi-caret-right-fill"></i>{{ __('error.oops.item_1') }}</li>
                        <li><i class="bi-caret-right-fill"></i>{{ __('error.oops.item_2') }}</li>
                        <li><i class="bi-caret-right-fill"></i>{{ __('error.oops.item_3') }}</li>
                        <li><i class="bi-caret-right-fill"></i>{{ __('error.oops.item_4') }}</li>
                    </ul>
                    <p>{{ __('error.oops.message_3') }}</p>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
