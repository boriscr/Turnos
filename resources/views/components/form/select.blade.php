@props(['name', 'label', 'icon'=>true, 'required' => false])
<div class="item">
    <x-input-label :for="$name" :icon="$icon" :value="$label" :required="$required" />
    <select name="{{ $name }}" id="{{ $name }}" class="w-full rounded" {{ $required ? 'required' : '' }}>
        {{ $slot }}
    </select>
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
