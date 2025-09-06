document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.cancel-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const form = this.closest('.delete-form');
            const originalButtonText = this.innerHTML;

            Swal.fire({
                title: '¿Estás seguro de cancelar tu reserva?',
                text: "¡No podrás revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'Cancelar',
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
                    // MOSTRAR LOADER antes de enviar el formulario
                    if (typeof showLoader === 'function') {
                        showLoader('Cancelado...');
                    } else {
                        // Fallback si showLoader no está disponible
                        console.warn('La función showLoader no está disponible');
                    }
                    
                    // Deshabilitar todos los botones de eliminación para evitar múltiples clics
                    deleteButtons.forEach(btn => {
                        btn.disabled = true;
                        btn.classList.add('submitting');
                    });
                    
                    // Enviar formulario después de un breve delay
                    setTimeout(() => {
                        try {
                            form.submit();
                        } catch (error) {
                            console.error('Error al enviar el formulario:', error);
                            // Restaurar botones en caso de error
                            deleteButtons.forEach(btn => {
                                btn.disabled = false;
                                btn.classList.remove('submitting');
                            });
                            // Ocultar loader si está visible
                            if (typeof hideLoader === 'function') {
                                hideLoader();
                            }
                        }
                    }, 300);
                }
            });
        });
    });
});