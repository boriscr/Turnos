<x-app-layout>
    <div class="main full-center">
        <div class="container-form full-center">
            <x-form.titles :value="__('appointment.edit_title')" size="edit-create" />
            <x-form.appointment :route="route('appointments.update', $appointment->id)" :appointment="$appointment" :specialties="$specialties" :availableTimeSlots="$availableTimeSlots"
                :dates="$available_dates" />
        </div>
    </div>
</x-app-layout>
