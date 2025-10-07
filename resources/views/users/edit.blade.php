<x-app-layout>
    
    <div class="main full-center">
        <div class="container-form full-center">
            <h1>{{ __('medical.titles.section_title_edit') }}</h1>
            @if (isset($user))
                <form action="{{ route('user.update', $user->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <x-form.text-input type="text" icon="person" name="name" label="{{ __('contact.name') }}"
                        placeholder="{{ __('placeholder.name') }}" minlength="3" maxlength="40"
                        value="{{ $user->name }}" :required="true" />
                    <x-form.text-input type="text" icon="person" name="surname" label="{{ __('contact.surname') }}"
                        placeholder="{{ __('placeholder.surname') }}" minlength="3" maxlength="40"
                        value="{{ $user->surname }}" :required="true" />
                    <x-form.text-input type="text" icon="credit-card" name="idNumber"
                        label="{{ __('contact.idNumber') }}" placeholder="{{ __('placeholder.id_number') }}"
                        minlength="7" maxlength="8" pattern="[a-zA-Z0-9]{7,8}" value="{{ $user->idNumber }}"
                        :required="true" />
                    <x-form.text-input type="date" icon="gift" name="birthdate"
                        label="{{ __('contact.birthdate') }}" value="{{ $user->birthdate }}" :required="true" />
                    <small id="edad"></small>
                    <x-form.select name="gender" icon="gender-ambiguous" :label="__('contact.gender')" :required="true">
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

                    <div class="mt-4">
                        <x-form.select icon="globe" name="country_id" :label="__('contact.country')" :required="true">
                            <option value="">{{ __('medical.select_default') }}</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}"
                                    {{ old('country_id', $user->country_id) == $country->id ? 'selected' : '' }}>
                                    {!! $country->name !!}
                                </option>
                            @endforeach
                        </x-form.select>
                        <x-form.select icon="geo-alt" name="state_id" :label="__('contact.state')" :required="true">
                            <option value="">{{ __('medical.select_default') }}</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->id }}"
                                    {{ old('state_id', $user->state_id) == $state->id ? 'selected' : '' }}>
                                    {!! $state->name !!}
                                </option>
                            @endforeach
                        </x-form.select>
                        <x-form.select icon="building" name="city_id" :label="__('contact.city')" :required="true">
                            <option value="">{{ __('medical.select_default') }}</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}"
                                    {{ old('city_id', $user->city_id) == $city->id ? 'selected' : '' }}>
                                    {!! $city->name !!}
                                </option>
                            @endforeach
                        </x-form.select>
                        <x-form.text-input type="text" icon="house-door" name="address"
                            label="{{ __('contact.address') }}" placeholder="{{ __('placeholder.address') }}"
                            minlength="10" maxlength="100" value="{{ $user->address }}" :required="true" autofocus
                            autocomplete="address" />
                    </div>

                    <x-form.text-input type="tel" icon="telephone" name="phone" label="{{ __('contact.phone') }}"
                        value="{{ $user->phone }}" :required="true" minlength="9" maxlength="15" />
                    <x-form.text-input type="email" icon="envelope" name="email" label="{{ __('contact.email') }}"
                        value="{{ $user->email }}" :required="true" minlength="5" maxlength="100" />

                    <x-form.select name="role" icon="person-badge" :label="__('medical.role')" :required="true">
                        <option value="user" {{ $user->getRoleNames()->first() == 'user' ? 'selected' : '' }}>
                            {{ __('medical.user') }}</option>
                        <option value="doctor" {{ $user->getRoleNames()->first() == 'doctor' ? 'selected' : '' }}>
                            {{ __('medical.doctor') }}</option>
                        <option value="admin" {{ $user->getRoleNames()->first() == 'admin' ? 'selected' : '' }}>
                            {{ __('medical.admin') }}</option>
                    </x-form.select>

                    <x-form.select name="status" icon="circle-fill" :label="__('medical.status.title')" :required="true">
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
                    <x-primary-button>
                        {{ __('button.confirm') }}
                        <i class="bi bi-check-circle"></i>
                    </x-primary-button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
