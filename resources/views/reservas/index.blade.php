<x-app-layout>
    <div class="main-table full-center">
        <div class="container-form full-center">
            <h3 class="title-form">Turnos reservados</h3>

            <!-- Barra de búsqueda y filtros -->
            @include('layouts.filtro-reservas')

            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Fecha del turno</th>
                        <th class="option-movil">Hora</th>
                        <th>Profesional</th>
                        <th>Paciente</th>
                        <th>Asistencia</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reservas as $reserva)
                        <tr>
                            <td>{{ $reserva->id }}</td>
                            <td>{{ Carbon\Carbon::parse($reserva->turnoDisponible->fecha)->format('d/m/Y') }}</td>
                            <td class="option-movil">
                                {{ Carbon\Carbon::parse($reserva->turnoDisponible->hora)->format('H:i') }}</td>
                            <td><a
                                    href="{{ route('doctor.create', $reserva->turnoDisponible->doctor->id) }}">{{ $reserva->turnoDisponible->doctor->name . ' ' . $reserva->turnoDisponible->doctor->surname }}</a>
                            </td>
                            <td><a
                                    href="{{ route('user.show', $reserva->user->id) }}">{{ $reserva->user->name . ' ' . $reserva->user->surname }}</a>
                            </td>


                            <td>
                                @if ($reserva->asistencia === null)
                                    <div class="ios-dropdown">
                                        <button class="btn-asistencia btn-default dropdown-toggle"
                                            title="Marcar asistencia">
                                            <i class="bi bi-hourglass-split"></i> Pendiente
                                        </button>
                                        <div class="ios-dropdown-menu">
                                            <form action="{{ route('reservas.asistencia', $reserva->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="asistencia" value="1">
                                                <button type="submit" class="ios-dropdown-item success">
                                                    <i class="bi bi-check-circle-fill"></i> Asistió
                                                </button>
                                            </form>
                                            <form action="{{ route('reservas.asistencia', $reserva->id) }}"
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
                                    <form action="{{ route('reservas.asistencia', $reserva->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="btn-asistencia {{ $reserva->asistencia ? 'btn-success' : 'btn-danger' }}"
                                            title="{{ $reserva->asistencia ? 'Cambiar a No asistió' : 'Cambiar a Asistió' }}">
                                            <i
                                                class="bi {{ $reserva->asistencia ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                            {{ $reserva->asistencia ? 'Asistió' : 'No asistió' }}
                                        </button>
                                    </form>
                                @endif
                            </td>

                            <td class="acciones full-center">
                                <a href="{{ route('reservas.show', $reserva->id) }}" class="btn btn-view">
                                    <i class="bi bi-eye"></i><b class="accionesMovil">Ver</b>
                                </a>
                                <form action="{{ route('availableAppointments.destroy', $reserva->id) }}" method="POST"
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
                            <td colspan="7" class="text-center">No se encontraron reservas</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
            <!-- Paginación -->
            @if ($reservas->hasPages())
                <div class="mt-4">
                    {{ $reservas->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
