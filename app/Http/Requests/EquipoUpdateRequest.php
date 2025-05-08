<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EquipoUpdateRequest extends FormRequest
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
            'apellido' => 'required|string|max:255',
            'dni' => 'required|integer|unique:equipos,dni',
            'email' => 'required|email|unique:equipos,email',
            'telefono' => 'required|string|max:15',
            'especialidad' => 'required|string|max:255',
            'matricula' => 'required|string|max:255',
            'rol' => 'required|string|max:255',
            'usuario' => 'nullable|string|max:255|unique:equipos,usuario',
        ];
    }
}
