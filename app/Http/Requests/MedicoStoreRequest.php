<?php

namespace App\Http\Requests;

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
            'name' => 'required|string|min:3|max:40',
            'surname' => 'required|string|min:3|max:15',
            'idNumber' => 'required|string|min:7|max:8|unique:' . Medico::TABLE . ',idNumber',
            'email' => 'required|email|min:3|max:60|unique:' . Medico::TABLE . ',email',
            'phone' => 'required|string|min:5|max:15',
            'specialty_id' => 'required|exists:specialties,id',
            'licenseNumber' => 'required|string|min:3|max:60',
            'role' => 'required|string|in:admin,doctor',
            'status' => 'sometimes|boolean',
        ];
    }
    // Limpia los espacios antes de validar
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim($this->name),
            'surname' => trim($this->surname),
            'idNumber' => trim($this->idNumber),
            'email' => trim($this->email),
            'phone' => trim($this->phone),
            'licenseNumber' => trim($this->licenseNumber),
        ]);
    }
}
