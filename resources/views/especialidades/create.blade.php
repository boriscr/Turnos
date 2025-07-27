<x-app-layout>
    <div class="main centrado-total">
        <div class="container-form centrado-total">
            <h3 class="title-form">Crear especialidad</h3>

            <x-form.especialidad
                :ruta="route('especialidad.store')"
                :nombre="old('nombre')"
                :descripcion="old('descripcion')"
                :isActive="old('isActive')"
            />

        </div>
    </div>
</x-app-layout>