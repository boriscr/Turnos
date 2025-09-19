<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class RegisteredStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:3', 'max:40'],
            'surname' => ['required', 'string', 'min:3', 'max:40'],
            'idNumber' => ['required', 'string', 'min:7', 'max:8', 'unique:' . User::class],
            'birthdate' => ['required', 'date', 'before:today'],//'before:-18 years'
            'gender' => ['required', 'string', 'in:Masculino,Femenino,No binario,Otro,Prefiero no decir'],
            'country' => ['required', 'string', 'min:3', 'max:50'],
            'province' => ['required', 'string', 'min:3', 'max:50'],
            'city' => ['required', 'string', 'min:3', 'max:50'],
            'address' => ['required', 'string', 'min:10', 'max:100'],
            'phone' => ['required', 'string', 'min:9', 'max:15', 'unique:' . User::class],
            'email' => ['required', 'string', 'email', 'min:5', 'max:100', 'unique:' . User::class],
            'password' => [
                'required',
                'confirmed',
                \Illuminate\Validation\Rules\Password::defaults()
                    ->min(12)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ];
    }

  public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.min' => 'El nombre debe tener al menos :min caracteres',
            'surname.required' => 'El apellido es obligatorio',
            'idNumber.required' => 'El número de documento es obligatorio',
            'idNumber.unique' => 'Este número de documento ya está registrado',
            'birthdate.required' => 'La date de nacimiento es obligatoria',
            'birthdate.before' => 'Debes ser mayor de 18 años',
            'email.unique' => 'Este email ya está registrado',
            'phone.unique' => 'Este teléfono ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.confirmed' => 'Las contraseñas no coinciden',
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
}
