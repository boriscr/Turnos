@props([
    'type' => '',
    'icon' => '',
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
    @if ($type !== 'radio' && $type !== 'color' && $type !== 'checkbox')
        <x-input-label :for="$name" :icon="$icon" :value="$label" :required="$required" />
        @if (!empty($context))
            <small>{{ $context }}</small>
        @endif
    @endif
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $id ?? $name }}" {{ $checkedValue }}
        @if ($maxlength !== null && $minlength !== null) minlength="{{ $minlength }}" maxlength="{{ $maxlength }}" @endif
        @if ($placeholder !== '') placeholder="{{ $placeholder }}" @endif
        @if ($required) required @endif value="{{ old($name, $value) }}">

    @if ($type === 'radio' || $type === 'color' || $type === 'checkbox')
        <x-input-label :for="$name" :value="$label" />
        @if (isset($context))
            <small>{{ $context }}</small>
        @endif
    @endif
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
