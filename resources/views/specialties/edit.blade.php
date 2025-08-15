<x-app-layout>
    <div class="main full-center">
        <div class="container-form full-center">
            <h1>Editar datos de la especialidad</h1>
            <x-form.specialty
                :ruta="route('specialty.update', $specialty->id)"
                :edit="true"
                :name="$specialty->name"
                :description="$specialty->description"
                :status="$specialty->status"
            />

        </div>
    </div>
</x-app-layout>