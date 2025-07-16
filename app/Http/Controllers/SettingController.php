<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Http\Requests\SettingUpdateRequest;

class SettingController extends Controller
{
    public function index()
    {
        //
    }
    public function edit()
    {
        $settings = app('settings'); // o Setting::all()->pluck('value', 'key')
        return view('settings.edit', compact('settings'));
    }


    public function update(SettingUpdateRequest $request)
    {
       
        foreach ($request->all() as $group => $items) {
            if (is_array($items)) {
                foreach ($items as $key => $value) {
                    $fullKey = "{$group}.{$key}";

                    // Determinar el tipo de dato
                    $type = match (true) {
                        is_numeric($value) => 'integer',
                        is_bool($value) => 'boolean',
                        $this->isJson($value) => 'json',
                        default => 'string'
                    };

                    Setting::updateOrCreate(
                        ['key' => $fullKey],
                        [
                            'value' => $value,
                            'type' => $type,
                            'group' => $group
                        ]
                    );
                }
            }
        }

        // Limpiar caché
        cache()->forget('app_settings');
        session()->flash('success', [
            'icon' => 'success',
            'title' => 'Configuración actualizada',
            'text' => 'Los cambios se han guardado correctamente.'
        ]);
        return redirect()->route('settings.edit');
    }

    protected function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
