@props(['href', 'icon', 'label', 'active' => false])
<a href="{{ $href }}" class="dropdown-item {{ $active ? 'active' : '' }}">
    <i class="bi bi-{{ $icon }}"></i>
    <span>{{ $label }}</span>
</a>
