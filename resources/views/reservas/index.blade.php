<x-body.body>
    <div class="main-table">
        <div class="container-form">
            <h3 class="title-form">Turnos reservados</h3>

            <!-- Barra de búsqueda y filtros -->
            <div class="search-filters mb-4">
                <!-- Barra de búsqueda existente -->
                <form action="{{ route('reservas.index') }}" method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="Buscar por nombre, apellido o DNI..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                        @if (request('search'))
                            <a href="{{ route('reservas.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Limpiar
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Filtro de fechas -->
                <div class="date-filters">
                    <form action="{{ route('reservas.index') }}" method="GET" id="dateFilterForm">
                        <input type="hidden" name="search" value="{{ request('search') }}">

                        <div class="btn-group" role="group">
                            <button type="submit" name="fecha" value="anteriores"
                                class="btn {{ request('fecha') == 'anteriores' ? 'btn-primary' : 'btn-outline-primary' }}">
                                Anteriores
                            </button>
                            <button type="submit" name="fecha" value="hoy"
                                class="btn {{ request('fecha', 'hoy') == 'hoy' ? 'btn-primary' : 'btn-outline-primary' }}">
                                Hoy
                            </button>
                            <button type="submit" name="fecha" value="futuros"
                                class="btn {{ request('fecha') == 'futuros' ? 'btn-primary' : 'btn-outline-primary' }}">
                                Futuros
                            </button>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-5">
                                <input type="date" name="fecha_inicio" class="form-control"
                                    value="{{ request('fecha_inicio') }}" placeholder="Fecha inicio">
                            </div>
                            <div class="col-md-5">
                                <input type="date" name="fecha_fin" class="form-control"
                                    value="{{ request('fecha_fin') }}" placeholder="Fecha fin">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" name="fecha" value="personalizado"
                                    class="btn btn-primary w-100">
                                    <i class="bi bi-filter"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Fecha del reserva</th>
                        <th class="option-movil">Hora</th>
                        <th>Profesional</th>
                        <th>Paciente</th>
                        <th class="option-movil">Asistencia</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reservas as $reserva)
                        <tr>
                            <td>{{ $reserva->id }}</td>
                            <td>{{ Carbon\Carbon::parse($reserva->turnoDisponible->fecha)->format('d/m/Y') }}</td>
                            <td class="option-movil">
                                {{ Carbon\Carbon::parse($reserva->turnoDisponible->hora)->format('H:i') }}
                            <td><a
                                    href="{{ route('equipo.show', $reserva->turnoDisponible->equipo->id) }}">{{ $reserva->turnoDisponible->equipo->nombre . ' ' . $reserva->turnoDisponible->equipo->apellido }}</a>
                            </td>
                            <td><a
                                    href="{{ route('usuario.show', $reserva->user->id) }}">{{ $reserva->user->name . ' ' . $reserva->user->surname }}</a>
                            </td>
                            <td
                                class="option-movil {{ $reserva->asistencia === null ? 'btn-secondary' : ($reserva->asistencia ? 'btn-success' : 'btn-danger') }}">
                                <form action="{{ route('reservas.asistencia', $reserva->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="btn-asistencia {{ $reserva->asistencia === null ? 'btn-secondary' : ($reserva->asistencia ? 'btn-success' : 'btn-danger') }}"
                                        title="{{ $reserva->asistencia === null ? 'Marcar asistencia' : ($reserva->asistencia ? 'Cambiar a No asistió' : 'Cambiar a Asistió') }}">
                                        <i
                                            class="bi {{ $reserva->asistencia === null ? 'bi-hourglass-split' : ($reserva->asistencia ? 'bi-check-circle-fill' : 'bi-x-circle-fill') }}"></i>
                                        {{ $reserva->asistencia === null ? 'Pendiente' : ($reserva->asistencia ? 'Asistió' : 'No asistió') }}
                                    </button>
                                </form>
                            </td>
                            <td class="acciones">
                                <a href="{{ route('reservas.show', $reserva->id) }}" class="btn btn-view">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="{{ route('disponible.destroy', $reserva->id) }}" method="POST"
                                    style="display:inline;" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-delete delete-btn">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="accionesMovil"><button type="button"><i class="bi bi-gear"></i></button></td>
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

    <script src="../../delete-btn.js"></script>

    <script>
        // Opcional: Validar que fecha inicio <= fecha fin
        document.getElementById('dateFilterForm').addEventListener('submit', function(e) {
            const fechaInicio = document.getElementsByName('fecha_inicio')[0].value;
            const fechaFin = document.getElementsByName('fecha_fin')[0].value;

            if (fechaInicio && fechaFin && fechaInicio > fechaFin) {
                alert('La fecha de inicio no puede ser mayor a la fecha final');
                e.preventDefault();
            }
        });
    </script>
</x-body.body>
