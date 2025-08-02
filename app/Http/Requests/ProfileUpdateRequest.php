<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Solo users autenticados pueden actualizar su perfil
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->user()?->id;

        $rules = [
            'name' => ['required', 'string', 'min:3', 'max:40'],
            'surname' => ['required', 'string', 'min:3', 'max:40'],
            'birthdate' => ['required', 'date', 'before:today'],//'before:-18 years'
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
        ];

        // SEGURIDAD: Explícitamente rechazar campos sensibles
        $this->prohibitedFields();

        return $rules;
    }

    /**
     * Campos que están prohibidos en la actualización de perfil
     */
    protected function prohibitedFields(): void
    {
        $prohibitedFields = ['idNumber', 'role', 'status', 'password'];

        foreach ($prohibitedFields as $field) {
            if ($this->has($field)) {
                // Remover el campo del request por seguridad
                $this->request->remove($field);
            }
        }
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.min' => 'El nombre debe tener al menos :min caracteres',
            'surname.required' => 'El apellido es obligatorio',
            'surname.min' => 'El apellido debe tener al menos :min caracteres',
            'birthdate.required' => 'La date de nacimiento es obligatoria',
            'birthdate.before' => 'Debes ser mayor de 18 años',
            'gender.required' => 'El género es obligatorio',
            'country.required' => 'El país es obligatorio',
            'province.required' => 'La provincia es obligatoria',
            'city.required' => 'La ciudad es obligatoria',
            'address.required' => 'La dirección es obligatoria',
            'phone.required' => 'El teléfono es obligatorio',
            'phone.unique' => 'Este teléfono ya está registrado',
            'email.required' => 'El email es obligatorio',
            'email.unique' => 'Este email ya está registrado',
            'email.email' => 'El formato del email no es válido',
        ];
    }

    // Limpia los espacios antes de validar
    protected function prepareForValidation(): void
    {
        $this->merge(array_filter([
            'name' => $this->name ? trim($this->name) : null,
            'surname' => $this->surname ? trim($this->surname) : null,
            'province' => $this->province ? trim($this->province) : null,
            'city' => $this->city ? trim($this->city) : null,
            'address' => $this->address ? trim($this->address) : null,
            'phone' => $this->phone ? trim($this->phone) : null,
            'email' => $this->email ? trim(strtolower($this->email)) : null,
        ], fn($value) => $value !== null));
    }
}
