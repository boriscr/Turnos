<!-- resources/views/components/dashboard/sidebar.blade.php -->
<nav class="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <i class="fas fa-chart-line logo-icon"></i>
            <span class="logo-text">Dashboard</span>
        </div>
        <button class="toggle-btn">
            <i class="bi bi-chevron-left"></i> </button>
    </div>

    <ul class="nav-links">
        <li>
            <a href="{{ route('dashboard.index') }}"
                class="nav-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                <i class="bi bi-house-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li>
            <a href="{{ route('dashboard.analytics') }}"
                class="nav-link {{ request()->routeIs('dashboard.analytics') ? 'active' : '' }}">
                <i class="bi bi-pie-chart-fill"></i>
                <span>Analytics</span>
            </a>
        </li>

        <li>
            <a href="{{ route('dashboard.users') }}"
                class="nav-link {{ request()->routeIs('dashboard.users') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span>Users</span>
            </a>
        </li>

        <!-- Dropdown Settings -->
        <li>
            <div
                class="dropdown {{ request()->routeIs('dashboard.design') || request()->routeIs('dashboard.general') || request()->routeIs('dashboard.shifts') ? 'active' : '' }}">
                <button class="dropdown-btn">
                    <i class="bi bi-gear-fill"></i>
                    <span>Settings</span>
                    <i class="bi bi-chevron-compact-down"></i>
                </button>
                <div class="dropdown-content">
                    <a href="{{ route('dashboard.general') }}"
                        class="dropdown-item {{ request()->routeIs('dashboard.general') ? 'active' : '' }}">
                        <i class="bi bi-display-fill"></i>
                        <span>General</span>
                    </a>
                    <a href="{{ route('dashboard.design') }}"
                        class="dropdown-item {{ request()->routeIs('dashboard.design') ? 'active' : '' }}">
                        <i class="bi bi-palette-fill"></i>
                        <span>Diseño</span>
                    </a>
                    <a href="{{ route('dashboard.appointment') }}"
                        class="dropdown-item {{ request()->routeIs('dashboard.appointment') ? 'active' : '' }}">
                        <i class="bi bi-calendar3-fill"></i>
                        <span>Turnos</span>
                    </a>
                    <a href="{{ route('dashboard.privacy') }}"
                        class="dropdown-item {{ request()->routeIs('dashboard.privacy') ? 'active' : '' }}">
                        <i class="bi bi-shield-fill"></i>
                        <span>Privacidad</span>
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
            <span>Dashboard</span>
        </div>
        <button class="mobile-toggle">
            <i class="bi bi-list"></i> </button>
    </div>
    <ul class="mobile-nav-links">
        <li><a href="{{ route('dashboard.index') }}"><i class="bi bi-house-fill"></i> Dashboard</a></li>
        <li><a href="{{ route('dashboard.analytics') }}"><i class="bi bi-pie-chart-fill"></i> Analytics</a></li>
        <li><a href="{{ route('dashboard.users') }}"><i class="bi bi-people-fill"></i> Users</a></li>
        <li><a href="{{ route('dashboard.general') }}"><i class="bi bi-display-fill"></i> General</a></li>
        <li><a href="{{ route('dashboard.design') }}"><i class="bi bi-palette-fill"></i> Diseño</a></li>
        <li><a href="{{ route('dashboard.appointment') }}"><i class="bi bi-calendar3-fill"></i> Turnos</a></li>
    </ul>
</nav>
