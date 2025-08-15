<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('settings', function () {
            return cache()->remember('app_settings', now()->addDay(), function () {
                return Setting::all()->pluck('value', 'key')->toArray();
            });
        });

        // Registrar el helper
        require_once app_path('Helpers/setting.php');
    }

    public function boot()
    {
        // Configuración de diseño
        $designSettings = [
            //Colores de la app
            'design.dark_application_background' => setting('design.dark_application_background'),
            'design.light_application_background' => setting('design.light_application_background'),
            'design.general_design_color' => setting('design.general_design_color'),
            'design.title_text_color' => setting('design.title_text_color'),
            'design.subtitle_text_color' => setting('design.subtitle_text_color'),
            'design.dark_text_color' => setting('design.dark_text_color'),
            'design.light_text_color' => setting('design.light_text_color'),
            'design.text_color_small_dark' => setting('design.text_color_small_dark'),
            'design.text_color_small_light' => setting('design.text_color_small_light'),

            'design.primary_color_btn' => setting('design.primary_color_btn'),
            'design.secondary_color_btn' => setting('design.secondary_color_btn'),
            'design.btn_text_color' => setting('design.btn_text_color'),

            'design.background_navbar_dark' => setting('design.background_navbar_dark'),
            'design.background_navbar_light' => setting('design.background_navbar_light'),
            'design.background_login_and_register_dark' => setting('design.background_login_and_register_dark'),
            'design.background_login_and_register_light' => setting('design.background_login_and_register_light'),
            'design.text_color_form_elements_dark' => setting('design.text_color_form_elements_dark'),
            'design.text_color_form_elements_light' => setting('design.text_color_form_elements_light'),
        ];
        config($designSettings);
        // Cargar configuraciones importantes
        config([
            'app.name' => setting('app.name', config('app.name')),
            'app.description' => setting('app.description'),
            'app.patient_message' => setting('app.patient_message'),

        ]);
    }
}
