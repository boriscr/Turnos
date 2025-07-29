<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EspecialidadStoreRequest extends FormRequest
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
            'name' => 'required|string|min:5|max:30|unique:specialties,name',
            'description' => 'required|string|min:5|max:500',
            'status' => 'required|boolean',
        ];
    }
    // Limpia los espacios antes de validar
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim($this->name),
            'description' => trim($this->description),
        ]);
    }
}
