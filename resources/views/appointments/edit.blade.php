<x-app-layout>
    <div class="main full-center">
        <div class="container-form full-center">
            <h3 class="title-form">Editar appointment</h3>
            <x-form.appointment :route="route('appointments.update', $appointment->id)" :appointment="$appointment" :specialties="$specialties" :availableTimeSlots="$availableTimeSlots"
                :fechas="$fechas" />
        </div>
    </div>
</x-app-layout>
