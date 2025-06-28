<?php

namespace App\Http\Requests;

use App\Models\Medico;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Especialidad;

class MedicoUpdateRequest extends FormRequest
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
        // Obtener el ID del medico desde la ruta (ej: /medicos/{equip
        $medico_id = $this->route('id'); // Asegúrate de que el nombre del parámetro en la ruta coincida

        return [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'required|digits_between:7,8|unique:medicos,dni,' . $medico_id,
            'email' => 'required|email|unique:' . Medico::TABLE . ',email,' . $medico_id,
            'telefono' => 'required|string|max:255',
            'especialidad_id' => 'required|exists:especialidades,id',
            'matricula' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'estado' => 'sometimes|boolean',
        ];
    }
}
