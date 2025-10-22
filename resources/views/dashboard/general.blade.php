<!-- resources/views/dashboard/design.blade.php -->
@extends('layouts.dashboard')

@section('dashboard-content')
        <h1>{{ __('navbar.settings') }}</h1>
        <div class="container-form full-center">
            <form action="{{ route('settings.update') }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <h2>{{ __('medical.setting.title_1') }}</h2>
                    <x-form.text-input type="text" name="app[name]" :label="__('medical.setting.name')"
                        context="{{ __('medical.setting.name_context') }}" minlength="3" maxlength="255" :required="true"
                        value="{{ $settings['app.name'] }}" />

                    <x-form.textarea name="app[welcome_message]" label="{{ __('medical.setting.welcome_message') }}"
                        context="{{ __('medical.setting.welcome_message_context') }}" minlength="20" maxlength="500"
                        value="{{ $settings['app.welcome_message'] }}" required />

                    <x-form.textarea name="app[footer]" label="{{ __('medical.setting.footer') }}"
                        context="{{ __('medical.setting.footer_context') }}" minlength="10" maxlength="300"
                        value="{{ $settings['app.footer'] }}" required />

                    <x-form.text-input type="text" name="app[institution_name]" :label="__('medical.setting.institution_name')"
                        context="{{ __('medical.setting.footer_context') }}" minlength="5" maxlength="80"
                        value="{{ $settings['app.institution_name'] }}" :required="true" />

                    <x-form.textarea name="app[patient_message]" label="{{ __('medical.setting.patient_message') }}"
                        context="{{ __('medical.setting.patient_message_context') }}" minlength="20" maxlength="500"
                        value="{{ $settings['app.patient_message'] }}" required />
                </div>
                <hr>
                <br>
                <x-primary-button>
                    {{ __('medical.update') }}
                </x-primary-button>
            </form>
        </div>
@endsection
