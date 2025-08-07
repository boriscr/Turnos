<x-app-layout>
    <div class="main full-center">
        <div class="container-form full-center">
            <h3 class="title-form">Crear turno</h3>
            <x-form.appointment :route="route('appointments.store')" :create="true" :specialties="$specialties" />
        </div>
    </div>
</x-app-layout>
