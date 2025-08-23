<!-- Filtro de reservations -->
<div class="full-center mb-2">
    <div class="btn-group w-100" role="group">
        <input type="hidden" name="reservation" id="reservaInput" value="{{ request('reservation', 'pending') }}">

        <button type="button" data-value="not_attendance"
            class="btn {{ request('reservation') == 'not_attendance' ? 'btn-active' : 'btn-outline-primary' }} reservation-btn">
            {{ __('button.search.not_attendance') }}
        </button>

        <button type="button" data-value="pending"
            class="btn {{ request('reservation', 'pending') == 'pending' ? 'btn-active' : 'btn-outline-primary' }} reservation-btn">
            {{ __('button.search.pending') }}
        </button>

        <button type="button" data-value="assisted"
            class="btn {{ request('reservation') == 'assisted' ? 'btn-active' : 'btn-outline-primary' }} reservation-btn">
            {{ __('button.search.assisted') }}
        </button>


        <button type="button" data-value="all"
            class="btn {{ request('reservation') == 'all' ? 'btn-active' : 'btn-outline-primary' }} reservation-btn">
            {{ __('button.search.all') }}
        </button>
    </div>
</div>
