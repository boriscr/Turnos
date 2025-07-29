<x-app-layout>
    <div class="main full-center">
        <div class="container-form full-center">
            <h3 class="title-form">{{__('medical.section_title_edit')}}</h3>
            <x-form.medico :ruta="route('medico.update', $medicos->id)" :nuevoMedico="$medicos->user_id" :specialties="$specialties" :specialty="$medicos->$specialty_id ?? null" :name="$medicos->name"
                :surname="$medicos->surname" :idNumber="$medicos->idNumber" :licenseNumber="$medicos->licenseNumber" :email="$medicos->email" :phone="$medicos->phone"
                :role="$medicos->role" :status="$medicos->status" :specialty="$medicos->specialty_id" />

            {{-- Formulario para crear una nueva specialty --}}
            @if ($medicos->user_id != null)
                <div class="form-specialty" id="specialty-form">
                    <x-form.specialty :ruta="route('specialty.store')" :crear="false" :name="old('name')" :description="old('description')" />
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
