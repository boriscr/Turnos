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
        $user = $this->user();
        return $user && (
            $user->hasRole('admin') ||
            ($user->hasRole('doctor') && $user->doctor !== null)
        );
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
            'shift' => ['required', 'string', 'in:morning,afternoon,night'],
            'number_of_reservations' =>  ['required', 'integer', 'min:1'],
            'start_time' =>  ['required'],
            'end_time' =>  ['required'],
            'available_time_slots' => [],
            'selected_dates' =>  ['required', 'json'], // Aseguramos que sea un JSON válido
            'status' =>  ['required', 'boolean'],
        ];
    }
    // Limpia los espacios antes de validar
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim($this->name),
            'address' => trim($this->address),
            'number_of_reservations' => trim($this->number_of_reservations),
            'phone' => trim($this->phone),
            'licenseNumber' => trim($this->licenseNumber),
        ]);
    }
}
