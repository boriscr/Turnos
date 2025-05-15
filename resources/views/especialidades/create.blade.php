<x-body.body>
    <div class="main">
        <div class="container-form">
            <h3 class="title-form">Crear especialidad</h3>

            <x-form.especialidad
                :ruta="route('especialidad.store')"
                :nombre="old('nombre')"
                :descripcion="old('descripcion')"
                :estado="old('estado')"
            />

        </div>
    </div>
</x-body.body>