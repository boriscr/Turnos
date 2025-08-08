if (window.location.pathname.includes('/appointments/create') ||window.location.pathname.includes('/appointments/edit')) {

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('multiStepForm');
        const steps = document.querySelectorAll('.form-step');
        const stepIndicators = document.querySelectorAll('.step-indicator .step');
        let currentStep = 0;

        // Mostrar el primer paso al cargar
        showStep(currentStep);

        // Botón Siguiente
        document.querySelectorAll('.next-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                if (validateStep(currentStep)) {
                    currentStep++;
                    showStep(currentStep);
                }
            });
        });

        // Botón Anterior
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

            // Configurar navegación según el paso
            const allPrevBtns = document.querySelectorAll('.prev-btn');
            const allNextBtns = document.querySelectorAll('.next-btn');
            const submitBtn = document.querySelector('.primary-btn');

            if (stepIndex === 0) {
                // Primer paso - solo siguiente
                allPrevBtns.forEach(btn => btn.style.display = 'none');
                document.querySelector('.navegation-next').style.justifyContent = 'center';
            } else {
                // Pasos intermedios - mostrar anterior
                allPrevBtns.forEach(btn => btn.style.display = 'block');
            }

            if (stepIndex === steps.length - 1) {
                // Último paso - mostrar enviar
                allNextBtns.forEach(btn => btn.style.display = 'none');
                if (submitBtn) submitBtn.style.display = 'block';
            } else {
                // Pasos intermedios - mostrar siguiente
                allNextBtns.forEach(btn => btn.style.display = 'block');
                if (submitBtn) submitBtn.style.display = 'none';
            }
        }

        function validateStep(stepIndex) {
            const currentStepForm = steps[stepIndex];
            const inputs = currentStepForm.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.style.borderColor = 'red';
                } else {
                    input.style.borderColor = '';
                }
            });

            // Validación adicional para checkboxes en paso 2
            if (stepIndex === 1) {
                const turnoCheckboxes = currentStepForm.querySelectorAll('input[name="shift"]:checked');
                if (turnoCheckboxes.length === 0) {
                    isValid = false;
                    alert('Seleccione al menos un tipo de appointment');
                }
            }

            if (!isValid) {
                alert('Por favor complete todos los campos requeridos antes de continuar.');
            }

            return isValid;
        }
    });
}