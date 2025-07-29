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
            'design.fondo_aplicacion_dark' => setting('design.fondo_aplicacion_dark'),
            'design.fondo_aplicacion_light' => setting('design.fondo_aplicacion_light'),
            'design.color_texto_titulo' => setting('design.color_texto_titulo'),
            'design.color_texto_dark' => setting('design.color_texto_dark'),
            'design.color_texto_light' => setting('design.color_texto_light'),
            'design.color_texto_small_dark' => setting('design.color_texto_small_dark'),
            'design.color_texto_small_light' => setting('design.color_texto_small_light'),

            'design.color_primario_btn' => setting('design.color_primario_btn'),
            'design.color_secundario_btn' => setting('design.color_secundario_btn'),
            'design.color_texto_btn' => setting('design.color_texto_btn'),

            'design.fondo_navbar_dark' => setting('design.fondo_navbar_dark'),
            'design.fondo_navbar_light' => setting('design.fondo_navbar_light'),
            'design.fondo_login_register_dark' => setting('design.fondo_login_register_dark'),
            'design.fondo_login_register_light' => setting('design.fondo_login_register_light'),
            'design.color_texto_form_elements_dark' => setting('design.color_texto_form_elements_dark'),
            'design.color_texto_form_elements_light' => setting('design.color_texto_form_elements_light'),
        ];
        config($designSettings);
        // Cargar configuraciones importantes
        config([
            'app.name' => setting('app.name', config('app.name')),
            'app.description' => setting('app.description'),
            'app.mensaje_paciente' => setting('app.mensaje_paciente'),

        ]);
    }
}
