<x-app-layout>
    <div class="content-wrapper">
        <h1>{{ __('medical.titles.details') }}</h1>
        <div class="status-box {{ $appointment->status ? 'status-active' : 'status-inactive' }}">
            <span> {{ $appointment->status ? __('medical.active') : __('medical.inactive') }}</span>
        </div>
        <div class="section-container full-center">
            <div class="card">
                <h2>{{ __('medical.titles.management') }}</h2>
                <x-field-with-icon icon="activity" :label="__('reservation.title_name')" :value="$appointment->name" />
                <x-field-with-icon icon="geo-alt-fill" :label="__('contact.address')" :value="$appointment->address" />
                <x-field-with-icon icon="heart-pulse-fill" :label="__('specialty.title')" :value="$appointment->specialty->name" :link="route('specialty.show', $appointment->specialty->id)" />
                <x-field-with-icon icon="person-fill" :label="__('medical.doctor')" :value="$appointment->doctor->name . ' ' . $appointment->doctor->surname" :link="route('doctor.show', $appointment->doctor->id)" />
                <x-field-with-icon icon="brightness-high-fill" :label="__('appointment.shift.name')" :value="$appointment->shift === 'morning' ? __('appointment.shift.morning_shift') : ($appointment->shift === 'afternoon' ? __('appointment.shift.afternoon_shift') : __('appointment.shift.night_shift'))" />
                <x-field-with-icon icon="clock-fill" :label="__('appointment.schedule.start_time')" :value="\Carbon\Carbon::parse($appointment->start_time)->format('H:i')" />
                <x-field-with-icon icon="clock-fill" :label="__('appointment.schedule.end_time')" :value="\Carbon\Carbon::parse($appointment->end_time)->format('H:i')" />
                @php
                    $dates = collect($appointment->available_dates);
                @endphp
                <x-field-with-icon icon="calendar-check-fill" :label="__('appointment.date.start_date')" :value="\Carbon\Carbon::parse($dates->first())->format('d/m/Y')" />
                <x-field-with-icon icon="calendar-check-fill" :label="__('appointment.date.end_date')" :value="\Carbon\Carbon::parse($dates->last())->format('d/m/Y')" />
            </div>
            <div class="card">
                @php
                    $number_of_hours_available = 0;
                    $number_of_reservations = 0;
                @endphp
                <x-field-with-icon icon="clipboard2-data-fill" :label="__('appointment.schedule.number_of_reservations')" :value="$appointment->number_of_reservations" />
                <x-field-with-icon icon="clipboard2-data-fill" :label="__('appointment.schedule.total_amount_of_reservations')" :value="$appointment->number_of_reservations * $number_of_reservations" />
                <x-field-with-icon icon="clipboard2-data-fill" :label="__('appointment.schedule.number_of_available_schedules')" :value="$number_of_hours_available ?? '1'" />
                <x-field-with-icon icon="clipboard2-data-fill" :label="__('appointment.schedule.number_of_dates')" :value="$number_of_reservations" />
            </div>
            <div class="card">
                <h2>{{ __('medical.titles.creation') }}</h2>
                <x-field-with-icon icon="calendar-minus-fill" :label="__('medical.created_by')" :value="$appointment->createdBy->name . ' ' . $appointment->createdBy->surname"
                    :link="route('user.show', $appointment->created_by)" />
                <x-field-with-icon icon="calendar-minus-fill" :label="__('medical.creation_date')" :value="\Carbon\Carbon::parse($appointment->created_at)->format('d/m/Y H:i')" />
                <x-field-with-icon icon="calendar-range-fill" :label="__('medical.updated_by')" :value="$appointment->updatedBy->name . ' ' . $appointment->updatedBy->surname"
                    :link="route('user.show', $appointment->updated_by)" />
                <x-field-with-icon icon="calendar-range-fill" :label="__('medical.update_date')" :value="\Carbon\Carbon::parse($appointment->updated_at)->format('d/m/Y H:i')" />
                </p>
            </div>
            <hr>
            <div class="card half-width">
                @if (!empty($appointment->available_time_slots))
                    <p>
                        <x-field-with-icon icon="clock-fill" :label="__('appointment.schedule.title')" />
                        <small>
                            @foreach ($appointment->available_time_slots as $time)
                                @php
                                    $number_of_hours_available++;
                                @endphp
                                <span class="existing datetime">
                                    {{ $time }}
                                </span>
                            @endforeach
                        </small>
                    </p>
                @else
                    <x-field-with-icon icon="clock-fill" :label="__('appointment.schedule.title')" :value="\Carbon\Carbon::parse($appointment->start_time)->format('H:i')" />
                @endif
            </div>
            <div class="card half-width">
                <p>
                    <x-field-with-icon icon="calendar-check-fill" :label="__('appointment.date.title')" />
                    <small>
                        @foreach ($appointment->available_dates as $item)
                            @php
                                $number_of_reservations++;
                            @endphp
                            {{-- Mostrar la primera fecha almacenada --}}
                            <span class="existing datetime">
                                {{ Carbon\Carbon::parse($item)->format('d/m/Y') }}
                            </span>
                        @endforeach
                    </small>
                </p>
            </div>

        </div>
        <div class="options full-center">
            <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn-edit"><i
                    class="bi bi-pencil-fill">{{ __('button.edit') }}</i></a>
            <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" class="delete-form">
                @csrf
                @method('DELETE')
                <button type="button" class="btn-delete delete-btn"><i
                        class="bi bi-trash-fill">{{ __('button.delete') }}</i></button>
            </form>
        </div>
        <br>
        <div class="full-center mt-4">
            <x-secondary-button>
                <a href="{{ route('availableAppointments.index', $appointment->doctor_id) }}">
                    {{ __('button.view_available_reservations') }}</a>
            </x-secondary-button>
        </div>
</x-app-layout>
