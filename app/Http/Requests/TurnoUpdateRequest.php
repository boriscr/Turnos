<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TurnoUpdateRequest extends FormRequest
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
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string',
            'especialidad_id' => 'required|exists:especialidades,id',
            'medico_id' => 'required|exists:medicos,id',
            'cantidad' => 'required|integer|min:1',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
            'selected_dates' => 'required',
            'isActive' => 'required|boolean',
        ];
    }
}
