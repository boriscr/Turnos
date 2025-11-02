// Agrega este script al final de tu body
document.addEventListener('DOMContentLoaded', function() {
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    const dropdownMenu = document.querySelector('.dropdown-menu');
    let dropdownTimeout;

    if (dropdownToggle && dropdownMenu) {
        // Abrir dropdown
        dropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isOpen = dropdownMenu.style.display === 'block';
            dropdownMenu.style.display = isOpen ? 'none' : 'block';
            dropdownMenu.style.opacity = isOpen ? '0' : '1';
            dropdownMenu.style.transform = isOpen ? 'translateX(-100%) translateY(10px)' : 'translateX(-100%) translateY(0)';
        });

        // Cerrar dropdown al hacer click fuera
        document.addEventListener('click', function(e) {
            if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.style.display = 'none';
                dropdownMenu.style.opacity = '0';
                dropdownMenu.style.transform = 'translateX(-100%) translateY(10px)';
            }
        });

        // Para hover en desktop (opcional)
        dropdownToggle.addEventListener('mouseenter', function() {
            clearTimeout(dropdownTimeout);
            dropdownMenu.style.display = 'block';
            dropdownMenu.style.opacity = '1';
            dropdownMenu.style.transform = 'translateX(-100%) translateY(0)';
        });

        dropdownToggle.addEventListener('mouseleave', function() {
            dropdownTimeout = setTimeout(() => {
                if (!dropdownMenu.matches(':hover')) {
                    dropdownMenu.style.display = 'none';
                    dropdownMenu.style.opacity = '0';
                    dropdownMenu.style.transform = 'translateX(-100%) translateY(10px)';
                }
            }, 300);
        });

        dropdownMenu.addEventListener('mouseleave', function() {
            dropdownTimeout = setTimeout(() => {
                dropdownMenu.style.display = 'none';
                dropdownMenu.style.opacity = '0';
                dropdownMenu.style.transform = 'translateX(-100%) translateY(10px)';
            }, 300);
        });
    }
});