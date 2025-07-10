<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Configuración General de la App
            'app' => [
                'nombre' => [
                    'value' => 'Turnos.com',
                    'type' => 'string',
                    'desc' => 'Nombre público de la aplicación'
                ],
                'mensaje_bienvenida' => [
                    'value' => 'Bienvenido al sistema de turnos médicos en línea',
                    'type' => 'string',
                    'desc' => 'Mensaje mostrado en la página de inicio'
                ],
                'pie_pagina' => [
                    'value' => '© 2025 Turnos.com - Sistema de Gestión de Turnos Hospitalarios',
                    'type' => 'string',
                    'desc' => 'Texto para el footer de la aplicación'
                ],
                'nombre_institucion' => [
                    'value' => 'Hospital Regional General',
                    'type' => 'string',
                    'desc' => 'Nombre de la institución de salud'
                ],
                'mensaje_paciente' => [
                    'value' => 'Por favor, presentese 15 minutos antes de su turno. Si no puede asistir, cancele su turno con antelación. Traer DNI. Gracias.',
                    'type' => 'string',
                    'desc' => 'Mensaje para los pacientes al solicitar un turno'
                ],
                'logo_url' => [
                    'value' => '/images/logo.png',
                    'type' => 'string',
                    'desc' => 'Ruta del logo institucional'
                ]
            ],

            // Configuración de Turnos
            'turnos' => [
                'faltas_maximas' => [
                    'value' => 3,
                    'type' => 'integer',
                    'desc' => 'Número máximo de faltas permitidas antes de bloqueo'
                ],
                'limite_diario' => [
                    'value' => 2,
                    'type' => 'integer',
                    'desc' => 'Máximo de turnos por paciente por día'
                ],
                'horas_cancelacion' => [
                    'value' => 24,
                    'type' => 'integer',
                    'desc' => 'Horas mínimas para cancelar un turno'
                ],
                'antelacion_reserva' => [
                    'value' => 24,
                    'type' => 'integer',
                    'desc' => 'Horas de anticipación para reservar'
                ],
                'unidad_antelacion' => [
                    'value' => 'hora',
                    'type' => 'string',
                    'desc' => 'Unidad de tiempo para antelación (horas/días)'
                ]
            ],

            // Configuración de Asistencias
            'asistencias' => [
                'intervalo_verificacion' => [
                    'value' => 1,
                    'type' => 'integer',
                    'desc' => 'Horas entre verificaciones automáticas'
                ]
            ]
        ];

        foreach ($settings as $group => $items) {
            foreach ($items as $key => $data) {
                Setting::updateOrCreate(
                    ['key' => "{$group}.{$key}"],
                    [
                        'value' => $data['value'],
                        'type' => $data['type'],
                        'group' => $group,
                    ]
                );
            }
        }
    }
}
