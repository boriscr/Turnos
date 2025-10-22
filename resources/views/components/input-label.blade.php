@props(['value', 'required' => false, 'icon' => ''])

<label {{ $attributes->merge(['class' => 'block font-medium']) }}>
    @if ($required)
    <span aria-hidden="true" style="color: red;">*</span>
    @endif
    <i class="bi bi-{{ $icon??'person' }}"></i>
    {{ $value ?? $slot }}
</label>
