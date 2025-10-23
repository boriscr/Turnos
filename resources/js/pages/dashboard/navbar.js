// resources/js/dashboard.js
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard JS loaded');
    
    // Cargar estado del sidebar desde localStorage
    const savedState = localStorage.getItem('sidebarCollapsed');
    if (savedState === 'true') {
        document.querySelector('.sidebar').classList.add('collapsed');
    }

    // Toggle sidebar con persistencia
    const toggleBtn = document.querySelector('.toggle-btn');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('collapsed');
            
            // Guardar estado en localStorage
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        });
    }

    // Toggle del menú móvil (ESTO ES LO QUE FALTABA)
    const mobileToggle = document.querySelector('.mobile-toggle');
    if (mobileToggle) {
        mobileToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            document.querySelector('.mobile-nav-links').classList.toggle('active');
        });
    }

    // Cerrar menú móvil al hacer clic en un enlace
    document.querySelectorAll('.mobile-nav-links a').forEach(link => {
        link.addEventListener('click', function() {
            document.querySelector('.mobile-nav-links').classList.remove('active');
        });
    });

    // Cerrar menú móvil al hacer clic fuera de él
    document.addEventListener('click', function(event) {
        const mobileNav = document.querySelector('.mobile-nav');
        const mobileToggle = document.querySelector('.mobile-toggle');
        const mobileNavLinks = document.querySelector('.mobile-nav-links');

        if (mobileNavLinks && mobileNavLinks.classList.contains('active') && 
            mobileNav && !mobileNav.contains(event.target) && 
            mobileToggle && !mobileToggle.contains(event.target)) {
            mobileNavLinks.classList.remove('active');
        }
    });

    // Inicializar dropdowns
    initializeDropdowns();
    
    // Manejar estado activo inicial
    handleInitialActiveState();
});

function initializeDropdowns() {
    const dropdownButtons = document.querySelectorAll('.dropdown-btn');
    
    dropdownButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = this.closest('.dropdown');
            const dropdownContent = this.nextElementSibling;
            
            // Cerrar otros dropdowns
            document.querySelectorAll('.dropdown-content').forEach(content => {
                if (content !== dropdownContent) {
                    content.classList.remove('active');
                }
            });
            
            document.querySelectorAll('.dropdown-btn').forEach(btn => {
                if (btn !== button) {
                    btn.classList.remove('active');
                }
            });
            
            // Alternar dropdown actual
            dropdownContent.classList.toggle('active');
            button.classList.toggle('active');
            dropdown.classList.toggle('active');
        });
    });

    // Cerrar dropdowns al hacer clic fuera
    document.addEventListener('click', function() {
        closeAllDropdowns();
    });

    // Prevenir cierre al hacer clic dentro del dropdown
    document.querySelectorAll('.dropdown-content').forEach(content => {
        content.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
}

function handleInitialActiveState() {
    // Si hay un dropdown item activo, asegurarse de que el dropdown esté abierto
    const activeDropdownItems = document.querySelectorAll('.dropdown-item.active');
    
    if (activeDropdownItems.length > 0) {
        activeDropdownItems.forEach(item => {
            const dropdown = item.closest('.dropdown');
            if (dropdown) {
                const dropdownContent = dropdown.querySelector('.dropdown-content');
                const dropdownBtn = dropdown.querySelector('.dropdown-btn');
                
                if (dropdownContent) {
                    dropdownContent.classList.add('active');
                }
                if (dropdownBtn) {
                    dropdownBtn.classList.add('active');
                }
            }
        });
    }
}

function closeAllDropdowns() {
    document.querySelectorAll('.dropdown-content').forEach(content => {
        content.classList.remove('active');
    });
    
    document.querySelectorAll('.dropdown-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    document.querySelectorAll('.dropdown').forEach(dropdown => {
        dropdown.classList.remove('active');
    });
}