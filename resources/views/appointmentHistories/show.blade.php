<x-app-layout>
    @if (isset($appointmentHistoryId))
        <div class="content-wrapper">
            <h1>{{ __('medical.titles.details') }}</h1>
            <div class="section-container full-center">
                <div class="card">
                    <h2>{{ __('medical.titles.reserved_appointment_details') }}</h2>
                    <p><b><i class="bi bi-fingerprint"></i>{{ __('medical.id') }}:</b>
                        {{ $appointmentHistoryId->id }}
                    </p>
                    <p><b><i class="bi bi-activity"></i>{{ __('reservation.title_name') }}:
                        </b>{{ $appointmentHistoryId->appointment_name }}
                        <a href="{{ route('appointments.show', $appointmentHistoryId->appointment_id) }}">
                            <i class="bi bi-eye">{{ __('button.view') }}</i>
                        </a>
                    </p>
                    @if (isset($appointmentHistoryId->appointment_id))
                        <p><b><i class="bi bi-geo-alt-fill"></i>{{ __('contact.address') }}:
                            </b>{{ $appointmentHistoryId->appointment->address }}</p>
                        <p><b><i class="bi bi-heart-pulse-fill"></i>{{ __('specialty.title') }}:
                            </b>{{ $appointmentHistoryId->appointment->doctor->specialty->name }}<a
                                href="{{ route('specialty.show', $appointmentHistoryId->appointment->doctor->specialty->id) }}">
                                <i class="bi bi-eye">{{ __('button.view') }}</i>
                            </a></p>
                        </p>
                        <p><b><i class="bi bi-person-check-fill"></i>{{ __('medical.doctor') }}:
                            </b>{{ $appointmentHistoryId->appointment->doctor->name . ' ' . $appointmentHistoryId->appointment->doctor->surname }}
                            <a href="{{ route('doctor.show', $appointmentHistoryId->appointment->doctor_id) }}">
                                <i class="bi bi-eye">{{ __('button.view') }}</i>
                            </a>
                        </p>
                    @endif
                    <p><b><i class="bi bi-clock-fill"></i>{{ __('appointment.schedule.time') }}:
                        </b>{{ \Carbon\Carbon::parse($appointmentHistoryId->appointment_time)->format('H:i') }}
                    </p>
                    <p><b><i class="bi bi-calendar-check-fill"></i>{{ __('appointment.date.date') }}:
                        </b>{{ \Carbon\Carbon::parse($appointmentHistoryId->appointment_date)->format('d/m/Y') }}
                    </p>
                    <p><b><i class="bi bi-clipboard"></i>{{ __('medical.status.title') }}: </b>
                        </b>
                        <span
                            @switch($appointmentHistoryId->status)
                                        @case('assisted')
                                            <i class="bi bi-check-circle-fill btn-success">{{ __('button.search.assisted') }}</i>
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
                                    @endswitch</span>
                    </p>
                    @if ($appointmentHistoryId->status == 'cancelled_by_user' || $appointmentHistoryId->status == 'cancelled_by_admin')
                        <p><b><i class="bi bi-calendar-x"></i>{{ __('medical.cancellation_date') }}:
                            </b>{{ $appointmentHistoryId->cancelled_at }}</p>
                    @elseif ($appointmentHistoryId->status == 'deleted_by_admin')
                        <p><b><i class="bi bi-calendar-x"></i>{{ __('medical.deletion_date') }}:
                            </b>{{ $appointmentHistoryId->cancelled_at }}</p>
                    @endif
                    <p><b><i class="bi bi-calendar-date"></i>{{ __('medical.creation_date') }}:
                        </b>{{ $appointmentHistoryId->created_at }}</p>
                    <p><b><i class="bi bi-calendar-date"></i>{{ __('medical.update_date') }}:
                        </b>{{ $appointmentHistoryId->updated_at }}</p>
                </div>

                <div class="card">
                    <h2>{{ __('medical.titles.patient_data') }}</h2>
                    @if ($appointmentHistoryId->user->id)
                        <p><b><i class="bi bi-person-fill"></i>{{ __('medical.patient') }}: </b>
                            {{ $appointmentHistoryId->user->name . ' ' . $appointmentHistoryId->user->surname }}
                            <a href="{{ route('user.show', $appointmentHistoryId->user->id) }}"><i
                                    class="bi bi-eye">{{ __('button.view') }}</i></a>
                        </p>
                        <p><b><i class="bi bi-fingerprint"></i>{{ __('contact.idNumber') }}:
                            </b>{{ $appointmentHistoryId->user->idNumber }}</p>
                        <p><b><i class="bi bi-clipboard"></i>{{ __('medical.status.title') }}:
                            </b>
                            <span class="{{ $appointmentHistoryId->user->status ? 'btn-success' : 'btn-danger' }}">
                                {{ $appointmentHistoryId->user->status ? __('medical.active') : __('medical.inactive') }}
                            </span>
                        </p>
                    @endif
                </div>
            </div>

            <div class="options full-center">
                <form action="{{ route('appointmentHistory.show', $appointmentHistoryId->id) }}" method="POST"
                    style="display:inline;" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-delete delete-btn">
                        <i class="bi bi-trash-fill">{{ __('button.delete') }}</i>
                    </button>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
