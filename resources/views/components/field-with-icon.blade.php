@props(['icon', 'label', 'value','link' => null])

<p>
    <i class="bi bi-{{ $icon }} me-2"></i>
    <b>{{ $label }}: </b>{{ $value }} @if($link) <a href="{{ $link }}"><i class="bi bi-eye">{{ __('button.view') }}</i></a> @endif
</p>