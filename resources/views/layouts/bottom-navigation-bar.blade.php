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
                            <i class="bi bi-person-circle"></i>
                            <span>Mi Perfil</span>
                        </a>
                    </li>
                    @role('admin')
                        <div x-show="adminOpen" x-collapse class="space-y-1 pl-4">
                            <li>
                                <div class="subtitulo">{{ __('navbar.manage_users') }}</div>{{-- Gestionar Usuarios --}}
                                <a href="{{ route('users.index') }}"
                                    class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                                    <i class="bi bi-eye"></i> <span> {{ __('navbar.view_users') }}</span>
                                </a>
                            </li>
                            <!-- Sección Doctor -->
                            <li>
                                <div class="subtitulo">{{ __('navbar.manage_doctors') }}</div>{{-- Gestionar Doctors --}}
                                <a href="{{ route('doctors.index') }}"
                                    class="{{ request()->routeIs('doctors.index') ? 'active' : '' }}">
                                    <i class="bi bi-eye"></i><span> {{ __('navbar.view_doctors') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('doctors.create') }}"
                                    class="{{ request()->routeIs('doctors.create') ? 'active' : '' }}">
                                    <i class="bi bi-person-fill-add"></i><span> {{ __('navbar.add_doctor') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('specialties.index') }}"
                                    class="{{ request()->routeIs('specialties.*') ? 'active' : '' }}">
                                    <i class="bi bi-heart-pulse-fill"></i><span> {{ __('navbar.specialties') }}</span>
                                </a>
                            </li>

                            <!-- Sección Appointments -->
                            <li>
                                <div class="subtitulo"> {{ __('navbar.manage_appointments') }}</div>{{-- Gestionar Appointments --}}
                                <a href="{{ route('appointments.index') }}"
                                    class="{{ request()->routeIs('appointments.index') ? 'active' : '' }}">
                                    <i class="bi bi-eye"></i> <span>
                                        {{ __('navbar.view_appointments') }}</span>{{-- Ver Appointments --}} </a>
                            </li>
                            <li>
                                {{-- Crear Appointments --}}
                                <a href="{{ route('specialties.create') }}"
                                    class="{{ request()->routeIs('specialties.create') ? 'active' : '' }}">
                                    <i class="bi bi-clock"></i><span> {{ __('navbar.create_appointment') }}</span>
                                </a>
                            </li>
                            <li>
                                <!-- Sección Reservations -->
                                <div class="subtitulo"> {{ __('navbar.manage_booking') }}</div>{{-- Ver reservations --}}
                                <a href="{{ route('reservations.index') }}"
                                    class="{{ request()->routeIs('reservations.index') ? 'active' : '' }}">
                                    <i class="bi bi-eye"></i><span> {{ __('navbar.view_booking') }}</span>
                                </a>
                            </li>
                        </div>
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
