<section>
    <header>
        <h1 class="text-lg font-medium">
            {{ __('Profile Information') }}
        </h1>
    </header>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')
        <!-- Paso 1 -->
        <div x-show="step === 1" x-transition>
            <h1>{{ __('medical.titles.personal_data') }}</h1>
            <div class="mt-4">
                <x-form.text-input type="text" icon="person" name="name" label="{{ __('contact.name') }}"
                    placeholder="{{ __('placeholder.name') }}" minlength="3" maxlength="40" value="{{ $user->name }}"
                    :required="true" autofocus autocomplete="name" />
                <x-form.text-input type="text" icon="person" name="surname" label="{{ __('contact.surname') }}"
                    placeholder="{{ __('placeholder.surname') }}" minlength="3" maxlength="40"
                    value="{{ $user->surname }}" :required="true" autofocus autocomplete="surname" />

                <div class="form-step active">
                    <div class="item">
                        <small><i class="bi bi-credit-card"></i>{{ __('contact.idNumber') }} </small>
                        <p>{{ $user->idNumber }}</p>
                    </div>
                </div>

                <x-form.text-input type="date" icon="gift" name="birthdate" label="{{ __('contact.birthdate') }}"
                    placeholder="{{ __('placeholder.birthdate') }}" value="{{ $user->birthdate }}" :required="true"
                    autofocus autocomplete="birthdate" />
                <small id="edad"></small>
                <x-form.select icon="gender-ambiguous" name="gender" :label="__('contact.gender')" :required="true">
                    <option value="">Selecciona...</option>
                    <option {{ $user->gender == 'Femenino' ? 'selected' : '' }} value="Femenino">Femenino</option>
                    <option {{ $user->gender == 'Masculino' ? 'selected' : '' }} value="Masculino">Masculino
                    </option>
                    <option {{ $user->gender == 'No binario' ? 'selected' : '' }} value="No binario">No binario
                    </option>
                    <option {{ $user->gender == 'Otro' ? 'selected' : '' }} value="Otro">Otro</option>
                    <option {{ $user->gender == 'Prefiero no decir' ? 'selected' : '' }} value="Prefiero no decir">
                        Prefiero
                        no
                        decir</option>
                </x-form.select>
            </div>
            <!-- Paso 2 -->
            <div x-show="step === 2" x-transition>
                <!-- Address -->
                <h1>{{ __('contact.address') }}</h1>
                <div class="mt-4">
                    <x-form.select icon="globe" name="country_id" :label="__('contact.country')" :required="true">
                        <option value="">Selecciona un país</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}"
                                {{ old('country_id', $user->country_id) == $country->id ? 'selected' : '' }}>
                                {!! $country->name !!}
                            </option>
                        @endforeach
                    </x-form.select>
                    <x-form.select icon="geo-alt" name="state_id" :label="__('contact.state')" :required="true">
                        <option value="">Selecciona una provincia</option>
                        @foreach ($states as $state)
                            <option value="{{ $state->id }}"
                                {{ old('state_id', $user->state_id) == $state->id ? 'selected' : '' }}>
                                {!! $state->name !!}
                            </option>
                        @endforeach
                    </x-form.select>
                    <x-form.select icon="building" name="city_id" :label="__('contact.city')" :required="true">
                        <option value="">Selecciona una ciudad</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}"
                                {{ old('city_id', $user->city_id) == $city->id ? 'selected' : '' }}>
                                {!! $city->name !!}
                            </option>
                        @endforeach
                    </x-form.select>
                </div>

                <x-form.text-input type="text" icon="house-door" name="address" label="{{ __('contact.address') }}"
                    placeholder="{{ __('placeholder.address') }}" minlength="10" maxlength="100"
                    value="{{ $user->address }}" :required="true" autofocus autocomplete="address" />
            </div>
        </div>
        <!-- Paso 3 -->
        <div x-show="step === 3" x-transition>
            <h1>{{ __('contact.contact_and_access') }}</h1>
            <div class="mt-4">
                <x-form.text-input type="tel" icon="telephone" name="phone" label="{{ __('contact.phone') }}"
                    placeholder="{{ __('placeholder.phone') }}" minlength="9" maxlength="15"
                    value="{{ $user->phone }}" :required="true" autofocus autocomplete="phone" />
                <x-form.text-input type="email" icon="envelope" name="email" label="{{ __('contact.email') }}"
                    placeholder="{{ __('placeholder.email') }}" minlength="5" maxlength="100"
                    value="{{ $user->email }}" :required="true" autofocus autocomplete="email" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification"
                                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        <div class="buttonSend gap-4 mt-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
