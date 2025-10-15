<x-app-layout>
    <div class="main-table full-center">
        <div class="container-form full-center">
            <h1>{{ __('medical.titles.appointment_quotas_created') }}</h1>
            <div class="search-filters filter-box">
                <!-- Formulario de búsqueda y filtros rápidos -->
                <form action="{{ route('availableAppointments.index') }}" method="GET" class="mb-4 border p-3 rounded"
                    id="filterForm">
                    <div class="mb-3">
                        <!-- Filtro de reservations -->
                        @include('components.form.assistance-filter')
                        <!-- Filtro rápido de available_dates -->
                        @include('components.form.date-filter')
                    </div>
                    <!-- Botón para mostrar todo sin filtros -->
                    <div class="text-center">
                        <a href="{{ route('availableAppointments.index') }}?show_all=1"
                            class="secondary-btn full-center">
                            <i class="bi bi-list-ul"></i> Mostrar Todo (sin filtros)
                        </a>
                    </div>
                </form>
            </div>
            <div class="main-table full-center">
                <div class="container-form full-center">
                    <table>
                        <thead>
                            <tr>
                                <th class="option-movil">{{ __('medical.id') }}</th>
                                <th class="option-movil">{{ __('reservation.title_name') }}</th>
                                <th>{{ __('appointment.date.date') }}</th>
                                <th>{{ __('appointment.schedule.time') }}</th>
                                <th>{{ __('medical.available') }}</th>
                                <th>{{ __('medical.reserved') }}</th>
                                <th>{{ __('medical.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($availableAppointment as $item)
                                <tr>
                                    <td class="option-movil">{{ $item->id }}</td>
                                    <td class="option-movil">{{ $item->appointment->name }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
                                    <td> {{ \Carbon\Carbon::parse($item->time)->format('H:i') }}</td>
                                    <td class="{{ $item->available_spots ? 'btn-success' : 'btn-danger' }}">
                                        {{ $item->available_spots }}
                                    </td>
                                    <td class="{{ $item->reserved_spots ? 'btn-success' : 'btn-danger' }}">
                                        {{ $item->reserved_spots }}
                                    </td>
                                    <td class="acciones full-center">
                                        <a href="{{ route('reservations.index', $item->id) }}" class="btn btn-view"><i
                                                class="bi bi-eye"></i><b
                                                class="accionesMovil">{{ __('button.view') }}</b></a>
                                        <form action="{{ route('appointments.destroy', $item->id) }}" method="POST"
                                            class="delete-form" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-delete delete-btn">
                                                <i class="bi bi-trash-fill"></i><b
                                                    class="accionesMovil">{{ __('button.delete') }}</b>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="accionesMovil">
                                        <button type="button" class="accionesMovilBtn">
                                            <i class="bi bi-gear"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="pagination">
                {{ $availableAppointment->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
