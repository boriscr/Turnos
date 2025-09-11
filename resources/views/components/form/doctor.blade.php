<form action="{{ $ruta }}" method="post">
    @csrf
    @if (!isset($crear))
        @method('PUT')
    @endif
    <x-form.text-input type="text" name="name" label="{{ __('contact.name') }}"
        placeholder="{{ __('placeholder.name') }}" minlength="3" maxlength="40" value="{{ $name }}"
        :required="true" />
    <x-form.text-input type="text" name="surname" label="{{ __('contact.surname') }}"
        placeholder="{{ __('placeholder.surname') }}" minlength="3" maxlength="15" value="{{ $surname }}"
        :required="true" />
    <x-form.text-input type="text" name="idNumber" label="{{ __('contact.idNumber') }}"
        placeholder="{{ __('placeholder.id_number') }}" minlength="7" maxlength="8" value="{{ $idNumber }}"
        :required="true" />
    <x-form.text-input type="email" name="email" label="{{ __('contact.email') }}"
        placeholder="{{ __('placeholder.email') }}" minlength="3" maxlength="60" value="{{ $email }}"
        :required="true" />

    <x-form.text-input type="tel" name="phone" label="{{ __('contact.phone') }}"
        placeholder="{{ __('placeholder.phone') }}" minlength="5" maxlength="15" value="{{ $phone }}"
        :required="true" />

    <x-form.select name="specialty_id" :label="__('specialty.title')" :required="true">
        <option value="">{{ __('medical.select_default') }}</option>
        @if (isset($specialties) && !empty($specialties))
            @foreach ($specialties as $item)
                <option value="{{ $item->id }}" {{ $item->id == $specialty ? 'selected' : '' }}>
                    {{ $item->name }}
                </option>
            @endforeach
        @endif
    </x-form.select>

    
    @if (isset($nuevoMedico) || isset($crear))
        <div class="new-specialty-box full-center">
            <x-secondary-button id="specialty-btn">
                {{ __('specialty.btn_name') }}
            </x-secondary-button>
        </div>
    @endif
    <x-form.text-input type="text" name="licenseNumber" label="{{ __('medical.license_number') }}"
        placeholder="{{ __('placeholder.license_number') }}" minlength="3" maxlength="60"
        value="{{ $licenseNumber }}" :required="true" />
    <x-form.select name="role" :label="__('medical.role')" :required="true">
        <option value="doctor" {{ $role == 'doctor' ? 'selected' : '' }}>{{ __('medical.doctor') }}</option>
    </x-form.select>
    <x-form.select name="status" :label="__('medical.status.title')" :required="true">
        <option value="1"{{ isset($edit) ? '' : 'selected' }}
            {{ isset($status) && $status == 1 ? 'selected' : '' }}>{{ __('medical.active') }}</option>
        <option value="0" {{ isset($status) && $status == 0 ? 'selected' : '' }}>
            {{ __('medical.inactive') }}</option>
    </x-form.select>
    <br>
    <hr>
    <br>
    <x-primary-button>
        @if (isset($crear))
            {{ __('medical.register') }}
        @else
            {{ __('medical.update') }}
        @endif
    </x-primary-button>
</form>
