@if (auth()->check())
    <nav class="bottom-navigation-bar">
        <ul>
            <li>
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="bi bi-house-fill"></i>
                    <span>{{ __('navbar.home') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('reservations.create') }}"
                    class="{{ request()->routeIs('reservations.create') ? 'active' : '' }}">
                    <i class="bi bi-calendar-plus"></i>
                    <span>{{ __('navbar.appointment') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('myAppointments.index') }}"
                    class="{{ request()->routeIs('myAppointments.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check-fill"></i>
                    <span>{{ __('navbar.my_appointments') }}</span>
                </a>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle">
                    <i class="bi bi-three-dots"></i>
                    <span>Más</span>
                </a>
                <ul class="dropdown-menu">
                    <div class="px-4" style="word-wrap: break-word; overflow-wrap: break-word;">
                        <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                            {{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                    <hr>
                    <li>
                        <a href="{{ route('profile.index') }}"
                            class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                            <i class="bi bi-person-fill"></i>
                            <span>Mi Perfil</span>
                        </a>
                    </li>
                    @role('admin')
                        <li>
                            <a href="{{ route('settings.edit') }}"
                                class="{{ request()->routeIs('settings') ? 'active' : '' }}">
                                <i class="bi bi-gear-fill"></i>
                                <span>Configuración</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.index') }}"
                                class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="bi bi-bar-chart-fill"></i>
                                <span>Panel</span>
                            </a>
                        </li>
                    @endrole
                </ul>
            </li>
        </ul>
    </nav>
@endif
