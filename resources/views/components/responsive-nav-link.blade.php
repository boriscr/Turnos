@props(['active'])

@php
$classes = ($active ?? false)
            ? 'dropdown-item block w-full ps-3 pe-4 py-2 border-l-4 transition duration-150 ease-in-out'
            : 'dropdown-item block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
