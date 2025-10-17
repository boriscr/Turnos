<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Barra lateral para escritorio -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-chart-line logo-icon"></i>
                <span class="logo-text">Dashboard</span>
            </div>
            <button class="toggle-btn">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>
        <ul class="nav-links">
            <li><a href="#dashboard" class="active"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="#analytics"><i class="fas fa-chart-bar"></i> <span>Analytics</span></a></li>
            <li><a href="#users"><i class="fas fa-users"></i> <span>Users</span></a></li>
            <li><a href="#products"><i class="fas fa-box"></i> <span>Products</span></a></li>
            <li><a href="#orders"><i class="fas fa-shopping-cart"></i> <span>Orders</span></a></li>
            <li>
                <div class="dropdown">
                    <button class="dropdown-btn">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-content">
                        <a href="#" class="dropdown-item">
                            <i class="bi bi-display-fill"></i>
                            <span>General</span>
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="bi bi-palette-fill"></i>
                            <span>Diseño</span>
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="bi bi-calendar3-fill"></i>
                            <span>Turnos</span>
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-lock"></i>
                            <span>Privacidad</span>
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-sign-out-alt"></i>
                            <span> Sesión</span>
                        </a>
                    </div>
                </div>
            <li>
        </ul>
    </nav>

    <!-- Barra de navegación para móvil -->
    <nav class="mobile-nav">
        <div class="mobile-nav-header">
            <div class="mobile-logo">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </div>
            <button class="mobile-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <ul class="mobile-nav-links">
            <li><a href="#dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="#analytics"><i class="fas fa-chart-bar"></i> Analytics</a></li>
            <li><a href="#users"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="#products"><i class="fas fa-box"></i> Products</a></li>
            <li><a href="#orders"><i class="fas fa-shopping-cart"></i> Orders</a></li>
            <li><a href="#settings"><i class="fas fa-cog"></i> Settings</a></li>
        </ul>

    </nav>

    <!-- Contenido principal -->
    <main class="main-content">
        <div class="dashboard-content">
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-black dark:text-gray-200 leading-tight">
                    {{ __('Panel') }}
                </h2>
            </x-slot>

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-black dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ __('Bienvenido!!') }}
                        </div>
                    </div>
                </div>
            </div>


            <h1>Panel de Control Principal</h1>
            <p>Bienvenido a tu dashboard. Aquí puedes gestionar todas las funcionalidades de tu aplicación.</p>
            <p>La barra de navegación de la izquierda (en escritorio) o superior (en móvil) te permite acceder
                rápidamente a las diferentes secciones.</p>
            <p>En la versión móvil, puedes usar el carrusel para acceder rápidamente a las opciones principales sin
                necesidad de desplegar el menú completo.</p>
        </div>
    </main>




</x-app-layout>
