<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link href="{{ asset('main.css') }}" rel="stylesheet">
    <link href="{{ asset('nav.css') }}" rel="stylesheet">
    <link href="{{ asset('buttons.css') }}" rel="stylesheet">
    <link href="{{ asset('forms/form-especialidad.css') }}" rel="stylesheet">
    <link href="{{ asset('forms/forms.css') }}" rel="stylesheet">
    <link href="{{ asset('forms/switch.css') }}" rel="stylesheet">
    <link href="{{ asset('forms/calendario.css') }}" rel="stylesheet">
    <link href="{{ asset('forms/formulario-multipaso-turno.css') }}" rel="stylesheet">
    <link href="{{ asset('table.css') }}" rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <x-app-layout>
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
        {{ $slot }}
    </x-app-layout>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</html>
