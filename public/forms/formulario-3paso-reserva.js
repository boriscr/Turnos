// Este script maneja la lógica del formulario multipaso para la gestión de turnos
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('multiStepForm');
    const steps = document.querySelectorAll('.form-step');
    const stepIndicators = document.querySelectorAll('.step-indicator .step');
    let currentStep = 0;
    
    // Mostrar el primer paso al cargar
    showStep(currentStep);
    
    // Botón Siguiente
    document.querySelector('.next-btn').addEventListener('click', function() {
        if (validateStep(currentStep)) {
            currentStep++;
            showStep(currentStep);
        }
    });
    
    // Botón Anterior
    document.querySelector('.prev-btn').addEventListener('click', function() {
        currentStep--;
        showStep(currentStep);
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
        if (stepIndex === 0) {
            document.querySelector('.prev-btn').style.display = 'none';
        } else {
            document.querySelector('.prev-btn').style.display = 'block';
        }
        
        if (stepIndex === steps.length - 1) {
            document.querySelector('.next-btn').style.display = 'none';
            document.querySelector('.submit-btn').style.display = 'block';
        } else {
            document.querySelector('.next-btn').style.display = 'block';
            document.querySelector('.submit-btn').style.display = 'none';
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
            } else {
                input.style.borderColor = '';
            }
        });
        
        if (!isValid) {
            alert('Por favor complete todos los campos requeridos antes de continuar.');
        }
        
        return isValid;
    }
});
