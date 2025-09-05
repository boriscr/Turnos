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
    <!-- Favicon tradicional (.ico) -->
    <link rel="icon" href="{{ asset('images/app-icon.ico') }}" type="image/x-icon">
    <style>
        :root {
            /*Color de fondo de la aplicacion*/
            --dark_application_background: {{ setting('design.dark_application_background') }};
            --light_application_background: {{ setting('design.light_application_background') }};
            /*Color de fondo del pie de página*/
            --footer_background: {{ setting('design.footer_background') }};
            /*Color de diseño general*/
            --general_design_color: {{ setting('design.general_design_color') }};
            /*Colores de los textos*/
            --title_text_color: {{ setting('design.title_text_color') }};
            --subtitle_text_color: {{ setting('design.subtitle_text_color') }};
            --dark_text_color: {{ setting('design.dark_text_color') }};
            --light_text_color: {{ setting('design.light_text_color') }};
            /*Color texto letra pequeña (Small)*/
            --text_color_small_dark: {{ setting('design.text_color_small_dark') }};
            --text_color_small_light: {{ setting('design.text_color_small_light') }};
            /*Color de fondo botones*/
            --primary_color_btn: {{ setting('design.primary_color_btn') }};
            --secondary_color_btn: {{ setting('design.secondary_color_btn') }};
            --btn_text_color: {{ setting('design.btn_text_color') }};
            /*Color de fondo de Barra de navegacion*/
            --background_navbar_dark: {{ setting('design.background_navbar_dark') }};
            --background_navbar_light: {{ setting('design.background_navbar_light') }};
            /*Color de fondo de seccion login y registro*/
            --background_login_and_register_dark: {{ setting('design.background_login_and_register_dark') }};
            --background_login_and_register_light: {{ setting('design.background_login_and_register_light') }};
            /*Color de texto de elementos (input,option y textarea)*/
            --text_color_form_elements_dark: {{ setting('design.text_color_form_elements_dark') }};
            --text_color_form_elements_light: {{ setting('design.text_color_form_elements_light') }};
        }

        /*loader*/
        /* Estilos para el loader */
        .loader-overlay {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px;
            height: 400px;
            background-color: var(--light_application_background);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            transition: opacity 0.3s ease-out, visibility 0.3s ease-out;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.438);
            backdrop-filter: blur(5px);
            opacity: 0;
            visibility: hidden;
        }

        .loader-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Resto del CSS permanece igual */
        .modern-loader {
            text-align: center;
            padding: 20px;
        }

        .loader-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid #f0f0f0;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1.2s linear infinite;
            margin: 0 auto 20px;
            position: relative;
        }

        .loader-spinner::after {
            content: '';
            position: absolute;
            top: -8px;
            left: -8px;
            right: -8px;
            bottom: -8px;
            border: 4px solid transparent;
            border-top: 4px solid var(--general_design_color);
            border-radius: 50%;
            animation: spin 1.6s linear infinite;
            opacity: 0.7;
        }

        .loader-spinner::before {
            content: '';
            position: absolute;
            top: -14px;
            left: -14px;
            right: -14px;
            bottom: -14px;
            border: 4px solid transparent;
            border-top: 4px solid #e74c3c;
            border-radius: 50%;
            animation: spin 2s linear infinite;
            opacity: 0.4;
        }

        .loader-text {
            color: var(--light_text_color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 18px;
            font-weight: 500;
            margin: 0;
            letter-spacing: 0.5px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Variantes de color */
        .loader-spinner.primary {
            border-top: 4px solid #3498db;
        }

        .loader-spinner.success {
            border-top: 4px solid #2ecc71;
        }

        .loader-spinner.warning {
            border-top: 4px solid #f39c12;
        }

        .loader-spinner.danger {
            border-top: 4px solid #e74c3c;
        }

        .loader-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .loader-content .loader-logo {
            width: 150px;
            height: 150px;
            margin-bottom: 15px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Tamaños responsivos */
        @media (max-width: 768px) {
            .loader-overlay {
                width: 90%;
                max-width: 400px;
                height: 300px;
            }

            .loader-spinner {
                width: 50px;
                height: 50px;
            }

            .loader-text {
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .loader-overlay {
                max-width: 95%;
                height: 250px;
                border-radius: 12px;
            }

            .loader-spinner {
                width: 40px;
                height: 40px;
            }

            .loader-text {
                font-size: 14px;
            }
        }

        /* Tema oscuro */
        @media (prefers-color-scheme: dark) {
            .loader-overlay {
                background-color: var(--dark_application_background);
                box-shadow: 0 10px 30px rgba(255, 255, 255, 0.167);
            }

            .loader-text {
                color: var(--dark_text_color);
            }

            .loader-spinner {
                border: 4px solid rgba(255, 255, 255, 0.1);
            }
        }
    </style>
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

    @include('layouts.footer')
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
