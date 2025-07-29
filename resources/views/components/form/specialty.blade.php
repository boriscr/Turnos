<form action="{{ $ruta }}" method="post">
    @csrf
    @if (isset($edit))
        @method('PUT')
    @endif
    @if (isset($crear))
        <h3 class="title-form">{{ __('specialty.btn_name') }}</h3>
        <hr>
    @endif
    <x-form.text-input type="text" name="name" label="{{ __('specialty.name') }}"
        placeholder="{{ __('specialty.name_placeholder') }}" minlength="5" maxlength="30" value="{{ $name }}"
        :required="true" />

    <x-form.textarea name="description" label="{{ __('medical.description_txt') }}"
        placeholder="{{ __('specialty.description_placeholder') }}" minlength="5" maxlength="500" required
        :value="$description" />

    <div class="item">
        <x-input-label for="status" :value="__('medical.role')" :required="true" />
        <select name="status" id="status" required>
            <option value="1"{{ isset($edit) ? '' : 'selected' }}
                {{ isset($status) && $status == 1 ? 'selected' : '' }}>{{ __('medical.active') }}</option>
            <option value="0" {{ isset($status) && $status == 0 ? 'selected' : '' }}>{{ __('medical.inactive') }}
            </option>
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>
    <br>
    <hr>
    <br>
    <x-primary-button>
        {{ __('medical.register') }}
    </x-primary-button>
    @if (isset($crear))
        <x-primary-button id="close-btn" style="background: red; color: #fff;">
            {{ __('medical.cancel') }}
        </x-primary-button>
    @endif
</form>