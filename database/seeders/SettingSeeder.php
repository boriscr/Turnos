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
                'name' => [
                    'value' => 'Turnos.com',
                    'type' => 'string',
                    'desc' => 'Nombre público de la aplicación'
                ],
                'mensaje_bienvenida' => [
                    'value' => 'Bienvenido al sistema de appointments médicos en línea',
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
                    'value' => 'Por favor, presentese 15 minutos antes de su appointment. Si no puede asistir, cancele su appointment con antelación. Traer DNI. Gracias.',
                    'type' => 'string',
                    'desc' => 'Mensaje para los pacientes al solicitar un appointment'
                ],
                'logo_url' => [
                    'value' => '/images/logo.png',
                    'type' => 'string',
                    'desc' => 'Ruta del logo institucional'
                ]
            ],

            // Configuración de Turnos
            'appointments' => [
                'faltas_maximas' => [
                    'value' => 3,
                    'type' => 'integer',
                    'desc' => 'Número máximo de faltas permitidas antes de bloqueo'
                ],
                'limite_diario' => [
                    'value' => 2,
                    'type' => 'integer',
                    'desc' => 'Máximo de appointments por paciente por día'
                ],
                'horas_cancelacion' => [
                    'value' => 24,
                    'type' => 'integer',
                    'desc' => 'Horas mínimas para cancelar un appointment'
                ],
                'antelacion_reserva' => [
                    'value' => 24,
                    'type' => 'integer',
                    'desc' => 'Horas de anticipación para reservar'
                ],
                'unidad_antelacion' => [
                    'value' => 'time',
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
            ],
            //Configuracion de estilos y diseño
            'design' => [
                //color de fonde de la aplicacion
                'fondo_aplicacion_dark' => [
                    'value' => '#00272E',  // Verde-azulado (gunmetal)
                    'type' => 'string',
                    'desc' => 'Color de fondo de la aplicación'
                ],
                'fondo_aplicacion_light' => [
                    'value' => '#FFFAFA', // Blanco nieve
                    'type' => 'string',
                    'desc' => 'Color de fondo de la aplicación'
                ],
                'color_texto_titulo' => [
                    'value' => '#4CAF50', // Verde claro
                    'type' => 'string',
                    'desc' => 'Color de texto para títulos y encabezados'
                ],
                'color_texto_dark' => [
                    'value' => '#FFFAFA', // Blanco nieve
                    'type' => 'string',
                    'desc' => 'Color de texto para el modo oscuro'
                ],
                'color_texto_light' => [
                    'value' => '#262626', // Gris claro
                    'type' => 'string',
                    'desc' => 'Color de texto para el modo claro2'
                ],
                //Color texto small
                'color_texto_small_dark' => [
                    'value' => '#808080', //Gris
                    'type' => 'string',
                    'desc' => 'Color de letra small en tema claro'
                ],
                'color_texto_small_light' => [
                    'value' => '#808080', //Gris
                    'type' => 'string',
                    'desc' => 'Color de letra small en tema claro'
                ],
                //Color de botones
                'color_primario_btn' => [
                    'value' => '#4CAF50', // Verde claro
                    'type' => 'string',
                    'desc' => 'Color primario para botones y elementos principales'
                ],
                'color_secundario_btn' => [
                    'value' => '#4CAF50', // Verde claro
                    'type' => 'string',
                    'desc' => 'Color secundario para acentos'
                ],
                'color_texto_btn' => [
                    'value' => '#FFFAFA', // Blanco nieve
                    'type' => 'string',
                    'desc' => 'Color de texto para botones'
                ],
                //Barra de navegacion
                'fondo_navbar_dark' => [
                    'value' => '#002b33', //Verde Oscuro
                    'type' => 'string',
                    'desc' => 'Color de fondo del navbar en tema oscuro'
                ],
                'fondo_navbar_light' => [
                    'value' => '#F0F0F0', // Blanco oscuro
                    'type' => 'string',
                    'desc' => 'Color de fondo del navbar en tema claro'
                ],
                //Formulario Login y Registro
                'fondo_login_register_dark' => [
                    'value' =>  '#003B46',  //Verde Petroleo
                    'type' => 'string',
                    'desc' => 'Color de fondo del login/register en tema claro'
                ],
                'fondo_login_register_light' => [
                    'value' => '#FFFAFA', // Blanco nieve
                    'type' => 'string',
                    'desc' => 'Color de fondo del login/register en tema claro'
                ],
                //Form elements text color /inputs,option y textarea
                'color_texto_form_elements_dark' => [
                    'value' => '#FFFAFA', //Blanco nieve
                    'type' => 'string',
                    'desc' => 'Color de letra de los elementos de los formularios en tema claro'
                ],
                'color_texto_form_elements_light' => [
                    'value' => '#00bcd4', //Seleste claro
                    'type' => 'string',
                    'desc' => 'Color de letra de los elementos de los formularios en tema claro'
                ],
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
