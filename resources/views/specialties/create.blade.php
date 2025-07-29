<x-app-layout>
    <div class="main full-center">
        <div class="container-form full-center">
            <h3 class="title-form">Crear specialty</h3>

            <x-form.specialty
                :ruta="route('specialty.store')"
                :name="old('name')"
                :description="old('description')"
                :status="old('status')"
            />

        </div>
    </div>
</x-app-layout>