<x-app-layout>
    <div class="main full-center">
        <div class="container-form full-center">
            <h1 class="title-form">{{__('appointment.edit_title')}}</h1>
            <x-form.appointment :route="route('appointments.update', $appointment->id)" :appointment="$appointment" :specialties="$specialties" :availableTimeSlots="$availableTimeSlots"
                :fechas="$fechas" />
        </div>
    </div>
</x-app-layout>
