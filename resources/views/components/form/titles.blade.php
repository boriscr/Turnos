@if (isset($size))
    @if ($size == 'index')
        <h2>{{ $value }}</h2>
    @elseif ($size == 'show')
        <h3>{{ $value }}</h3>
    @elseif ($size == 'edit-create')
        <h3>{{ $value }}</h3>
    @endif
@else
    @if (isset($type))
        @if ($type == 'subtitle')
            <h4>{{ $value }}</h4>
        @endif

    @endif
@endif
