<x-app-layout>
    <div class="main full-center">
        <div class="container-form full-center">
            <h1 class="title-form">{{__('appointment.create_title')}}</h1>
            <x-form.appointment :route="route('appointments.store')" :create="true" :specialties="$specialties" />
        </div>
    </div>
</x-app-layout>
