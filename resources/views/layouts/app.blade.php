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
            --fondo_aplicacion_dark: {{ setting('design.fondo_aplicacion_dark') }};
            --fondo_aplicacion_light: {{ setting('design.fondo_aplicacion_light') }};
            /*Colores de los textos*/
            --color_texto_titulo: {{ setting('design.color_texto_titulo') }};
            --color_texto_dark: {{ setting('design.color_texto_dark') }};
            --color_texto_light: {{ setting('design.color_texto_light') }};
            /*Color texto letra pequeña (Small)*/
            --color_texto_small_dark: {{ setting('design.color_texto_small_dark') }};
            --color_texto_small_light: {{ setting('design.color_texto_small_light') }};
            /*Color de fondo botones*/
            --color_primario_btn: {{ setting('design.color_primario_btn') }};
            --color_secundario_btn: {{ setting('design.color_secundario_btn') }};
            --color_texto_btn: {{ setting('design.color_texto_btn') }};
            /*Color de fondo de Barra de navegacion*/
            --fondo_navbar_dark: {{ setting('design.fondo_navbar_dark') }};
            --fondo_navbar_light: {{ setting('design.fondo_navbar_light') }};
            /*Color de fondo de seccion login y registro*/
            --fondo_login_register_dark: {{ setting('design.fondo_login_register_dark') }};
            --fondo_login_register_light: {{ setting('design.fondo_login_register_light') }};
            /*Color de texto de elementos (input,option y textarea)*/
            --color_texto_form_elements_dark: {{ setting('design.color_texto_form_elements_dark') }};
            --color_texto_form_elements_light: {{ setting('design.color_texto_form_elements_light') }};
        }
    </style>
    @vite(['resources/css/app.css'])
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/js/app.js'])
</body>

</html>
