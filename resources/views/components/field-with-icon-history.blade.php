@props(['icon' => null, 'label' => null, 'value' => null, 'link' => null])

<p class="history-paragraph">
    <b> <i class="bi bi-{{ $icon }} me-2"></i>
        {{ $label }}: </b>
    {{ $value }}
    @if ($link)
        <a href="{{ $link }}"><i class="bi bi-eye">{{ __('button.view') }}</i></a>
    @endif
</p>
