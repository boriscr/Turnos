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
            /*Colores de los textos*/
            --title_text_color: {{ setting('design.title_text_color') }};
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
