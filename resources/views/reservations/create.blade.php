<x-app-layout>

    <div class="main full-center">
        <div class="container-form full-center">
            <h1>{{ __('medical.titles.book_a_new_appointment') }}</h1>

            <!-- Indicador de pasos -->
            <div class="step-indicator">
                <div class="step active" data-step="1"><i
                        class="bi bi-person-circle"></i><span>{{ __('medical.titles.personal_data') }}</span>
                </div>
                <div class="step" data-step="2"><i
                        class="bi bi-clock-history"></i><span>{{ __('reservation.step_2') }}</span> </div>
                <div class="step" data-step="3"><i
                        class="bi bi-check-circle-fill"></i><span>{{ __('reservation.step_3') }}</span>
                </div>
            </div>

            <form action="{{ route('confirmReservation') }}" method="post" id="multiStepForm">
                @csrf
                <!-- Paso 1 - Datos Personales -->
                <div class="form-step active" data-step="1">
                    <div class="item">
                        <x-input-label :value="__('contact.name')" />
                        <input name="name" value="{{ Auth::user()->name }}" readonly>

                        <x-input-label :value="__('contact.surname')" />
                        <input name="surname" value="{{ Auth::user()->surname }}" readonly>

                        <x-input-label :value="__('contact.email')" />
                        <input name="email" value="{{ Auth::user()->email }}" readonly>

                        <x-input-label :value="__('contact.phone')" />
                        <input name="phone" value="{{ Auth::user()->phone }}" readonly>

                        <x-input-label :value="__('contact.idNumber')" />
                        <input name="idNumber" value="{{ Auth::user()->idNumber }}" readonly>

                        <x-input-label :value="__('contact.address')" />
                        <input name="address" value="{{ Auth::user()->address }}" readonly>
                        <x-input-label :value="__('contact.birthdate')" />
                        <input name="fecha_nacimiento" value="{{ Auth::user()->birthdate }}" readonly>
                    </div>

                    <div class="navegation-next full-center  mt-4">
                        <button type="button" class="next-btn full-center">{{ __('button.next') }}<i
                                class="bi bi-chevron-right"></i></button>
                    </div>
                </div>

                <!-- Paso 2 - Selección de Appointment -->
                <div class="form-step" data-step="2">
                    <x-form.select name="specialty_id" :label="__('specialty.title')" icon="bi-1-circle" :required="true">
                        <option value="">{{ __('reservation.specialty_option') }}</option>
                        @if (!empty($specialties))
                            @foreach ($specialties as $specialty)
                                @if ($specialty->status == 1)
                                    <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                @endif
                            @endforeach
                        @endif
                    </x-form.select>

                    <x-form.select name="doctor_id" :label="__('medical.doctor')" icon="bi-2-circle" :required="true">
                        <option value="">{{ __('reservation.doctor_option') }}</option>
                    </x-form.select>
                    <x-form.select name="appointment_name_id" :label="__('reservation.title_name')" icon="bi-3-circle" :required="true">
                        <option value="">{{ __('reservation.title_name_option') }}</option>
                    </x-form.select>


                    <x-form.select name="appointment_date" :label="__('reservation.date')" icon="bi-4-circle" :required="true">
                        <option value="">{{ __('reservation.date_option') }}</option>
                    </x-form.select>


                    <x-form.select name="appointment_time" :label="__('reservation.time')" icon="bi-5-circle" :required="true">
                        <option value="">{{ __('reservation.time_option') }}</option>
                        <input type="hidden" name="appointment_id" id="appointment_id">
                    </x-form.select>


                    <div class="form-navigation  mt-4">
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
                        <!-- Los detalles se cargarán automáticamente -->
                    </div>
                    <div class="form-navigation mt-4">
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
        document.addEventListener('DOMContentLoaded', () => {
            // Configuración dinámica según tema
            function getCurrentTheme() {
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            const isDarkMode = getCurrentTheme() === 'dark';

            const form = document.getElementById('multiStepForm');
            const confirmBtn = document.querySelector('#multiStepForm [type="submit"]');

            // Cargar datos en el paso 3 cuando se avance desde el paso 2
            document.querySelectorAll('.next-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (this.closest('.form-step').dataset.step === "2") {
                        loadConfirmationData();
                    }
                });
            });

            if (confirmBtn) {
                confirmBtn.addEventListener('click', (e) => {
                    e.preventDefault();

                    // Validación básica
                    const requiredFields = ['specialty_id', 'doctor_id', 'appointment_name_id',
                        'appointment_date', 'appointment_time'
                    ];
                    const isValid = requiredFields.every(field => {
                        const element = document.getElementById(field);
                        return element && element.value;
                    });

                    if (!isValid) {
                        Swal.fire({
                            title: 'Campos incompletos',
                            text: 'Por favor complete todos los campos del formulario',
                            icon: 'warning',
                        });
                        return;
                    }

                    // Mostrar confirmación simple
                    Swal.fire({
                        title: '¿Confirmar turno?',
                        background: isDarkMode ?
                            'var(--dark_application_background)' :
                            'var(--light_application_background)',
                        color: isDarkMode ?
                            'var(--dark_text_color)' : 'var(--light_text_color)',
                        confirmButtonColor: 'var(--primary_color_btn)',
                        cancelButtonColor: '#dc3545',
                        html: '<p>{{ config('app.patient_message') }}</p>',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            }

            function loadConfirmationData() {
                // Obtener elementos
                const specialtySelect = document.getElementById('specialty_id');
                const doctorSelect = document.getElementById('doctor_id');
                const appointmentNameSelect = document.getElementById('appointment_name_id');
                const dateSelect = document.getElementById('appointment_date');
                const timeSelect = document.getElementById('appointment_time');
                const confirmationDetails = document.getElementById('confirmation-details');

                // Obtener textos seleccionados
                const specialtyText = specialtySelect.options[specialtySelect.selectedIndex].text;
                const doctorText = doctorSelect.options[doctorSelect.selectedIndex].text;
                const appointmentNameText = appointmentNameSelect.options[appointmentNameSelect.selectedIndex].text;
                const dateText = dateSelect.options[dateSelect.selectedIndex].text;
                const timeText = timeSelect.options[timeSelect.selectedIndex].text;

                // Obtener datos adicionales (usando Blade)
                let address = 'No especificada';
                @if (!empty($appointments))
                    @foreach ($appointments as $item)
                        if ({{ $item->id }} == appointmentNameSelect.value) {
                            address = '{{ $item->address }}';
                        }
                    @endforeach
                @endif
                let specialtyDescription = 'No especificada';
                @if (!empty($specialties))
                    @foreach ($specialties as $item)
                        if ({{ $item->id }} == specialtySelect.value) {
                            specialtyDescription = '{{ $item->description }}';
                        }
                    @endforeach
                @endif

                // Construir HTML de confirmación
                confirmationDetails.innerHTML = `
            <div class="confirmation-detail">
                <p><strong>{{ __('reservation.title_name') }}:</strong> ${appointmentNameText}</p>
                <p><strong>{{ __('contact.address') }}:</strong> ${address}</p>
                <p><strong>{{ __('specialty.title') }}:</strong> ${specialtyText}</p>
                <p><strong>{{ __('medical.description_txt') }}:</strong> ${specialtyDescription}</p>
                <hr>
                <p><strong>{{ __('medical.doctor') }}:</strong> ${doctorText}</p>
                <p><strong>{{ __('reservation.date') }}:</strong> ${dateText}</p>
                <p><strong>{{ __('reservation.time') }}:</strong> ${timeText}</p>
            </div>
        `;
            }
        });
    </script>

</x-app-layout>
