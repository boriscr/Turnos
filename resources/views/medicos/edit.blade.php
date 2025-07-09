<x-app-layout>
    <div class="main">
        <div class="container-form">
            <h3 class="title-form">Editar datos del medico</h3>
            <x-form.medico :ruta="route('medico.update', $medicos->id)" :nuevoMedico="$medicos->user_id" :especialidades="$especialidades" :especialidad="$medicos->$especialidad_id ?? null" :nombre="$medicos->nombre"
                :apellido="$medicos->apellido" :dni="$medicos->dni" :matricula="$medicos->matricula" :email="$medicos->email" :telefono="$medicos->telefono"
                :role="$medicos->role" :estado="$medicos->estado" :especialidad="$medicos->especialidad_id" />

            {{-- Formulario para crear una nueva especialidad --}}
            @if ($medicos->user_id != null)
                <div class="form-especialidad" id="especialidad-form">
                    <x-form.especialidad :ruta="route('especialidad.store')" :crear="false" :nombre="old('nombre')" :descripcion="old('descripcion')" />
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
