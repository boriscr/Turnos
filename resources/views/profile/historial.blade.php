<x-app-layout>
    <div class="main-table full-center">
        <div class="container-form full-center">
            <h1>{{ __('medical.titles.historical') }}</h1>
            <table>
                <thead>
                    <tr>
                        <th>{{ __('medical.id') }}</th>
                        <th>{{ __('specialty.title') }}</th>
                        <th>{{ __('appointment.date.date') }}</th>
                        <th>{{ __('appointment.schedule.time') }}</th>
                        <th>{{ __('medical.status.title') }}</th>
                        <th>{{ __('medical.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $id = $totalReservas + 1;
                    ?>
                    @foreach ($reservations as $reservation)
                        <?php
                        $id--;
                        ?>
                        <tr>
                            <td>{{ $id }}</td>
                            <td>{{ $reservation->availableAppointment->doctor->specialty->name }}</td>
                            <td class="date-td">
                                {{ \Carbon\Carbon::parse($reservation->availableAppointment->date)->format('d/m/Y') }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($reservation->availableAppointment->time)->format('H:i') }}
                            </td>
                            <td
                                class="{{ $reservation->availableAppointment->appointment->status === true ? ($reservation->asistencia === null ? 'btn-default' : ($reservation->asistencia === true ? 'btn-success' : 'btn-danger')) : 'inactive' }}">
                                <i
                                    class="bi {{ $reservation->availableAppointment->appointment->status === true ? ($reservation->asistencia === null ? 'bi-hourglass-split' : ($reservation->asistencia ? 'bi-check-circle-fill' : 'bi-x-circle-fill')) : 'bi-slash-circle' }} ">
                                </i>
                                {{ $reservation->availableAppointment->appointment->status === true ? ($reservation->asistencia === null ? __('button.search.pending') : ($reservation->asistencia ? __('button.search.assisted') : __('button.search.not_attendance'))) : __('button.search.inactive_appointment') }}
                            </td>
                            <td>
                                <a href="{{ route('profile.show', $reservation->id) }}" class="btn btn-view">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @php
                                    // Correcto: Combinar solo la date (de availableAppointment->date) con la time (de availableAppointment->time)
                                    $fechaHoraReserva = \Carbon\Carbon::parse(
                                        $reservation->availableAppointment->date->format('Y-m-d') .
                                            ' ' .
                                            $reservation->availableAppointment->time->format('H:i:s'),
                                    );
                                @endphp

                                @if ($fechaHoraReserva->gt($now))
                                    <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST"
                                        style="display:inline;" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-delete delete-btn">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $reservations->links() }}
        </div>
    </div>
</x-app-layout>
