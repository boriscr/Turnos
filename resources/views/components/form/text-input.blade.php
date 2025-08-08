@props([
    'type' => '',
    'name',
    'label' => '',
    'required' => false,
    'minlength' => null,
    'maxlength' => null,
    'placeholder' => '',
    'value' => '',
    'checkedValue' => null,
])

<div class="item">
    @if (!isset($checkedValue))
        <x-input-label :for="$name" :value="$label" :required="$required" />
    @endif
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" {{ $checkedValue }}
        minlength="{{ $minlength }}" maxlength="{{ $maxlength }}" placeholder="{{ $placeholder }}"
        @if ($required) required @endif value="{{ old($name, $value) }}">
    @if (isset($checkedValue))
        <x-input-label :for="$name" :value="$label" />
    @endif
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
