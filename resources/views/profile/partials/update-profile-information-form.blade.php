<section>
    <header>
        <h2 class="text-lg font-medium">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')
        <!-- Paso 1 -->
        <div x-show="step === 1" x-transition>
            <h1>Registro</h1>
            <div>
                <x-input-label for="name" :value="__('Nombre/s')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                    required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="surname" :value="__('Apellido')" />
                <x-text-input id="surname" name="surname" type="text" class="mt-1 block w-full" :value="old('surname', $user->surname)"
                    required autofocus autocomplete="surname" />
                <x-input-error class="mt-2" :messages="$errors->get('surname')" />
            </div>

            <div>
                <x-input-label for="idNumber" :value="__('DNI')" />
                <x-text-input style="background: transparent; border: 1px solid gray" id="idNumber" name="idNumber"
                    type="text" class="mt-1 block w-full" :value="old('idNumber', $user->idNumber)" readonly />
                <x-input-error class="mt-2" :messages="$errors->get('idNumber')" />
            </div>

            <div>
                <x-input-label for="birthdate" :value="__('Fecha de nacimiento')" />
                <x-text-input id="birthdate" name="birthdate" type="date" class="mt-1 block w-full"
                    :value="old('birthdate', $user->birthdate)" required autofocus autocomplete="birthdate" />
                <x-input-error class="mt-2" :messages="$errors->get('birthdate')" />
                <small id="edad"></small>
            </div>

            <div class="mt-4">
                <x-input-label for="gender" :value="__('Género')" />
                <select name="gender" id="gender" class="w-full rounded p-2" required>
                    <option value="">Selecciona...</option>
                    <option {{ $user->gender == 'Femenino' ? 'selected' : '' }} value="Femenino">Femenino</option>
                    <option {{ $user->gender == 'Masculino' ? 'selected' : '' }} value="Masculino">Masculino</option>
                    <option {{ $user->gender == 'No binario' ? 'selected' : '' }} value="No binario">No binario</option>
                    <option {{ $user->gender == 'Otro' ? 'selected' : '' }} value="Otro">Otro</option>
                    <option {{ $user->gender == 'Prefiero no decir' ? 'selected' : '' }} value="Prefiero no decir">
                        Prefiero
                        no
                        decir</option>
                </select>
                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
            </div>
        </div>
        <!-- Paso 2 -->
        <div x-show="step === 2" x-transition>
            <!-- Address -->
            <h1>Domicillio</h1>
            <div class="mt-4">
                <x-input-label for="country" :value="__('Pais')" />
                <select name="country" id="country">
                    <option {{ $user->country == 'Argentina' ? 'selected' : '' }} value="Argentina">Argentina</option>
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
                    <option {{ $user->country == 'Guatemala' ? 'selected' : '' }} value="Guatemala">Guatemala</option>
                    <option {{ $user->country == 'Honduras' ? 'selected' : '' }} value="Honduras">Honduras</option>
                    <option {{ $user->country == 'México' ? 'selected' : '' }} value="México">México</option>
                    <option {{ $user->country == 'Nicaragua' ? 'selected' : '' }} value="Nicaragua">Nicaragua</option>
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
                    <option {{ $user->country == 'Venezuela' ? 'selected' : '' }} value="Venezuela">Venezuela</option>

                    <!-- Países populares de Europa -->
                    <option {{ $user->country == 'Alemania' ? 'selected' : '' }} value="Alemania">Alemania</option>
                    <option {{ $user->country == 'España' ? 'selected' : '' }} value="España">España</option>
                    <option {{ $user->country == 'Francia' ? 'selected' : '' }} value="Francia">Francia</option>
                    <option {{ $user->country == 'Italia' ? 'selected' : '' }} value="Italia">Italia</option>
                    <option {{ $user->country == 'Países Bajos' ? 'selected' : '' }} value="Países Bajos">Países Bajos
                    </option>
                    <option {{ $user->country == 'Portugal' ? 'selected' : '' }} value="Portugal">Portugal</option>
                    <option {{ $user->country == 'Reino Unido' ? 'selected' : '' }} value="Reino Unido">Reino Unido
                    </option>
                    <option {{ $user->country == 'Suiza' ? 'selected' : '' }} value="Suiza">Suiza</option>

                    <!-- Países populares de Asia -->
                    <option {{ $user->country == 'China' ? 'selected' : '' }} value="China">China</option>
                    <option {{ $user->country == 'Corea del Sur' ? 'selected' : '' }} value="Corea del Sur">Corea del
                        Sur
                    </option>
                    <option {{ $user->country == 'India' ? 'selected' : '' }} value="India">India</option>
                    <option {{ $user->country == 'Japón' ? 'selected' : '' }} value="Japón">Japón</option>
                    <option {{ $user->country == 'Tailandia' ? 'selected' : '' }} value="Tailandia">Tailandia</option>
                    <option {{ $user->country == 'Vietnam' ? 'selected' : '' }} value="Vietnam">Vietnam</option>
                    <option {{ $user->country == 'Otro' ? 'selected' : '' }} value="Otro">Otro</option>
                </select>
                <x-input-error :messages="$errors->get('country')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="province" :value="__('Provincia')" />
                <x-text-input id="province" class="block mt-1 w-full" type="text" name="province" required
                    :value="old('province', $user->province)" />
                <x-input-error :messages="$errors->get('province')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="city" :value="__('Ciudad')" />
                <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" required
                    :value="old('city', $user->city)" />
                <x-input-error :messages="$errors->get('city')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="address" :value="__('Calle, número y barrio')" />
                <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" required
                    :value="old('address', $user->address)" />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>
        </div>

        <!-- Paso 3 -->
        <div x-show="step === 3" x-transition>
            <h1>Contacto</h1>
            <div class="mt-4">
                <x-input-label for="phone" :value="__('Teléfono')" />
                <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" required
                    :value="old('phone', $user->phone)" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                    :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

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


        <div class="buttonSend gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
            @endif

        </div>
    </form>
</section>
