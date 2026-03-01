<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // public — no auth required
    }

    public function rules(): array
    {
        return [
            'visitor_name'  => ['required', 'string', 'max:255'],
            'visitor_email' => ['required', 'email', 'max:255'],
            'visitor_phone' => ['nullable', 'string', 'max:30'],
            'message'       => ['nullable', 'string', 'max:2000'],
        ];
    }
}
