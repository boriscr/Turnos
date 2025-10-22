<!-- resources/views/dashboard/design.blade.php -->
@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="">
        <h1>{{ __('navbar.settings') }}</h1>
        <h2>{{ __('medical.setting.title_3') }}</h2>
        <div class="container-form full-center">
            <form action="{{ route('settings.update') }}" method="post">
                @csrf
                @method('PUT')
                <br>
                <div class="item-colores">
                    <div class="card">
                        <h5>{{ __('medical.setting.subtitle_1') }}</h5>
                        <x-form.text-input type="color" name="design[general_design_color]" :label="__('medical.setting.general_design_color')"
                            value="{{ $settings['design.general_design_color'] }}" />
                        <x-form.text-input type="color" name="design[title_text_color]" :label="__('medical.setting.title_text_color')"
                            value="{{ $settings['design.title_text_color'] }}" />
                        <x-form.text-input type="color" name="design[subtitle_text_color]" :label="__('medical.setting.subtitle_text_color')"
                            value="{{ $settings['design.subtitle_text_color'] }}" />
                        <x-form.text-input type="color" name="design[primary_color_btn]" :label="__('medical.setting.primary_color_btn')"
                            value="{{ $settings['design.primary_color_btn'] }}" />
                        <x-form.text-input type="color" name="design[secondary_color_btn]" :label="__('medical.setting.secondary_color_btn')"
                            value="{{ $settings['design.secondary_color_btn'] }}" />
                        <x-form.text-input type="color" name="design[btn_text_color]" :label="__('medical.setting.btn_text_color')"
                            value="{{ $settings['design.btn_text_color'] }}" />
                        <x-form.text-input type="color" name="design[footer_background]" :label="__('medical.setting.footer_background')"
                            value="{{ $settings['design.footer_background'] }}" />
                    </div>
                    <hr>
                    <div class="card dark-card">
                        <h3>{{ __('medical.setting.subtitle_2') }}</h3>
                        <x-form.text-input type="color" name="design[dark_application_background]" :label="__('medical.setting.application_background')"
                            value="{{ $settings['design.dark_application_background'] }}" />
                        <x-form.text-input type="color" name="design[dark_text_color]" :label="__('medical.setting.text_color')"
                            value="{{ $settings['design.dark_text_color'] }}" />
                        <x-form.text-input type="color" name="design[text_color_small_dark]" :label="__('medical.setting.text_color_small')"
                            value="{{ $settings['design.text_color_small_dark'] }}" />
                        <x-form.text-input type="color" name="design[background_navbar_dark]" :label="__('medical.setting.background_navbar')"
                            value="{{ $settings['design.background_navbar_dark'] }}" />
                        <x-form.text-input type="color" name="design[background_login_and_register_dark]"
                            :label="__('medical.setting.background_login_and_register')" value="{{ $settings['design.background_login_and_register_dark'] }}" />
                        <x-form.text-input type="color" name="design[text_color_form_elements_dark]" :label="__('medical.setting.text_color_form_elements')"
                            value="{{ $settings['design.text_color_form_elements_dark'] }}" />
                    </div>
                    <hr>
                    <div class="card light-card">
                        <h3>{{ __('medical.setting.subtitle_3') }}</h3>
                        <x-form.text-input type="color" name="design[light_application_background]" :label="__('medical.setting.application_background')"
                            value="{{ $settings['design.light_application_background'] }}" />
                        <x-form.text-input type="color" name="design[light_text_color]" :label="__('medical.setting.text_color')"
                            value="{{ $settings['design.light_text_color'] }}" />
                        <x-form.text-input type="color" name="design[text_color_small_light]" :label="__('medical.setting.text_color_small')"
                            value="{{ $settings['design.text_color_small_light'] }}" />
                        <x-form.text-input type="color" name="design[background_navbar_light]" :label="__('medical.setting.background_navbar')"
                            value="{{ $settings['design.background_navbar_light'] }}" />
                        <x-form.text-input type="color" name="design[background_login_and_register_light]"
                            :label="__('medical.setting.background_login_and_register')" value="{{ $settings['design.background_login_and_register_light'] }}" />
                        <x-form.text-input type="color" name="design[text_color_form_elements_light]" :label="__('medical.setting.text_color_form_elements')"
                            value="{{ $settings['design.text_color_form_elements_light'] }}" />
                    </div> <br>
                    <hr>
                    <br>
                    <x-primary-button>
                        {{ __('medical.update') }}
                    </x-primary-button>
            </form>
        </div>
    </div>
@endsection
