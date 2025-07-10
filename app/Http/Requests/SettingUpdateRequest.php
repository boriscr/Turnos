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
            'app.nombre' => 'required|string|max:255',
            'app.mensaje_bienvenida' => 'required|string|max:255',
            'app.pie_pagina' => 'required|string|max:255',
            'app.nombre_institucion' => 'required|string|max:255',
            'app.mensaje_paciente' => 'required|string|max:255',
            'turnos.faltas_maximas' => 'required|integer|min:0',
            'turnos.limite_diario' => 'required|integer|min:1',
            'turnos.horas_cancelacion' => 'required|integer|min:0',
            'turnos.antelacion_reserva' => 'required|integer|min:1',
            'turnos.unidad_antelacion' => 'required|in:hora,dia,mes',
            'asistencias.intervalo_verificacion' => 'required|integer|min:1',
        ];
    }
}
