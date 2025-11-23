<x-app-layout>

    <div class="main full-center">
        <x-form.titles :value="__('medical.titles.book_a_new_appointment')" size="edit-create" />
        <div class="container-form full-center">
            <!-- Indicador de pasos -->
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-fill" id="progress-fill"></div>
                </div>
                <div class="steps-wrapper">
                    <div class="step active" data-step="1">
                        <div class="step-circle">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <span class="step-label">{{ __('medical.titles.personal_data') }}</span>
                    </div>
                    <div class="step" data-step="2">
                        <div class="step-circle">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <span class="step-label">{{ __('reservation.step_2') }}</span>
                    </div>
                    <div class="step" data-step="3">
                        <div class="step-circle">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <span class="step-label">{{ __('reservation.step_3') }}</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('reservations.store') }}" method="post" id="multiStepForm">
                @csrf

                <!-- Campo hidden para indicar el tipo de paciente -->
                <input type="hidden" name="patient_type" id="patient_type" value="myself">

                <!-- Paso 1 - Datos Personales -->
                <div class="form-step active" data-step="1">
                    <!-- Selector de tipo de paciente -->
                    <div class="patient-type-selector mb-4">
                        <label class="form-label">{{ __('medical.titles.who_is_the_appointment_for') }}</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="patient_type_radio" id="patient_type_myself"
                                value="myself" checked>
                            <label class="btn btn-outline-primary" for="patient_type_myself">
                                <i class="bi bi-person-circle"></i> {{ __('medical.titles.for_myself') }}
                            </label>

                            <input type="radio" class="btn-check" name="patient_type_radio" id="patient_type_other"
                                value="other">
                            <label class="btn btn-outline-primary" for="patient_type_other">
                                <div class="feature">
                                    {{ __('medical.feature') }}
                                </div>
                                <i class="bi bi-person-plus-fill"></i> {{ __('medical.titles.for_other_person') }}
                            </label>
                        </div>
                    </div>

                    <!-- Sección para "Para mí" -->
                    <div id="for-myself-section" class="patient-section">
                        <div class="item">
                            <small>{{ __('contact.name_and_surname') }} </small>
                            <p>{{ $user->name . ' ' . $user->surname }}</p>
                            <small>{{ __('contact.idNumber') }} </small>
                            <p>{{ $user->idNumber }}</p>
                            <small>{{ __('contact.address') }} </small>
                            <p>{{ $user->address }}</p>
                            <small>{{ __('contact.phone') }} </small>
                            <p>{{ $user->phone }}</p>
                            <small>{{ __('contact.email') }} </small>
                            <p>{{ $user->email }}</p>
                        </div>
                    </div>

                    <!-- Sección para "Para otra persona" -->
                    <div id="for-other-section" class="patient-section" style="display: none;">
                        <x-form.text-input type="text" icon="person-fill" name="other_name" :label="__('contact.name')"
                            placeholder="{{ __('placeholder.name') }}" minlength="3" maxlength="40"
                            :required="false" />
                        <x-form.text-input type="text" icon="person-fill" name="other_surname" :label="__('contact.surname')"
                            placeholder="{{ __('placeholder.surname') }}" minlength="3" maxlength="40"
                            :required="false" />
                        <x-form.text-input type="text" icon="person-vcard-fill" name="other_idNumber"
                            :label="__('contact.idNumber')" placeholder="{{ __('placeholder.idNumber') }}" minlength="7"
                            maxlength="8" :required="false" />
                    </div>

                    <div class="full-center mt-10">
                        <button type="button" class="next-btn full-center">{{ __('button.next') }}<i
                                class="bi bi-chevron-right"></i></button>
                    </div>
                </div>
                <!-- Paso 2 - Selección de Appointment -->
                <div class="form-step" data-step="2">
                    <x-form.select name="specialty_id" :label="__('specialty.title')" icon="1-circle" :required="true">
                        <option value="">{{ __('reservation.specialty_option') }}</option>
                        @if (!empty($specialties))
                            @foreach ($specialties as $specialty)
                                @if ($specialty->status == 1)
                                    <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                @endif
                            @endforeach
                        @endif
                    </x-form.select>

                    <x-form.select name="doctor_id" :label="__('medical.doctor')" icon="2-circle" :required="true">
                        <option value="">{{ __('reservation.doctor_option') }}</option>
                    </x-form.select>
                    <x-form.select name="appointment_name_id" :label="__('reservation.title_name')" icon="3-circle" :required="true">
                        <option value="">{{ __('reservation.title_name_option') }}</option>
                    </x-form.select>


                    <x-form.select name="appointment_date" :label="__('reservation.date')" icon="4-circle" :required="true">
                        <option value="">{{ __('reservation.date_option') }}</option>
                    </x-form.select>


                    <x-form.select name="appointment_time" :label="__('reservation.time')" icon="5-circle" :required="true">
                        <option value="">{{ __('reservation.time_option') }}</option>
                        <input type="hidden" name="appointment_id" id="appointment_id">
                    </x-form.select>


                    <div class="form-navigation">
                        <button type="button" class="prev-btn full-center"><i
                                class="bi bi-chevron-left"></i>{{ __('button.back') }}</button>
                        <button type="button" class="next-btn full-center">{{ __('button.next') }}<i
                                class="bi bi-chevron-right"></i></button>
                    </div>
                </div>

                <!-- Paso 3 - Mensaje de Confirmación -->
                <div class="form-step" data-step="3">
                    <h2>{{ __('reservation.reservation_details_txt') }}</h2>
                    <div id="confirmation-details" class="mt-4">
                        <!-- Los detalles se cargan automáticamente -->
                    </div>

                    <x-boxed-info-section :reminder="true" />

                    <div class="form-navigation">
                        <button type="button" class="prev-btn full-center">
                            <i class="bi bi-chevron-left"></i>{{ __('button.back') }}
                        </button>
                        <x-primary-button>
                            {{ __('button.confirm') }}
                            <i class="bi bi-check-circle"></i>
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <script>
        // Pasar datos de Laravel a JavaScript
        const confirmationData = {
            specialtyText: document.getElementById('confirmation-data')?.dataset.specialtyText || 'Especialidad',
            addressText: document.getElementById('confirmation-data')?.dataset.addressText || 'Dirección',
            doctorText: document.getElementById('confirmation-data')?.dataset.doctorText || 'Profesional',
            dateText: document.getElementById('confirmation-data')?.dataset.dateText || 'Fecha',
            timeText: document.getElementById('confirmation-data')?.dataset.timeText || 'Horario',
            appointmentText: document.getElementById('confirmation-data')?.dataset.appointmentText || 'Turno',
            patientMessage: document.getElementById('confirmation-data')?.dataset.patientMessage ||
                'Por favor confirme su turno.'
        };
    </script>

</x-app-layout>
