<x-guest-layout>
    <style>
        select {
            width: 100%;
            border-radius: 5px;
            padding: 10px;
        }

        h1 {
            color: #4CAF50;
            text-align: center;
            text-decoration: underline;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 600;

        }

        .message-list-password {
            color: #4CAF50;
            font-size: 0.9rem;
            margin-top: 1rem;
            margin-bottom: 1rem;
            border: 1px solid #4CAF50;
            padding: 10px;
            border-radius: 5px;
        }

        option {
            color: #000;
        }

        .btn-registro {
            width: 50%;
            height: 100%;
            border-radius: 5px;
            background-color: #3e8e41;
            color: white;
            padding: 10px;
            text-align: center;

        }

        .btn-registro:hover {
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
        }

        @media (prefers-color-scheme: dark) {
            select {
                background-color: transparent;
                color: #fff !important;
            }

        }

        @media (prefers-color-scheme: light) {
            select {
                background-color: transparent;
                color: #000 !important;
            }
        }
    </style>
    <form method="POST" action="{{ route('register') }}" x-data="registerForm()" x-ref="registerForm">
        @csrf

        <!-- Barra de progreso -->
        <div class="flex items-center justify-between mb-6">
            <template x-for="s in 3">
                <div class="flex items-center">
                    <div :class="{
                        'bg-green-500 text-white': step > s,
                        'bg-pink-500 text-white': step ===
                            s,
                        'bg-gray-300 text-gray-700': step < s
                    }"
                        class="rounded-full w-8 h-8 flex items-center justify-center font-bold">
                        <span x-text="s"></span>
                    </div>
                    <div x-show="s !== 3" class="h-1 w-10"
                        :class="{ 'bg-green-500': step > s, 'bg-gray-300': step <= s }">
                    </div>
                </div>
            </template>
        </div>

        <!-- Paso 1 -->
        <div x-show="step === 1" x-transition>
            <h1>Registro</h1>
            <div class="mt-4">
                <x-input-label for="name" :value="__('Nombre/s')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="surname" :value="__('Apellido/s')" />
                <x-text-input id="surname" class="block mt-1 w-full" type="text" name="surname" required />
                <x-input-error :messages="$errors->get('surname')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="dni" :value="__('DNI')" />
                <x-text-input id="dni" class="block mt-1 w-full" type="text" name="dni" required />
                <x-input-error :messages="$errors->get('dni')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="birthdate" :value="__('Fecha de nacimiento')" />
                <x-text-input id="birthdate" class="block mt-1 w-full" type="date" name="birthdate" required />
                <x-input-error :messages="$errors->get('birthdate')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="genero" :value="__('Género')" />
                <select name="genero" id="genero" class="w-full rounded p-2" required>
                    <option value="">Selecciona...</option>
                    <option value="Femenino">Femenino</option>
                    <option value="Masculino">Masculino</option>
                    <option value="No binario">No binario</option>
                    <option value="Otro">Otro</option>
                    <option value="Prefiero no decir">Prefiero no decir</option>
                </select>
                <x-input-error :messages="$errors->get('genero')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" @click="nextStep(1)"
                    class="border border-white bg-pink-500 text-white px-4 py-2 rounded">Siguiente</button>
            </div>
            <br>
            <hr>
            <br>
            <x-secondary-button class="ms-3">
                <a href="{{ route('login') }}">{{ __('¿Ya tienes una cuenta?') }}</a>
            </x-secondary-button>
        </div>

        <!-- Paso 2 -->
        <div x-show="step === 2" x-transition>
            <!-- Address -->
            <h1>Domicillio</h1>
            <div class="mt-4">
                <x-input-label for="country" :value="__('Pais')" />
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
                <x-input-label for="province" :value="__('Provincia')" />
                <x-text-input id="province" class="block mt-1 w-full" type="text" name="province" required />
                <x-input-error :messages="$errors->get('province')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="city" :value="__('Ciudad')" />
                <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" required />
                <x-input-error :messages="$errors->get('city')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="address" :value="__('Calle, número y barrio')" />
                <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" required />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-between">
                <button type="button" @click="step--"
                    class="border border-white bg-gray-400 text-white px-4 py-2 rounded">Atrás</button>
                <button type="button" @click="nextStep(2)"
                    class="border border-white bg-pink-500 text-white px-4 py-2 rounded">Siguiente</button>
            </div>
        </div>

        <!-- Paso 3 -->
        <div x-show="step === 3" x-transition>
            <h1>Contacto y Acceso</h1>
            <div class="mt-4">
                <x-input-label for="phone" :value="__('Teléfono')" />
                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" required />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password" :value="__('Contraseña')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </p>
            </div>

            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    name="password_confirmation" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
            <ul class="message-list-password">
                <li>Mínimo 12 caracteres (recomendado)</li>
                <li>Combina mayúsculas, minúsculas, números y símbolos</li>
                <li>Evita contraseñas comunes o filtradas en hackeos</li>
                <li>No uses datos personales (fechas, nombres, etc.)</li>
            </ul>

            <div class="mt-6 flex justify-between">
                <button type="button" @click="step--"
                    class="border border-white bg-gray-400 text-white px-4 py-2 rounded">Atrás</button>
                <button type="button" @click="confirmSubmit" class="btn-registro">Registrar</button>
            </div>
        </div>
    </form>
</x-guest-layout>

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
                    <p class="text-left">
                        Los datos que estás por registrar serán utilizados para la reserva de turnos. 
                        <strong>Algunos de estos datos no podrán ser editados posteriormente</strong>, incluyendo tu 
                        <strong>DNI</strong>, <strong>nombre</strong> y <strong>apellido</strong>, ya que estarán asociados de forma exclusiva a esta cuenta.
                    </p>
                    <p class="text-left mt-2">
                        Si los datos son incorrectos, podrías recibir la negación de turnos o no poder gestionarlos correctamente.
                    </p>
                    <p class="text-left mt-2 font-semibold">
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