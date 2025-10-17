if (window.location.pathname.includes('/dashboard')) {

    document.addEventListener('DOMContentLoaded', function () {
        const dropdownBtn = document.querySelector('.dropdown-btn');
        const dropdownContent = document.querySelector('.dropdown-content');

        // Alternar menú al hacer clic en el botón
        dropdownBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdownBtn.classList.toggle('active');
            dropdownContent.classList.toggle('active');
        });

        // Cerrar menú al hacer clic en una opción
        dropdownContent.addEventListener('click', function (e) {
            if (e.target.tagName === 'A') {
                dropdownBtn.classList.remove('active');
                dropdownContent.classList.remove('active');
            }
        });

        // Cerrar menú al hacer clic fuera de él
        document.addEventListener('click', function () {
            dropdownBtn.classList.remove('active');
            dropdownContent.classList.remove('active');
        });
    });
}