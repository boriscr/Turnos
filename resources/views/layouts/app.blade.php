<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
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
    <!--CSS crítico con preload (evita el FOUC, mejora la experiencia):-->
    <link rel="preload" as="style" href="{{ Vite::asset('resources/css/app.css') }}">
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/app.css') }}">
</head>


<body class="font-sans antialiased">
    <div class="min-h-screen">
        <header>
            <!-- Navigation Bar -->
            @include('layouts.navigation')
        </header>

        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire(@json(session('success')))
                })
            </script>
        @endif

        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire(@json(session('error')))
                })
            </script>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    <!-- Footer -->
    @include('layouts.footer')
    <!-- Bottom Navigation Bar -->
    @include('layouts.bottom-navigation-bar')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const css = document.querySelector('link[href*="app.css"]');

            function markCssReady() {
                document.body.classList.add("css-ready");
            }

            if (css) {
                if (css.sheet) {
                    // El CSS ya está cargado
                    markCssReady();
                } else {
                    // Esperar a que cargue
                    css.addEventListener("load", markCssReady);
                    css.addEventListener("error", markCssReady); // fallback si falla
                }
            } else {
                // No se encontró el link, mostrar igual
                markCssReady();
            }
        });
    </script>
    <!-- Loader Component-->
    <div class="active loader-overlay" id="globalLoader" style="width: 100%; height: 100%;">
        <div class="modern-loader">
            <div class="loader-content">
                <div class="loader-spinner primary"></div>
                <p class="loader-text">Cargando...</p>
            </div>
        </div>
    </div>
    <script>
        /* ============================================
       GLOBAL LOADER CONTROL – OPTIMIZED VERSION
       ============================================ */

        // Mostrar loader
        window.showLoader = function(message = 'Cargando...') {
            const loader = document.getElementById('globalLoader');
            if (loader) {
                const textElement = loader.querySelector('.loader-text');
                if (textElement) textElement.textContent = message;
                loader.classList.add('active');
            }
        };

        // Ocultar loader
        window.hideLoader = function() {
            const loader = document.getElementById('globalLoader');
            if (loader) {
                loader.classList.remove('active');
            }
        };

        /* ============================================
           OCULTAR LOADER EN CARGA NORMAL
           ============================================ */
        window.addEventListener('load', function() {
            hideLoader();
        });


        /* ============================================================================
           FIX CRÍTICO: OCULTAR LOADER AL VOLVER ATRÁS (Back/Forward Cache)
           ============================================================================ */
        window.addEventListener("pageshow", function(event) {
            // Si viene desde el bfcache, event.persisted === true
            hideLoader();
        });


        /* ============================================
           CUANDO EL DOM ESTÁ LISTO
           ============================================ */
        document.addEventListener('DOMContentLoaded', function() {

            /* ================================
               ESPERAR A QUE CARGUE EL CSS
               ================================ */
            const css = document.querySelector('link[href*="app.css"]');

            if (css) {
                if (css.sheet) {
                    hideLoader();
                    document.body.classList.add('css-ready');
                } else {
                    css.addEventListener('load', () => {
                        hideLoader();
                        document.body.classList.add('css-ready');
                    });

                    css.addEventListener('error', () => {
                        hideLoader(); // fallback
                    });
                }
            } else {
                hideLoader(); // si no existe el CSS
            }


            /* ============================================
               INTERCEPTAR NAVEGACIÓN CON ENLACES
               ============================================ */
            document.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                if (!link) return;

                // Excepciones comunes
                if (
                    link.getAttribute('target') ||
                    link.hasAttribute('download') ||
                    link.getAttribute('href') === '#' ||
                    link.getAttribute('href')?.startsWith('javascript:')
                ) return;

                // Solo enlaces internos
                if (link.hostname !== window.location.hostname) return;

                // Evitar doble ejecución
                if (e.defaultPrevented) return;

                e.preventDefault();

                showLoader('Cargando...');

                // Navegar luego de mostrar loader
                setTimeout(() => {
                    window.location.href = link.href;
                }, 90);
            });


            /* ============================================
               FORMULARIOS
               ============================================ */
            document.addEventListener('submit', function(e) {
                const form = e.target;

                // Excepción opcional
                if (form.hasAttribute('data-no-loader')) return;

                showLoader('Enviando...');
            });


            /* ============================================
               SWEETALERT2 + FORMULARIOS
               ============================================ */
            document.addEventListener('click', function(e) {
                const button = e.target;

                if (button.classList.contains('swal2-confirm')) {
                    const popup = button.closest('.swal2-popup');
                    if (popup) {
                        const form = popup.querySelector('form');
                        if (form) {
                            showLoader('Procesando...');
                        }
                    }
                }
            });


            /* ============================================
               FALLBACK DE SEGURIDAD (por si algo falla)
               ============================================ */
            setTimeout(() => hideLoader(), 8000);
        });
    </script>
    @vite(['resources/js/app.js'])
</body>

</html>
