<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" x-data="registerForm()" x-ref="registerForm">
        @csrf

        <!-- Barra de progreso -->
        <div class="flex items-center justify-between mb-6">
            <template x-for="s in 3">
                <div class="flex items-center">
                    <div :class="{
                        'bg-[var(--general-design-color)] text-white': step > s,
                        'bg-grey-500 text-white': step ===
                            s,
                        'bg-gray-300 text-gray-700': step < s
                    }"
                        class="rounded-full w-8 h-8 flex items-center justify-center font-bold">
                        <span x-text="s"></span>
                    </div>
                    <div x-show="s !== 3" class="h-1 w-10"
                        :class="{ 'bg-[var(--general-design-color)]': step > s, 'bg-gray-300': step <= s }">
                    </div>
                </div>
            </template>
        </div>

        <!-- Paso 1 -->
        <div x-show="step === 1" x-transition>
            <h1>{{ __('Register') }}</h1>
            <div class="mt-4">
                <x-input-label for="name" :value="__('contact.name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="surname" :value="__('contact.surname')" />
                <x-text-input id="surname" class="block mt-1 w-full" type="text" name="surname" required />
                <x-input-error :messages="$errors->get('surname')" class="mt-2" />
            </div>

            <div class="mt-4">
                {{-- idNumber --}}
                <x-input-label for="idNumber" :value="__('contact.idNumber')" />
                <x-text-input id="idNumber" name="idNumber" type="text" pattern="[a-zA-Z0-9]{7,8}" minlength="7" maxlength="8"
                    autocomplete="off" required class="block mt-1 w-full" />
                <x-input-error :messages="$errors->get('idNumber')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="birthdate" :value="__('contact.birthdate')" />
                <x-text-input id="birthdate" class="block mt-1 w-full" type="date" name="birthdate" required />
                <x-input-error :messages="$errors->get('birthdate')" class="mt-2" />
                <small id="edad"></small>
            </div>

            <div class="mt-4">
                <x-input-label for="gender" :value="__('contact.gender')" />
                <select name="gender" id="gender" class="w-full rounded p-2" required>
                    <option value="">Selecciona...</option>
                    <option value="Femenino">Femenino</option>
                    <option value="Masculino">Masculino</option>
                    <option value="No binario">No binario</option>
                    <option value="Otro">Otro</option>
                    <option value="Prefiero no decir">Prefiero no decir</option>
                </select>
                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" @click="nextStep(1)" class="next-btn full-center">{{ __('button.next') }}<i
                        class="bi bi-chevron-right"></i></button>
            </div>
            <br>
            <hr>
            <br>

            <div class="flex items-center justify-center mt-4">
                <span>{{ __('Already registered?') }}</span>
                <x-secondary-button>
                    <a href="{{ route('login') }}">{{ __('Login') }}</a>
                </x-secondary-button>
            </div>
        </div>

        <!-- Paso 2 -->
        <div x-show="step === 2" x-transition>
            <!-- Address -->
            <h1>{{ __('contact.address') }}</h1>
            <div class="mt-4">
                <x-input-label for="country" :value="__('contact.country')" />
                <select name="country" id="country">
                    <option value="Argentina" selected>Argentina</option>
                    <!-- Países de América -->
                    <option value="Bolivia">Bolivia</option>
                    <option value="Brasil">Brasil</option>
                    <option value="Chile">Chile</option>
                    <option value="Colombia">Colombia</option>
                    <option value="Costa Rica">Costa Rica</option>
                    <option value="Cuba">Cuba</option>
                    <option value="Ecuador">Ecuador</option>
                    <option value="El Salvador">El Salvador</option>
                    <option value="Guatemala">Guatemala</option>
                    <option value="Honduras">Honduras</option>
                    <option value="México">México</option>
                    <option value="Nicaragua">Nicaragua</option>
                    <option value="Panamá">Panamá</option>
                    <option value="Paraguay">Paraguay</option>
                    <option value="Perú">Perú</option>
                    <option value="Puerto Rico">Puerto Rico</option>
                    <option value="República Dominicana">República Dominicana</option>
                    <option value="Uruguay">Uruguay</option>
                    <option value="Venezuela">Venezuela</option>

                    <!-- Países populares de Europa -->
                    <option value="Alemania">Alemania</option>
                    <option value="España">España</option>
                    <option value="Francia">Francia</option>
                    <option value="Italia">Italia</option>
                    <option value="Países Bajos">Países Bajos</option>
                    <option value="Portugal">Portugal</option>
                    <option value="Reino Unido">Reino Unido</option>
                    <option value="Suiza">Suiza</option>

                    <!-- Países populares de Asia -->
                    <option value="China">China</option>
                    <option value="Corea del Sur">Corea del Sur</option>
                    <option value="India">India</option>
                    <option value="Japón">Japón</option>
                    <option value="Tailandia">Tailandia</option>
                    <option value="Vietnam">Vietnam</option>
                    <option value="Otro">Otro</option>
                </select>
                <x-input-error :messages="$errors->get('country')" class="mt-2" />
            </div>


            <div class="mt-4">
                <x-input-label for="province" :value="__('contact.province')" />
                <x-text-input id="province" class="block mt-1 w-full" type="text" name="province" required />
                <x-input-error :messages="$errors->get('province')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="city" :value="__('contact.city')" />
                <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" required />
                <x-input-error :messages="$errors->get('city')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="address" :value="__('contact.address')" />
                <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" required />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-between">
                <button type="button" @click="step--" class="prev-btn full-center btn-danger"><i
                        class="bi bi-chevron-left"></i>{{ __('button.back') }}</button>
                <button type="button" @click="nextStep(2)" class="next-btn full-center">{{ __('button.next') }}<i
                        class="bi bi-chevron-right"></i></button>
            </div>
        </div>

        <!-- Paso 3 -->
        <div x-show="step === 3" x-transition>
            <h1>{{ __('contact.contact_and_access') }}</h1>
            <div class="mt-4">
                <x-input-label for="phone" :value="__('contact.phone')" />
                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" required />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </p>
            </div>

            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('security.password_confirmation')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    name="password_confirmation" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
            <ul class="message-list-password">
                <li>{{ __('security.min_length') }}</li>
                <li>{{ __('security.char_mix') }}</li>
                <li>{{ __('security.avoid_common') }}</li>
                <li>{{ __('security.no_personal_data') }}</li>
            </ul>

            <div class="boxBtn-register full-center" style="gap:10px">
                <button type="button" @click="step--" class="prev-btn full-center btn-danger"><i
                        class="bi bi-chevron-left"></i></button>
                <button type="button" @click="confirmSubmit" class="primary-btn"> {{ __('Register') }}</button>
            </div>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function registerForm() {
            return {
                step: 1,

                nextStep(stepNumber) {
                    let stepDiv = document.querySelectorAll('[x-show="step === ' + stepNumber + '"]')[0];
                    let inputs = stepDiv.querySelectorAll('input, select');
                    let valid = true;

                    inputs.forEach(input => {
                        if (input.hasAttribute('required') && !input.value) {
                            valid = false;
                            input.classList.add('border-red-500');
                        } else {
                            input.classList.remove('border-red-500');
                        }
                    });

                    if (valid) {
                        this.step++;
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Campos incompletos',
                            text: 'Por favor completa todos los campos antes de continuar.'
                        });
                    }
                },

                confirmSubmit() {
                    let stepDiv = document.querySelectorAll('[x-show="step === 3"]')[0];
                    let inputs = stepDiv.querySelectorAll('input, select');
                    let valid = true;

                    inputs.forEach(input => {
                        if (input.hasAttribute('required') && !input.value) {
                            valid = false;
                            input.classList.add('border-red-500');
                        } else {
                            input.classList.remove('border-red-500');
                        }
                    });

                    if (valid) {
                        Swal.fire({
                            title: '¿Confirmar registro?',
                            html: `
                    <p class="text-left" style="color:black">
                        Los datos que estás por registrar serán utilizados para la reservation de appointments. 
                        <strong>Algunos datos no podrán ser editados posteriormente</strong>, ya que estarán asociados de forma exclusiva a esta cuenta.
                    </p>
                    <p class="text-left mt-2" style="color:black">
                        Si los datos son incorrectos, podrías recibir la negación de appointments o no poder gestionarlos correctamente.
                    </p>
                    <p class="text-left mt-2 font-semibold" style="color:black">
                        Por favor, revisa cuidadosamente toda la información antes de continuar y asegúrate de que los datos ingresados coincidan exactamente con los que figuran en tu DNI.                    </p>
                    `,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#10B981',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Sí, registrar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.$refs.registerForm.submit();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Campos incompletos',
                            text: 'Por favor completa todos los campos antes de continuar.'
                        });
                    }
                }
            }
        }
    </script>
</x-guest-layout>
