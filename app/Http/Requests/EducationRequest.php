<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EducationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'institution'    => 'required|string|max:255',
            'degree'         => 'required|string|max:255',
            'field_of_study' => 'nullable|string|max:255',
            'start_year'     => 'required|integer|min:1950|max:2035',
            'end_year'       => 'nullable|integer|min:1950|max:2035|gte:start_year',
            'is_current'     => 'boolean',
            'description'    => 'nullable|string|max:2000',
        ];
    }
}
