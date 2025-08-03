document.addEventListener('DOMContentLoaded', function() {
    // Inicializar pasos
    const totalSteps = 3;
    const stepsContainer = document.querySelector('.progress-steps .d-flex');
    const template = document.getElementById('step-template');
    
    // Generar pasos
    for (let i = 1; i <= totalSteps; i++) {
        const stepClone = template.content.cloneNode(true);
        const stepCircle = stepClone.querySelector('.step-circle');
        stepCircle.textContent = i;
        stepCircle.setAttribute('data-step', i);
        
        if (i === 1) {
            stepCircle.classList.add('active');
        }
        
        if (i < totalSteps) {
            const connector = stepClone.querySelector('.step-connector');
            if (i === 1) {
                connector.classList.add('active');
            }
        }
        
        stepsContainer.appendChild(stepCircle.parentNode);
    }
    
    // Mostrar primer paso
    document.getElementById('step-1').classList.remove('d-none');
    
    // Manejar botones de siguiente/atrás
    document.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', function() {
            const currentStep = document.querySelector('.form-step:not(.d-none)');
            const nextStepId = this.getAttribute('data-next');
            const nextStep = document.getElementById(`step-${nextStepId}`);
            
            if (validateStep(currentStep)) {
                currentStep.classList.add('d-none');
                nextStep.classList.remove('d-none');
                updateProgress(nextStepId);
            }
        });
    });
    
    document.querySelectorAll('.prev-step').forEach(button => {
        button.addEventListener('click', function() {
            const currentStep = document.querySelector('.form-step:not(.d-none)');
            const prevStepId = this.getAttribute('data-prev');
            const prevStep = document.getElementById(`step-${prevStepId}`);
            
            currentStep.classList.add('d-none');
            prevStep.classList.remove('d-none');
            updateProgress(prevStepId);
        });
    });
    
    // Validar formulario antes de enviar
    document.getElementById('submit-form').addEventListener('click', function() {
        if (validateAllSteps()) {
            Swal.fire({
                title: '¿Confirmar registro?',
                html: `
                    <p class="text-left">
                        Los datos que estás por registrar serán utilizados para la reservation de appointments. 
                        <strong>Algunos datos no podrán ser editados posteriormente</strong>, ya que estarán asociados de forma exclusiva a esta cuenta.
                    </p>
                    <p class="text-left mt-2">
                        Si los datos son incorrectos, podrías recibir la negación de appointments o no poder gestionarlos correctamente.
                    </p>
                    <p class="text-left mt-2 font-semibold">
                        Por favor, revisa cuidadosamente toda la información antes de continuar.
                    </p>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Sí, registrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('registerForm').submit();
                }
            });
        }
    });
    
    // Funciones auxiliares
    function validateStep(stepElement) {
        let isValid = true;
        const inputs = stepElement.querySelectorAll('input[required], select[required]');
        
        inputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Por favor completa todos los campos antes de continuar.'
            });
        }
        
        return isValid;
    }
    
    function validateAllSteps() {
        let isValid = true;
        const allInputs = document.querySelectorAll('input[required], select[required]');
        
        allInputs.forEach(input => {
            if (!input.value.trim()) {
                // Mostrar el paso que contiene este campo
                const step = input.closest('.form-step');
                if (step) {
                    document.querySelectorAll('.form-step').forEach(s => s.classList.add('d-none'));
                    step.classList.remove('d-none');
                }
                
                input.classList.add('is-invalid');
                isValid = false;
            }
        });
        
        if (!isValid) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Por favor completa todos los campos antes de enviar el formulario.'
            });
        }
        
        return isValid;
    }
    
    function updateProgress(currentStep) {
        document.querySelectorAll('.step-circle').forEach(circle => {
            const step = parseInt(circle.getAttribute('data-step'));
            
            circle.classList.remove('active', 'completed');
            if (step < currentStep) {
                circle.classList.add('completed');
            } else if (step == currentStep) {
                circle.classList.add('active');
            }
        });
        
        document.querySelectorAll('.step-connector').forEach(connector => {
            const step = parseInt(connector.previousElementSibling.getAttribute('data-step'));
            
            connector.classList.remove('active', 'completed');
            if (step < currentStep) {
                connector.classList.add('completed');
            } else if (step < currentStep) {
                connector.classList.add('active');
            }
        });
    }
});