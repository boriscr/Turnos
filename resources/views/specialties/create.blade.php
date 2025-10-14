<x-app-layout>
    <div class="main full-center">
        <div class="container-form full-center">
            <h1>{{ __('medical.titles.section_title_add') }}</h1>

            <x-form.specialty
                :ruta="route('specialties.store')"
                :name="old('name')"
                :description="old('description')"
                :status="old('status')"
            />

        </div>
    </div>
</x-app-layout>