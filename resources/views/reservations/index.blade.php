<x-app-layout>
    <div class="main-table full-center">
        <div class="container-form full-center">
            <x-form.titles :value="__('medical.titles.reservation_list')" size="index" />
            <div class="search-filters filter-box">

                <form action="{{ route('reservations.index') }}" method="GET" class="rounded" id="filterForm">
                    <!-- Buscardor de usuarios o doctores-->
                    <x-form.search indexReservation="reservations" />
                    <div class="mb-3">
                        <!-- Filtro de tipos de statuss-->
                        @include('components.form.assistance-filter')
                        <br>
                        <hr>
                        <br>
                        <!-- Contenedor principal de filtros de fecha -->
                        @include('components.form.date-filter')

                        <!-- Filtro de especialidades -->
                        <x-form.select name="specialty_id" :label="__('specialty.title')">
                            <option value="all_specialties">{{ __('specialty.all') }}</option>
                            @if (!empty($specialties))
                                @foreach ($specialties as $specialty)
                                    @if ($specialty->status == 1)
                                        <option value="{{ $specialty->id }}"
                                            {{ request('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                            {{ $specialty->name }}
                                        </option>
                                    @endif
                                @endforeach
                            @endif
                        </x-form.select>
                    </div>
                </form>
            </div>
            <table>
                <thead>
                    <tr>
                        <th class="option-movil">{{ __('medical.id') }}</th>
                        <th>{{ __('medical.patient') }}</th>
                        <th>{{ __('appointment.date.date') }}</th>
                        <th class="option-movil">{{ __('appointment.schedule.time') }}</th>
                        <th>{{ __('medical.doctor') }}</th>
                        <th>{{ __('medical.attendance') }}</th>
                        <th>{{ __('medical.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reservations as $reservation)
                        <tr>
                            <td class="option-movil">{{ $reservation->id }}</td>
                            <td>
                                {{ $reservation->user->name . ' ' . $reservation->user->surname }} </td>
                            <td>{{ Carbon\Carbon::parse($reservation->availableAppointment->date)->format('d/m/Y') }}
                            </td>
                            <td class="option-movil">
                                {{ Carbon\Carbon::parse($reservation->availableAppointment->time)->format('H:i') }}
                            </td>
                            <td><a
                                    href="{{ route('doctors.create', $reservation->availableAppointment->doctor->id) }}">{{ $reservation->availableAppointment->doctor->name . ' ' . $reservation->availableAppointment->doctor->surname }}</a>
                            </td>
                            <td>
                                @if ($reservation->availableAppointment->appointment->status === true)
                                    @if ($reservation->status === 'pending')
                                        <div class="ios-dropdown">
                                            <button class="btn-status btn-default dropdown-toggle-btn"
                                                title="Marcar status">
                                                <i class="bi bi-hourglass-split"></i>{{ __('button.search.pending') }}
                                            </button>
                                            <div class="ios-dropdown-menu">
                                                <form action="{{ route('reservations.status', $reservation->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="assisted">
                                                    <button type="submit" class="ios-dropdown-item success">
                                                        <span><i class="bi bi-check-circle-fill"></i>
                                                            {{ __('button.search.assisted') }}</span>
                                                    </button>
                                                </form>
                                                <form action="{{ route('reservations.status', $reservation->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="not_attendance">
                                                    <button type="submit" class="ios-dropdown-item danger">
                                                        <span><i class="bi bi-x-circle-fill"></i>
                                                            {{ __('button.search.not_attendance') }}</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <form action="{{ route('reservations.status', $reservation->id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status"
                                                value="{{ $reservation->status === 'assisted' ? 'not_attendance' : 'assisted' }}">
                                            <button type="submit"
                                                class="btn-status {{ $reservation->status === 'assisted' ? 'btn-success' : 'btn-danger' }}"
                                                title="{{ $reservation->status === 'assisted' ? __('button.reservation.assisted_txt') : __('button.reservation.not_attendance_txt') }}">
                                                <i
                                                    class="bi {{ $reservation->status === 'assisted' ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                                {{ $reservation->status === 'assisted' ? __('button.search.assisted') : __('button.search.not_attendance') }}
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <button type="button" class="btn-status inactive"
                                        title="{{ __('button.search.inactive_appointment') }}">
                                        <i class="bi bi-slash-circle"></i>
                                        {{ __('button.search.inactive_appointment') }}
                                    </button>
                                @endif
                            </td>

                            <td class="acciones full-center">
                                <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-view">
                                    <i class="bi bi-eye"></i><b class="accionesMovil">{{ __('button.view') }}</b>
                                </a>
                                @role('admin')
                                    <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST"
                                        style="display:inline;" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-delete delete-btn">
                                            <i class="bi bi-trash-fill"></i><b
                                                class="accionesMovil">{{ __('button.delete') }}</b>
                                        </button>
                                    </form>
                                @endrole
                            </td>
                            <td class="accionesMovil">
                                <button type="button" class="accionesMovilBtn">
                                    <i class="bi bi-gear"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">{{ __('medical.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="pagination">
                {{ $reservations->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
