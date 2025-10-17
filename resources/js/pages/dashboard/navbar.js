// Toggle de la barra lateral en escritorio
if (window.location.pathname.includes('/dashboard')) {

    document.querySelector('.toggle-btn').addEventListener('click', function () {
        document.querySelector('.sidebar').classList.toggle('collapsed');
    });

    // Toggle del menú móvil
    document.querySelector('.mobile-toggle').addEventListener('click', function () {
        document.querySelector('.mobile-nav-links').classList.toggle('active');
    });

    // Cerrar menú móvil al hacer clic en un enlace
    document.querySelectorAll('.mobile-nav-links a').forEach(link => {
        link.addEventListener('click', function () {
            document.querySelector('.mobile-nav-links').classList.remove('active');
        });
    });

    // Cerrar menú móvil al hacer clic fuera de él
    document.addEventListener('click', function (event) {
        const mobileNav = document.querySelector('.mobile-nav');
        const mobileToggle = document.querySelector('.mobile-toggle');

        if (!mobileNav.contains(event.target) && !mobileToggle.contains(event.target)) {
            document.querySelector('.mobile-nav-links').classList.remove('active');
        }
    });

    // Actualizar enlace activo
    document.querySelectorAll('.nav-links a, .mobile-nav-links a, .carousel-item').forEach(link => {
        link.addEventListener('click', function () {
            // Remover clase activa de todos los enlaces
            document.querySelectorAll('.nav-links a, .mobile-nav-links a').forEach(item => {
                item.classList.remove('active');
            });

            // Añadir clase activa al enlace clickeado
            this.classList.add('active');

            // Actualizar también el carrusel
            const href = this.getAttribute('href');
            document.querySelectorAll('.carousel-item').forEach(item => {
                if (item.getAttribute('href') === href) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
        });
    });
}