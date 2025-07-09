<x-app-layout>
    <div class="main-table">
        <div class="container-form">
            <h3 class="title-form">Turnos creados</h3>
            <div class="mb-4">
                <!-- Formulario de búsqueda y filtros rápidos -->
                <form action="{{ route('disponible.index') }}" method="GET" class="mb-4" id="filterForm">
                    <div class="input-group mb-3">
                        <input type="text" name="search" class="form-control"
                            placeholder="Buscar por DNI o nombre..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                        @if (request('search'))
                            <a href="{{ route('disponible.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Limpiar
                            </a>
                        @endif
                    </div>

                    <div class="row mb-3">
                        <!-- Filtro de reservas -->
                        <div class="col-md-6 mb-2">
                            <div class="btn-group w-100" role="group">
                                <input type="hidden" name="reserva" id="reservaInput"
                                    value="{{ request('reserva', 'reservados') }}">
                                <button type="button" data-value="reservados"
                                    class="btn {{ request('reserva', 'reservados') == 'reservados' ? 'btn-primary' : 'btn-outline-primary' }} reserva-btn">
                                    Reservados
                                </button>
                                <button type="button" data-value="sin_reserva"
                                    class="btn {{ request('reserva') == 'sin_reserva' ? 'btn-primary' : 'btn-outline-primary' }} reserva-btn">
                                    Sin reserva
                                </button>
                                <button type="button" data-value="todos"
                                    class="btn {{ request('reserva') == 'todos' ? 'btn-primary' : 'btn-outline-primary' }} reserva-btn">
                                    Todos
                                </button>
                            </div>
                        </div>

                        <!-- Filtro rápido de fechas -->
                        <div class="col-md-6 mb-2">
                            <div class="btn-group w-100" role="group">
                                <input type="hidden" name="fecha" id="fechaInput"
                                    value="{{ request('fecha', 'hoy') }}">
                                <button type="button" data-value="anteriores"
                                    class="btn {{ request('fecha') == 'anteriores' ? 'btn-primary' : 'btn-outline-primary' }} fecha-btn">
                                    Anteriores
                                </button>
                                <button type="button" data-value="hoy"
                                    class="btn {{ request('fecha', 'hoy') == 'hoy' ? 'btn-primary' : 'btn-outline-primary' }} fecha-btn">
                                    Hoy
                                </button>
                                <button type="button" data-value="futuros"
                                    class="btn {{ request('fecha') == 'futuros' ? 'btn-primary' : 'btn-outline-primary' }} fecha-btn">
                                    Mañana
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Botón para mostrar todo sin filtros -->
                    <div class="text-center">
                        <a href="{{ route('disponible.index') }}?mostrar_todo=1" class="btn btn-success">
                            <i class="bi bi-list-ul"></i> Mostrar Todo (sin filtros)
                        </a>
                    </div>
                </form>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Manejar clics en botones de reserva
                        document.querySelectorAll('.reserva-btn').forEach(button => {
                            button.addEventListener('click', function() {
                                // Remover clase active de todos los botones de reserva
                                document.querySelectorAll('.reserva-btn').forEach(btn => {
                                    btn.classList.remove('btn-primary');
                                    btn.classList.add('btn-outline-primary');
                                });

                                // Agregar clase active al botón clickeado
                                this.classList.remove('btn-outline-primary');
                                this.classList.add('btn-primary');

                                // Actualizar valor del input hidden
                                document.getElementById('reservaInput').value = this.dataset.value;

                                // Enviar el formulario
                                document.getElementById('filterForm').submit();
                            });
                        });

                        // Manejar clics en botones de fecha
                        document.querySelectorAll('.fecha-btn').forEach(button => {
                            button.addEventListener('click', function() {
                                // Remover clase active de todos los botones de fecha
                                document.querySelectorAll('.fecha-btn').forEach(btn => {
                                    btn.classList.remove('btn-primary');
                                    btn.classList.add('btn-outline-primary');
                                });

                                // Agregar clase active al botón clickeado
                                this.classList.remove('btn-outline-primary');
                                this.classList.add('btn-primary');

                                // Actualizar valor del input hidden
                                document.getElementById('fechaInput').value = this.dataset.value;

                                // Enviar el formulario
                                document.getElementById('filterForm').submit();
                            });
                        });
                    });
                </script>

                <!-- Formulario de rango de fechas personalizado -->
                <form action="{{ route('disponible.index') }}" method="GET" class="mb-4 border p-3 rounded">
                    <h5 class="mb-3">Filtrar por rango de fechas</h5>

                    <div class="row align-items-end">
                        <!-- Mantener el filtro de reservas en el formulario de rango -->
                        <input type="hidden" name="reserva" value="{{ request('reserva', 'reservados') }}">

                        <div class="col-md-4">
                            <label class="form-label">Fecha inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control"
                                value="{{ request('fecha_inicio') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Fecha fin</label>
                            <input type="date" name="fecha_fin" class="form-control"
                                value="{{ request('fecha_fin') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" name="fecha" value="personalizado" class="btn btn-primary w-100">
                                <i class="bi bi-filter"></i> Filtrar
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('disponible.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-x-circle"></i> Limpiar
                            </a>
                        </div>
                    </div>
                </form>

            </div>


        <div class="main-table">
            <div class="container-form">
                <h3 class="title-form">Turnos reservados</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th class="option-movil">Nombre del turno</th>
                            <th class="option-movil">Descripcion</th>
                            <th class="option-movil">Fecha</th>
                            <th>Hora</th>
                            <th class="option-movil">Disponible</th>
                            <th>Reservas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($turnoDisponibles as $turnoDisponible)
                            <tr>
                                <td>{{ $turnoDisponible->id }}</td>
                                <td class="option-movil">{{ $turnoDisponible->turno->nombre }}</td>
                                <td class="option-movil">{{ $turnoDisponible->turno->descripcion }}</td>
                                <td class="option-movil">
                                    {{ \Carbon\Carbon::parse($turnoDisponible->fecha)->format('d-m-Y') }}</td>
                                <td> {{ \Carbon\Carbon::parse($turnoDisponible->hora)->format('H:i') }}</td>
                                <td class="option-movil">{{ $turnoDisponible->cupos_disponibles }}</td>
                                <td class="{{ $turnoDisponible->cupos_reservados ? 'btn-success' : 'btn-danger' }}">
                                    {{ $turnoDisponible->cupos_reservados ? 'Reservado' : 'Sin reserva' }}</td>
                                <td class="acciones">
                                    <a href="{{ route('turnos.show', $turnoDisponible->id) }}"
                                        class="btn btn-view"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('turnos.edit', $turnoDisponible->id) }}"
                                        class="btn btn-edit"><i class="bi bi-pencil-fill"></i></a>
                                    <form action="{{ route('turnos.destroy', $turnoDisponible->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete delete-btn"><i
                                                class="bi bi-trash-fill"></i></button>
                                    </form>
                                </td>
                                <td class="accionesMovil"><button class="btn-acciones-movil"><i
                                            class="bi bi-gear"></i></button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
            <div class="mt-3">
                {{ $turnoDisponibles->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <script>
        function limpiarFechas() {
            document.querySelector('input[name="fecha_inicio"]').value = '';
            document.querySelector('input[name="fecha_fin"]').value = '';
            // opcionalmente desmarcar el select de fecha o setearlo a "todos"
            document.querySelector('select[name="fecha"]').value = 'todos';
            // enviar el formulario
            document.querySelector('form').submit();
        }
    </script>

</x-app-layout>