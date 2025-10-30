<x-app-layout>

    @if (isset($reservation) && $reservation->availableAppointment->appointment->status === true)
        <div class="content-wrapper">
            <h1>{{ __('medical.titles.reserved_appointment_details') }}</h1>
            <br>
            <div class="card-heder full-center">
                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <circle class="checkmark__circle" cx="26" cy="26" r="25" />
                    <path class="checkmark__check" fill="none" stroke="#fff" stroke-width="3" d="M14 27l7 7 16-16" />
                </svg>
                <span>{{ __('medical.confirmed') }}</span>
            </div>

            <div class="section-container full-center">
                <div class="content-date-profile">
                    <div class="profile-container">
                        <img src="https://img.freepik.com/foto-gratis/retrato-hombre-blanco-aislado_53876-40306.jpg?semt=ais_hybrid&w=740&q=80"
                            alt="img-profile" class="profile-img">
                        <div class="profile-id">
                            <p class="profile-name">
                                {{ __('medical.dr') . $reservation->availableAppointment->doctor->name . ' ' . $reservation->availableAppointment->doctor->surname }}
                            </p>
                            <small>
                                {{ $reservation->availableAppointment->doctor->specialty->name . ' | ' . $reservation->availableAppointment->appointment->name }}
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <x-field-with-icon-history icon="calendar-check-fill" :label="__('appointment.date.date')" :value="\Carbon\Carbon::parse($reservation->availableAppointment->date)
                        ->locale('es') // establece el idioma
                        ->translatedFormat('l d \d\e F \d\e Y')" />
                    <x-field-with-icon-history icon="clock-fill" :label="__('appointment.schedule.time')" :value="\Carbon\Carbon::parse($reservation->availableAppointment->time)->format('H:i')" />
                    <x-field-with-icon-history icon="geo-alt-fill" :label="__('contact.address')" :value="$reservation->availableAppointment->appointment->address" />
                    <spam
                        class="status full-center {{ $reservation->availableAppointment->appointment->status === true ? ($reservation->status === 'pending' ? 'btn-default' : ($reservation->status === 'assisted' ? 'btn-success' : 'btn-danger')) : 'inactive' }}">
                        @if ($reservation->availableAppointment->appointment->status)
                            <x-change-of-state :status="$reservation->status" />
                        @else
                            {{ __('button.search.inactive_appointment') }}
                        @endif
                    </spam>
                </div>

                <div class="card">
                    <h2>{{ __('medical.titles.my_data') }}</h2>
                    <x-field-with-icon icon="person-fill" :label="__('contact.name_and_surname')" :value="@Auth::user()->name . ' ' . @Auth::user()->surname" />
                    <x-field-with-icon icon="person-vcard-fill" :label="__('contact.idNumber')" :value="@Auth::user()->idNumber" />
                        <br>
                    <span class="full-center {{ @Auth::user()->status ? 'btn-success' : 'btn-danger' }}">
                        <x-field-with-icon :value="$reservation->user->status ? __('medical.active') : __('medical.inactive')" />
                    </span>
                </div>

                <div class="card">
                    <div class="reminder-box full-center">
                        <i class="bi bi-bell-fill"></i>
                        <small>
                            {{ config('app.patient_message') }}
                        </small>
                    </div>
                </div>
                @php
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
        </div> @endif
</x-app-layout>
