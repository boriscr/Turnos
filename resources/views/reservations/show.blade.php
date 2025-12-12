<x-app-layout>
    @if (isset($reservation))
        <div class="content-wrapper">
            <x-form.titles :value="__('medical.titles.details')" size="show" />
            <div class="section-container full-center">
                <div class="card">
                    <x-form.titles :value="__('medical.titles.reserved_appointment_details')" type="subtitle" />
                    <x-field-with-icon icon="activity" :label="__('reservation.title_name')" :value="$reservation->availableAppointment->appointment->name" :link="route('appointments.show', $reservation->availableAppointment->appointment->id)" />
                    <x-field-with-icon icon="geo-alt-fill" :label="__('contact.address')" :value="$reservation->availableAppointment->appointment->address" />
                    <x-field-with-icon icon="heart-pulse-fill" :label="__('specialty.title')" :value="$reservation->availableAppointment->doctor->specialty->name" :link="route('specialties.show', $reservation->availableAppointment->doctor->specialty->id)" />
                    <x-field-with-icon icon="person-fill" :label="__('medical.doctor')" :value="$reservation->availableAppointment->doctor->name .
                        ' ' .
                        $reservation->availableAppointment->doctor->surname" :link="route('doctors.show', $reservation->availableAppointment->doctor_id)" />
                    <x-field-with-icon icon="clock-fill" :label="__('appointment.schedule.time')" :value="\Carbon\Carbon::parse($reservation->availableAppointment->time)->format('H:i')" />
                    <x-field-with-icon icon="calendar-check-fill" :label="__('appointment.date.date')" :value="\Carbon\Carbon::parse($reservation->availableAppointment->date)->format('d/m/Y')" />
                    <x-field-with-icon icon="circle-fill" :label="__('medical.status.title')" />
                    @if ($reservation->availableAppointment->doctor->specialty->status)
                        <x-change-of-state :status="$reservation->status" />
                    @else
                        <spam class="full-center status btn-danger">
                            {{ __('button.search.inactive_appointment') }}
                        </spam>
                    @endif
                    <x-field-with-icon icon="calendar-minus-fill" :label="__('medical.creation_date')" :value="$reservation->created_at" />
                    <x-field-with-icon icon="calendar-range-fill" :label="__('medical.update_date')" :value="$reservation->updated_at" />
                </div>
                @if ($reservation->type === 'third_party')
                    <div class="card">
                        <x-form.titles :value="__('medical.titles.patient_data')" type="subtitle" />
                        <x-field-with-icon icon="person-fill" :label="__('medical.patient')" :value="$reservation->third_party_name . ' ' . $reservation->third_party_surname" />
                        <x-field-with-icon icon="person-vcard-fill" :label="__('contact.idNumber')" :value="$reservation->third_party_idNumber" />
                        <x-field-with-icon icon="envelope-fill me-2" :label="__('contact.email')" :value="$reservation->third_party_email" />
                        <span class="full-center status btn-success">
                            {{ __('medical.third_party') }}
                        </span>
                    </div>
                @endif
                <div class="card">
                    <x-form.titles :value="__('medical.titles.patient_data')" type="subtitle" />
                    <x-field-with-icon icon="person-fill" :label="__('medical.patient')" :value="$reservation->user->name . ' ' . $reservation->user->surname" :link="route('users.show', $reservation->user->id)" />
                    <x-field-with-icon icon="person-vcard-fill" :label="__('contact.idNumber')" :value="$reservation->user->idNumber" />
                    <x-field-with-icon icon="circle-fill" :label="__('medical.status.title')" />
                    <span class="full-center status {{ $reservation->user->status ? 'btn-success' : 'btn-danger' }}">
                        <x-field-with-icon :value="$reservation->user->status ? __('medical.active') : __('medical.inactive')" />
                    </span>
                </div>
            </div>
            @role('admin')
                <div class="options full-center">
                    <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST"
                        style="display:inline;" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-delete delete-btn">
                            <i class="bi bi-trash-fill">{{ __('button.delete') }}</i>
                        </button>
                    </form>
                </div>
            @endrole
        </div>
    @endif
</x-app-layout>
