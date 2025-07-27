<x-app-layout>
    <div class="main-table centrado-total">
        <div class="container-form centrado-total">
            <h3 class="title-form">Historial</h3>
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Especialidad</th>
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
                    @foreach ($reservas as $reserva)
                        <?php
                        $id--;
                        ?>
                        <tr>
                            <td>{{ $id }}</td>
                            <td>{{ $reserva->turnoDisponible->medico->especialidad->nombre }}</td>
                            <td class="fecha-td">
                                {{ \Carbon\Carbon::parse($reserva->turnoDisponible->fecha)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($reserva->turnoDisponible->hora)->format('H:i') }}</td>
                            <td
                                class="{{ $reserva->turnoDisponible->turno->isActive === true ? ($reserva->asistencia === null ? 'btn-default' : ($reserva->asistencia == true ? 'btn-success' : 'btn-danger')) : 'btn-danger' }}">
                                <i
                                    class="bi {{ $reserva->asistencia === null ? 'bi-hourglass-split' : ($reserva->asistencia ? 'bi-check-circle-fill' : 'bi-x-circle-fill') }}">
                                </i>
                                {{ $reserva->turnoDisponible->turno->isActive === true ? ($reserva->asistencia === null ? 'Pendiente' : ($reserva->asistencia ? 'Asistió' : 'No asistió')) : 'Turno Inactivo' }}
                            </td>
                            <td>
                                <a href="{{ route('profile.show', $reserva->id) }}" class="btn btn-view">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @php
                                    // Correcto: Combinar solo la fecha (de turnoDisponible->fecha) con la hora (de turnoDisponible->hora)
                                    $fechaHoraReserva = \Carbon\Carbon::parse(
                                        $reserva->turnoDisponible->fecha->format('Y-m-d') .
                                            ' ' .
                                            $reserva->turnoDisponible->hora->format('H:i:s'),
                                    );
                                @endphp

                                @if ($fechaHoraReserva->gt($now))
                                    <form action="{{ route('disponible.destroy', $reserva->id) }}" method="POST"
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
            {{ $reservas->links() }}

        </div>
    </div>
</x-app-layout>
