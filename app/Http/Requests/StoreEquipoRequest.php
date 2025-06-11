<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEquipoRequest extends FormRequest
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
            'dni' => 'required|integer|digits_between:7,8|unique:equipos,dni',
            'email' => 'required|email|unique:equipos,email',
            'telefono' => 'required|string|max:255',
            'especialidad_id' => 'required|exists:especialidads,id',
            'matricula' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'estado' => 'sometimes|boolean',
        ];
    }
}
