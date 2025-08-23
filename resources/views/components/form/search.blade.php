<div class="input-group mb-3">
    <input type="text" name="search" class="form-control" placeholder="Buscar por DNI o name..."
        value="{{ request('search') }}">
    <button type="submit" class="secondary-btn full-center">
        <i class="bi bi-search"></i> {{ __('button.search.search') }}
    </button>
    @if (request('search') || request('start_date') || request('end_date') || request('specialty_id') || request('show_all'))
        <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-x-circle"></i> {{ __('button.search.clear') }}
        </a>
    @endif
</div>
