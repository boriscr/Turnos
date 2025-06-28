<?php

namespace App\Http\Requests;

use App\Models\Especialidad;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Medico;

class MedicoStoreRequest extends FormRequest
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
            'dni' => 'required|digits_between:7,8|unique:medicos,dni',
            'email' => 'required|email|unique:' . Medico::TABLE . ',email',
            'telefono' => 'required|string|max:255',
            'especialidad_id' => 'required|exists:especialidades,id',
            'matricula' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'estado' => 'sometimes|boolean',
        ];
    }
}
