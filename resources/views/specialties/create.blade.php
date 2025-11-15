<x-app-layout>
    <div class="main full-center">
        <div class="container-form full-center">
            <x-form.titles :value="__('medical.titles.section_title_add')" size="edit-create"/>

            <x-form.specialty :ruta="route('specialties.store')" :name="old('name')" :description="old('description')" :status="old('status')" />

        </div>
    </div>
</x-app-layout>
