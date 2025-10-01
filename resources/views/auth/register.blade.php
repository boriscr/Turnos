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
                <x-form.text-input type="text" icon="person" name="name" label="{{ __('contact.name') }}"
                    placeholder="{{ __('placeholder.name') }}" minlength="3" maxlength="40" :required="true" autofocus
                    autocomplete="name" :value="old('name')" />
                <x-form.text-input type="text" icon="person" name="surname" label="{{ __('contact.surname') }}"
                    placeholder="{{ __('placeholder.surname') }}" minlength="3" maxlength="40" :required="true"
                    autocomplete="surname" />
                <x-form.text-input type="text" icon="credit-card" name="idNumber"
                    label="{{ __('contact.idNumber') }}" placeholder="{{ __('placeholder.idNumber') }}"
                    pattern="[a-zA-Z0-9]{7,8}" minlength="7" maxlength="8" :required="true" />

                <x-form.text-input type="date" icon="gift" name="birthdate" label="{{ __('contact.birthdate') }}"
                    placeholder="{{ __('placeholder.birthdate') }}" :required="true" />
                <small id="edad"></small>

                <x-form.select icon="gender-ambiguous" name="gender" :label="__('contact.gender')" :required="true">
                    <option value="">Selecciona...</option>
                    <option value="Femenino">Femenino</option>
                    <option value="Masculino">Masculino</option>
                    <option value="No binario">No binario</option>
                    <option value="Otro">Otro</option>
                    <option value="Prefiero no decir">Prefiero no decir</option>
                    </select>
                </x-form.select>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" @click="nextStep(1)" class="next-btn full-center">{{ __('button.next') }}<i
                        class="bi bi-chevron-right"></i></button>
            </div>
            <br>
            <hr>
            <br>

            <div class="flex items-center justify-center mt-4">
                <a href="{{ route('login') }}">{{ __('Already registered?') }}</a>
            </div>
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
                            {{ old('country_id', $argentinaId) == $country->id ? 'selected' : '' }}>
                            {!! $country->name !!}
                        </option>
                    @endforeach
                </x-form.select>
                <x-form.select icon="geo-alt" name="state_id" :label="__('contact.state')" :required="true">
                    <option value="">Selecciona una provincia</option>
                </x-form.select>
                <x-form.select icon="building" name="city_id" :label="__('contact.city')" :required="true">
                    <option value="">Selecciona una ciudad</option>
                </x-form.select>
                <x-form.text-input type="text" icon="house-door" name="address" label="{{ __('contact.address') }}"
                    placeholder="{{ __('placeholder.address') }}" minlength="10" maxlength="100" :required="true"
                    autocomplete="address" />
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
                <x-form.text-input type="text" icon="telephone" name="phone" label="{{ __('contact.phone') }}"
                    placeholder="{{ __('placeholder.phone') }}" minlength="9" maxlength="15" :required="true"
                    autocomplete="phone" />
                <x-form.text-input type="email" icon="envelope" name="email" label="{{ __('contact.email') }}"
                    placeholder="{{ __('placeholder.email') }}" minlength="5" maxlength="100" :required="true"
                    autocomplete="email" />
            </div>

            <div class="mt-4">
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

            <div class="boxBtn-register full-center" style="gap:10px">
                <button type="button" @click="step--" class="prev-btn full-center btn-danger"><i
                        class="bi bi-chevron-left"></i></button>
                <button type="button" @click="confirmSubmit" class="primary-btn"> {{ __('Register') }}</button>
            </div>
        </div>
    </form>

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
                        Los datos que estás por registrar serán utilizados para la reservation de turnos. 
                        <strong>Algunos datos no podrán ser editados posteriormente</strong>, ya que estarán asociados de forma exclusiva a esta cuenta.
                    </p>
                    <p class="text-left mt-2" style="color:black">
                        Si los datos son incorrectos, podrías recibir la negación o no poder gestionarlos correctamente.
                    </p>
                    <p class="text-left mt-2 font-semibold" style="color:black">
                        Por favor, revisa cuidadosamente toda la información antes de continuar y asegúrate de que los datos ingresados coincidan exactamente con los que figuran en tu DNI.
                    </p>
                    `,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#10B981',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Sí, registrar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // MOSTRAR EL LOADER antes de enviar el formulario
                                if (typeof showLoader === 'function') {
                                    showLoader('Registrando cuenta...');
                                } else {
                                    // Fallback si showLoader no está disponible
                                    console.log('Loader function not available');
                                }

                                // Enviar formulario después de un breve delay
                                setTimeout(() => {
                                    this.$refs.registerForm.submit();
                                }, 300);
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
