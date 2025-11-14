// Este script maneja la lógica del formulario multipaso para la gestión de appointments
if (window.location.pathname.includes('/reservations/create') ||
    window.location.pathname.includes('/availableAppointments/edit') ||
    window.location.pathname.includes('/appointments/create') ||
    window.location.pathname.includes('/appointments/edit')) {

    document.addEventListener('DOMContentLoaded', function () {
        const steps = document.querySelectorAll('.form-step');
        let currentStep = 0;

        // Elementos comunes para todos los formularios
        const progressFill = document.getElementById('progress-fill');
        const allSteps = document.querySelectorAll('.step');

        // Elementos específicos para el formulario de reservas
        const patientTypeRadios = document.querySelectorAll('input[name="patient_type_radio"]');
        const patientTypeHidden = document.getElementById('patient_type');
        const forMyselfSection = document.getElementById('for-myself-section');
        const forOtherSection = document.getElementById('for-other-section');
        const otherPersonInputs = forOtherSection ? forOtherSection.querySelectorAll('input') : [];

        // Inicializar secciones de paciente si existen
        if (patientTypeRadios.length > 0) {
            initializePatientSections();
            
            // Manejar cambio en el tipo de paciente
            patientTypeRadios.forEach(radio => {
                radio.addEventListener('change', function () {
                    updatePatientSections(this.value);
                });
            });
        }

        // Función para inicializar el estado de las secciones (solo para reservas)
        function initializePatientSections() {
            const selectedPatientType = document.querySelector('input[name="patient_type_radio"]:checked').value;
            updatePatientSections(selectedPatientType);
        }

        // Función para actualizar las secciones según el tipo de paciente (solo para reservas)
        function updatePatientSections(patientType) {
            if (patientTypeHidden) patientTypeHidden.value = patientType;

            if (patientType === 'myself') {
                forMyselfSection.style.display = 'block';
                forOtherSection.style.display = 'none';
                clearOtherPersonFields();
            } else {
                forMyselfSection.style.display = 'none';
                forOtherSection.style.display = 'block';
            }
        }

        // Función para limpiar campos de "otra persona" (solo para reservas)
        function clearOtherPersonFields() {
            otherPersonInputs.forEach(input => {
                input.value = '';
                input.style.borderColor = '';
            });
        }

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

            // Configuración común para todos los formularios
            if (stepIndex === 0) {
                // Primer paso - ocultar botones Anterior
                allPrevButtons.forEach(btn => btn.style.display = 'none');
                allNextButtons.forEach(btn => btn.style.display = 'block');
                if (submitButton) submitButton.style.display = 'none';
                
                // Ajuste específico para appointments
                const navegationNext = document.querySelector('.navegation-next');
                if (navegationNext && window.location.pathname.includes('/appointments')) {
                    navegationNext.style.justifyContent = 'center';
                }
            } 
            else if (stepIndex === steps.length - 1) {
                // Último paso - ocultar Siguiente, mostrar Confirmar
                allPrevButtons.forEach(btn => btn.style.display = 'block');
                allNextButtons.forEach(btn => btn.style.display = 'none');
                if (submitButton) submitButton.style.display = 'block';
            }
            else {
                // Pasos intermedios - mostrar ambos botones
                allPrevButtons.forEach(btn => btn.style.display = 'block');
                allNextButtons.forEach(btn => btn.style.display = 'block');
                if (submitButton) submitButton.style.display = 'none';
            }
        }

        function updateProgressIndicator(currentStep) {
            if (!progressFill || !allSteps.length) return;

            // Calcular porcentaje de progreso
            const progressPercentage = (currentStep / (allSteps.length - 1)) * 100;
            progressFill.style.width = `${progressPercentage}%`;

            // Actualizar estados de los pasos
            allSteps.forEach((step, index) => {
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

            // Validación específica para el paso 1 de reservas
            if (stepIndex === 0 && window.location.pathname.includes('/reservations')) {
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
            }
            // Validación específica para el paso 1 de appointments
            else if (stepIndex === 1 && window.location.pathname.includes('/appointments')) {
                // Validación adicional para checkboxes en paso 2 de appointments
                const turnoCheckboxes = currentStepForm.querySelectorAll('input[name="shift"]:checked');
                if (turnoCheckboxes.length === 0) {
                    isValid = false;
                    // Mostrar error específico para appointments
                    showValidationError('Seleccione al menos un tipo de turno');
                    return isValid;
                }
            }

            // Validación normal para campos requeridos
            const inputs = currentStepForm.querySelectorAll('input[required], select[required], textarea[required]');
            
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

            if (!isValid) {
                showValidationError('Por favor complete todos los campos requeridos antes de continuar.');
            }

            return isValid;
        }

        function showValidationError(message) {
            // Usar SweetAlert si está disponible, si no usar alert nativo
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Campos incompletos',
                    text: message,
                    icon: 'warning',
                    confirmButtonColor: 'var(--primary_color_btn)'
                });
            } else {
                alert(message);
            }
        }
    });
}