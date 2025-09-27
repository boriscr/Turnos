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
                    <x-form.select icon="globe" name="country" :label="__('contact.country')" :required="true">
                        <option {{ $user->country == 'Argentina' ? 'selected' : '' }} value="Argentina">Argentina
                        </option>
                        <!-- Países de América -->
                        <option {{ $user->country == 'Bolivia' ? 'selected' : '' }} value="Bolivia">Bolivia</option>
                        <option {{ $user->country == 'Brasil' ? 'selected' : '' }} value="Brasil">Brasil</option>
                        <option {{ $user->country == 'Chile' ? 'selected' : '' }} value="Chile">Chile</option>
                        <option {{ $user->country == 'Colombia' ? 'selected' : '' }} value="Colombia">Colombia</option>
                        <option {{ $user->country == 'Costa Rica' ? 'selected' : '' }} value="Costa Rica">Costa Rica
                        </option>
                        <option {{ $user->country == 'Cuba' ? 'selected' : '' }} value="Cuba">Cuba</option>
                        <option {{ $user->country == 'Ecuador' ? 'selected' : '' }} value="Ecuador">Ecuador</option>
                        <option {{ $user->country == 'El Salvador' ? 'selected' : '' }} value="El Salvador">El Salvador
                        </option>
                        <option {{ $user->country == 'Guatemala' ? 'selected' : '' }} value="Guatemala">Guatemala
                        </option>
                        <option {{ $user->country == 'Honduras' ? 'selected' : '' }} value="Honduras">Honduras</option>
                        <option {{ $user->country == 'México' ? 'selected' : '' }} value="México">México</option>
                        <option {{ $user->country == 'Nicaragua' ? 'selected' : '' }} value="Nicaragua">Nicaragua
                        </option>
                        <option {{ $user->country == 'Panamá' ? 'selected' : '' }} value="Panamá">Panamá</option>
                        <option {{ $user->country == 'Paraguay' ? 'selected' : '' }} value="Paraguay">Paraguay</option>
                        <option {{ $user->country == 'Perú' ? 'selected' : '' }} value="Perú">Perú</option>
                        <option {{ $user->country == 'Puerto Rico' ? 'selected' : '' }} value="Puerto Rico">Puerto Rico
                        </option>
                        <option
                            {{ $user->country ==
                            'República
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        Dominicana'
                                ? 'selected'
                                : '' }}
                            value="República
                    Dominicana">República
                            Dominicana</option>
                        <option {{ $user->country == 'Uruguay' ? 'selected' : '' }} value="Uruguay">Uruguay</option>
                        <option {{ $user->country == 'Venezuela' ? 'selected' : '' }} value="Venezuela">Venezuela
                        </option>

                        <!-- Países populares de Europa -->
                        <option {{ $user->country == 'Alemania' ? 'selected' : '' }} value="Alemania">Alemania</option>
                        <option {{ $user->country == 'España' ? 'selected' : '' }} value="España">España</option>
                        <option {{ $user->country == 'Francia' ? 'selected' : '' }} value="Francia">Francia</option>
                        <option {{ $user->country == 'Italia' ? 'selected' : '' }} value="Italia">Italia</option>
                        <option {{ $user->country == 'Países Bajos' ? 'selected' : '' }} value="Países Bajos">Países
                            Bajos
                        </option>
                        <option {{ $user->country == 'Portugal' ? 'selected' : '' }} value="Portugal">Portugal</option>
                        <option {{ $user->country == 'Reino Unido' ? 'selected' : '' }} value="Reino Unido">Reino Unido
                        </option>
                        <option {{ $user->country == 'Suiza' ? 'selected' : '' }} value="Suiza">Suiza</option>

                        <!-- Países populares de Asia -->
                        <option {{ $user->country == 'China' ? 'selected' : '' }} value="China">China</option>
                        <option {{ $user->country == 'Corea del Sur' ? 'selected' : '' }} value="Corea del Sur">Corea
                            del
                            Sur
                        </option>
                        <option {{ $user->country == 'India' ? 'selected' : '' }} value="India">India</option>
                        <option {{ $user->country == 'Japón' ? 'selected' : '' }} value="Japón">Japón</option>
                        <option {{ $user->country == 'Tailandia' ? 'selected' : '' }} value="Tailandia">Tailandia
                        </option>
                        <option {{ $user->country == 'Vietnam' ? 'selected' : '' }} value="Vietnam">Vietnam</option>
                        <option {{ $user->country == 'Otro' ? 'selected' : '' }} value="Otro">Otro</option>
                    </x-form.select>

                    <x-form.text-input type="text" icon="geo-alt" name="province"
                        label="{{ __('contact.province') }}" placeholder="{{ __('placeholder.province') }}"
                        minlength="3" maxlength="50" value="{{ $user->province }}" :required="true" autofocus
                        autocomplete="province" />

                    <x-form.text-input type="text" icon="building" name="city" label="{{ __('contact.city') }}"
                        placeholder="{{ __('placeholder.city') }}" minlength="3" maxlength="50"
                        value="{{ $user->city }}" :required="true" autofocus autocomplete="city" />

                    <x-form.text-input type="text" icon="house-door" name="address"
                        label="{{ __('contact.address') }}" placeholder="{{ __('placeholder.address') }}"
                        minlength="10" maxlength="100" value="{{ $user->address }}" :required="true" autofocus
                        autocomplete="address" />
                </div>
            </div>
            <!-- Paso 3 -->
            <div x-show="step === 3" x-transition>
                <h1>{{ __('contact.contact_and_access') }}</h1>
                <div class="mt-4">
                    <x-form.text-input type="tel" icon="telephone" name="phone"
                        label="{{ __('contact.phone') }}" placeholder="{{ __('placeholder.phone') }}"
                        minlength="9" maxlength="15" value="{{ $user->phone }}" :required="true" autofocus
                        autocomplete="phone" />
                    <x-form.text-input type="email" icon="envelope" name="email"
                        label="{{ __('contact.email') }}" placeholder="{{ __('placeholder.email') }}"
                        minlength="5" maxlength="100" value="{{ $user->email }}" :required="true" autofocus
                        autocomplete="email" />

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
