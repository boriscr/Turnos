document.addEventListener('DOMContentLoaded', function () {
    // Seleccionar todos los inputs del formulario
    const inputs = document.querySelectorAll('input, select');

    // Agregar eventos de validación en tiempo real
    inputs.forEach(input => {
        // Validar mientras se escribe
        input.addEventListener('input', function () {
            validateField(this);
        });

        // Validar al perder el foco
        input.addEventListener('blur', function () {
            validateField(this);
        });
    });

    // Función para validar cada campo
    function validateField(field) {
        const value = field.value.trim();
        const errorElement = field.nextElementSibling;

        // Limpiar errores previos
        field.classList.remove('border-red-500');
        if (errorElement && errorElement.classList.contains('input-error')) {
            errorElement.textContent = '';
        }

        // Validaciones específicas para cada campo
        switch (field.id) {
            case 'name':
            case 'surname':
                if (!value) {
                    showError(field, 'Este campo es obligatorio');
                } else if (value.length > 255) {
                    showError(field, 'Máximo 255 caracteres permitidos');
                }
                break;

            case 'idNumber':
                if (!value) {
                    showError(field, 'El DNI es obligatorio');
                } else if (!/^\d{7,8}$/.test(value)) {
                    showError(field, 'El DNI debe tener 7 u 8 dígitos numéricos');
                }
                break;

            case 'birthdate':
                if (!value) {
                    showError(field, 'La fecha de nacimiento es obligatoria');
                } else {
                    const birthDate = new Date(value);
                    const today = new Date();

                    let age = today.getFullYear() - birthDate.getFullYear();
                    const m = today.getMonth() - birthDate.getMonth();

                    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }

                    if (age < 0 || isNaN(age)) {
                        showError(field, 'Fecha inválida');
                    } else {
                        document.getElementById('edad').textContent = `Edad: ${age} años`;
                    }
                }
                break;
            case 'gender':
                if (!value) {
                    showError(field, 'Por favor selecciona un género');
                } else if (!['Masculino', 'Femenino', 'No binario', 'Otro', 'Prefiero no decir'].includes(value)) {
                    showError(field, 'Selecciona una opción válida');
                }
                break;

            case 'country':
            case 'province':
            case 'city':
            case 'address':
                if (!value) {
                    showError(field, 'Este campo es obligatorio');
                } else if (value.length > 255) {
                    showError(field, 'Máximo 255 caracteres permitidos');
                }
                break;

            case 'phone':
                if (!value) {
                    showError(field, 'El teléfono es obligatorio');
                } else if (value.length < 9) {
                    showError(field, 'Mínimo 9 caracteres permitidos');
                } else if (value.length > 15) {
                    showError(field, 'Máximo 15 caracteres permitidos');
                } else if (!/^[\d\s+-]+$/.test(value)) {
                    showError(field, 'Ingresa un número de teléfono válido');
                }
                break;

            case 'email':
                if (!value) {
                    showError(field, 'El email es obligatorio');
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    showError(field, 'Ingresa un email válido');
                } else if (value.length > 255) {
                    showError(field, 'Máximo 255 caracteres permitidos');
                }
                break;

            case 'password':
                if (!value) {
                    showError(field, 'La contraseña es obligatoria');
                } else if (value.length < 12) {
                    showError(field, 'Mínimo 12 caracteres requeridos');
                } else if (!/[A-Z]/.test(value)) {
                    showError(field, 'Debe contener al menos una mayúscula');
                } else if (!/[a-z]/.test(value)) {
                    showError(field, 'Debe contener al menos una minúscula');
                } else if (!/[0-9]/.test(value)) {
                    showError(field, 'Debe contener al menos un número');
                } else if (!/[^A-Za-z0-9]/.test(value)) {
                    showError(field, 'Debe contener al menos un símbolo');
                }
                break;

            case 'password_confirmation':
                const password = document.getElementById('password').value;
                if (!value) {
                    showError(field, 'Confirma tu contraseña');
                } else if (value !== password) {
                    showError(field, 'Las contraseñas no coinciden');
                }
                break;
        }
    }

    // Función para mostrar errores
    function showError(field, message) {
        field.classList.add('border-red-500');
        const errorElement = field.nextElementSibling;

        if (errorElement && errorElement.classList.contains('input-error')) {
            errorElement.textContent = message;
        } else {
            // Si no hay elemento de error, crear uno
            const errorDiv = document.createElement('div');
            errorDiv.className = 'input-error mt-2 text-sm text-red-600';
            errorDiv.textContent = message;
            field.parentNode.insertBefore(errorDiv, field.nextSibling);
        }
    }

    // Validar DNI mientras se escribe (solo números)
    const dniInput = document.getElementById('idNumber');
    if (dniInput) {
        dniInput.addEventListener('input', function () {
            // Permitir letras y números
            this.value = this.value.replace(/[^a-zA-Z0-9]/g, '').slice(0, 10);

        });
    }

    // Validar contraseña en tiempo real
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function () {
            validatePasswordStrength(this.value);
        });
    }

    // Función para mostrar fortaleza de contraseña
    function validatePasswordStrength(password) {
        const requirements = {
            length: password.length >= 12,
            upper: /[A-Z]/.test(password),
            lower: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            symbol: /[^A-Za-z0-9]/.test(password)
        };

        const messageList = document.querySelector('.message-list-password');
        if (messageList) {
            const items = messageList.querySelectorAll('li');

            items[0].style.color = requirements.length ? 'green' : 'red';
            items[1].style.color = (requirements.upper && requirements.lower &&
                requirements.number && requirements.symbol) ? 'green' : 'red';
        }
    }
});