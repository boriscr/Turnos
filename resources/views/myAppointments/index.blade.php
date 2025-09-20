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
                    {{ $reservations->links() }}
                @empty
                    <p>{{ __('medical.no_appointments') }}</p>
                @endforelse
            </div>
            <div class="container-form full-center">
                <h1>{{ __('medical.titles.historical') }}</h1>

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
                                    @switch($item->status)
                                        @case('pending')
                                            <i class="bi bi-hourglass-split btn-default">{{ __('button.search.pending') }}</i>
                                        @break

                                        @case('assisted')
                                            <i
                                                class="bi bi-check-circle-fill btn-success">{{ __('button.search.assisted') }}</i>
                                        @break

                                        @case('not_attendance')
                                            <i
                                                class="bi bi-x-circle-fill btn-danger">{{ __('button.search.not_attendance') }}</i>
                                        @break

                                        @case('cancelled_by_user')
                                            <i class="bi bi-x-circle-fill btn-danger">{{ __('medical.status.canceled') }}</i>
                                        @break

                                        @case('cancelled_by_admin')
                                            <i
                                                class="bi bi-x-circle-fill btn-danger">{{ __('medical.status.cancelled_by_admin') }}</i>
                                        @break

                                        @case('deleted_by_admin')
                                            <i
                                                class="bi bi-x-circle-fill btn-danger">{{ __('medical.status.deleted_by_admin') }}</i>
                                        @break

                                        @default
                                            <i
                                                class="bi bi-question-circle-fill btn-default">{{ __('medical.status.unknown') }}</i>
                                    @endswitch
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">{{ __('medical.no_data') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $appointmentHistory->links() }}
                </div>
            </div>
        </div>
    </x-app-layout>
