<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'name' => ['required', 'string', 'min:3', 'max:80'],
            'address' =>  ['required', 'string', 'min:3', 'max:150'],
            'specialty_id' => ['required', 'exists:specialties,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'shift'=>['required','string','in:morning,afternoon,night'],
            'cantidad' =>  ['required', 'integer', 'min:1'],
            'start_time' =>  ['required'],
            'end_time' =>  ['required'],
            'selected_dates' =>  ['required', 'json'], // Aseguramos que sea un JSON válido
            'status' =>  ['required', 'boolean'],
        ];
    }
}
