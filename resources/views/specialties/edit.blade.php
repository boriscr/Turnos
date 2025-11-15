<x-app-layout>
    <div class="main full-center">
        <div class="container-form full-center">
            <x-form.titles :value="__('medical.titles.section_title_edit')" size="edit-create"/>

            <x-form.specialty :ruta="route('specialties.update', $specialty->id)" :edit="true" :name="$specialty->name" :description="$specialty->description"
                :status="$specialty->status" />
        </div>
    </div>
</x-app-layout>
