<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Variables de diseño (colores, tipografías, etc.) y loader-->
    @include('partials.design-variables')
    <!-- Estilos para el loader -->
    @include('partials.design-loader')
    <!-- Favicon tradicional (.ico) -->
    <link rel="icon" href="{{ asset('images/app-icon.ico') }}" type="image/x-icon">
    <!--scripts-->
    @vite(['resources/css/app.css'])
</head>

<body>
    <div class="min-h-screen main full-center">
        <div>
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <div class="container-form-register full-center">
            {{ $slot }}
        </div>
    </div>
    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Loader Component -->
    <div class="active loader-overlay" id="globalLoader" style="width: 500px; height: 400px;">
        <div class="modern-loader">
            <div class="loader-content">
                <div class="loader-spinner primary"></div>
                <p class="loader-text">Cargando...</p>
            </div>
        </div>
    </div>

    <script>
        // Funciones globales para controlar el loader
        window.showLoader = function(message = 'Cargando...') {
            const loader = document.getElementById('globalLoader');
            if (loader) {
                const textElement = loader.querySelector('.loader-text');
                if (textElement && message) {
                    textElement.textContent = message;
                }
                loader.classList.add('active');
            }
        };

        window.hideLoader = function() {
            const loader = document.getElementById('globalLoader');
            if (loader) {
                loader.classList.remove('active');
            }
        };

        // Ocultar el loader cuando la página esté completamente cargada
        window.addEventListener('load', function() {
            hideLoader();
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Nueva lógica: esperar a que el CSS crítico cargue
            const css = document.querySelector('link[href*="app.css"]');

            if (css) {
                css.addEventListener('load', () => {
                    // El CSS está listo → mostramos la app
                    hideLoader();
                    document.body.classList.add('css-ready');
                });

                css.addEventListener('error', () => {
                    // Si falla el CSS, ocultar el loader igual para no trabar la app
                    hideLoader();
                });
            } else {
                // Si no existe link al CSS, fallback
                hideLoader();
            }
            // Manejar todos los enlaces
            document.addEventListener('click', function(e) {
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
            document.addEventListener('submit', function(e) {
                const form = e.target;

                // No aplicar a formularios con data-no-loader
                if (form.hasAttribute('data-no-loader')) return;

                showLoader('Enviando...');

                // El loader permanecerá visible hasta que se complete el envío
                // y se redirija a la nueva página
            });

            // Interceptar SweetAlert2 confirms que envían formularios
            document.addEventListener('click', function(e) {
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
    </script>
    @vite(['resources/js/app.js'])
</body>

</html>
