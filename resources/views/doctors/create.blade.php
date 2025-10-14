<x-app-layout>
    <div class="main full-center">
        <div class="container-form full-center">
            <h1>{{ __('medical.titles.section_title_add') }}</h1>
            <x-form.doctor :ruta="route('doctors.store')" :crear="true" :specialties="$specialties" :name="old('name')" :surname="old('surname')"
                :idNumber="old('idNumber')" :licenseNumber="old('licenseNumber')" :email="old('email')" :phone="old('phone')" :role="old('role')"
                :status="old('status')" :specialty="old('specialty')" />

            {{-- Formulario para crear una nueva specialty --}}
            <div class="form-specialty" id="specialty-form">
                <x-form.specialty :ruta="route('specialty.store')" :crear="true" :name="old('name')" :description="old('description')" />
            </div>
        </div>
</x-app-layout>
