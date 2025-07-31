<x-app-layout>
    <div class="main full-center">
        <div class="container-form full-center">
            <h3 class="title-form">{{ __('medical.section_title_edit') }}</h3>
            @if (isset($user))
                <form action="{{ route('user.update', $user->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <x-form.text-input type="text" name="name" label="{{ __('contact.name') }}"
                        placeholder="{{ __('placeholder.name') }}" minlength="3" maxlength="40"
                        value="{{ $user->name }}" :required="true" />
                    <x-form.text-input type="text" name="surname" label="{{ __('contact.surname') }}"
                        placeholder="{{ __('placeholder.surname') }}" minlength="3" maxlength="15"
                        value="{{ $user->surname }}" :required="true" />
                    <x-form.text-input type="text" name="idNumber" label="{{ __('contact.idNumber') }}"
                        placeholder="{{ __('placeholder.id_number') }}" minlength="7" maxlength="8"
                        value="{{ $user->idNumber }}" :required="true" />
                    <x-form.text-input type="date" name="birthdate" label="{{ __('contact.birthdate') }}"
                        value="{{ $user->birthdate }}" :required="true" />
                    <small id="edad"></small>
                    <x-form.select name="gender" :label="__('contact.gender')" :required="true">
                        <option value="Femenino" {{ $user->gender == 'Femenino' ? 'Selected' : '' }}>Femenino
                        </option>
                        <option value="Masculino" {{ $user->gender == 'Masculino' ? 'Selected' : '' }}>Masculino
                        </option>
                        <option value="No binario" {{ $user->gender == 'No binario' ? 'Selected' : '' }}>No binario
                        </option>
                        <option value="Otro" {{ $user->gender == 'Otro' ? 'Selected' : '' }}>Otro</option>
                        <option value="Prefiero no decir" {{ $user->gender == 'Prefiero no decir' ? 'Selected' : '' }}>
                            Prefiero
                            no decir
                        </option>
                    </x-form.select>

                    <x-form.select name="country" :label="__('contact.country')" :required="true">
                        <option value="Argentina" {{ $user->country == 'Argentina' ? 'Selected' : '' }}>Argentina
                        </option>
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
                    </x-form.select>
                    <x-form.text-input type="text" name="province" label="{{ __('contact.province') }}"
                        value="{{ $user->province }}" :required="true" />
                    <x-form.text-input type="text" name="city" label="{{ __('contact.city') }}"
                        value="{{ $user->city }}" :required="true" />
                    <x-form.text-input type="text" name="address" label="{{ __('contact.address') }}"
                        value="{{ $user->address }}" :required="true" />
                    <x-form.text-input type="tel" name="phone" label="{{ __('contact.phone') }}"
                        value="{{ $user->phone }}" :required="true" />
                    <x-form.text-input type="email" name="email" label="{{ __('contact.email') }}"
                        value="{{ $user->email }}" :required="true" />

                    <x-form.select name="role" :label="__('medical.role')" :required="true">
                        <option value="user" {{ $user->getRoleNames()->first() == 'user' ? 'selected' : '' }}>
                            {{ __('medical.user') }}</option>
                        <option value="doctor" {{ $user->getRoleNames()->first() == 'doctor' ? 'selected' : '' }}>
                            {{ __('medical.doctor') }}</option>
                        <option value="admin" {{ $user->getRoleNames()->first() == 'admin' ? 'selected' : '' }}>
                            {{ __('medical.admin') }}</option>
                    </x-form.select>

                    <x-form.select name="status" :label="__('medical.status')" :required="true">
                        <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>
                            {{ __('medical.active') }}
                        </option>
                        <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>
                            {{ __('medical.inactive') }}
                        </option>
                    </x-form.select>
                    <br>
                    <hr>
                    <br>
                    <button type="submit" class="primary-btn">Registrar</button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
