<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Doctor;

class DoctorStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Solo los administradores pueden crear doctores
        return $this->user() && $this->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:40'],
            'surname' => ['required', 'string', 'min:3', 'max:15'],
            'idNumber' => ['required', 'string', 'min:7', 'max:8', 'unique:' . Doctor::TABLE . ',idNumber'],
            'email' => ['required', 'email', 'min:3', 'max:60', 'unique:' . Doctor::TABLE . ',email'],
            'phone' => ['required', 'string', 'min:5', 'max:15'],
            'specialty_id' => ['required', 'exists:specialties,id'],
            'licenseNumber' => ['required', 'string', 'min:3', 'max:60'],
            'role' => ['required', 'string', 'in:doctor'],
            'status' => ['sometimes', 'boolean'],
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
