<x-app-layout>
    <div class="main-table full-center">
        <div class="container-form full-center">
            <h1>Turnos creados</h1>
            <div class="mb-4">
                <!-- Formulario de búsqueda y filtros rápidos -->
                <form action="{{ route('availableAppointments.index') }}" method="GET" class="mb-4" id="filterForm">
                    <div class="input-group mb-3">
                        <input type="text" name="search" class="form-control"
                            placeholder="Buscar por DNI o name..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-default">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                        @if (request('search'))
                            <a href="{{ route('availableAppointments.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Limpiar
                            </a>
                        @endif
                    </div>

                    <div class="row mb-3">
                        <!-- Filtro de reservations -->
                        <div class="col-md-6 mb-2">
                            <div class="btn-group w-100" role="group">
                                <input type="hidden" name="reservation" id="reservaInput"
                                    value="{{ request('reservation', 'reservados') }}">
                                <button type="button" data-value="reservados"
                                    class="btn {{ request('reservation', 'reservados') == 'reservados' ? 'btn-default' : 'btn-outline-primary' }} reservation-btn">
                                    Reservados
                                </button>
                                <button type="button" data-value="sin_reserva"
                                    class="btn {{ request('reservation') == 'sin_reserva' ? 'btn-default' : 'btn-outline-primary' }} reservation-btn">
                                    Sin reservation
                                </button>
                                <button type="button" data-value="todos"
                                    class="btn {{ request('reservation') == 'todos' ? 'btn-default' : 'btn-outline-primary' }} reservation-btn">
                                    Todos
                                </button>
                            </div>
                        </div>

                        <!-- Filtro rápido de fechas -->
                        <div class="col-md-6 mb-2">
                            <div class="btn-group w-100" role="group">
                                <input type="hidden" name="date" id="fechaInput"
                                    value="{{ request('date', 'hoy') }}">
                                <button type="button" data-value="anteriores"
                                    class="btn {{ request('date') == 'anteriores' ? 'btn-default' : 'btn-outline-primary' }} date-btn">
                                    Anteriores
                                </button>
                                <button type="button" data-value="hoy"
                                    class="btn {{ request('date', 'hoy') == 'hoy' ? 'btn-default' : 'btn-outline-primary' }} date-btn">
                                    Hoy
                                </button>
                                <button type="button" data-value="futuros"
                                    class="btn {{ request('date') == 'futuros' ? 'btn-default' : 'btn-outline-primary' }} date-btn">
                                    Mañana
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Botón para mostrar todo sin filtros -->
                    <div class="text-center">
                        <a href="{{ route('availableAppointments.index') }}?mostrar_todo=1" class="btn btn-success">
                            <i class="bi bi-list-ul"></i> Mostrar Todo (sin filtros)
                        </a>
                    </div>
                </form>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Manejar clics en botones de reservation
                        document.querySelectorAll('.reservation-btn').forEach(button => {
                            button.addEventListener('click', function() {
                                // Remover clase active de todos los botones de reservation
                                document.querySelectorAll('.reservation-btn').forEach(btn => {
                                    btn.classList.remove('btn-default');
                                    btn.classList.add('btn-outline-primary');
                                });

                                // Agregar clase active al botón clickeado
                                this.classList.remove('btn-outline-primary');
                                this.classList.add('btn-default');

                                // Actualizar valor del input hidden
                                document.getElementById('reservaInput').value = this.dataset.value;

                                // Enviar el formulario
                                document.getElementById('filterForm').submit();
                            });
                        });

                        // Manejar clics en botones de date
                        document.querySelectorAll('.date-btn').forEach(button => {
                            button.addEventListener('click', function() {
                                // Remover clase active de todos los botones de date
                                document.querySelectorAll('.date-btn').forEach(btn => {
                                    btn.classList.remove('btn-default');
                                    btn.classList.add('btn-outline-primary');
                                });

                                // Agregar clase active al botón clickeado
                                this.classList.remove('btn-outline-primary');
                                this.classList.add('btn-default');

                                // Actualizar valor del input hidden
                                document.getElementById('fechaInput').value = this.dataset.value;

                                // Enviar el formulario
                                document.getElementById('filterForm').submit();
                            });
                        });
                    });
                </script>

                <!-- Formulario de rango de fechas personalizado -->
                <form action="{{ route('availableAppointments.index') }}" method="GET" class="mb-4 border p-3 rounded">
                    <h3 class="mb-3">Filtrar por rango de fechas</h3>

                    <div class="row align-items-end">
                        <!-- Mantener el filtro de reservations en el formulario de rango -->
                        <input type="hidden" name="reservation" value="{{ request('reservation', 'reservados') }}">

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
                            <button type="submit" name="date" value="personalizado" class="btn btn-default w-100">
                                <i class="bi bi-filter"></i> Filtrar
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('availableAppointments.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-x-circle"></i> Limpiar
                            </a>
                        </div>
                    </div>
                </form>

            </div>


        <div class="main-table full-center">
            <div class="container-form full-center">
                <h2>Turnos reservados</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th class="option-movil">Nombre del appointment</th>
                            <th class="option-movil">Fecha</th>
                            <th>Hora</th>
                            <th class="option-movil">Disponible</th>
                            <th>Reservations</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($turnoDisponibles as $availableAppointment)
                            <tr>
                                <td>{{ $availableAppointment->id }}</td>
                                <td class="option-movil">{{ $availableAppointment->appointment->name }}</td>
                                <td class="option-movil">
                                    {{ \Carbon\Carbon::parse($availableAppointment->date)->format('d-m-Y') }}</td>
                                <td> {{ \Carbon\Carbon::parse($availableAppointment->time)->format('H:i') }}</td>
                                <td class="option-movil">{{ $availableAppointment->available_spots }}</td>
                                <td class="{{ $availableAppointment->reserved_spots ? 'btn-success' : 'btn-danger' }}">
                                    {{ $availableAppointment->reserved_spots ? 'Reservado' : 'Sin reservation' }}</td>
                                <td class="acciones full-center">
                                    <a href="{{ route('appointments.show', $availableAppointment->id) }}"
                                        class="btn btn-view"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('appointments.edit', $availableAppointment->id) }}"
                                        class="btn btn-edit"><i class="bi bi-pencil-fill"></i></a>
                                    <form action="{{ route('appointments.destroy', $availableAppointment->id) }}" method="POST"
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
            // opcionalmente desmarcar el select de date o setearlo a "todos"
            document.querySelector('select[name="date"]').value = 'todos';
            // enviar el formulario
            document.querySelector('form').submit();
        }
    </script>

</x-app-layout>