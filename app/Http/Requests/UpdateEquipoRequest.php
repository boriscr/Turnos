<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEquipoRequest extends FormRequest
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
        // Obtener el ID del equipo desde la ruta (ej: /equipos/{equipo})
        $equipo_id = $this->route('id'); // Asegúrate de que el nombre del parámetro en la ruta coincida

        return [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'required|integer|digits_between:7,8|unique:equipos,dni,' . $equipo_id,
            'email' => 'required|email|unique:equipos,email,' . $equipo_id,
            'telefono' => 'required|string|max:255',
            'especialidad_id' => 'required|exists:especialidads,id',
            'matricula' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'estado' => 'sometimes|boolean',
        ];
    }
}
