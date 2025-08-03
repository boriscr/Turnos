<x-app-layout>
    <div class="main-table full-center">
        <div class="container-form full-center">
            <h3 class="title-form">Historial</h3>
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Specialty</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Asistencia</th>
                        <th>Acciones</th>
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
                                {{ \Carbon\Carbon::parse($reservation->availableAppointment->date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($reservation->availableAppointment->time)->format('H:i') }}</td>
                            <td
                                class="{{ $reservation->availableAppointment->appointment->status === true ? ($reservation->asistencia === null ? 'btn-default' : ($reservation->asistencia == true ? 'btn-success' : 'btn-danger')) : 'btn-danger' }}">
                                <i
                                    class="bi {{ $reservation->asistencia === null ? 'bi-hourglass-split' : ($reservation->asistencia ? 'bi-check-circle-fill' : 'bi-x-circle-fill') }}">
                                </i>
                                {{ $reservation->availableAppointment->appointment->status === true ? ($reservation->asistencia === null ? 'Pendiente' : ($reservation->asistencia ? 'Asistió' : 'No asistió')) : 'Appointment Inactivo' }}
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
