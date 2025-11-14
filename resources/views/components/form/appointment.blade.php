<!-- Indicador de pasos -->
<div class="progress-container">
    <div class="progress-bar">
        <div class="progress-fill" id="progress-fill"></div>
    </div>
    <div class="steps-wrapper">
        <div class="step active" data-step="1">
            <div class="step-circle">
                <i class="bi bi-stars"></i>
            </div>
            <span class="step-label">{{ __('appointment.creation') }}</span>
        </div>
        <div class="step" data-step="2">
            <div class="step-circle">
                <i class="bi bi-clock-history"></i>
            </div>
            <span class="step-label">{{ __('reservation.time') }}</span>
        </div>
        <div class="step" data-step="3">
            <div class="step-circle">
                <i class="bi bi-calendar-date-fill"></i>
            </div>
            <span class="step-label">{{ __('reservation.date') }}</span>
        </div>
    </div>
</div>
<form x-data="iosCalendar()" x-init="init({{ $dates ?? '[]' }})" id="multiStepForm"
    @submit.prevent="updateSelectedDatesInput(); $el.submit()" method="POST" action="{{ $route }}">
    @csrf
    @if (!isset($create))
        @method('PATCH')
    @endif
    <!-- Paso 1 - Datos Iniciales -->
    <div class="form-step active" data-step="1">
        <div class="form-grid">
            <x-form.text-input type="text" name="name" :label="__('reservation.title_name')"
                value="{{ !isset($create) ? $appointment->name : old('name') }}" minlength="3" maxlength="80"
                :required="true" />

            <x-form.text-input type="text" name="address" :label="__('contact.address')"
                value="{{ !isset($create) ? $appointment->address : old('address') }}" minlength="3" maxlength="150"
                :required="true" />

            <x-form.select name="specialty_id" :label="__('specialty.name')" :required="true">
                @foreach ($specialties as $specialty)
                    @if ($specialty->status == 1)
                        <option value="{{ $specialty->id }}"
                            {{ !isset($create) ? ($appointment->specialty_id == $specialty->id ? 'selected' : '') : '' }}>
                            {{ $specialty->name }}</option>
                    @else
                        <option value="">{{ __('reservation.specialty_option') }}</option>
                    @endif
                @endforeach
            </x-form.select>

            <x-form.select name="doctor_id" :label="__('medical.doctor')" :required="true">
                @if (!isset($create) || isset($doctor_id))
                    <option value="{{ $appointment->doctor_id }}" selected>
                        {{ $appointment->doctor->name }}
                    </option>
                @else
                    <option value="">{{ __('reservation.doctor_option') }}</option>
                @endif
            </x-form.select>
        </div>

        <div class="form-navigation navegation-next">
            <button type="button" class="next-btn full-center">{{ __('button.next') }}<i
                    class="bi bi-chevron-right"></i></button>
        </div>
    </div>

    <!-- Paso 2 - Horarios -->
    <div class="form-step" data-step="2">
        <div class="form-grid">
            <div class="box-style">
                <h3>{{ __('appointment.shift.title') }}</h3>
                <div class="item-style">
                    <x-form.text-input type="radio" name="shift" value="morning" :label="__('appointment.shift.morning_shift')"
                        checkedValue="{{ !isset($create) ? ($appointment->shift == 'morning' ? 'checked' : '') : '' }}" />
                    <x-form.text-input type="radio" name="shift" value="afternoon " :label="__('appointment.shift.afternoon_shift')"
                        checkedValue="{{ !isset($create) ? ($appointment->shift == 'afternoon' ? 'checked' : '') : '' }}" />
                    <x-form.text-input type="radio" name="shift" value="night" :label="__('appointment.shift.night_shift')"
                        checkedValue="{{ !isset($create) ? ($appointment->shift == 'night' ? 'checked' : '') : '' }}" />
                </div>
            </div>

            <x-form.text-input type="number" name="number_of_reservations" :label="__('appointment.schedule.number_of_reservations')"
                value="{{ !isset($create) ? $appointment->number_of_reservations : old('number_of_reservations') }}"
                min="1" :required="true" />

            <x-form.text-input type="time" name="start_time" :label="__('appointment.schedule.start_time')"
                value="{{ !isset($create) ? $appointment->start_time->format('H:i') : old('start_time') }}"
                :required="true" />

            <x-form.text-input type="time" name="end_time" :label="__('appointment.schedule.end_time')"
                value="{{ !isset($create) ? $appointment->end_time->format('H:i') : old('end_time') }}"
                :required="true" />

            <div class="box-style">
                <br>
                <h3>{{ __('appointment.schedule.title') }}</h3>
                <div class="item-style">
                    <x-form.text-input type="radio" name="appointment_type" id="single_slot" value="single_slot"
                        :label="__('appointment.schedule.single')"
                        checkedValue="{{ !isset($appointment) || $appointment->available_time_slots == null ? 'checked' : '' }}" />
                    <x-form.text-input type="radio" name="appointment_type" id="multi_slot" value="multi_slot"
                        :label="__('appointment.schedule.multiple')"
                        checkedValue="{{ isset($appointment) && $appointment->available_time_slots != null ? 'checked' : '' }}" />
                    <input type="hidden" name="available_time_slots" id="available_time_slots">
                </div>
                <div id="horario-box">
                    <div class="item-style" id="horarios-item">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-navigation navegation-next">
            <button type="button" class="prev-btn full-center"><i
                    class="bi bi-chevron-left"></i>{{ __('button.back') }}</button>
            <button type="button" class="next-btn full-center">{{ __('button.next') }}<i
                    class="bi bi-chevron-right"></i></button>
        </div>
    </div>

    <!-- Paso 3 - Fecha y Estado -->
    <div class="form-step" data-step="3">
        <div class="form-grid">
            @include('layouts.calendar')
        </div>

        <x-form.select name="status" :label="__('medical.status.title')" :required="true">
            <option value="1" {{ !isset($create) ? ($appointment->status == true ? 'selected' : '') : '' }}>
                {{ __('medical.active') }}</option>
            <option value="0" {{ !isset($create) ? ($appointment->status == false ? 'selected' : '') : '' }}>
                {{ __('medical.inactive') }}</option>
        </x-form.select>

        <div class="form-navigation">
            <button type="button" class="prev-btn full-center"><i
                    class="bi bi-chevron-left"></i>{{ __('button.back') }}</button>
            <x-primary-button>
                @if (!isset($create))
                    {{ __('medical.update') }}
                    <i class="bi bi-cloud-arrow-up-fill"></i>
                @else
                    {{ __('medical.register') }}
                    <i class="bi bi-check-circle"></i>
                @endif
            </x-primary-button>
        </div>
    </div>
</form>
