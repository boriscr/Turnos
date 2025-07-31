<?php

namespace App\Http\Requests;

use App\Models\Specialty;
use Illuminate\Foundation\Http\FormRequest;

class SpecialtyUpdateRequest extends FormRequest
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
        $specialty_id = $this->route('id'); // Asegúrate de que el name del parámetro en la ruta coincida

        return [
            'name' => 'required|string|min:5|max:30|unique:specialties,name,' . $specialty_id,
            'description' => 'required|string|min:5|max:500',
            'status' => 'required|boolean',
        ];
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim($this->name),
            'description' => trim($this->description),
        ]);
    }
}
