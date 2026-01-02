<x-app-layout>
    <div class="content-wrapper">
        <div class="box-container">

            <div class="content-date-profile">
                <div class="profile-container">
                    <label for="profile-photo" class="profile-img-label">
                        <img src="https://www.shutterstock.com/image-vector/user-profile-icon-vector-avatar-600nw-2558760599.jpg"
                            alt="img-profile" class="profile-img">
                        <input type="file" id="profile-photo" class="profile-input" accept="image/*">
                    </label>
                    <div class="profile-id">
                        <p class="profile-name">
                            <span>
                                @switch($user->gender->name)
                                    @case('Male')
                                        {{ __('medical.greetings.welcome') }}
                                    @break

                                    @case('Female')
                                        {{ __('medical.greetings.famale_welcome') }}
                                    @break

                                    @default
                                        {{ __('medical.greetings.hello') }}
                                @endswitch
                            </span>
                            {{ explode(' ', $user->name)[0] }}
                        </p>
                        <small>
                            {{ $user->email }}
                        </small>
                        <div class="profile-edit-btn-container">
                            <x-secondary-button>
                                <a href="{{ route('profile.edit', $user->id) }}">
                                    {{ __('medical.update') }}
                                </a>
                            </x-secondary-button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-date-profile {{ $user->status ? '' : 'status-inactive' }}">
                @if ($user->status)
                    <div class="profile-container full-center">
                        <ul>
                            <li class="item"> <i class="bi bi-check-circle"></i>
                                {{ __('medical.profile.requested_appointmets') }}:
                                {{ $tellHistory }}
                            </li>
                            <li class="item"> <i class="bi bi-hourglass-split"></i>
                                {{ __('medical.profile.pending_appointments') }}:
                                {{ $appointmentsPending }}</li>
                        </ul>
                        <ul>
                            <li class="item"> <i class="bi bi-dash-circle-fill"></i>
                                {{ __('medical.profile.missed_appointments') }}:
                                {{ $appointmentsNotAttendance }}
                            </li>
                            <li class="item"> <i class="bi bi-exclamation-circle-fill"></i>
                                {{ __('medical.profile.faults') }}:
                                {{ $user->faults }}
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="profile-container full-center">
                        <strong>
                            <i class="bi bi-x-circle-fill"></i>
                            {{ __('medical.account_inactive') }}
                        </strong>
                @endif
            </div>
        </div>

        <div class="section-container-profile desktop-elements">
            <!-- Notificaciones importantes -->
            <a href="{{ route('myAppointments.index') }}">
                <div class="card-profile full-center">
                    <i class="bi bi-journal-text"></i>
                    <strong>{{ __('navbar.my_appointments') }}</strong>
                </div>
            </a>

            <a href="{{ route('profile.session') }}">
                <div class="card-profile full-center">
                    <i class="bi bi-browser-chrome"></i>
                    <strong>{{ __('medical.titles.sessions') }}</strong>
                </div>
            </a>

            <a href="#">
                <div class="card-profile full-center feature-style">
                    <div class="feature">
                        {{ __('medical.feature') }}
                    </div>
                    <i class="bi bi-bell-fill"></i>
                    <strong>{{ __('medical.notifications') }}</strong>
                </div>
            </a>
            <a href="#">
                <div class="card-profile full-center feature-style">
                    <div class="feature">
                        {{ __('medical.feature') }}
                    </div>
                    <i class="bi bi-chat-square-fill"></i>
                    <strong>Soporte</strong>
                </div>
            </a>
            <a href="#">
                <div class="card-profile full-center feature-style">
                    <div class="feature">
                        {{ __('medical.feature') }}
                    </div>
                    <i class="bi bi-mortarboard"></i>
                    <strong>{{ __('medical.tutorial') }}</strong>
                </div>
            </a>
            <a href="#">
                <div class="card-profile full-center feature-style">
                    <div class="feature">
                        {{ __('medical.feature') }}
                    </div>
                    <i class="bi bi-file-earmark-text"></i>
                    <strong>{{ __('medical.bases_and_conditions') }}</strong>
                </div>
            </a>
            {{-- Cambio de contraseña --}}
            <a href="{{ route('password.change') }}">
                <div class="card-profile full-center">
                    <i class="bi bi-shield-lock-fill"></i>
                    <strong>Cambio de contraseña</strong>
                </div>
            </a>

            {{-- Eliminación de cuenta --}}
            <a href="{{ route('deleteCountForm') }}">
                <div class="card-profile full-center">
                    <i class="bi bi-person-x-fill"></i>
                    <strong>Eliminar cuenta</strong>
                </div>
            </a>
        </div>



        <div class="mobile-elements">
            <!-- Historial -->
            <a href="{{ route('myAppointments.index') }}">
                <div class="card-profile">
                    <div class="element-container">
                        <i class="bi bi-calendar-check-fill"></i>
                        <strong>{{ __('navbar.my_appointments') }}</strong>
                    </div>
                    <i class="bi bi-chevron-right"></i>
                </div>
            </a>
            <!-- Sesiones -->
            <a href="{{ route('profile.session') }}">
                <div class="card-profile">
                    <div class="element-container">
                        <i class="bi bi-browser-chrome"></i>
                        <strong>{{ __('medical.titles.sessions') }}</strong>
                    </div>
                    <i class="bi bi-chevron-right"></i>
                </div>
            </a>
            <!-- Notificaciones importantes -->
            <a href="#">
                <div class="card-profile feature-style">
                    <div class="feature">
                        {{ __('medical.feature') }}
                    </div>
                    <div class="element-container">
                        <i class="bi bi-bell-fill"></i>
                        <strong>{{ __('medical.notifications') }}</strong>
                    </div>
                    <i class="bi bi-chevron-right"></i>
                </div>
            </a>
            <a href="#">
                <div class="card-profile feature-style">
                    <div class="feature">
                        {{ __('medical.feature') }}
                    </div>
                    <div class="element-container">
                        <i class="bi bi-chat-square-fill"></i>
                        <type="">Soporte</type="">
                    </div>
                    <i class="bi bi-chevron-right"></i>
                </div>
            </a>
            <hr>
            <a href="#">
                <div class="card-profile feature-style">
                    <div class="feature">
                        {{ __('medical.feature') }}
                    </div>
                    <div class="element-container">
                        <i class="bi bi-mortarboard"></i>
                        <type="">{{ __('medical.tutorial') }}</type="">
                    </div>
                    <i class="bi bi-chevron-right"></i>
                </div>
            </a>
            <a href="#">
                <div class="card-profile feature-style">
                    <div class="feature">
                        {{ __('medical.feature') }}
                    </div>
                    <div class="element-container">
                        <i class="bi bi-file-earmark-text"></i>
                        <type="">{{ __('medical.bases_and_conditions') }}</type="">
                    </div>
                    <i class="bi bi-chevron-right"></i>
                </div>
            </a>
            <!-- contraseña -->
            <a href="{{ route('password.change') }}">
                <div class="card-profile">
                    <div class="element-container">
                        <i class="bi bi-shield-lock-fill"></i>
                        <strong>Cambio de contraseña</strong>
                    </div>
                    <i class="bi bi-chevron-right"></i>
                </div>
            </a>
            <!-- cuenta -->
            <a href="{{ route('deleteCountForm') }}">
                <div class="card-profile">
                    <div class="element-container">
                        <i class="bi bi-trash-fill"></i>
                        <strong>Eliminacion de cuenta</strong>
                    </div>
                    <i class="bi bi-chevron-right"></i>
                </div>
            </a>
            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link class="btn-salir" :href="route('logout')"
                    onclick="event.preventDefault();
                                this.closest('form').submit();">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>{{ __('Logout') }}</span>
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</x-app-layout>
