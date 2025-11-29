<!-- resources/views/dashboard/index.blade.php -->
@extends('layouts.dashboard')

@section('dashboard-content')
    <h1>Panel de Control Principal</h1>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 full-center border-b border-gray-200 text-xl font-bold">
                    {{ __('Bienvenido!!') }}
                </div>
                <div class="p-6 ">
                    <p>Bienvenido a tu dashboard. Aquí puedes gestionar todas las funcionalidades de tu aplicación.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
