<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingUpdateRequest extends FormRequest
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
            'app.name' => ['required', 'string', 'min:3', 'max:255'],
            'app.welcome_message' => ['required', 'string', 'min:20', 'max:500'],
            'app.footer' => ['required', 'string', 'min:10', 'max:300'],
            'app.institution_name' => ['required', 'string', 'min:5', 'max:80'],
            'app.patient_message' => ['required', 'string', 'min:20', 'max:500'],
            'appointments.maximum_faults' => ['required', 'integer', 'min:0'],
            'appointments.daily_limit' => ['required', 'integer', 'min:0'],
            'appointments.cancellation_hours' => ['required', 'integer', 'min:0'],
            'appointments.advance_reservation' => ['required', 'integer', 'min:0'],
            'appointments.unit_advance' => ['required', 'in:time,day,month'],
            'assists.verification_interval' => ['required', 'integer', 'min:1'],
            //color de fonde de la aplicacion
            'design.dark_application_background' => ['required', 'string', 'max:10'],
            'design.light_application_background' => ['required', 'string', 'max:10'],
            //Color general de diseño
            'design.general_design_color' => ['required', 'string', 'max:10'],
            //Colores de los textos
            'design.title_text_color' => ['required', 'string', 'max:10'],
            'design.subtitle_text_color' => ['required', 'string', 'max:10'],
            'design.dark_text_color' => ['required', 'string', 'max:10'],
            'design.footer_background' => ['required', 'string', 'max:10'],

            'design.light_text_color' => ['required', 'string', 'max:10'],
            //Color texto letra pequeña (Small)
            'design.text_color_small_dark' => ['required', 'string', 'max:10'],
            'design.text_color_small_light' => ['required', 'string', 'max:10'],
            //Color de fondo botones
            'design.primary_color_btn' => ['required', 'string', 'max:10'],
            'design.secondary_color_btn' => ['required', 'string', 'max:10'],
            'design.btn_text_color' => ['required', 'string', 'max:10'],
            //Navbar
            'design.background_navbar_dark' => ['required', 'string', 'max:10'],
            'design.background_navbar_light' => ['required', 'string', 'max:10'],
            //Login-Register
            'design.background_login_and_register_dark' => ['required', 'string', 'max:10'],
            'design.background_login_and_register_light' => ['required', 'string', 'max:10'],
            //Form elements text color
            'design.text_color_form_elements_dark' => ['required', 'string', 'max:10'],
            'design.text_color_form_elements_light' => ['required', 'string', 'max:10'],

        ];
    }
}
