document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.querySelector('.nav-toggle');
    const navCollapse = document.querySelector('.nav-collapse');

    // Solo aplicar el toggle para móvil
    if (toggleButton) {
        toggleButton.addEventListener('click', function(e) {
            e.preventDefault(); // Prevenir el comportamiento por defecto
            e.stopPropagation(); // Detener la propagación del evento
            
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);
            navCollapse.classList.toggle('active');
        });
    }

    // Manejar dropdowns solo para móvil
    function setupDropdowns() {
        const isMobile = window.innerWidth < 769;

        document.querySelectorAll('.dropdown-toggle').forEach(button => {
            // Eliminar eventos anteriores para evitar duplicados
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);
        });

        document.querySelectorAll('.dropdown-toggle').forEach(button => {
            if (isMobile) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const dropdown = this.nextElementSibling;
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';

                    this.setAttribute('aria-expanded', !isExpanded);
                    dropdown.classList.toggle('active');

                    // Cerrar otros dropdowns abiertos
                    if (!isExpanded) {
                        document.querySelectorAll('.dropdown-menu').forEach(menu => {
                            if (menu !== dropdown && menu.classList.contains('active')) {
                                menu.classList.remove('active');
                                const toggle = menu.previousElementSibling;
                                if (toggle && toggle.classList.contains('dropdown-toggle')) {
                                    toggle.setAttribute('aria-expanded', 'false');
                                }
                            }
                        });
                    }
                });
            } else {
                // Para escritorio, manejamos hover
                const dropdown = button.nextElementSibling;
                button.addEventListener('mouseenter', function() {
                    dropdown.style.display = 'block';
                });

                button.parentElement.addEventListener('mouseleave', function() {
                    dropdown.style.display = 'none';
                });
            }
        });
    }

    // Configurar dropdowns al cargar y al redimensionar
    setupDropdowns();
    window.addEventListener('resize', setupDropdowns);
    
    // Cerrar menús al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.main-nav')) {
            const openMenus = document.querySelectorAll('.nav-collapse.active, .dropdown-menu.active');
            openMenus.forEach(menu => {
                menu.classList.remove('active');
            });
            document.querySelectorAll('[aria-expanded="true"]').forEach(button => {
                button.setAttribute('aria-expanded', 'false');
            });
        }
    });
});