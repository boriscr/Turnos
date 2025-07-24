@props(['href'])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'link-nav']) }}>
    {{ $slot }}
</a>