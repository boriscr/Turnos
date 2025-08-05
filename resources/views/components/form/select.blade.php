@props(['name', 'label', 'icon', 'required' => false])
<div class="item">
    @if (!isset($icon))
        <x-input-label :for="$name" :value="$label" :required="$required" />
    @else
        <label for="{{ $name }}"><i class="bi {{ $icon }}"></i><span>{{ $label }}</span></label>
    @endif
    <select name="{{ $name }}" id="{{ $name }}" class="w-full rounded p-2"
        {{ $required ? 'required' : '' }}>
        {{ $slot }}
    </select>
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
