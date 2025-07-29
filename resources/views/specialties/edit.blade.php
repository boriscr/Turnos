<x-app-layout>
    <div class="main full-center">
        <div class="container-form full-center">
            <h3 class="title-form">Editar datos de la specialty</h3>
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