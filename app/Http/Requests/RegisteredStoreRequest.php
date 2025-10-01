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
            'birthdate' => [
                'required',
                'date',
                'before:-18 years',  // 👈 Mayor de 18 años
                'after:-120 years' // 👈 Menor de 120 años
            ], //'before:-18 years'
            'gender' => ['required', 'string', 'in:Masculino,Femenino,No binario,Otro,Prefiero no decir'],
            'country_id' => ['required', 'integer', 'exists:countries,id'],
            'state_id' => ['required', 'integer', 'exists:states,id'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'address' => ['required', 'string', 'min:10', 'max:100'],
            'phone' => ['required', 'string', 'min:9', 'max:15', 'regex:/^(\+?\d{1,3}[-.\s]?)?\(?\d{1,4}\)?[-.\s]?\d{1,4}[-.\s]?\d{1,9}$/', 'unique:' . User::class],
            'email' => ['required', 'string', 'email', 'min:5', 'max:100', 'unique:' . User::class],
            'password' => [
                'required',
                'confirmed',
                'string',
                'max:72',
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
            'name.max' => 'El nombre no puede exceder los :max caracteres',

            'surname.required' => 'El apellido es obligatorio',
            'surname.min' => 'El apellido debe tener al menos :min caracteres',
            'surname.max' => 'El apellido no puede exceder los :max caracteres',

            'idNumber.required' => 'El número de documento es obligatorio',
            'idNumber.unique' => 'Este número de documento ya está registrado',
            'idNumber.min' => 'El documento debe tener al menos :min caracteres',
            'idNumber.max' => 'El documento no puede exceder los :max caracteres',

            'birthdate.required' => 'La date de nacimiento es obligatoria',
            'birthdate.before' => 'El usuario debe ser mayor de 18 años.',
            'birthdate.after' => 'La fecha de nacimiento no es válida.',

            'gender.required' => 'El género es obligatorio',
            'gender.in' => 'El género seleccionado no es válido',

            'country_id.required' => 'El país es obligatorio',

            'state_id.required' => 'La provincia es obligatoria',
            'state_id.min' => 'La provincia debe tener al menos :min caracteres',
            'state_id.max' => 'La provincia no puede exceder los :max caracteres',

            'city_id.required' => 'La ciudad es obligatoria',
            'city_id.min' => 'La ciudad debe tener al menos :min caracteres',
            'city_id.max' => 'La ciudad no puede exceder los :max caracteres',

            'address.required' => 'La dirección es obligatoria',
            'address.min' => 'La dirección debe tener al menos :min caracteres',
            'address.max' => 'La dirección no puede exceder los :max caracteres',

            'phone.unique' => 'Este teléfono ya está registrado',
            'phone.required' => 'El teléfono es obligatorio',
            'phone.regex' => 'El formato del teléfono no es válido',
            'phone.min' => 'El teléfono debe tener al menos :min caracteres',
            'phone.max' => 'El teléfono no puede exceder los :max caracteres',

            'email.unique' => 'Este email ya está registrado',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El formato del email no es válido',
            'email.min' => 'El email debe tener al menos :min caracteres',
            'email.max' => 'El email no puede exceder los :max caracteres',

            'password.required' => 'La contraseña es obligatoria',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.min' => 'La contraseña no puede ser menor a 12 caracteres por limitaciones técnicas de seguridad.',
            'password.max' => 'La contraseña no puede exceder los 72 caracteres por limitaciones técnicas de seguridad.',
            // Agregar estos mensajes específicos para Password rules
            'password.letters' => 'La contraseña debe contener letras',
            'password.mixed_case' => 'La contraseña debe contener mayúsculas y minúsculas',
            'password.numbers' => 'La contraseña debe contener números',
            'password.symbols' => 'La contraseña debe contener símbolos especiales',
            'password.uncompromised' => 'Esta contraseña ha aparecido en filtraciones de datos. Por seguridad, elige otra.',
        ];
    }

    // Limpia los espacios antes de validar
    protected function prepareForValidation(): void
    {
        $this->merge(array_filter([
            'name' => $this->name ? trim($this->name) : null,
            'surname' => $this->surname ? trim($this->surname) : null,
            'idNumber' => $this->idNumber ? trim($this->idNumber) : null,
            'address' => $this->address ? trim($this->address) : null,
            'phone' => $this->phone ? trim($this->phone) : null,
            'email' => $this->email ? trim(strtolower($this->email)) : null,
        ], fn($value) => $value !== null));
    }
}
