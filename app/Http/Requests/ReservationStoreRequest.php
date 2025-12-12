<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ReservationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    public function authorize(): bool
    {
        // Usar una política para la autorización
        //return $this->user()->can('reserve-appointment');
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        return [
            'appointment_id' => ['required', 'exists:available_appointments,id'],
            'specialty_id'   => ['required', 'exists:specialties,id'],
            'patient_type_radio'=> ['required', 'in:self,third_party'],
            'third_party_name' => ['required_if:type,third_party', 'nullable', 'string', 'max:40'],
            'third_party_surname' => ['required_if:type,third_party', 'nullable', 'string', 'max:40'],
            'third_party_idNumber' => ['required_if:type,third_party', 'nullable', 'string', 'max:8'],
            'third_party_email' => ['required_if:type,third_party', 'nullable', 'string', 'email', 'max:100'],
        ];
    }
}
