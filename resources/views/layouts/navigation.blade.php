<nav class="main-nav border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="navbar navbar-box h-16 hidden sm:flex">
            <!-- Logo -->
            <div class="shrink-0 flex items-center text-xl font-bold">
                <a href="{{ route('home') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>
            <div class="navbar-items hidden sm:flex justify-between">
                <!-- Navigation Links -->
                <!-- Menú principal -->
                <x-nav-link-active href="{{ route('home') }}" route="home">
                    <i class="bi bi-house"></i><span>{{ __('navbar.home') }}</span>
                </x-nav-link-active>
                <!-- Settings Dropdown -->
                @role('doctor')
                    <div class="hidden sm:flex sm:items-center sm:ms-0">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="menu-btn inline-flex items-center leading-4 font-medium rounded-md focus:outline-none transition ease-in-out duration-150">
                                    <div><i class="bi bi-clipboard-pulse"></i>
                                        <span>{{ __('navbar.administration') }}</span>{{-- Administracion --}}
                                    </div>

                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <!-- Sección Appointments -->
                                <div class="subtitulo"> {{ __('navbar.manage_appointments') }}</div>
                                {{-- Gestionar Appointments --}}
                                <x-dropdown-link :href="route('appointments.index')">
                                    <i class="bi bi-eye"></i> <span>
                                        {{ __('navbar.view_appointments') }}</span>{{-- Ver Appointments --}}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('appointments.create')">
                                    <i class="bi bi-clock"></i> <span>
                                        {{ __('navbar.create_appointment') }}</span>{{-- Crear Appointments --}}
                                </x-dropdown-link>

                                <!-- Sección Reservations -->
                                <div class="subtitulo"> {{ __('navbar.manage_booking') }}</div>{{-- Gestionar Reservations --}}
                                <x-dropdown-link :href="route('reservations.index')">
                                    <i class="bi bi-eye"></i> <span>
                                        {{ __('navbar.view_booking') }}</span>{{-- Ver reservations --}}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endrole
                <!-- Settings Dropdown -->
                @role('admin')
                    <div class="hidden sm:flex sm:items-center sm:ms-0">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="menu-btn inline-flex items-center leading-4 font-medium rounded-md focus:outline-none transition ease-in-out duration-150">
                                    <div><i class="bi bi-clipboard-pulse"></i>
                                        <span>{{ __('navbar.administration') }}</span>{{-- Administracion --}}
                                    </div>

                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="dropdown-section">

                                    <!-- Sección Usuarios -->
                                    <div class="subtitulo">{{ __('navbar.manage_users') }}</div>{{-- Gestionar Usuarios --}}
                                    <x-dropdown-link :href="route('users.index')">
                                        <i class="bi bi-eye"></i> <span> {{ __('navbar.view_users') }}</span>
                                        {{-- Ver users --}}
                                    </x-dropdown-link>

                                    <!-- Sección Doctors -->
                                    <div class="subtitulo">{{ __('navbar.manage_doctors') }}</div>{{-- Gestionar Doctors --}}
                                    <x-dropdown-link :href="route('doctors.index')">
                                        <i class="bi bi-eye"></i><span> {{ __('navbar.view_doctors') }}</span>
                                        {{-- Ver doctors --}}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('doctors.create')">
                                        <i class="bi bi-person-fill-add"></i> <span>
                                            {{ __('navbar.add_doctor') }}</span>{{-- Agregar doctors --}}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('specialties.index')">
                                        <i class="bi bi-heart-pulse-fill"></i> <span>
                                            {{ __('navbar.specialties') }}</span>{{-- Specialties --}}
                                    </x-dropdown-link>

                                    <!-- Sección Appointments -->
                                    <div class="subtitulo"> {{ __('navbar.manage_appointments') }}</div>
                                    {{-- Gestionar Appointments --}}
                                    <x-dropdown-link :href="route('appointments.index')">
                                        <i class="bi bi-eye"></i> <span>
                                            {{ __('navbar.view_appointments') }}</span>{{-- Ver Appointments --}}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('appointments.create')">
                                        <i class="bi bi-clock"></i> <span>
                                            {{ __('navbar.create_appointment') }}</span>{{-- Crear Appointments --}}
                                    </x-dropdown-link>

                                    <!-- Sección Reservations -->
                                    <div class="subtitulo"> {{ __('navbar.manage_booking') }}</div>{{-- Gestionar Reservations --}}
                                    <x-dropdown-link :href="route('reservations.index')">
                                        <i class="bi bi-eye"></i> <span>
                                            {{ __('navbar.view_booking') }}</span>{{-- Ver reservations --}}
                                    </x-dropdown-link>
                                    <!-- Sección Historial -->
                                    <div class="subtitulo"> {{ __('navbar.manage_records') }}</div>{{-- Gestionar Historiales --}}
                                    <x-dropdown-link :href="route('appointmentHistory.index')">
                                        <i class="bi bi-eye"></i> <span>
                                            {{ __('navbar.view_records') }}</span>{{-- Ver historial --}}
                                    </x-dropdown-link>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Settings Dropdown -->
                    <x-nav-link-active href="{{ route('settings.edit') }}" route="settings.edit">
                        <i class="bi bi-gear-fill"></i><span>{{ __('navbar.settings') }}</span>
                    </x-nav-link-active>
                @endrole
            </div>
            @if (auth()->check())
                <div class="hidden sm:flex justify-between">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="menu-btn inline-flex items-center px-3 py-2 leading-4 font-medium rounded-md focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}
                                </div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.index')">
                                <i class="bi bi-person-circle"></i><span>{{ __('Profile') }}</span>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('myAppointments.index')">
                                <i class="bi bi-calendar-check-fill"></i><span>{{ __('myAppointments') }}</span>
                            </x-dropdown-link>
                            @role('admin')
                                <x-dropdown-link :href="route('dashboard.index')" :active="request()->routeIs('dashboard.index')">
                                    <i class="bi bi-bar-chart-fill"></i><span>{{ __('Dashboard') }}</span>
                                </x-dropdown-link>
                            @endrole
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link class="btn-salir" :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    <i class="bi bi-box-arrow-left"></i><span>{{ __('Logout') }}</span>
                                </x-dropdown-link>

                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @else
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-nav-link-active :href="route('login')" route="login">
                        {{ __('Login') }}
                    </x-nav-link-active>
                    <x-nav-link-active :href="route('register')" route="register">
                        {{ __('Register') }}
                    </x-nav-link-active>
                </div>
            @endif
        </div>
    </div>
</nav>