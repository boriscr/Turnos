<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
public function rules(): array
{
    $rules = [
        'name' => ['required', 'string', 'max:255'],
        'surname' => ['required', 'string', 'max:255'],
        'birthdate' => ['required', 'date', 'before:-18 years'],
        'gender' => ['required', 'in:Femenino,Masculino,No binario,Otro,Prefiero no decir'],
        'country' => ['required', 'string', 'max:100'],
        'province' => ['required', 'string', 'max:100'],
        'city' => ['required', 'string', 'max:100'],
        'address' => ['required', 'string', 'max:255'],
        'phone' => ['required', 'string', 'max:20'],
        'email' => ['required', 'string', 'email', 'max:255', 
                   Rule::unique('users')->ignore($this->user()->id)],
    ];

    // Solo validar DNI si está presente en el request (no debería estarlo normalmente)
    if ($this->has('idNumber')) {
        $rules['idNumber'] = ['numeric', Rule::unique('users')->ignore($this->user()->id)];
    }

    return $rules;
}
}
