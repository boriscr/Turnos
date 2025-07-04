<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->updateOrInsert([
            'nombre' => 'Turnos.com',
            'mensaje_bienvenida' => 'Bienvenido a nuestra aplicación de turnos...',
            'pie_pagina' => '© 2025 Turnos.com. Todos los derechos reservados.',
            'nombre_institucion' => 'Institución de Salud',
            'faltas' => 3,
            'limites' => 3,
            'cancelacion_turnos' => 24,
            'preview_window_amount' => 24,
            'preview_window_unit' => 'hora',
            'hora_verificacion_asistencias' => 1, // Horas para verificar asistencias automáticamente
        ]);
    }
}
