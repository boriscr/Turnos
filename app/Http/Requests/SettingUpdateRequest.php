<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'app.name' => 'required|string|max:255',
            'app.mensaje_bienvenida' => 'required|string|max:255',
            'app.pie_pagina' => 'required|string|max:255',
            'app.nombre_institucion' => 'required|string|max:255',
            'app.mensaje_paciente' => 'required|string|max:255',
            'appointments.faltas_maximas' => 'required|integer|min:0',
            'appointments.limite_diario' => 'required|integer|min:1',
            'appointments.horas_cancelacion' => 'required|integer|min:0',
            'appointments.antelacion_reserva' => 'required|integer|min:1',
            'appointments.unidad_antelacion' => 'required|in:time,dia,mes',
            'asistencias.intervalo_verificacion' => 'required|integer|min:1',
            //color de fonde de la aplicacion
            'design.fondo_aplicacion_dark' => 'required|string|max:10',
            'design.fondo_aplicacion_light' => 'required|string|max:10',
            //Colores de los textos
            'design.color_texto_titulo' => 'required|string|max:10',
            'design.color_texto_dark' => 'required|string|max:10',
            'design.color_texto_light' => 'required|string|max:10',
            //Color texto letra pequeña (Small)
            'design.color_texto_small_dark' => 'required|string|max:10',
            'design.color_texto_small_light' => 'required|string|max:10',
            //Color de fondo botones
            'design.color_primario_btn' => 'required|string|max:10',
            'design.color_secundario_btn' => 'required|string|max:10',
            'design.color_texto_btn' => 'required|string|max:10',
            //Navbar
            'design.fondo_navbar_dark' => 'required|string|max:10',
            'design.fondo_navbar_light' => 'required|string|max:10',
            //Login-Register
            'design.fondo_login_register_dark' => 'required|string|max:10',
            'design.fondo_login_register_light' => 'required|string|max:10',
            //Form elements text color
            'design.color_texto_form_elements_dark' => 'required|string|max:10',
            'design.color_texto_form_elements_light' => 'required|string|max:10',

        ];
    }
}
