@props([
    'name',
    'label' => '',
    'required' => false,
    'minlength' => null,
    'maxlength' => null,
    'placeholder' => '',
    'rows' => 5,
    'value' => '',
])

<div class="item">
    <x-input-label :for="$name" :value="$label" :required="$required" />
    <textarea name="{{ $name }}" id="{{ $name }}" rows="{{ $rows }}" minlength="{{ $minlength }}"
        maxlength="{{ $maxlength }}" placeholder="{{ $placeholder }}" @if ($required) required @endif
        >{{ old($name, $value) }}</textarea>

    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
