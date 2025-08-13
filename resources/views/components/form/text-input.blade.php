@props([
    'type' => '',
    'name',
    'id',
    'label' => '',
    'required' => false,
    'minlength' => null,
    'maxlength' => null,
    'placeholder' => '',
    'value' => '',
    'checkedValue' => null,
    'context',
])

<div class="item">
    @if ($type !== 'radio' && $type !== 'color')
        <x-input-label :for="$name" :value="$label" :required="$required" />
        @if (!empty($context))
            <small>{{ $context }}</small>
        @endif
    @endif
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $id ?? $name }}" {{ $checkedValue }}
        minlength="{{ $minlength }}" maxlength="{{ $maxlength }}" placeholder="{{ $placeholder }}"
        @if ($required) required @endif value="{{ old($name, $value) }}">
    @if ($type === 'radio' || $type === 'color')
        <x-input-label :for="$name" :value="$label" />
        @if (isset($context))
            <small>{{ $context }}</small>
        @endif
    @endif
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
