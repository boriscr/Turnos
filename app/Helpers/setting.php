<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    /**
     * Obtiene un valor de configuración
     * 
     * @param string $key Clave en formato 'grupo.clave'
     * @param mixed $default Valor por defecto si no existe
     * @return mixed
     */
    function setting($key, $default = null)
    {
        try {
            // Primero intenta obtener del contenedor de servicios
            $settings = app('settings');
            
            if (array_key_exists($key, $settings)) {
                return $settings[$key];
            }
            
            // Fallback: Buscar directamente en la base de datos
            return Cache::rememberForever("setting.{$key}", function () use ($key, $default) {
                $setting = Setting::where('key', $key)->first();
                return $setting ? $setting->value : value($default);
            });
            
        } catch (Exception $e) {
            return value($default);
        }
    }
}