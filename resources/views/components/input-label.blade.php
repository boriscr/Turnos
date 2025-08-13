@props(['value', 'required' => false])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm']) }}>
    @if ($required)
        <span aria-hidden="true" style="color: red;">*</span>
    @endif
    {{ $value ?? $slot }}
</label>
