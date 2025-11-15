<x-app-layout>
    <div class="main-table full-center">
        <div class="container-form full-center">
            <div class="container-form full-center">
                <x-form.titles :value="__('medical.titles.historical')" size="index" />

                <table>
                    <thead>
                        <tr>
                            <th class="option-movil">{{ __('medical.id') }}</th>
                            <th class="option-movil">{{ __('reservation.title_name') }}</th>
                            <th>{{ __('medical.patient') }}</th>
                            <th>{{ __('medical.doctor') }}</th>
                            <th>{{ __('specialty.title') }}</th>
                            <th>{{ __('appointment.date.date') }}</th>
                            <th class="option-movil">{{ __('appointment.schedule.time') }}</th>
                            <th>{{ __('medical.status.title') }}</th>
                            <th>{{ __('medical.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($appointmentHistory as $item)
                            <tr>
                                <td class="option-movil">{{ $item->id }}</td>
                                <td class="option-movil">{{ $item->appointment_name }}</td>
                                <td>{{ $item->user->idNumber }}</td>
                                <td>{{ $item->doctor_name }}</td>
                                <td>{{ $item->specialty }}</td>
                                <td>{{ Carbon\Carbon::parse($item->appointment_date)->format('d/m/Y') }}
                                </td>
                                <td class="option-movil">
                                    {{ Carbon\Carbon::parse($item->appointment_time)->format('H:i') }}
                                </td>
                                <td
                                    class="{{ $item->status == 'pending' ? 'btn-default' : ($item->status == 'assisted' ? 'btn-success' : 'btn-danger') }}">
                                    <x-change-of-state :status="$item->status" />
                                </td>
                                <td>
                                    <a href="{{ route('appointmentHistory.show', $item->id) }}" class="btn btn-view"><i
                                            class="bi bi-eye"></i><b
                                            class="accionesMovil">{{ __('button.view') }}</b></a>
                                    <form action="{{ route('appointmentHistory.destroy', $item->id) }}" method="POST"
                                        style="display:inline;" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-delete delete-btn">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <h5 colspan="7" class="text-center">{{ __('medical.no_data') }}</h5>
                        @endforelse
                    </tbody>
                </table>
                <div class="pagination">
                    {{ $appointmentHistory->links() }}
                </div>
            </div>
        </div>
</x-app-layout>
