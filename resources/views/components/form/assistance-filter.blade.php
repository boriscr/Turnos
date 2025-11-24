<!-- Filtro de reservations -->
<div class="full-center mb-2">
    <div class="btn-group w-100" role="group">
        <input type="hidden" name="reservation" id="reservaInput" value="{{ request('reservation', 'pending') }}">

        <button type="button" data-value="not_attendance"
            class="btn {{ request('reservation') == 'not_attendance' ? 'btn-active' : 'btn-outline-primary' }} reservation-btn">
            <span>{{ __('button.search.not_attendance') }}</span>
        </button>

        <button type="button" data-value="pending"
            class="btn {{ request('reservation', 'pending') == 'pending' ? 'btn-active' : 'btn-outline-primary' }} reservation-btn">
            <span>{{ __('button.search.pending') }}</span>
        </button>

        <button type="button" data-value="assisted"
            class="btn {{ request('reservation') == 'assisted' ? 'btn-active' : 'btn-outline-primary' }} reservation-btn">
            <span>{{ __('button.search.assisted') }}</span>
        </button>


        <button type="button" data-value="all"
            class="btn {{ request('reservation') == 'all' ? 'btn-active' : 'btn-outline-primary' }} reservation-btn">
            <span>{{ __('button.search.all') }}</span>
        </button>
    </div>
</div>
