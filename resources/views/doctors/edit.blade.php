<x-app-layout>
    <div class="main full-center">
        <div class="container-form full-center">
            <h3 class="title-form">{{ __('medical.section_title_edit') }}</h3>
            <x-form.doctor :ruta="route('doctor.update', $doctors->id)" :nuevoMedico="$doctors->user_id" :specialties="$specialties" :specialty="$doctors->$specialty_id ?? null" :name="$doctors->name"
                :surname="$doctors->surname" :idNumber="$doctors->idNumber" :licenseNumber="$doctors->licenseNumber" :email="$doctors->email" :phone="$doctors->phone"
                :role="$doctors->role" :status="$doctors->status" :specialty="$doctors->specialty_id" />

            {{-- Formulario para crear una nueva specialty --}}
            <div class="form-specialty" id="specialty-form">
                <x-form.specialty :nuevoMedico="isset($nuevoMedico) ? true : false" :ruta="route('specialty.store')" :name="old('name')" :description="old('description')" />
            </div>
        </div>
    </div>
</x-app-layout>
