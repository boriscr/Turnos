<nav x-data="{ open: false }" class="main-nav border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="navbar navbar-box h-16 sm:flex">
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

                <x-nav-link-active href="{{ route('reservations.create') }}" route="reservations.create">
                    <i
                        class="bi bi-calendar-plus"></i><span>{{ __('navbar.book_appointment') }}</span>{{-- Reservar Appointment --}}
                </x-nav-link-active>

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
                                    <x-dropdown-link :href="route('user.index')">
                                        <i class="bi bi-eye"></i> <span> {{ __('navbar.view_users') }}</span>
                                        {{-- Ver users --}}
                                    </x-dropdown-link>

                                    <!-- Sección Doctors -->
                                    <div class="subtitulo">{{ __('navbar.manage_doctors') }}</div>{{-- Gestionar Doctors --}}
                                    <x-dropdown-link :href="route('doctor.index')">
                                        <i class="bi bi-eye"></i><span> {{ __('navbar.view_doctors') }}</span>
                                        {{-- Ver doctors --}}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('doctor.create')">
                                        <i class="bi bi-person-fill-add"></i> <span>
                                            {{ __('navbar.add_doctor') }}</span>{{-- Agregar doctors --}}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('specialty.index')">
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
                            <x-dropdown-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                <i class="bi bi-speedometer2"></i><span>{{ __('Dashboard') }}</span>
                            </x-dropdown-link>
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-primary-button class="btn-salir">
                                    <i class="bi bi-box-arrow-left"></i><span>{{ __('logout') }}</span>
                                </x-primary-button>
                                {{--
                                <x-dropdown-link class="btn-salir" :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    <i class="bi bi-box-arrow-left"></i><span>{{ __('Logout') }}</span>
                                </x-dropdown-link>
                                --}}
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
            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="hamburger inline-flex items-center justify-center p-2 rounded-md transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>


    <!-- Alpine Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                <i class="bi bi-house"></i><span>{{ __('navbar.home') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('reservations.create')" :active="request()->routeIs('reservations.create')">
                <i
                    class="bi bi-calendar-plus"></i><span>{{ __('navbar.book_appointment') }}</span>{{-- Reservar Appointment --}}
            </x-responsive-nav-link>

            @role('admin')
                <div x-data="{ adminOpen: false }" class="space-y-1">
                    <button @click="adminOpen = !adminOpen" class="w-full text-left px-4 py-2">
                        <div class="flex items-center justify-between">
                            <div>
                                <i class="bi bi-clipboard-pulse"></i>
                                <span>{{ __('navbar.administration') }}</span>{{-- Administracion --}}

                            </div>
                            <svg class="h-4 w-4 transform transition-transform" :class="{ 'rotate-180': adminOpen }"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>

                    <div x-show="adminOpen" x-collapse class="space-y-1 pl-4">
                        <!-- Versión móvil del dropdown -->
                        <div class="menu pt-2 pb-2 space-y-1">
                            <!-- Sección Usuarios -->
                            <div class="subtitulo">{{ __('navbar.manage_users') }}</div>{{-- Gestionar Usuarios --}}
                            <x-responsive-nav-link :href="route('user.index')">
                                <i class="bi bi-eye"></i> <span> {{ __('navbar.view_users') }}</span>
                                {{-- Ver users --}}
                            </x-responsive-nav-link>

                            <!-- Sección Doctor -->
                            <div class="subtitulo">{{ __('navbar.manage_doctors') }}</div>{{-- Gestionar Doctors --}}
                            <x-responsive-nav-link :href="route('doctor.index')">
                                <i class="bi bi-eye"></i><span> {{ __('navbar.view_doctors') }}</span>
                                {{-- Ver doctors --}}
                            </x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('doctor.create')">
                                <i class="bi bi-person-fill-add"></i> <span>
                                    {{ __('navbar.add_doctor') }}</span>{{-- Agregar doctors --}}
                            </x-responsive-nav-link>

                            <x-responsive-nav-link :href="route('specialty.index')">
                                <i class="bi bi-heart-pulse-fill"></i> <span>
                                    {{ __('navbar.specialties') }}</span>{{-- Specialties --}}
                            </x-responsive-nav-link>

                            <!-- Sección Appointments -->
                            <div class="subtitulo"> {{ __('navbar.manage_appointments') }}</div>{{-- Gestionar Appointments --}}
                            <x-responsive-nav-link :href="route('appointments.index')">
                                <i class="bi bi-eye"></i> <span>
                                    {{ __('navbar.view_appointments') }}</span>{{-- Ver Appointments --}}
                            </x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('appointments.create')">
                                <i class="bi bi-clock"></i> <span>
                                    {{ __('navbar.create_appointment') }}</span>{{-- Crear Appointments --}}
                            </x-responsive-nav-link>

                            <!-- Sección Reservations -->
                            <div class="subtitulo"> {{ __('navbar.manage_booking') }}</div>{{-- Gestionar Reservations --}}
                            <x-responsive-nav-link :href="route('reservations.index')">
                                <i class="bi bi-eye"></i> <span>
                                    {{ __('navbar.view_booking') }}</span>{{-- Ver reservations --}}
                            </x-responsive-nav-link>
                        </div>

                    </div>
                    <x-responsive-nav-link :href="route('settings.edit')">
                        <i class="bi bi-gear-fill"></i><span>{{ __('navbar.settings') }}</span>
                    </x-responsive-nav-link>
                @endrole

                <!-- Responsive Settings Options -->
                @if (auth()->check())
                    <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                        <div class="px-4" style="word-wrap: break-word; overflow-wrap: break-word;">
                            <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                                {{ Auth::user()->name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <x-responsive-nav-link :href="route('profile.index')" :active="request()->routeIs('profile.index')">
                                <i class="bi bi-person-circle"></i><span>{{ __('Profile') }}</span>
                            </x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('myAppointments.index')" :active="request()->routeIs('myAppointments.index')">
                                <i class="bi bi-calendar-check-fill"></i><span>{{ __('myAppointments') }}</span>
                            </x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                <i class="bi bi-speedometer2"></i><span>{{ __('Dashboard') }}</span>
                            </x-responsive-nav-link>
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-primary-button class="btn-salir">
                                    <i class="bi bi-box-arrow-left"></i><span>{{ __('logout') }}</span>
                                </x-primary-button>
                                {{--
                                <x-responsive-nav-link class="btn-salir" :href="route('logout')"
                                    onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                    <i class="bi bi-box-arrow-left"></i><span>{{ __('Logout') }}</span>
                                </x-responsive-nav-link>
                                --}}
                            </form>
                        </div>
                    </div>
                @else
                    <div class="px-4">
                        <div class="font-medium text-base">Inicie sesion o cree una
                            cuenta.
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('login')">
                            {{ __('Login') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('register')">
                            {{ __('Register') }}
                        </x-responsive-nav-link>
                @endif
            </div>
</nav>
