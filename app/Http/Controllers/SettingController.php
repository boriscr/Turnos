<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
class SettingController extends Controller
{
    public function index(){
        //
    }
    public function edit()
    {
        $settings = Setting::first();
        return view('settings.edit',compact('settings'));
    }
    public function update(Request $request)
    {
        $settings = Setting::first();
        $settings->update([
            'nombre' => $request->input('nombre'),
            'mensaje_bienvenida' => $request->input('mensaje_bienvenida'),
            'pie_pagina' => $request->input('pie_pagina'),
            'nombre_institucion' => $request->input('nombre_institucion'),
            'cancelacion_turnos' => $request->input('cancelacion_turnos'),
            'preview_window_amount' => $request->input('preview_window_amount'),
            'preview_window_unit' => $request->input('preview_window_unit'),
            'faltas' => $request->input('faltas'),
            'limites' => $request->input('limites'),
            'hora_verificacion_asistencias' => $request->input('hora_verificacion_asistencias'),
        ]);
        session()->flash('success', [
            'title' => 'Configuración actualizada',
            'text' => 'La configuración se ha actualizado correctamente.',
            'icon' => 'success',
        ]);
        return redirect()->route('home');
    }
}
