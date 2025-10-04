@props(['icon' => null, 'label' => null, 'value' => null, 'link' => null])

<p>
    @if ($icon)
        <i class="bi bi-{{ $icon }} me-2"></i>
    @endif
    @if ($label)
        <b>{{ $label }}: </b>
    @endif
    {{ $value }}
    @if ($link)
        <a href="{{ $link }}"><i class="bi bi-eye">{{ __('button.view') }}</i></a>
    @endif
</p>
