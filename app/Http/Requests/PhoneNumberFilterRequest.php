<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhoneNumberFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'country'  => 'nullable|string|in:212,237,251,256,258',  // only valid country codes
            'state'    => 'nullable|string|in:valid,invalid',        // only valid states
            'per_page' => 'nullable|integer|min:1|max:100',          // pagination
            'page'     => 'nullable|integer|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            'country.in'  => 'Country must be one of: 212,237,251,256,258',
            'state.in'    => 'State must be either valid or invalid',
            'per_page.min' => 'per_page must be at least 1',
            'per_page.max' => 'per_page cannot exceed 100',
        ];
    }
}
