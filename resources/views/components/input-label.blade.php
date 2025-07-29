@props(['value','required'=>false])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700 dark:text-gray-300']) }}>
    @if ($required)
        <span aria-hidden="true" style="color: red;">*</span>
    @endif
    {{ $value ?? $slot }}
</label>
