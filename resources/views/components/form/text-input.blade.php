@props([
    'type' => '',
    'name',
    'label' => '',
    'required' => false,
    'minlength' => null,
    'maxlength' => null,
    'placeholder' => '',
    'value' => '',
])

<div class="item">
    <x-input-label :for="$name" :value="$label" :required="$required" />
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
        minlength="{{ $minlength }}" maxlength="{{ $maxlength }}" placeholder="{{ $placeholder }}"
        @if ($required) required @endif value="{{ old($name, $value) }}">
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>