<x-app-layout>
    <div class="content-wrapper">
        <x-form.titles :value="__('medical.titles.details')" size="show" />
        <div class="status-box {{ $user->status ? 'status-active' : 'status-inactive' }}">
            <span> {{ $user->status ? __('medical.active') : __('medical.inactive') }}</span>
        </div>
        <div class="section-container full-center">
            <div class="card">
                <x-form.titles :value="__('medical.titles.personal_data')" type="subtitle"/>

                <x-field-with-icon icon="person-fill" :label="__('contact.name_and_surname')" :value="$user->name . ' ' . $user->surname" />
                <x-field-with-icon icon="person-vcard-fill" :label="__('contact.idNumber')" :value="$user->idNumber" />
                <x-field-with-icon icon="gift-fill" :label="__('contact.birthdate')" :value="\Carbon\Carbon::parse($user->birthdate)->format('d/m/Y') .
                    ' (' .
                    \Carbon\Carbon::parse($user->birthdate)->age .
                    ' años)'" />
                <x-field-with-icon icon="gender-ambiguous" :label="__('contact.gender')" :value="$user->gender->name" />
                <x-field-with-icon icon="person-badge-fill" :label="__('medical.role')" :value="$user->role" />
            </div>

            <div class="card">
                <x-form.titles :value="__('contact.address')" type="subtitle"/>

                <x-field-with-icon icon="globe" :label="__('contact.country')" :value="$user->country->name ?? __('medical.no_data')" />
                <x-field-with-icon icon="geo-alt-fill" :label="__('contact.state')" :value="$user->state->name ?? __('medical.no_data')" />
                <x-field-with-icon icon="building-fill" :label="__('contact.city')" :value="$user->city->name ?? __('medical.no_data')" />
                <x-field-with-icon icon="house-door-fill" :label="__('contact.address')" :value="$user->address" />
            </div>

            <div class="card">
                <x-form.titles :value="__('medical.titles.contact_details')" type="subtitle"/>

                <x-field-with-icon icon="telephone-fill" :label="__('contact.phone')" :value="$user->phone" />
                <x-field-with-icon icon="envelope-fill" :label="__('contact.email')" :value="$user->email" />
            </div>

            <div class="card">
                <x-form.titles :value="__('medical.titles.reservations')" type="subtitle"/>

                <x-field-with-icon icon="x" :label="__('medical.unassisted_reservations')" :value="$user->faults === 0 ? __('medical.none') : $user->faults" />
            </div>

            <div class="card">
                <x-form.titles :value="__('medical.titles.creation')" type="subtitle"/>

                <x-field-with-icon icon="calendar-minus-fill" :label="__('medical.creation_date')" :value="\Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i')" />

                @if (!empty($user->updatedById->name))
                    <x-field-with-icon icon="calendar-range-fill" :label="__('medical.updated_by')" :value="$user->updatedById->name . ' ' . $user->updatedById->surname"
                        :link="route('users.show', $user->updated_by)" />
                @else
                    <x-field-with-icon icon="calendar-range-fill" :label="__('medical.updated_by')" :value="__('medical.no_data')" />
                @endif
                <x-field-with-icon icon="calendar-range-fill" :label="__('medical.update_date')" :value="\Carbon\Carbon::parse($user->updated_at)->format('d/m/Y H:i')" />
            </div>
        </div>

        <div class="options full-center">
            <a href="{{ route('users.edit', $user->id) }}" class="btn-edit"><i
                    class="bi bi-pencil-fill">{{ __('button.edit') }}</i></a>
            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="delete-form">
                @csrf
                @method('DELETE')
                <button type="button" class="btn-delete delete-btn"><i
                        class="bi bi-trash-fill">{{ __('button.delete') }}</i> </button>
            </form>
        </div>
    </div>

    <div class="main-table full-center">
        <div class="container-form full-center">
            <div class="container-form full-center">
                <x-form.titles :value="__('medical.titles.historical')" size="index" />

                <table>
                    <thead>
                        <tr>
                            <th class="option-movil">{{ __('medical.id') }}</th>
                            <th class="option-movil">{{ __('reservation.title_name') }}</th>
                            <th class="option-movil">{{ __('medical.patient') }}</th>
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
                                <td class="option-movil">{{ $item->user->idNumber }}</td>
                                <td>{{ $item->doctor_name }}</td>
                                <td>{{ $item->specialty }}</td>
                                <td>{{ Carbon\Carbon::parse($item->appointment_date)->format('d/m/Y') }}
                                </td>
                                <td class="option-movil">
                                    {{ Carbon\Carbon::parse($item->appointment_time)->format('H:i') }}
                                </td>
                                <td
                                    class="{{ $item->status == 'pending' ? 'btn-default' : ($item->status == 'assisted' ? 'btn-success' : 'btn-danger') }}">
                                    @switch($item->status)
                                        @case('pending')
                                            <i class="bi bi-hourglass-split btn-default">{{ __('button.search.pending') }}</i>
                                        @break

                                        @case('assisted')
                                            <i
                                                class="bi bi-check-circle-fill btn-success">{{ __('button.search.assisted') }}</i>
                                        @break

                                        @case('not_attendance')
                                            <i
                                                class="bi bi-x-circle-fill btn-danger">{{ __('button.search.not_attendance') }}</i>
                                        @break

                                        @case('cancelled_by_user')
                                            <i
                                                class="bi bi-x-circle-fill btn-danger">{{ __('medical.status.cancelled_by_user') }}</i>
                                        @break

                                        @case('cancelled_by_admin')
                                            <i
                                                class="bi bi-x-circle-fill btn-danger">{{ __('medical.status.cancelled_by_admin') }}</i>
                                        @break

                                        @case('deleted_by_admin')
                                            <i
                                                class="bi bi-x-circle-fill btn-danger">{{ __('medical.status.deleted_by_admin') }}</i>
                                        @break

                                        @default
                                            <i
                                                class="bi bi-question-circle-fill btn-default">{{ __('medical.status.unknown') }}</i>
                                    @endswitch
                                </td>
                                <td class="acciones full-center">
                                    <a href="{{ route('appointmentHistory.show', $item->id) }}" class="btn btn-view">
                                        <i class="bi bi-eye"></i><b class="accionesMovil">{{ __('button.view') }}</b>
                                    </a>
                                    <form action="{{ route('appointmentHistory.destroy', $item->id) }}" method="POST"
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
