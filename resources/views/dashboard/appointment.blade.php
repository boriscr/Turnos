<!-- resources/views/dashboard/design.blade.php -->
@extends('layouts.dashboard')

@section('dashboard-content')
        <h1>{{ __('navbar.settings') }}</h1>
        <div class="container-form full-center">
            <form action="{{ route('settings.update') }}" method="post">
                @csrf
                @method('PUT')
                <div class="item-reservations">
                    <h2>{{ __('medical.setting.title_2') }}</h2>
                    <x-form.text-input type="number" name="appointments[maximum_faults]" :label="__('medical.setting.maximum_faults')"
                        context="{{ __('medical.setting.maximum_faults_context') }}" minlength="0" :required="true"
                        value="{{ $settings['appointments.maximum_faults'] }}" />

                    <x-form.text-input type="number" name="appointments[daily_limit]" :label="__('medical.setting.daily_limit')"
                        context="{{ __('medical.setting.daily_limit_context') }}" minlength="0" :required="true"
                        value="{{ $settings['appointments.daily_limit'] }}" />

                    <x-form.text-input type="number" name="appointments[cancellation_hours]" :label="__('medical.setting.cancellation_hours')"
                        context="{{ __('medical.setting.cancellation_hours_context') }}" minlength="0" :required="true"
                        value="{{ $settings['appointments.cancellation_hours'] }}" />
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
                <hr>
                <br>
                <x-primary-button>
                    {{ __('medical.update') }}
                </x-primary-button>
            </form>
        </div>
@endsection
