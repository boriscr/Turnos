<x-app-layout>
    <div class="main-table full-center">
        <div class="container-form full-center">
            <h1>{{ __('medical.titles.reservation_list') }}</h1>
            <div class="search-filters filter-box">
                <form action="{{ route('reservations.index') }}" method="GET" class="rounded" id="filterForm">
                    <!-- Buscardor de usuarios o doctores-->
                    @include('components.form.search')
                    <div class="mb-3">
                        <!-- Filtro de tipos de asistencias-->
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
                        <th>{{ __('medical.id') }}</th>
                        <th>{{ __('appointment.date.date') }}</th>
                        <th class="option-movil">{{ __('appointment.schedule.time') }}</th>
                        <th>{{ __('medical.doctor') }}</th>
                        <th>{{ __('medical.patient') }}</th>
                        <th>{{ __('medical.attendance') }}</th>
                        <th>{{ __('medical.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->id }}</td>
                            <td>{{ Carbon\Carbon::parse($reservation->availableAppointment->date)->format('d/m/Y') }}
                            </td>
                            <td class="option-movil">
                                {{ Carbon\Carbon::parse($reservation->availableAppointment->time)->format('H:i') }}
                            </td>
                            <td><a
                                    href="{{ route('doctor.create', $reservation->availableAppointment->doctor->id) }}">{{ $reservation->availableAppointment->doctor->name . ' ' . $reservation->availableAppointment->doctor->surname }}</a>
                            </td>
                            <td><a
                                    href="{{ route('user.show', $reservation->user->id) }}">{{ $reservation->user->name . ' ' . $reservation->user->surname }}</a>
                            </td>
                            <td>
                                @if ($reservation->availableAppointment->appointment->status === true)
                                    @if ($reservation->asistencia === null)
                                        <div class="ios-dropdown">
                                            <button class="btn-asistencia btn-default dropdown-toggle"
                                                title="Marcar asistencia">
                                                <i class="bi bi-hourglass-split"></i>{{ __('button.search.pending') }}
                                            </button>
                                            <div class="ios-dropdown-menu">
                                                <form action="{{ route('reservations.asistencia', $reservation->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="asistencia" value="1">
                                                    <button type="submit" class="ios-dropdown-item success">
                                                        <i class="bi bi-check-circle-fill"></i>
                                                        {{ __('button.search.assisted') }}
                                                    </button>
                                                </form>
                                                <form action="{{ route('reservations.asistencia', $reservation->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="asistencia" value="0">
                                                    <button type="submit" class="ios-dropdown-item danger">
                                                        <i class="bi bi-x-circle-fill"></i>
                                                        {{ __('button.search.not_attendance') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <form action="{{ route('reservations.asistencia', $reservation->id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="btn-asistencia {{ $reservation->asistencia ? 'btn-success' : 'btn-danger' }}"
                                                title="{{ $reservation->asistencia ? __('button.reservation.assisted_txt') : __('button.reservation.not_attendance_txt') }}">
                                                <i
                                                    class="bi {{ $reservation->asistencia ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                                {{ $reservation->asistencia ? __('button.search.assisted') : __('button.search.not_attendance') }}
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <button type="button" class="btn-asistencia inactive"
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
                                <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST"
                                    style="display:inline;" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-delete delete-btn">
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
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">{{ __('medical.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $reservations->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
