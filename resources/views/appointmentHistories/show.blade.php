<x-app-layout>
    @if (isset($appointmentHistoryId))
        <div class="content-wrapper">
            <h1>{{ __('medical.titles.details') }}</h1>
            <div class="section-container full-center">
                <div class="card">
                    <h2>{{ __('medical.titles.reserved_appointment_details') }}</h2>
                    <x-field-with-icon icon="fingerprint" :label="__('medical.id')" :value="$appointmentHistoryId->id" />
                    @if (isset($appointmentHistoryId->appointment_id))
                        <x-field-with-icon icon="activity" :label="__('reservation.title_name')" :value="$appointmentHistoryId->appointment->name" :link="route('appointments.show', $appointmentHistoryId->appointment_id)" />
                    @else
                        <x-field-with-icon icon="activity" :label="__('reservation.title_name')" :value="$appointmentHistoryId->appointment_name" />
                    @endif
                    <x-field-with-icon icon="heart-pulse-fill" :label="__('specialty.title')" :value="$appointmentHistoryId->specialty" />
                    <x-field-with-icon icon="person-fill" :label="__('medical.doctor')" :value="$appointmentHistoryId->doctor_name" />
                    <x-field-with-icon icon="clock-fill" :label="__('appointment.schedule.time')" :value="\Carbon\Carbon::parse($appointmentHistoryId->appointment_time)->format('H:i')" />
                    <x-field-with-icon icon="calendar-check-fill" :label="__('appointment.date.date')" :value="\Carbon\Carbon::parse($appointmentHistoryId->appointment_date)->format('d/m/Y')" />
                    <x-field-with-icon icon="circle-fill" :label="__('medical.status.title')" />
                    <x-change-of-state :status="$appointmentHistoryId->status" />
                    @if ($appointmentHistoryId->status == 'cancelled_by_user' || $appointmentHistoryId->status == 'cancelled_by_admin')
                        <x-field-with-icon icon="calendar-x" :label="__('medical.cancellation_date')" :value="$appointmentHistoryId->cancelled_at" />
                    @elseif ($appointmentHistoryId->status == 'deleted_by_admin')
                        <x-field-with-icon icon="calendar-x" :label="__('medical.deletion_date')" :value="$appointmentHistoryId->cancelled_at" />
                    @endif
                    <x-field-with-icon icon="calendar-minus-fill" :label="__('medical.creation_date')" :value="$appointmentHistoryId->created_at" />
                    <x-field-with-icon icon="calendar-range-fill" :label="__('medical.update_date')" :value="$appointmentHistoryId->updated_at" />
                </div>

                <div class="card">
                    <h2>{{ __('medical.titles.patient_data') }}</h2>
                    @if ($appointmentHistoryId->user->id)
                        <x-field-with-icon icon="person-fill" :label="__('medical.patient')" :value="$appointmentHistoryId->user->name . ' ' . $appointmentHistoryId->user->surname"
                            :link="route('user.show', $appointmentHistoryId->user->id)" />
                        <x-field-with-icon icon="person-vcard-fill me-2" :label="__('contact.idNumber')" :value="$appointmentHistoryId->user->idNumber" />
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
