<x-app-layout>
    @if (isset($reservation) && $reservation->availableAppointment->appointment->status === true)
        <div class="content-wrapper">
            <h1>{{ __('medical.titles.details') }}</h1>
            <div class="section-container full-center">
                <div class="card">
                    <h2>{{ __('medical.titles.reserved_appointment_details') }}</h2>
                    <x-field-with-icon icon="activity" :label="__('reservation.title_name')" :value="$reservation->availableAppointment->appointment->name" />
                    <x-field-with-icon icon="geo-alt-fill" :label="__('contact.address')" :value="$reservation->availableAppointment->appointment->address" />
                    <x-field-with-icon icon="heart-pulse-fill" :label="__('specialty.title')" :value="$reservation->availableAppointment->doctor->specialty->name" />
                    @if (!$reservation->availableAppointment->doctor->specialty->status)
                        <spam
                            class="full-center {{ $reservation->availableAppointment->doctor->specialty->status ? 'btn-success' : 'btn-danger' }}">
                            <x-field-with-icon icon="exclamation-diamond" :value="__('medical.inactive')" />
                        </spam>
                    @endif
                    <x-field-with-icon icon="person-fill" :label="__('medical.doctor')" :value="$reservation->availableAppointment->doctor->name .
                        ' ' .
                        $reservation->availableAppointment->doctor->surname" />
                    @if (!$reservation->availableAppointment->doctor->status)
                        <spam
                            class="full-center {{ $reservation->availableAppointment->doctor->specialty->status ? 'btn-success' : 'btn-danger' }}">
                            <x-field-with-icon icon="exclamation-diamond" :value="__('medical.inactive')" />
                        </spam>
                    @endif
                    <x-field-with-icon icon="clock-fill" :label="__('appointment.schedule.time')" :value="\Carbon\Carbon::parse($reservation->availableAppointment->time)->format('H:i')" />
                    <x-field-with-icon icon="calendar-check-fill" :label="__('appointment.date.date')" :value="\Carbon\Carbon::parse($reservation->availableAppointment->date)->format('d/m/Y')" />

                    <x-field-with-icon icon="clipboard" :label="__('medical.status.title')" />
                    <spam
                        class="status full-center {{ $reservation->availableAppointment->appointment->status === true ? ($reservation->status === 'pending' ? 'btn-default' : ($reservation->status === 'assisted' ? 'btn-success' : 'btn-danger')) : 'inactive' }}">
                        @if ($reservation->availableAppointment->appointment->status)
                            <x-change-of-state :status="$reservation->status" />
                        @else
                            {{ __('button.search.inactive_appointment') }}
                        @endif
                    </spam>
                    <x-field-with-icon icon="calendar-minus-fill" :label="__('medical.creation_date')" :value="$reservation->created_at" />
                </div>
                <div class="card">
                    <h2>{{ __('medical.titles.my_data') }}</h2>
                    <x-field-with-icon icon="person-fill" :label="__('contact.name_and_surname')" :value="@Auth::user()->name . ' ' . @Auth::user()->surname" />
                    <x-field-with-icon icon="person-vcard-fill" :label="__('contact.idNumber')" :value="@Auth::user()->idNumber" />
                    <x-field-with-icon icon="circle-fill" :label="__('medical.status.title')" />
                    <span class="full-center {{ @Auth::user()->status ? 'btn-success' : 'btn-danger' }}">
                        <x-field-with-icon :value="$reservation->user->status ? __('medical.active') : __('medical.inactive')" />
                    </span>
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
        </div> @endif
</x-app-layout>
