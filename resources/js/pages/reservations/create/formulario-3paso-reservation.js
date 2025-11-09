// Este script maneja la lógica del formulario multipaso para la gestión de appointments
if (window.location.pathname.includes('/reservations/create') ||
    window.location.pathname.includes('/availableAppointments/edit') ||
    window.location.pathname.includes('/appointments/edit')) {

    document.addEventListener('DOMContentLoaded', function () {
        const steps = document.querySelectorAll('.form-step');
        const stepIndicators = document.querySelectorAll('.step-indicator .step');
        let currentStep = 0;

        // Elementos para el manejo del tipo de paciente
        const patientTypeRadios = document.querySelectorAll('input[name="patient_type_radio"]');
        const patientTypeHidden = document.getElementById('patient_type');
        const forMyselfSection = document.getElementById('for-myself-section');
        const forOtherSection = document.getElementById('for-other-section');
        const otherPersonInputs = forOtherSection.querySelectorAll('input');

        // Función para inicializar el estado de las secciones
        function initializePatientSections() {
            const selectedPatientType = document.querySelector('input[name="patient_type_radio"]:checked').value;
            updatePatientSections(selectedPatientType);
        }

        // Función para actualizar las secciones según el tipo de paciente
        function updatePatientSections(patientType) {
            patientTypeHidden.value = patientType;

            if (patientType === 'myself') {
                forMyselfSection.style.display = 'block';
                forOtherSection.style.display = 'none';
                // Limpiar campos de "otra persona"
                clearOtherPersonFields();
            } else {
                forMyselfSection.style.display = 'none';
                forOtherSection.style.display = 'block';
            }
        }

        // Función para limpiar campos de "otra persona"
        function clearOtherPersonFields() {
            otherPersonInputs.forEach(input => {
                input.value = '';
                input.style.borderColor = '';
            });
        }

        // Inicializar las secciones al cargar la página
        initializePatientSections();

        // Manejar cambio en el tipo de paciente
        patientTypeRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                updatePatientSections(this.value);
            });
        });

        // Mostrar el primer paso al cargar
        showStep(currentStep);

        // Manejadores para todos los botones Siguiente
        document.querySelectorAll('.next-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                if (validateStep(currentStep)) {
                    currentStep++;
                    showStep(currentStep);
                }
            });
        });

        // Manejadores para todos los botones Anterior
        document.querySelectorAll('.prev-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                currentStep--;
                showStep(currentStep);
            });
        });

        function showStep(stepIndex) {
            // Ocultar todos los pasos
            steps.forEach(step => step.classList.remove('active'));

            // Mostrar el paso actual
            steps[stepIndex].classList.add('active');

            // Actualizar indicadores de progreso
            updateProgressIndicator(stepIndex);

            // Mostrar/ocultar botones según el paso
            const allPrevButtons = document.querySelectorAll('.prev-btn');
            const allNextButtons = document.querySelectorAll('.next-btn');
            const submitButton = document.querySelector('.primary-btn');

            if (stepIndex === 0) {
                allPrevButtons.forEach(btn => btn.style.display = 'none');
                allNextButtons.forEach(btn => btn.style.display = 'block');
                if (submitButton) submitButton.style.display = 'none';
            }
            else if (stepIndex === 1) {
                allPrevButtons.forEach(btn => btn.style.display = 'block');
                allNextButtons.forEach(btn => btn.style.display = 'block');
                if (submitButton) submitButton.style.display = 'none';
            }
            else if (stepIndex === 2) {
                allPrevButtons.forEach(btn => btn.style.display = 'block');
                allNextButtons.forEach(btn => btn.style.display = 'none');
                if (submitButton) submitButton.style.display = 'block';
            }
        }

        function updateProgressIndicator(currentStep) {
            const steps = document.querySelectorAll('.step');
            const progressFill = document.getElementById('progress-fill');

            // Calcular porcentaje de progreso
            const progressPercentage = (currentStep / (steps.length - 1)) * 100;
            progressFill.style.width = `${progressPercentage}%`;

            // Actualizar estados de los pasos
            steps.forEach((step, index) => {
                step.classList.remove('active', 'completed');

                if (index < currentStep) {
                    step.classList.add('completed');
                } else if (index === currentStep) {
                    step.classList.add('active');
                }
            });
        }

        function validateStep(stepIndex) {
            const currentStepForm = steps[stepIndex];
            let isValid = true;

            // Validación específica para el paso 1
            if (stepIndex === 0) {
                const patientType = document.querySelector('input[name="patient_type_radio"]:checked').value;

                if (patientType === 'other') {
                    // Validar campos de "otra persona"
                    const requiredFields = ['other_name', 'other_surname', 'other_idNumber'];

                    requiredFields.forEach(fieldName => {
                        const input = document.querySelector(`[name="${fieldName}"]`);
                        if (input && !input.value.trim()) {
                            isValid = false;
                            input.style.borderColor = 'red';

                            // Scroll al primer campo con error
                            if (!isValid) {
                                input.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center'
                                });
                            }
                        } else if (input) {
                            input.style.borderColor = '';
                        }
                    });
                }
                // Para "para mí" no se necesita validación adicional
            } else {
                // Validación normal para otros pasos
                const inputs = currentStepForm.querySelectorAll('input[required], select[required]');

                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        isValid = false;
                        input.style.borderColor = 'red';

                        // Scroll al primer campo con error
                        if (!isValid) {
                            input.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }
                    } else {
                        input.style.borderColor = '';
                    }
                });
            }

            if (!isValid) {
                Swal.fire({
                    title: 'Campos incompletos',
                    text: 'Por favor complete todos los campos requeridos antes de continuar.',
                    icon: 'warning',
                    confirmButtonColor: 'var(--primary_color_btn)'
                });
            }

            return isValid;
        }
    });
}