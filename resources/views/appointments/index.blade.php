<x-app-layout>
    <div class="main-table full-center">
        
        <div class="container-form full-center">
            <h1>{{ __('medical.titles.appointment_index_title') }}</h1>
            <table>
                <thead>
                    <tr>
                        <th>{{ __('medical.id') }}</th>
                        <th>{{ __('reservation.title_name') }}</th>
                        <th class="option-movil">{{ __('contact.address') }}</th>
                        <th class="option-movil">{{ __('specialty.title') }}</th>
                        <th>{{ __('medical.doctor') }}</th>
                        <th class="option-movil">{{ __('appointment.shift.name') }}</th>
                        <th>{{ __('medical.status.title') }}</th>
                        <th>{{ __('medical.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->id }}</td>
                            <td>{{ $appointment->name }}</td>
                            <td class="option-movil">{{ $appointment->address }}</td>
                            <td class="option-movil">{{ $appointment->specialty->name }}</td>
                            <td>{{ $appointment->doctor->name . ' ' . $appointment->doctor->surname }}</td>
                            <td class="option-movil">
                                {{ $appointment->shift === 'morning' ? __('appointment.shift.morning_shift') : ($appointment->shift === 'afternoon' ? __('appointment.shift.afternoon_shift') : __('appointment.shift.night_shift')) }}
                            </td>
                            <td class="{{ $appointment->status ? 'existing' : 'no_data' }}">
                                {{ $appointment->status ? __('medical.active') : __('medical.inactive') }}</td>
                            <td class="acciones full-center">
                                <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-view"><i
                                        class="bi bi-eye"></i><b class="accionesMovil">{{ __('button.view') }}</b></a>
                                <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-edit"><i
                                        class="bi bi-pencil-fill"></i><b
                                        class="accionesMovil">{{ __('button.edit') }}</b></a>

                                <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST"
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
                {{ $appointments->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
