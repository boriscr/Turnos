// Este script maneja la lógica del formulario multipaso para la gestión de appointments
if (window.location.pathname.includes('/reservations/create') || 
    window.location.pathname.includes('/availableAppointments/edit') || 
    window.location.pathname.includes('/appointments/edit')) {

    document.addEventListener('DOMContentLoaded', function () {
        const steps = document.querySelectorAll('.form-step');
        const stepIndicators = document.querySelectorAll('.step-indicator .step');
        let currentStep = 0;

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

            // Actualizar indicadores
            stepIndicators.forEach((indicator, index) => {
                if (index <= stepIndex) {
                    indicator.classList.add('active');
                } else {
                    indicator.classList.remove('active');
                }
            });

            // Mostrar/ocultar botones según el paso
            const allPrevButtons = document.querySelectorAll('.prev-btn');
            const allNextButtons = document.querySelectorAll('.next-btn');
            const submitButton = document.querySelector('.primary-btn');

            if (stepIndex === 0) {
                // Primer paso - ocultar botones Anterior
                allPrevButtons.forEach(btn => btn.style.display = 'none');
                allNextButtons.forEach(btn => btn.style.display = 'block');
                if (submitButton) submitButton.style.display = 'none';
            } 
            else if (stepIndex === 1) {
                // Segundo paso - mostrar ambos botones
                allPrevButtons.forEach(btn => btn.style.display = 'block');
                allNextButtons.forEach(btn => btn.style.display = 'block');
                if (submitButton) submitButton.style.display = 'none';
            }
            else if (stepIndex === 2) {
                // Tercer paso - ocultar Siguiente, mostrar Confirmar
                allPrevButtons.forEach(btn => btn.style.display = 'block');
                allNextButtons.forEach(btn => btn.style.display = 'none');
                if (submitButton) submitButton.style.display = 'block';
            }
        }

        function validateStep(stepIndex) {
            const currentStepForm = steps[stepIndex];
            const inputs = currentStepForm.querySelectorAll('input[required], select[required]');
            let isValid = true;

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