<!-- resources/views/layouts/dashboard.blade.php -->
<x-app-layout>
    @section('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <style>
            /* Tus estilos CSS aquí */
            :root {
                --sidebar-width: 250px;
                --sidebar-collapsed-width: 100px;
                --header-height: 60px;
                --transition-speed: 0.3s;
            }

            .sidebar {
                width: var(--sidebar-width);
                background-color: var(--background_navbar_light);
                color: var(--light_text_color);
                height: 100vh;
                position: fixed;
                transition: width var(--transition-speed) ease;
                overflow-x: hidden;
                z-index: 1000;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            }

            /* ... resto de tus estilos ... */

            /* Dropdown mejorado */
            .dropdown-content {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: inherit;
                border-radius: 8px;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
                z-index: 1000;
                opacity: 0;
                visibility: hidden;
                transform: translateY(-10px);
                transition: all 0.3s ease;
                margin-top: 5px;
                overflow: hidden;
                max-height: 0;
            }

            .dropdown-content.active {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
                max-height: 500px;
            }

            .dropdown.active .dropdown-btn {
                background-color: color-mix(in srgb, var(--light_application_background) 50%, var(--general_design_color) 50%);
                border-left: 3px solid var(--general_design_color);
            }

            .sidebar.collapsed .dropdown-content {
                position: fixed;
                left: var(--sidebar-collapsed-width);
                width: 200px;
            }
        </style>
    @endsection

    <!-- Sidebar -->
    <x-dashboard.sidebar />
    
    <!-- Main Content -->
    <main class="main-content">
        <x-slot name="header">
            <h2 class="font-semibold text-xl">
                {{ $headerTitle ?? __('Dashboard') }}
            </h2>
        </x-slot>

        <div class="dashboard-content">
            @yield('dashboard-content')
        </div>
    </main>

    @section('scripts')
        <script>
            // JavaScript unificado para el dashboard
            document.addEventListener('DOMContentLoaded', function() {
                initializeDashboard();
            });

            function initializeDashboard() {
                // Toggle sidebar
                const toggleBtn = document.querySelector('.toggle-btn');
                if (toggleBtn) {
                    toggleBtn.addEventListener('click', function() {
                        document.querySelector('.sidebar').classList.toggle('collapsed');
                    });
                }

                // Toggle mobile menu
                const mobileToggle = document.querySelector('.mobile-toggle');
                if (mobileToggle) {
                    mobileToggle.addEventListener('click', function() {
                        document.querySelector('.mobile-nav-links').classList.toggle('active');
                    });
                }

                // Close mobile menu on link click
                document.querySelectorAll('.mobile-nav-links a').forEach(link => {
                    link.addEventListener('click', function() {
                        document.querySelector('.mobile-nav-links').classList.remove('active');
                    });
                });

                // Close mobile menu when clicking outside
                document.addEventListener('click', function(event) {
                    const mobileNav = document.querySelector('.mobile-nav');
                    const mobileToggle = document.querySelector('.mobile-toggle');
                    
                    if (mobileNav && !mobileNav.contains(event.target) && 
                        mobileToggle && !mobileToggle.contains(event.target)) {
                        document.querySelector('.mobile-nav-links').classList.remove('active');
                    }
                });

                // Initialize dropdowns
                initializeDropdowns();
                
                // Set active states based on current URL
                setActiveStates();
            }

            function initializeDropdowns() {
                const dropdownButtons = document.querySelectorAll('.dropdown-btn');
                
                dropdownButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const dropdown = this.closest('.dropdown');
                        const dropdownContent = dropdown.querySelector('.dropdown-content');
                        
                        // Close other dropdowns
                        document.querySelectorAll('.dropdown').forEach(otherDropdown => {
                            if (otherDropdown !== dropdown) {
                                otherDropdown.classList.remove('active');
                                otherDropdown.querySelector('.dropdown-content')?.classList.remove('active');
                            }
                        });
                        
                        // Toggle current dropdown
                        dropdown.classList.toggle('active');
                        dropdownContent.classList.toggle('active');
                    });
                });

                // Close dropdowns when clicking outside
                document.addEventListener('click', function() {
                    document.querySelectorAll('.dropdown').forEach(dropdown => {
                        dropdown.classList.remove('active');
                        dropdown.querySelector('.dropdown-content')?.classList.remove('active');
                    });
                });

                // Prevent dropdown from closing when clicking inside
                document.querySelectorAll('.dropdown-content').forEach(content => {
                    content.addEventListener('click', function(e) {
                        e.stopPropagation();
                    });
                });

                // Keep dropdown open when clicking on links (IMPORTANTE)
                document.querySelectorAll('.dropdown-content a').forEach(link => {
                    link.addEventListener('click', function(e) {
                        // NO cerrar el dropdown cuando se hace click en un enlace
                        e.stopPropagation();
                        // Solo actualizar el estado activo
                        updateActiveStates(this);
                    });
                });
            }

            function setActiveStates() {
                const currentPath = window.location.pathname;
                
                // Remove active class from all links
                document.querySelectorAll('.nav-links a, .mobile-nav-links a, .dropdown-item').forEach(item => {
                    item.classList.remove('active');
                });

                // Find and activate matching links
                document.querySelectorAll('a').forEach(link => {
                    const href = link.getAttribute('href');
                    if (href && currentPath === href) {
                        link.classList.add('active');
                        
                        // If it's a dropdown item, keep parent dropdown open
                        if (link.closest('.dropdown-content')) {
                            const dropdown = link.closest('.dropdown');
                            if (dropdown) {
                                dropdown.classList.add('active');
                                dropdown.querySelector('.dropdown-content').classList.add('active');
                            }
                        }
                    }
                });
            }

            function updateActiveStates(clickedLink) {
                // Remove active class from all links
                document.querySelectorAll('.nav-links a, .mobile-nav-links a, .dropdown-item').forEach(item => {
                    item.classList.remove('active');
                });
                
                // Add active class to clicked link
                clickedLink.classList.add('active');
                
                // If it's a dropdown item, keep parent dropdown open
                if (clickedLink.closest('.dropdown-content')) {
                    const dropdown = clickedLink.closest('.dropdown');
                    if (dropdown) {
                        dropdown.classList.add('active');
                        dropdown.querySelector('.dropdown-content').classList.add('active');
                    }
                }
            }
        </script>
    @endsection
</x-app-layout>