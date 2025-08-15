<x-app-layout>
    <div class="main full-center">
        <h1>{{ __('navbar.settings') }}</h1>
        <div class="container-form full-center">
            <form action="{{ route('settings.update') }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <h2>{{ __('medical.setting.title_1') }}</h2>
                    <x-form.text-input type="text" name="app[name]" :label="__('medical.setting.name')"
                        context="{{ __('medical.setting.name_context') }}" minlength="3" maxlength="255"
                        :required="true" value="{{ $settings['app.name'] }}" />

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
                <br>
                <div class="item-reservations">
                    <h2>{{ __('medical.setting.title_2') }}</h2>
                    <x-form.text-input type="number" name="appointments[maximum_faults]" :label="__('medical.setting.maximum_faults')"
                        context="{{ __('medical.setting.maximum_faults_context') }}" minlength="0" :required="true"
                        value="{{ $settings['appointments.maximum_faults'] }}" />

                    <x-form.text-input type="number" name="appointments[daily_limit]" :label="__('medical.setting.daily_limit')"
                        context="{{ __('medical.setting.daily_limit_context') }}" minlength="0" :required="true"
                        value="{{ $settings['appointments.daily_limit'] }}" />

                    <x-form.text-input type="number" name="appointments[cancellation_hours]" :label="__('medical.setting.cancellation_hours')"
                        context="{{ __('medical.setting.cancellation_hours_context') }}" minlength="0"
                        :required="true" value="{{ $settings['appointments.cancellation_hours'] }}" />
                    <br>
                    <div class="box-style box-border">
                        <x-form.text-input type="number" name="appointments[advance_reservation]" :label="__('medical.setting.advance_reservation')"
                            context="{{ __('medical.setting.advance_reservation_context') }}" minlength="0"
                            :required="true" value="{{ $settings['appointments.advance_reservation'] }}" />
                        <label for="unit_advance"> {{ __('medical.setting.unit_advance') }}</label>
                        <small>{{ __('medical.setting.unit_advance_context') }}</small>
                        <div class="item-style">
                            <x-form.text-input type="radio" name="appointments[unit_advance]" value="time"
                                :label="__('medical.setting.time')"
                                checkedValue="{{ $settings['appointments.unit_advance'] == 'time' ? 'checked' : '' }}" />

                            <x-form.text-input type="radio" name="appointments[unit_advance]" value="day"
                                :label="__('medical.setting.day')"
                                checkedValue="{{ $settings['appointments.unit_advance'] == 'day' ? 'checked' : '' }}" />

                            <x-form.text-input type="radio" name="appointments[unit_advance]" value="month"
                                :label="__('medical.setting.month')"
                                checkedValue="{{ $settings['appointments.unit_advance'] == 'month' ? 'checked' : '' }}" />
                        </div>
                    </div>

                    <x-form.text-input type="number" name="assists[verification_interval]" :label="__('medical.setting.verification_interval')"
                        context="{{ __('medical.setting.verification_interval_context') }}" minlength="1"
                        :required="true" value="{{ $settings['assists.verification_interval'] }}" />
                </div>
                <br>
                <div class="item-colores">
                    <h2>{{ __('medical.setting.title_3') }}</h2>
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
                        <x-form.text-input type="color" name="design[text_color_form_elements_dark]"
                            :label="__('medical.setting.text_color_form_elements')" value="{{ $settings['design.text_color_form_elements_dark'] }}" />
                    </div>
                    <hr>
                    <div class="card light-card">
                        <h3>{{ __('medical.setting.subtitle_3') }}</h3>
                        <x-form.text-input type="color" name="design[light_application_background]"
                            :label="__('medical.setting.application_background')" value="{{ $settings['design.light_application_background'] }}" />
                        <x-form.text-input type="color" name="design[light_text_color]" :label="__('medical.setting.text_color')"
                            value="{{ $settings['design.light_text_color'] }}" />
                        <x-form.text-input type="color" name="design[text_color_small_light]" :label="__('medical.setting.text_color_small')"
                            value="{{ $settings['design.text_color_small_light'] }}" />
                        <x-form.text-input type="color" name="design[background_navbar_light]" :label="__('medical.setting.background_navbar')"
                            value="{{ $settings['design.background_navbar_light'] }}" />
                        <x-form.text-input type="color" name="design[background_login_and_register_light]"
                            :label="__('medical.setting.background_login_and_register')"
                            value="{{ $settings['design.background_login_and_register_light'] }}" />
                        <x-form.text-input type="color" name="design[text_color_form_elements_light]"
                            :label="__('medical.setting.text_color_form_elements')" value="{{ $settings['design.text_color_form_elements_light'] }}" />
                    </div>
                </div>
                <br>
                <hr>
                <br>
                <x-primary-button>
                    {{ __('medical.update') }}
                </x-primary-button>
            </form>
        </div>
</x-app-layout>
