<x-app-layout>
    <div class="content-wrapper">
        <h1>{{ __('medical.titles.details') }}</h1>
        <div class="section-container full-center">
            <div class="card">
                <h2>{{ __('medical.titles.management') }}</h2>
                <p><b>{{ __('reservation.title_name') }}:</b> {{ $appointment->name }}</p>
                <p><b>{{ __('contact.address') }}:</b> {{ $appointment->address }}</p>
                <p><b>{{ __('specialty.title') }}:</b> {{ $appointment->specialty->name }}</p>
                <p><b>{{ __('medical.doctor') }}:</b>
                    {{ $appointment->doctor->name . ' ' . $appointment->doctor->surname }} <a
                        href="{{ route('doctor.show', $appointment->doctor->id) }}"><i class="bi bi-eye"></i></a></p>
                <p><b>{{ __('appointment.shift.name') }}:</b>
                    {{ $appointment->shift === 'morning' ? __('appointment.shift.morning_shift') : ($appointment->shift === 'afternoon' ? __('appointment.shift.afternoon_shift') : __('appointment.shift.night_shift')) }}
                </p>

                <p><b>{{ __('appointment.schedule.start_time') }}:</b>
                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</p>
                <p><b>{{ __('appointment.schedule.end_time') }}:</b>
                    {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</p>
                @if ($appointment->available_time_slots)
                    <p><b>{{ __('appointment.schedule.title') }}:</b> {{ $appointment->available_time_slots }}</p>
                @endif
                @php
                    $cantidad = 0;
                @endphp
                <p><b>{{ __('appointment.date.title') }}:</b>
                    @foreach ($appointment->available_dates as $item)
                        @php
                            $cantidad++;
                        @endphp
                        <small> - <i class="bi bi-calendar-check"></i> {{ $item }}</small>
                    @endforeach
                </p>
            </div>
            <div class="card">
                <h2>{{ __('medical.titles.creation') }}</h2>

                <p><b>{{ __('appointment.schedule.number_of_dates') }}:</b>
                    {{ $cantidad }}</p>
                <p><b>{{ __('appointment.schedule.number_of_slots') }}:</b> {{ $appointment->number_of_slots }} </p>
                <p><b>{{ __('appointment.schedule.total_amount_of_reservations') }}:</b>
                    {{ $appointment->number_of_slots * $cantidad }}</p>
                <p><b>{{ __('medical.status') }}:</b>
                    {{ $appointment->status ? __('medical.active') : __('medical.inactive') }}</p>
                <p><b>{{ __('medical.created_by') }}:</b>
                    {{ $appointment->createdBy->name . ' ' . $appointment->createdBy->surname }} <a
                        href="{{ route('user.show', $appointment->createBy) }}"><i class="bi bi-eye"></i></a></p>
                <p><b>{{ __('medical.creation_date') }}:</b>
                    {{ \Carbon\Carbon::parse($appointment->created_at)->format('d/m/Y H:i') }}
                </p>
                <p><b>{{ __('medical.updated_by') }}:</b>
                    {{ $appointment->updatedBy->name . ' ' . $appointment->updatedBy->surname }} <a
                        href="{{ route('user.show', $appointment->updateBy) }}"><i class="bi bi-eye"></i></a></p>
                <p><b>{{ __('medical.update_date') }}:</b>
                    {{ \Carbon\Carbon::parse($appointment->updated_at)->format('d/m/Y H:i') }}
                </p>
                </p>
            </div>
        </div>
        <div class="opciones full-center">
            <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn-edit"><i
                    class="bi bi-pencil-fill">{{ __('button.view') }}</i></a>
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
                <a href="{{ route('availableAppointments.index', $appointment->doctor_id) }}"
                    >{{ __('button.view_available_reservations') }}</a>
            </x-secondary-button>
        </div>
</x-app-layout>
