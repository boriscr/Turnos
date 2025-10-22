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
            'app.name' => ['string', 'min:3', 'max:255'],
            'app.welcome_message' => ['string', 'min:20', 'max:500'],
            'app.footer' => ['string', 'min:10', 'max:300'],
            'app.institution_name' => ['string', 'min:5', 'max:80'],
            'app.patient_message' => ['string', 'min:20', 'max:500'],
            'appointments.maximum_faults' => ['integer', 'min:0'],
            'appointments.daily_limit' => ['integer', 'min:0'],
            'appointments.cancellation_hours' => ['integer', 'min:0'],
            'appointments.advance_reservation' => ['integer', 'min:0'],
            'appointments.unit_advance' => ['in:time,day,month'],
            'assists.verification_interval' => ['integer', 'min:1'],
            //color de fonde de la aplicacion
            'design.dark_application_background' => ['string', 'max:10'],
            'design.light_application_background' => ['string', 'max:10'],
            //Color general de diseño
            'design.general_design_color' => ['string', 'max:10'],
            //Colores de los textos
            'design.title_text_color' => ['string', 'max:10'],
            'design.subtitle_text_color' => ['string', 'max:10'],
            'design.dark_text_color' => ['string', 'max:10'],
            'design.footer_background' => ['string', 'max:10'],

            'design.light_text_color' => ['string', 'max:10'],
            //Color texto letra pequeña (Small)
            'design.text_color_small_dark' => ['string', 'max:10'],
            'design.text_color_small_light' => ['string', 'max:10'],
            //Color de fondo botones
            'design.primary_color_btn' => ['string', 'max:10'],
            'design.secondary_color_btn' => ['string', 'max:10'],
            'design.btn_text_color' => ['string', 'max:10'],
            //Navbar
            'design.background_navbar_dark' => ['string', 'max:10'],
            'design.background_navbar_light' => ['string', 'max:10'],
            //Login-Register
            'design.background_login_and_register_dark' => ['string', 'max:10'],
            'design.background_login_and_register_light' => ['string', 'max:10'],
            //Form elements text color
            'design.text_color_form_elements_dark' => ['string', 'max:10'],
            'design.text_color_form_elements_light' => ['string', 'max:10'],

        ];
    }
}
