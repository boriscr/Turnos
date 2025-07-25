@props(['href'])

@php
    $isActive = request()->url() === $href;
    $classes = $isActive
        ? 'items-nav-active inline-flex items-center px-1 pt-1 border-b-2 leading-5 focus:outline-none transition duration-150 ease-in-out'
        : 'items-nav-inactive inline-flex items-center px-1 pt-1 border-b-2 border-transparent leading-5 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
