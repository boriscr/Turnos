<x-app-layout>
    <div class="main-table full-center">
        <div class="container-form full-center">
            <x-form.titles :value="__('myAppointments')" size="index" />
            <div class="container-appointments">
                @forelse ($reservations as  $reservation)
                    <a href="{{ route('myAppointments.show', $reservation->id) }}" class="my-appointments-box">
                        <div class="box-content">
                            <div class="details-content">
                                <strong>{{ $reservation->availableAppointment->doctor->specialty->name }}</strong>
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
                                class="status full-center {{ $reservation->availableAppointment->appointment->status === true ? ($reservation->status === 'pending' ? 'btn-default' : ($reservation->status === 'assisted' ? 'btn-success' : 'btn-danger')) : 'inactive' }}">
                                @if ($reservation->availableAppointment->appointment->status === true)
                                    <x-change-of-state :status="$reservation->status" />
                                @else
                                    {{ __('button.search.inactive_appointment') }}
                                @endif
                            </span>
                        </div>
                    </a>
                    <hr>
                    {{ $reservations->links() }}
                @empty
                    <p>{{ __('medical.no_appointments') }}</p>
                @endforelse
            </div>
            <div class="container-form full-center">
                <x-form.titles :value="__('medical.titles.historical')" size="index" />
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('medical.id') }}</th>
                            <th class="option-movil">{{ __('reservation.title_name') }}</th>
                            <th>{{ __('medical.doctor') }}</th>
                            <th>{{ __('specialty.title') }}</th>
                            <th>{{ __('appointment.date.date') }}</th>
                            <th class="option-movil">{{ __('appointment.schedule.time') }}</th>
                            <th>{{ __('medical.status.title') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($appointmentHistory as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td class="option-movil">{{ $item->appointment_name }}</td>
                                <td>{{ $item->doctor_name }}</td>
                                <td>{{ $item->specialty }}</td>
                                <td>{{ Carbon\Carbon::parse($item->appointment_date)->format('d/m/Y') }}
                                </td>
                                <td class="option-movil">
                                    {{ Carbon\Carbon::parse($item->appointment_time)->format('H:i') }}
                                </td>
                                <td
                                    class="{{ $item->status == 'pending' ? 'btn-default' : ($item->status == 'assisted' ? 'btn-success' : 'btn-danger') }}">
                                    <x-change-of-state :status="$item->status" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">{{ __('medical.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="pagination">
                    {{ $appointmentHistory->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
