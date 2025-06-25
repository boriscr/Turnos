<x-body.body>
    <div class="main">
        <div class="container-form">
            <h3 class="title-form">Agregar nuevo medico</h3>
            <x-form.medico :ruta="route('medico.store')" :crear="true" :especialidades="$especialidades" :nombre="old('nombre')" :apellido="old('apellido')"
                :dni="old('dni')" :matricula="old('matricula')" :email="old('email')" :telefono="old('telefono')" :role="old('role')"
                :estado="old('estado')" :especialidad="old('especialidad')" />

            {{-- Formulario para crear una nueva especialidad --}}
            <div class="form-especialidad" id="especialidad-form">
                <x-form.especialidad :ruta="route('especialidad.store')" 
                :crear="true" :nombre="old('nombre')" 
                :descripcion="old('descripcion')" />
            </div>
        </div>
</x-body.body>
