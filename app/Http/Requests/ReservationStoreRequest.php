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
        ];
    }
}
