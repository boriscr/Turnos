<x-app-layout>
    <div class="main full-center">
        <div class="container-form full-center">
            <h1>{{ __('medical.titles.section_title_edit') }}</h1>
            <x-form.specialty :ruta="route('specialties.update', $specialty->id)" :edit="true" :name="$specialty->name" :description="$specialty->description"
                :status="$specialty->status" />
        </div>
    </div>
</x-app-layout>
