<x-body.body>
    <div class="main">
        <div class="container-form">
            <h3 class="title-form">Editar datos de la especialidad</h3>
            <x-form.especialidad
                :ruta="route('especialidad.update', $especialidad->id)"
                :edit="true"
                :nombre="$especialidad->nombre"
                :descripcion="$especialidad->descripcion"
                :estado="$especialidad->estado"
            />

        </div>
    </div>
</x-body.body>