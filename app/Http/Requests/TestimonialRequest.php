<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // public — no auth required
    }

    public function rules(): array
    {
        return [
            'reviewer_name'    => ['required', 'string', 'max:255'],
            'reviewer_title'   => ['nullable', 'string', 'max:255'],
            'reviewer_company' => ['nullable', 'string', 'max:255'],
            'content'          => ['required', 'string', 'max:2000'],
            'rating'           => ['required', 'integer', 'min:1', 'max:5'],
        ];
    }
}
