<?php

namespace App\Http\Requests;

use App\Models\Especialidad;
use Illuminate\Foundation\Http\FormRequest;

class EspecialidadUpdateRequest extends FormRequest
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
        $especialidad_id = $this->route('id'); // Asegúrate de que el nombre del parámetro en la ruta coincida

        return [
            'nombre' => 'required|string|max:255|unique:especialidades,nombre,' . $especialidad_id,
            'descripcion' => 'required|string|max:255',
            'isActive' => 'required|boolean',
        ];
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'nombre' => trim($this->nombre),
            'descripcion' => trim($this->descripcion),
            // otros campos...
        ]);
    }
}
