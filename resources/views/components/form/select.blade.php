@props(['name', 'label', 'required' => false])
<div class="item">
    <x-input-label :for="$name" :value="$label" :required="$required" />
    <select name="{{ $name }}" id="{{ $name }}" class="w-full rounded p-2"
        {{ $required ? 'required' : '' }}>
        {{ $slot }}
    </select>
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
