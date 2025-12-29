<x-guest-layout>
    <h1>{{ __('Register') }}</h1>

    <!-- Indicador de pasos -->
    <div class="progress-container">
        <div class="progress-bar">
            <div class="progress-fill" id="progress-fill"></div>
        </div>
        <div class="steps-wrapper">
            <div class="step active" data-step="1">
                <div class="step-circle">
                    <i class="bi bi-person-circle"></i>
                </div>
                <span class="step-label">{{ __('Datos Personales') }}</span>
            </div>
            <div class="step" data-step="2">
                <div class="step-circle">
                    <i class="bi bi-geo-alt"></i>
                </div>
                <span class="step-label">{{ __('Dirección') }}</span>
            </div>
            <div class="step" data-step="3">
                <div class="step-circle">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <span class="step-label">{{ __('Acceso') }}</span>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('register') }}" id="multiStepForm">
        @csrf

        <!-- Paso 1 - Datos Personales -->
        <div class="form-step active" data-step="1">
            <div class="form-grid">
                <x-form.text-input type="text" icon="person-fill" name="name" label="{{ __('contact.name') }}"
                    placeholder="{{ __('placeholder.name') }}" minlength="3" maxlength="40" :required="true" autofocus
                    autocomplete="name" :value="old('name')" />
                <x-form.text-input type="text" icon="person-fill" name="surname" label="{{ __('contact.surname') }}"
                    placeholder="{{ __('placeholder.surname') }}" minlength="3" maxlength="40" :required="true"
                    autocomplete="surname" />
                <x-form.text-input type="text" icon="person-vcard-fill" name="idNumber"
                    label="{{ __('contact.idNumber') }}" placeholder="{{ __('placeholder.idNumber') }}"
                    pattern="[a-zA-Z0-9]{7,8}" minlength="7" maxlength="8" :required="true" />

                <x-form.text-input type="date" icon="gift" name="birthdate" label="{{ __('contact.birthdate') }}"
                    placeholder="{{ __('placeholder.birthdate') }}" :required="true" />
                <small id="edad"></small>

                <x-form.select icon="gender-ambiguous" name="gender_id" :label="__('contact.gender')" :required="true">
                    <option value="">{{ __('medical.select_default') }}</option>
                    @foreach ($genders as $item)
                        <option value="{{ $item->id }}">{{ $item->translated_name }}</option>
                    @endforeach
                </x-form.select>
            </div>

            <div class="full-center mt-10 navegation-next">
                <button type="button" class="next-btn full-center">{{ __('button.next') }}<i
                        class="bi bi-chevron-right"></i></button>
            </div>

            <br>
            <hr>
            <div class="flex items-center justify-center mt-8">
                <a href="{{ route('login') }}">{{ __('Already registered?') }}</a>
            </div>
        </div>

        <!-- Paso 2 - Dirección -->
        <div class="form-step" data-step="2">
            <h2>{{ __('contact.address') }}</h2>
            <div class="form-grid mt-4">
                <x-form.select icon="globe" name="country_id" :label="__('contact.country')" :required="true">
                    <option value="">{{ __('medical.select_default') }}</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}"
                            {{ old('country_id', $argentinaId) == $country->id ? 'selected' : '' }}>
                            {!! $country->name !!}
                        </option>
                    @endforeach
                </x-form.select>
                <x-form.select icon="geo-alt" name="state_id" :label="__('contact.state')" :required="true">
                    <option value="">{{ __('medical.select_default') }}</option>
                </x-form.select>
                <x-form.select icon="building" name="city_id" :label="__('contact.city')" :required="true">
                    <option value="">{{ __('medical.select_default') }}</option>
                </x-form.select>
                <x-form.text-input type="text" icon="house-door" name="address" label="{{ __('contact.address') }}"
                    placeholder="{{ __('placeholder.address') }}" minlength="10" maxlength="100" :required="true"
                    autocomplete="address" />
            </div>

            <div class="form-navigation mt-4">
                <button type="button" class="prev-btn full-center"><i
                        class="bi bi-chevron-left"></i>{{ __('button.back') }}</button>
                <button type="button" class="next-btn full-center">{{ __('button.next') }}<i
                        class="bi bi-chevron-right"></i></button>
            </div>
        </div>

        <!-- Paso 3 - Contacto y Acceso -->
        <div class="form-step" data-step="3">
            <h2>{{ __('contact.contact_and_access') }}</h2>
            <div class="form-grid mt-4">
                <x-form.text-input type="text" icon="telephone" name="phone" label="{{ __('contact.phone') }}"
                    placeholder="{{ __('placeholder.phone') }}" minlength="9" maxlength="15" :required="true"
                    autocomplete="phone" />
                <x-form.text-input type="email" icon="envelope" name="email" label="{{ __('contact.email') }}"
                    placeholder="{{ __('placeholder.email') }}" minlength="5" maxlength="100" :required="true"
                    autocomplete="email" />
            </div>

            <div class="form-grid mt-4">
                <x-form.text-input type="password" icon="key" name="password" label="{{ __('Password') }}"
                    minlength="12" maxlength="72" :required="true" />
                <x-form.text-input type="password" icon="key" name="password_confirmation"
                    label="{{ __('security.password_confirmation') }}" minlength="12" maxlength="72"
                    :required="true" />
            </div>

            <ul class="message-list-password">
                <li>{{ __('security.min_length') }}</li>
                <li>{{ __('security.char_mix') }}</li>
                <li>{{ __('security.avoid_common') }}</li>
                <li>{{ __('security.no_personal_data') }}</li>
            </ul>

            <div class="form-navigation mt-4">
                <button type="button" class="prev-btn full-center"><i
                        class="bi bi-chevron-left"></i>{{ __('button.back') }}</button>
                <x-primary-button type="button" id="submit-register">
                    {{ __('Register') }}
                    <i class="bi bi-check-circle"></i>
                </x-primary-button>
            </div>
        </div>
    </form>
</x-guest-layout>
