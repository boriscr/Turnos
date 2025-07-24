<nav x-data="{ open: false }" class="main-nav border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="navbar h-16">
            <!-- Logo -->
            <div class="shrink-0 flex items-center text-xl font-bold">
                <a href="{{ route('home') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>
            <div class="navbar-items hidden sm:flex justify-between">
                <!-- Navigation Links -->
                <!-- Menú principal -->
                <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    {{ __('home') }}
                </x-nav-link>
                <x-nav-link :href="route('disponible.create')" :active="request()->routeIs('disponible.create')">
                    {{ __('Reservar Turno') }}
                </x-nav-link>

                <!-- Settings Dropdown -->
                @role('admin')
                    <div class="hidden sm:flex sm:items-center sm:ms-0">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="menu-btn inline-flex items-center leading-4 font-medium rounded-md focus:outline-none transition ease-in-out duration-150">
                                    <div>Administración
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
                                    <div class="subtitulo">Sección Usuarios</div>
                                    <x-dropdown-link :href="route('usuario.index')">
                                        <i class="bi bi-eye"></i> Ver usuarios
                                    </x-dropdown-link>
                                    <div class="subtitulo">Sección Medicos</div>
                                    <x-dropdown-link :href="route('medico.index')">
                                        <i class="bi bi-eye"></i> Ver medicos
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('medico.create')">
                                        <i class="bi bi-person-fill-add"></i> Agregar medicos
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('especialidad.index')">
                                        <i class="bi bi-heart-pulse-fill"></i> Especialidades
                                    </x-dropdown-link>
                                    <div class="subtitulo">Sección Turnos</div>
                                    <x-dropdown-link :href="route('turnos.index')">
                                        <i class="bi bi-eye"></i> Ver Turnos
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('turnos.create')">
                                        <i class="bi bi-clock"></i> Crear Turnos
                                    </x-dropdown-link>
                                    <div class="subtitulo">Sección Reservas</div>
                                    <x-dropdown-link :href="route('reservas.index')">
                                        <i class="bi bi-eye"></i> Ver reservas
                                    </x-dropdown-link>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    <x-nav-link :href="route('settings.edit')" :active="request()->routeIs('settings.edit')">
                        {{ __('Settings') }}
                    </x-nav-link>
                @endrole


                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Panel') }}
                </x-nav-link>
            </div>
            <!-- Settings Dropdown -->
            @if (auth()->check())
                <div class="hidden sm:flex sm:items-center sm:ms-6">
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
                                {{ __('Perfil') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link class="btn-salir" :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Salir') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @else
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-nav-link :href="route('login')">
                        {{ __('Iniciar sesión') }}
                    </x-nav-link>
                    <x-nav-link :href="route('register')">
                        {{ __('Registrarse') }}
                    </x-nav-link>
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




    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('disponible.create')" :active="request()->routeIs('disponible.create')">
                {{ __('Reservar Turno') }}
            </x-responsive-nav-link>

            @role('admin')
                <div x-data="{ adminOpen: false }" class="space-y-1">
                    <button @click="adminOpen = !adminOpen" class="w-full text-left px-4 py-2">
                        <div class="flex items-center justify-between">
                            <span>Administración</span>
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
                            <div class="subtitulo">Sección Usuarios</div>
                            <x-responsive-nav-link :href="route('usuario.index')">
                                <i class="bi bi-eye"></i> Ver usuarios
                            </x-responsive-nav-link>

                            <!-- Sección Medico -->
                            <div class="subtitulo">Sección Medicos</div>
                            <x-responsive-nav-link :href="route('medico.index')">
                                <i class="bi bi-eye"></i> Ver medicos
                            </x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('medico.create')">
                                <i class="bi bi-person-fill-add"></i> Agregar medicos
                            </x-responsive-nav-link>

                            <x-responsive-nav-link :href="route('especialidad.index')">
                                <i class="bi bi-heart-pulse-fill"></i> Especialidades
                            </x-responsive-nav-link>

                            <!-- Sección Turnos -->
                            <div class="subtitulo">Sección Turnos</div>
                            <x-responsive-nav-link :href="route('turnos.index')">
                                <i class="bi bi-eye"></i> Ver Turnos
                            </x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('turnos.create')">
                                <i class="bi bi-clock"></i> Crear Turnos
                            </x-responsive-nav-link>

                            <!-- Sección Reservas -->
                            <div class="subtitulo">Sección Reservas</div>
                            <x-responsive-nav-link :href="route('reservas.index')">
                                <i class="bi bi-eye"></i> Ver reservas
                            </x-responsive-nav-link>
                        </div>

                    </div>
                    <x-responsive-nav-link :href="route('settings.edit')">
                        {{ __('Settings') }}
                    </x-responsive-nav-link>
                @endrole
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Panel') }}
                </x-responsive-nav-link>

                <!-- Responsive Settings Options -->
                @if (auth()->check())
                    <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                                {{ Auth::user()->name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <x-responsive-nav-link :href="route('profile.edit')">
                                {{ __('Perfil') }}
                            </x-responsive-nav-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-responsive-nav-link class="btn-salir" :href="route('logout')"
                                    onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                    {{ __('Salir') }}
                                </x-responsive-nav-link>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800 dark:text-gray-200">Inicie sesion o cree una
                            cuenta.
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('login')">
                            {{ __('Iniciar sesión') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('register')">
                            {{ __('Registrarse') }}
                        </x-responsive-nav-link>
                @endif
            </div>
</nav>
