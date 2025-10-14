@if (isset($indexRoute))
    <div class="search-bar-group mb-3">
        <div class="search-wrapper">
            <input type="text" name="search" class="form-control"
                placeholder="{{ $placeholder ?? __('button.search.placeholderId') }}" value="{{ request('search') }}">
            <button type="submit">
                <i class="bi bi-search"></i>
            </button>
        </div>

        @if (request('search') || request('start_date') || request('end_date') || request('specialty_id') || request('show_all'))
            <a href="{{ route($indexRoute . '.index') }}" class="clear-btn">
                <i class="bi bi-x"></i>
            </a>
        @endif
    </div>
@else
    <div class="filter-box">
        <form action="{{ route($resource . '.search') }}" method="GET" class="rounded" id="filterForm">
            <div class="search-bar-group mb-3">
                <div class="search-wrapper">
                    <input type="text" name="search" class="form-control"
                        placeholder="{{ $placeholder ?? __('button.search.placeholderId') }}" value="{{ request('search') }}">
                    <button type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>

                @if (request('search') || request('start_date') || request('end_date') || request('specialty_id') || request('show_all'))
                    <a href="{{ route($resource . 's.index') }}" class="clear-btn">
                        <i class="bi bi-x"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
@endif
