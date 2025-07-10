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
        // Cargar configuraciones importantes
        config([
            'app.name' => setting('app.nombre', config('app.name')),
            'app.description' => setting('app.descripcion'),
            'app.mensaje_paciente' => setting('app.mensaje_paciente'),
        ]);
    }
}
