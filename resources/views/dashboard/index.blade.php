<!-- resources/views/dashboard/index.blade.php -->
@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    {{ __('Bienvenido!!') }}
                </div>
            </div>
        </div>
    </div>

    <h1>Panel de Control Principal</h1>
    <p>Bienvenido a tu dashboard. Aquí puedes gestionar todas las funcionalidades de tu aplicación.</p>
@endsection