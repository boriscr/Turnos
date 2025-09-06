<x-app-layout>
    <div class="main-table full-center">
        <div class="container-form full-center">
            <h1>{{ __('myAppointments') }}</h1>
            <div class="container-appointments">
                @forelse ($reservations as  $reservation)
                    <a href="{{ route('myAppointments.show', $reservation->id) }}" class="my-appointments-box">
                        <div class="box-content">
                            <div class="details-content">
                                <h6>{{ $reservation->availableAppointment->doctor->specialty->name }}</h6>
                                <hr>
                                <div class="details">
                                    <p>{{ __('medical.dr') }}
                                        {{ $reservation->availableAppointment->doctor->name . ' ' . $reservation->availableAppointment->doctor->surname }}
                                    </p>
                                </div>
                            </div>
                            <div class="details-icon full-center">
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </div>
                        <div class="date-time-status">
                            <span class="date-td">
                                {{ \Carbon\Carbon::parse($reservation->availableAppointment->date)->format('d/m/Y') }}
                                {{ \Carbon\Carbon::parse($reservation->availableAppointment->time)->format('H:i') }}
                            </span>
                            <span
                                class="status full-center {{ $reservation->availableAppointment->appointment->status === true ? ($reservation->asistencia === null ? 'btn-default' : ($reservation->asistencia === true ? 'btn-success' : 'btn-danger')) : 'inactive' }}">
                                {{ $reservation->availableAppointment->appointment->status === true ? ($reservation->asistencia === null ? __('button.search.pending') : ($reservation->asistencia ? __('button.search.assisted') : __('button.search.not_attendance'))) : __('button.search.inactive_appointment') }}
                            </span>
                        </div>
                    </a>
                    <hr>
                @empty
                    <p>{{ __('medical.no_appointments') }}</p>
                @endforelse
            </div>
            {{ $reservations->links() }}
        </div>
    </div>
</x-app-layout>
