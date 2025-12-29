<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenderUpdate extends FormRequest
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
            'Female' => ['nullable', 'boolean'],
            'Male' => ['nullable', 'boolean'],
            'Non_binary' => ['nullable', 'boolean'],
            'X' => ['nullable', 'boolean'],
            'Other' => ['nullable', 'boolean'],
            'Prefer_not_to_say' => ['nullable', 'boolean'],
        ];
    }
}
