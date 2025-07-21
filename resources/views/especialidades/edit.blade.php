<x-app-layout>
    <div class="main centrado-total">
        <div class="container-form centrado-total">
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
</x-app-layout>