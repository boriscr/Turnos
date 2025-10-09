<x-app-layout>
    <div class="main-table full-center">
        <div class="container-form full-center">
            <h1>{{ __('medical.titles.appointment_quotas_created') }}</h1>
            <div class="search-filters filter-box">
                <!-- Formulario de búsqueda y filtros rápidos -->
                <form action="{{ route('availableAppointments.show') }}" method="GET" class="mb-4 border p-3 rounded"
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
                                <th class="option-movil">{{ __('medical.patient') }}</th>
                                <th>{{ __('appointment.date.date') }}</th>
                                <th>{{ __('appointment.schedule.time') }}</th>
                                <th>{{ __('medical.attendance') }}</th>
                                <th>{{ __('medical.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservations as $item)
                                <tr>
                                    <td class="option-movil">{{ $item->id }}</td>
                                    <td class="option-movil">{{ $item->user->name . ' ' . $item->user->surname }}</td>
                                    <td class="option-movil">
                                        {{ \Carbon\Carbon::parse($item->availableAppointment->date)->format('d-m-Y') }}
                                    </td>
                                    <td class="option-movil">
                                        {{ \Carbon\Carbon::parse($item->availableAppointment->time)->format('H:i') }}
                                    </td>

                                    <td
                                        class="{{ $item->asistencia === true ? ($item->asistencia === null ? 'btn-default' : ($item->asistencia === true ? 'btn-success' : 'btn-danger')) : 'inactive' }}">
                                        @switch($item->asistencia)
                                        @case(false)
                                            {{ __('button.search.not_attendance') }}
                                        @break
                                            @case(null)
                                                {{ __('button.search.pending') }}
                                            @break

                                            @case(true)
                                                {{ __('button.search.assisted') }}
                                            @break


                                            @default
                                                {{ __('button.search.inactive_appointment') }}
                                            @break
                                        @endswitch
                                    </td>
                                    <td class="acciones full-center">
                                        <a href="{{ route('appointments.show', $item->id) }}" class="btn btn-view"><i
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
                    <div class="pagination">
                        {{ $reservations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
