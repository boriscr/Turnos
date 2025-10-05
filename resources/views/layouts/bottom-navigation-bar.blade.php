<nav class="bottom-navigation-bar">
    <ul>
        <li>
            <a href="{{ route('home') }}">
                <i class="bi bi-house-fill"></i>
                <span>{{ __('navbar.home') }}</span>
            </a>
        </li>
        <li class="appointment-option">
            <a href="{{ route('reservations.create') }}">
                <i class="bi bi-calendar-plus"></i>
                <span>{{ __('navbar.appointment') }}</span>
            </a>
        </li>
        <li>
            <a href="{{ route('myAppointments.index') }}">
                <i class="bi bi-calendar-check-fill"></i>
                <span>{{ __('navbar.my_appointments') }}</span>
            </a>
        </li>
    </ul>
</nav>
