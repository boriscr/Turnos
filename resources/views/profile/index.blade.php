<x-app-layout>
    <div class="content-wrapper">
        <div class="content-date-profile {{ $user->status ? '' : 'status-inactive' }}" style="width: 100%">
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
                                @case('Masculino')
                                    {{ __('medical.greetings.welcome') }}
                                @break

                                @case('Femenino')
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

        <div class="section-container-profile desktop-elements">
            <!-- Notificaciones importantes -->
            <a href="{{ route('myAppointments.index') }}">
                <div class="card-profile full-center">
                    <strong>{{ __('navbar.my_appointments') }}</strong>
                    <i class="bi bi-journal-text"></i>
                </div>
            </a>
            <a href="#">
                <div class="card-profile full-center feature-style">
                    <div class="feature">
                        {{ __('medical.feature') }}
                    </div>
                    <strong>{{ __('medical.notifications') }}</strong>
                    <i class="bi bi-bell-fill"></i>
                </div>
            </a>
            <a href="#">
                <div class="card-profile full-center feature-style">
                    <div class="feature">
                        {{ __('medical.feature') }}
                    </div>
                    <strong>Soporte</strong>
                    <i class="bi bi-chat-square-fill"></i>
                </div>
            </a>
            <a href="#">
                <div class="card-profile full-center feature-style">
                    <div class="feature">
                        {{ __('medical.feature') }}
                    </div>
                    <strong>{{ __('medical.tutorial') }}</strong>
                    <i class="bi bi-mortarboard"></i>
                </div>
            </a>
            <a href="#">
                <div class="card-profile full-center feature-style">
                    <div class="feature">
                        {{ __('medical.feature') }}
                    </div>
                    <strong>{{ __('medical.bases_and_conditions') }}</strong>
                    <i class="bi bi-file-earmark-text"></i>
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
