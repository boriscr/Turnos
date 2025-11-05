document.addEventListener('DOMContentLoaded', function() {
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    const dropdownMenu = document.querySelector('.dropdown-menu');
    let isOpen = false;

    if (dropdownToggle && dropdownMenu) {
        // Inicializar estado
        dropdownMenu.style.display = 'none';
        dropdownMenu.style.opacity = '0';
        dropdownMenu.style.transform = 'translateX(-100%) translateY(10px)';

        // Abrir/cerrar dropdown
        dropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            isOpen = !isOpen;
            
            if (isOpen) {
                dropdownMenu.style.display = 'block';
                // Pequeño delay para permitir la transición
                setTimeout(() => {
                    dropdownMenu.style.opacity = '1';
                    dropdownMenu.style.transform = 'translateX(-100%) translateY(0)';
                }, 10);
            } else {
                dropdownMenu.style.opacity = '0';
                dropdownMenu.style.transform = 'translateX(-100%) translateY(10px)';
                // Esperar a que termine la transición para ocultar
                setTimeout(() => {
                    dropdownMenu.style.display = 'none';
                }, 300);
            }
        });

        // Cerrar dropdown al hacer click fuera
        document.addEventListener('click', function(e) {
            if (isOpen && !dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                closeDropdown();
            }
        });

        // Función para cerrar dropdown
        function closeDropdown() {
            isOpen = false;
            dropdownMenu.style.opacity = '0';
            dropdownMenu.style.transform = 'translateX(-100%) translateY(10px)';
            setTimeout(() => {
                dropdownMenu.style.display = 'none';
            }, 300);
        }

        // Para hover en desktop (opcional - si quieres mantenerlo)
        dropdownToggle.addEventListener('mouseenter', function() {
            if (!isOpen) {
                isOpen = true;
                dropdownMenu.style.display = 'block';
                setTimeout(() => {
                    dropdownMenu.style.opacity = '1';
                    dropdownMenu.style.transform = 'translateX(-100%) translateY(0)';
                }, 10);
            }
        });

        dropdownToggle.addEventListener('mouseleave', function() {
            // No cerrar inmediatamente, dar tiempo para mover el mouse al dropdown
            setTimeout(() => {
                if (!dropdownMenu.matches(':hover')) {
                    closeDropdown();
                }
            }, 200);
        });

        dropdownMenu.addEventListener('mouseleave', function() {
            setTimeout(() => {
                closeDropdown();
            }, 200);
        });

        // Prevenir que los clicks dentro del dropdown lo cierren
        dropdownMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
});