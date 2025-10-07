<div class="search-bar-group mb-3">
    <div class="search-wrapper">
        <input type="text" name="search" class="form-control"
            maxlength="8"
            minlength="7"
            placeholder="{{ __('button.search.placeholder') }}"
            value="{{ request('search') }}">
        <button type="submit">
            <i class="bi bi-search"></i>
        </button>
    </div>

    @if (request('search') || request('start_date') || request('end_date') || request('specialty_id') || request('show_all'))
        <a href="{{ route('reservations.index') }}" class="clear-btn">
            <i class="bi bi-x"></i>
        </a>
    @endif
</div>