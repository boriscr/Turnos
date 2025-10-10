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
                'welcome_message' => [
                    'value' => 'Bienvenido al sistema de turnos en línea',
                    'type' => 'string',
                    'desc' => 'Mensaje mostrado en la página de inicio'
                ],
                'footer' => [
                    'value' => '© 2025 Turnos.com - Sistema de Gestión de Turnos Hospitalarios',
                    'type' => 'string',
                    'desc' => 'Texto para el footer de la aplicación'
                ],
                'institution_name' => [
                    'value' => 'Hospital Regional General',
                    'type' => 'string',
                    'desc' => 'Nombre de la institución de salud'
                ],
                'patient_message' => [
                    'value' => 'Por favor, presentese 15 minutos antes. Si no puede asistir, cancele su turno con antelación. Traer DNI. Gracias.',
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
                'maximum_faults' => [
                    'value' => 3,
                    'type' => 'integer',
                    'desc' => 'Número máximo de faltas permitidas antes de bloqueo'
                ],
                'daily_limit' => [
                    'value' => 2,
                    'type' => 'integer',
                    'desc' => 'Máximo de reservas por paciente por día'
                ],
                'cancellation_hours' => [
                    'value' => 24,
                    'type' => 'integer',
                    'desc' => 'Horas mínimas para cancelar una reserva'
                ],
                'advance_reservation' => [
                    'value' => 24,
                    'type' => 'integer',
                    'desc' => 'Horas de anticipación para reservar'
                ],
                'unit_advance' => [
                    'value' => 'time',
                    'type' => 'string',
                    'desc' => 'Unidad de tiempo para antelación (horas/días)'
                ]
            ],

            // Configuración de statuss
            'assists' => [
                'verification_interval' => [
                    'value' => 1,
                    'type' => 'integer',
                    'desc' => 'Horas entre verificaciones automáticas'
                ]
            ],
            //Configuracion de estilos y diseño
            'design' => [
                //color de fonde de la aplicacion
                'dark_application_background' => [
                    'value' => '#00272E',  // Verde-azulado (gunmetal)
                    'type' => 'string',
                    'desc' => 'Color de fondo de la aplicación'
                ],
                'light_application_background' => [
                    'value' => '#ebebeb', // Gris claro
                    'type' => 'string',
                    'desc' => 'Color de fondo de la aplicación'
                ],
                'general_design_color' => [
                    'value' => '#4CAF50', // Verde claro
                    'type' => 'string',
                    'desc' => 'Color de texto para bordes, subrayados, iconos, sombras'
                ],
                'title_text_color' => [
                    'value' => '#4CAF50', // Verde claro
                    'type' => 'string',
                    'desc' => 'Color de texto para títulos y encabezados'
                ],
                'subtitle_text_color' => [
                    'value' => '#4CAF50', // Verde claro
                    'type' => 'string',
                    'desc' => 'Color de texto para subtítulos y encabezados'
                ],
                'footer_background' => [
                    'value' => '#000000', // negro
                    'type' => 'string',
                    'desc' => 'Color de texto para subtítulos y encabezados'
                ],
                'dark_text_color' => [
                    'value' => '#FFFAFA', // Blanco nieve
                    'type' => 'string',
                    'desc' => 'Color de texto para el modo oscuro'
                ],
                'light_text_color' => [
                    'value' => '#262626', // Gris claro
                    'type' => 'string',
                    'desc' => 'Color de texto para el modo claro2'
                ],
                //Color texto small
                'text_color_small_dark' => [
                    'value' => '#808080', //Gris
                    'type' => 'string',
                    'desc' => 'Color de letra small en tema claro'
                ],
                'text_color_small_light' => [
                    'value' => '#808080', //Gris
                    'type' => 'string',
                    'desc' => 'Color de letra small en tema claro'
                ],
                //Color de botones
                'primary_color_btn' => [
                    'value' => '#4CAF50', // Verde claro
                    'type' => 'string',
                    'desc' => 'Color primario para botones y elementos principales'
                ],
                'secondary_color_btn' => [
                    'value' => '#4CAF50', // Verde claro
                    'type' => 'string',
                    'desc' => 'Color secundario para acentos'
                ],
                'btn_text_color' => [
                    'value' => '#FFFAFA', // Blanco nieve
                    'type' => 'string',
                    'desc' => 'Color de texto para botones'
                ],
                //Barra de navegacion
                'background_navbar_dark' => [
                    'value' => '#002b33', //Verde Oscuro
                    'type' => 'string',
                    'desc' => 'Color de fondo del navbar en tema oscuro'
                ],
                'background_navbar_light' => [
                    'value' => '#F0F0F0', // Blanco oscuro
                    'type' => 'string',
                    'desc' => 'Color de fondo del navbar en tema claro'
                ],
                //Formulario Login y Registro
                'background_login_and_register_dark' => [
                    'value' =>  '#003B46',  //Verde Petroleo
                    'type' => 'string',
                    'desc' => 'Color de fondo del login/register en tema claro'
                ],
                'background_login_and_register_light' => [
                    'value' => '#FFFAFA', // Blanco nieve
                    'type' => 'string',
                    'desc' => 'Color de fondo del login/register en tema claro'
                ],
                //Form elements text color /inputs,option y textarea
                'text_color_form_elements_dark' => [
                    'value' => '#FFFAFA', //Blanco nieve
                    'type' => 'string',
                    'desc' => 'Color de letra de los elementos de los formularios en tema claro'
                ],
                'text_color_form_elements_light' => [
                    'value' => '#003B46',  //Verde Petroleo
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
