<x-app-layout>
    <div class="main full-center">
        <div class="container-form full-center">
            <x-form.titles :value="__('appointment.create_title')" size="edit-create" />
            <x-form.appointment :route="route('appointments.store')" :create="true" :specialties="$specialties" />
        </div>
    </div>
</x-app-layout>
