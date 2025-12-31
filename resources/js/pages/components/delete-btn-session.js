if (window.location.pathname.includes('/profile/session') || window.location.pathname.includes('/users/show')) {

    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-btn-session');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('.delete-form');

                Swal.fire({
                    title: 'Confirmación de identidad',
                    text: "Por favor, introduce tu contraseña para confirmar esta acción:",
                    icon: 'auth', // Puedes usar 'warning' o un icono personalizado
                    input: 'password',
                    inputAttributes: {
                        autocapitalize: 'off',
                        autocorrect: 'off',
                        placeholder: 'Tu contraseña actual'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Confirmar y Cerrar Sesión',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#d33',
                    inputValidator: (value) => {
                        if (!value) {
                            return '¡Debes introducir tu contraseña!'
                        }
                    },
                    // Personalizar colores según el tema
                    didOpen: () => {
                        const isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
                        if (isDarkMode) {
                            const popup = document.querySelector('.swal2-popup');
                            if (popup) {
                                popup.style.backgroundColor = 'var(--dark_application_background)';
                                popup.style.color = 'var(--dark_text_color)';
                            }
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // 1. Crear un input hidden dinámicamente para la contraseña
                        const passwordInput = document.createElement('input');
                        passwordInput.type = 'hidden';
                        passwordInput.name = 'password_confirmation';
                        passwordInput.value = result.value; // El valor introducido en el prompt
                        form.appendChild(passwordInput);

                        // 2. Mostrar loader y enviar
                        if (typeof showLoader === 'function') showLoader('Verificando...');

                        setTimeout(() => {
                            form.submit();
                        }, 300);
                    }
                });
            });
        });
    });
}