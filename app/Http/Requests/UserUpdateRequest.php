<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Solo los administradores pueden editar usuarios
         return $this->user() && $this->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('id');
        
        return [
            'name' => ['required', 'string', 'min:3', 'max:40'],
            'surname' => ['required', 'string', 'min:3', 'max:40'],
            'idNumber' => [
                'required',
                'string',
                'min:7',
                'max:8',
                Rule::unique('users', 'idNumber')->ignore($userId)
            ],
            'birthdate' => ['required', 'date', 'before:today'], // 'before:-18 years'
            'gender' => ['required', 'in:Masculino,Femenino,No binario,Otro,Prefiero no decir'],
            'country' => ['required', 'string', 'min:3', 'max:50'],
            'province' => ['required', 'string', 'min:3', 'max:50'],
            'city' => ['required', 'string', 'min:2', 'max:50'],
            'address' => ['required', 'string', 'min:10', 'max:100'],
            'phone' => [
                'required',
                'string',
                'min:9',
                'max:15',
                Rule::unique('users', 'phone')->ignore($userId)
            ],
            'email' => [
                'required',
                'string',
                'email',
                'min:5',
                'max:60',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'role' => ['required', 'string', 'in:user,admin,doctor'],
            'status' => ['required', 'boolean'], // Cambiado de 'sometimes' a 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.min' => 'El nombre debe tener al menos :min caracteres',
            'surname.required' => 'El apellido es obligatorio',
            'surname.min' => 'El apellido debe tener al menos :min caracteres',
            'idNumber.required' => 'El número de documento es obligatorio',
            'idNumber.unique' => 'Este número de documento ya está registrado',
            'idNumber.min' => 'El documento debe tener al menos :min caracteres',
            'birthdate.required' => 'La fecha de nacimiento es obligatoria',
            'birthdate.before' => 'El usuario debe ser mayor de 18 años',
            'gender.required' => 'El género es obligatorio',
            'gender.in' => 'El género seleccionado no es válido',
            'country.required' => 'El país es obligatorio',
            'province.required' => 'La provincia es obligatoria',
            'city.required' => 'La ciudad es obligatoria',
            'address.required' => 'La dirección es obligatoria',
            'phone.required' => 'El teléfono es obligatorio',
            'phone.unique' => 'Este teléfono ya está registrado',
            'email.required' => 'El email es obligatorio',
            'email.unique' => 'Este email ya está registrado',
            'email.email' => 'El formato del email no es válido',
            'role.required' => 'El rol es obligatorio',
            'role.in' => 'El rol seleccionado no es válido',
            'status.required' => 'El estado es obligatorio',
            'status.boolean' => 'El estado debe ser verdadero o falso',
        ];
    }

    // Limpia los espacios antes de validar
    protected function prepareForValidation(): void
    {
        $this->merge(array_filter([
            'name' => $this->name ? trim($this->name) : null,
            'surname' => $this->surname ? trim($this->surname) : null,
            'idNumber' => $this->idNumber ? trim($this->idNumber) : null,
            'province' => $this->province ? trim($this->province) : null,
            'city' => $this->city ? trim($this->city) : null,
            'address' => $this->address ? trim($this->address) : null,
            'phone' => $this->phone ? trim($this->phone) : null,
            'email' => $this->email ? trim(strtolower($this->email)) : null,
        ], fn($value) => $value !== null));
    }

    /**
     * Configurar los datos después de la validación
     */
    protected function passedValidation(): void
    {
        // Convertir status a boolean si viene como string
        if ($this->has('status') && is_string($this->status)) {
            $this->merge([
                'status' => filter_var($this->status, FILTER_VALIDATE_BOOLEAN)
            ]);
        }
    }
}