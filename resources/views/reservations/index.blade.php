<x-app-layout>
    <div class="main-table full-center">
        <div class="container-form full-center">
            <h3 class="title-form">Turnos reservados</h3>

            <!-- Barra de búsqueda y filtros -->
            @include('layouts.reservation-filter')

            <table>
                <thead>
                    <tr>
                        <th>Id1</th>
                        <th>Fecha del turno</th>
                        <th class="option-movil">Hora</th>
                        <th>Profesional</th>
                        <th>Paciente</th>
                        <th>Asistencia</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->id }}</td>
                            <td>{{ Carbon\Carbon::parse($reservation->availableAppointment->date)->format('d/m/Y') }}</td>
                            <td class="option-movil">
                                {{ Carbon\Carbon::parse($reservation->availableAppointment->time)->format('H:i') }}</td>
                            <td><a
                                    href="{{ route('doctor.create', $reservation->availableAppointment->doctor->id) }}">{{ $reservation->availableAppointment->doctor->name . ' ' . $reservation->availableAppointment->doctor->surname }}</a>
                            </td>
                            <td><a
                                    href="{{ route('user.show', $reservation->user->id) }}">{{ $reservation->user->name . ' ' . $reservation->user->surname }}</a>
                            </td>


                            <td>
                                @if ($reservation->asistencia === null)
                                    <div class="ios-dropdown">
                                        <button class="btn-asistencia btn-default dropdown-toggle"
                                            title="Marcar asistencia">
                                            <i class="bi bi-hourglass-split"></i> Pendiente
                                        </button>
                                        <div class="ios-dropdown-menu">
                                            <form action="{{ route('reservations.asistencia', $reservation->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="asistencia" value="1">
                                                <button type="submit" class="ios-dropdown-item success">
                                                    <i class="bi bi-check-circle-fill"></i> Asistió
                                                </button>
                                            </form>
                                            <form action="{{ route('reservations.asistencia', $reservation->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="asistencia" value="0">
                                                <button type="submit" class="ios-dropdown-item danger">
                                                    <i class="bi bi-x-circle-fill"></i> No asistió
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <form action="{{ route('reservations.asistencia', $reservation->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="btn-asistencia {{ $reservation->asistencia ? 'btn-success' : 'btn-danger' }}"
                                            title="{{ $reservation->asistencia ? 'Cambiar a No asistió' : 'Cambiar a Asistió' }}">
                                            <i
                                                class="bi {{ $reservation->asistencia ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                            {{ $reservation->asistencia ? 'Asistió' : 'No asistió' }}
                                        </button>
                                    </form>
                                @endif
                            </td>

                            <td class="acciones full-center">
                                <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-view">
                                    <i class="bi bi-eye"></i><b class="accionesMovil">Ver</b>
                                </a>
                                <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST"
                                    style="display:inline;" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-delete delete-btn">
                                        <i class="bi bi-trash-fill"></i><b class="accionesMovil">Eliminar</b>
                                    </button>
                                </form>
                            </td>
                            <td class="accionesMovil">
                                <button type="button" class="accionesMovilBtn">
                                    <i class="bi bi-gear"></i>
                                </button>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No se encontraron reservations</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
            <!-- Paginación -->
            @if ($reservations->hasPages())
                <div class="mt-4">
                    {{ $reservations->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
