<form action="{{ $ruta }}" method="post">
    @csrf
    @if (isset($edit))
        @method('PUT')
    @endif
    @if (isset($crear))
        <h1>{{ __('specialty.btn_name') }}</h1>
        <hr>
    @endif
    <x-form.text-input type="text" name="name" label="{{ __('specialty.name') }}"
        placeholder="{{ __('specialty.name_placeholder') }}" minlength="5" maxlength="30" value="{{ $name }}"
        :required="true" />

    <x-form.textarea name="description" label="{{ __('medical.description_txt') }}"
        placeholder="{{ __('specialty.description_placeholder') }}" minlength="5" maxlength="500" required
        :value="$description" />

    <x-form.select name="status" :label="__('medical.status.title')" :required="true">
        <option value="1"{{ isset($edit) ? '' : 'selected' }}
            {{ isset($status) && $status == 1 ? 'selected' : '' }}>{{ __('medical.active') }}</option>
        <option value="0" {{ isset($status) && $status == 0 ? 'selected' : '' }}>{{ __('medical.inactive') }}
        </option>
    </x-form.select>
    <br>
    <hr>
    <br>
    <x-primary-button>
        {{ __('medical.register') }}
    </x-primary-button>
    @if (isset($crear) || isset($nuevoMedico))
        <x-secondary-button id="close-btn" style="background: red; color: #fff;">
            {{ __('medical.cancel') }}
        </x-secondary-button>
    @endif
</form>
