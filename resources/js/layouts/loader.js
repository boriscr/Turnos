// Funciones globales para controlar el loader
window.showLoader = function (message = 'Cargando...') {
    const loader = document.getElementById('globalLoader');
    if (loader) {
        const textElement = loader.querySelector('.loader-text');
        if (textElement && message) {
            textElement.textContent = message;
        }
        loader.classList.add('active');
    }
};

window.hideLoader = function () {
    const loader = document.getElementById('globalLoader');
    if (loader) {
        loader.classList.remove('active');
    }
};

// Ocultar el loader cuando la página esté completamente cargada
window.addEventListener('load', function () {
    hideLoader();
});

document.addEventListener('DOMContentLoaded', function () {
    // Manejar todos los enlaces
    document.addEventListener('click', function (e) {
        const link = e.target.closest('a');
        if (link &&
            link.hostname === window.location.hostname &&
            !link.getAttribute('target') &&
            !link.hasAttribute('download') &&
            link.getAttribute('href') !== '#' &&
            !link.getAttribute('href').startsWith('javascript:') &&
            !e.defaultPrevented) {

            // Evitar que SweetAlert2 interfiera
            e.preventDefault();
            showLoader('Cargando...');

            // Navegar después de mostrar el loader
            setTimeout(() => {
                window.location.href = link.href;
            }, 100);
        }
    });

    // Manejar todos los formularios
    document.addEventListener('submit', function (e) {
        const form = e.target;

        // No aplicar a formularios con data-no-loader
        if (form.hasAttribute('data-no-loader')) return;

        showLoader('Enviando...');

        // El loader permanecerá visible hasta que se complete el envío
        // y se redirija a la nueva página
    });

    // Interceptar SweetAlert2 confirms que envían formularios
    document.addEventListener('click', function (e) {
        const button = e.target;
        if (button.classList.contains('swal2-confirm')) {
            const swal = button.closest('.swal2-popup');
            if (swal) {
                const form = swal.querySelector('form');
                if (form) {
                    showLoader('Procesando...');
                }
            }
        }
    });
});

// Fallback: ocultar loader después de 10 segundos
setTimeout(() => {
    hideLoader();
}, 10000);