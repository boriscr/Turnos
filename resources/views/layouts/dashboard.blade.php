<!-- resources/views/layouts/dashboard.blade.php -->
<x-app-layout>
    @section('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @endsection
        <style>
            /* Estilos para el menú responsivo inferior */
            @media screen and (max-width: 640px) {
                .bottom-navigation-bar {
                    display: none !important;
                }
            }
        </style>

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
</x-app-layout>
