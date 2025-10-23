<!-- resources/views/components/dashboard/sidebar.blade.php -->
<nav class="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <i class="fas fa-chart-line logo-icon"></i>
            <span class="logo-text">{{ __('dashboard.dashboard') }}</span>
        </div>
        <button class="toggle-btn">
            <i class="bi bi-chevron-left"></i>
        </button>
    </div>

    <ul class="nav-links">
        <li>
            <a href="{{ route('dashboard.index') }}"
                class="nav-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                <i class="bi bi-house-fill"></i>
                <span>{{ __('dashboard.dashboard') }}</span>
            </a>
        </li>

        <li>
            <a href="#"
                class="nav-link {{ request()->routeIs('dashboard.analytics') ? 'active' : '' }} feature-style">
                <i class="bi bi-pie-chart-fill"></i>
                <span>{{ __('dashboard.analytics') }}</span>
                <div class="feature">
                    {{ __('medical.feature') }}
                </div>
            </a>
        </li>

        <li>
            <a href="#"
                class="nav-link {{ request()->routeIs('dashboard.users') ? 'active' : '' }} feature-style">
                <i class="bi bi-people-fill"></i>
                <span>{{ __('dashboard.users') }}</span>
                <div class="feature">
                    {{ __('medical.feature') }}
                </div>
            </a>
        </li>

        <!-- Dropdown Settings -->
        <li>
            @php
                $isSettingsActive =
                    request()->routeIs('dashboard.general') ||
                    request()->routeIs('dashboard.design') ||
                    request()->routeIs('dashboard.appointment') ||
                    request()->routeIs('dashboard.privacy');
            @endphp

            <div class="dropdown {{ $isSettingsActive ? 'active' : '' }}">
                <button class="dropdown-btn {{ $isSettingsActive ? 'active' : '' }}">
                    <i class="bi bi-gear-fill"></i>
                    <span>{{ __('dashboard.settings') }}</span>
                    <i class="bi bi-chevron-compact-down"></i>
                </button>
                <div class="dropdown-content {{ $isSettingsActive ? 'active' : '' }}">
                    <a href="{{ route('dashboard.general') }}"
                        class="dropdown-item {{ request()->routeIs('dashboard.general') ? 'active' : '' }}">
                        <i class="bi bi-display-fill"></i>
                        <span>{{ __('dashboard.general') }}</span>
                    </a>
                    <a href="{{ route('dashboard.design') }}"
                        class="dropdown-item {{ request()->routeIs('dashboard.design') ? 'active' : '' }}">
                        <i class="bi bi-palette-fill"></i>
                        <span>{{ __('dashboard.design') }}</span>
                    </a>
                    <a href="{{ route('dashboard.appointment') }}"
                        class="dropdown-item {{ request()->routeIs('dashboard.appointment') ? 'active' : '' }}">
                        <i class="bi bi-calendar3-fill"></i>
                        <span>{{ __('dashboard.appointment') }}</span>
                    </a>
                    <a href="#"
                        class="dropdown-item {{ request()->routeIs('dashboard.privacy') ? 'active' : '' }} feature-style">
                        <i class="bi bi-shield-fill"></i>
                        <span>{{ __('dashboard.privacy') }}</span>
                        <div class="feature">
                            {{ __('medical.feature') }}
                        </div>
                    </a>
                </div>
            </div>
        </li>
    </ul>
</nav>

<!-- Mobile Nav -->
<nav class="mobile-nav">
    <div class="mobile-nav-header">
        <div class="mobile-logo">
            <i class="fas fa-chart-line"></i>
            <span>{{ __('dashboard.dashboard') }}</span>
        </div>
        <button class="mobile-toggle">
            <i class="bi bi-list"></i>
        </button>
    </div>
    <ul class="mobile-nav-links">
        <li><a href="{{ route('dashboard.index') }}"
                class="{{ request()->routeIs('dashboard.index') ? 'active' : '' }}"><i class="bi bi-house-fill"></i>
                {{ __('dashboard.dashboard') }}</a></li>
        <li><a href="#" class="{{ request()->routeIs('dashboard.analytics') ? 'active' : '' }} feature-style"><i
                    class="bi bi-pie-chart-fill"></i> {{ __('dashboard.analytics') }}
            </a>
        </li>

        <li><a href="#" class="{{ request()->routeIs('dashboard.users') ? 'active' : '' }} feature-style"><i
                    class="bi bi-people-fill"></i>
                {{ __('dashboard.users') }}</a></li>
        <li><a href="{{ route('dashboard.general') }}"
                class="{{ request()->routeIs('dashboard.general') ? 'active' : '' }}"><i
                    class="bi bi-display-fill"></i> {{ __('dashboard.general') }}</a></li>
        <li><a href="{{ route('dashboard.design') }}"
                class="{{ request()->routeIs('dashboard.design') ? 'active' : '' }}"><i class="bi bi-palette-fill"></i>
                Diseño</a></li>
        <li><a href="{{ route('dashboard.appointment') }}"
                class="{{ request()->routeIs('dashboard.appointment') ? 'active' : '' }}"><i
                    class="bi bi-calendar3-fill"></i> {{ __('dashboard.appointment') }}</a></li>
        <li><a href="#" class="{{ request()->routeIs('dashboard.appointment') ? 'active' : '' }} feature-style">
                <i class="bi bi-shield-fill"></i>
                {{ __('dashboard.privacy') }}</a></li>
    </ul>
</nav>
