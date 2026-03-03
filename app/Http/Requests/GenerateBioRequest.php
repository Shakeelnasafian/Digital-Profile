<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateBioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'context' => ['required', 'string', 'min:10', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'context.required' => 'Please provide some context about yourself.',
            'context.min'      => 'Please provide at least 10 characters of context.',
            'context.max'      => 'Context must be 500 characters or fewer.',
        ];
    }
}
