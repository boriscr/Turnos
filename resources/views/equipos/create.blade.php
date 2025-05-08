<x-body.body>
    <div class="main">
        <div class="container-form">
            <h3 class="title-form">Agregar nuevo equipo</h3>
            <x-form.equipo 
            :ruta="route('equipo.store')"
            :crear="true"
            :especialidades="$especialidades"
            :nombre="old('nombre')"
            :apellido="old('apellido')"
            :dni="old('dni')"
            :matricula="old('matricula')"
            :email="old('email')"
            :telefono="old('telefono')"
            :rol="old('rol')"
            :estado="old('estado')"
            :especialidad="old('especialidad')"
            />
            <div class="form-especialidad" id="especialidad-form">
                <x-form.especialidad />
            </div>
        </div>
</x-body.body>
