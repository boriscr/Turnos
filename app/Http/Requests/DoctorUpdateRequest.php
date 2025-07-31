<?php

namespace App\Http\Requests;

use App\Models\Doctor;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Specialty;

class DoctorUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Solo los administradores pueden editar doctores
        return $this->user() && $this->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array

    {
        // Obtener el ID del doctor desde la ruta (ej: /doctors/{equip
        $doctor_id = $this->route('id'); // Asegúrate de que el name del parámetro en la ruta coincida

        return [
            'name' => ['required', 'string', 'min:3', 'max:40'],
            'surname' => ['required', 'string', 'min:3', 'max:15'],
            'idNumber' => ['required', 'string', 'min:7', 'max:8', 'unique:' . Doctor::TABLE . ',idNumber,' . $doctor_id],
            'email' => ['required', 'email', 'min:3', 'max:60', 'unique:' . Doctor::TABLE . ',email,' . $doctor_id],
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
