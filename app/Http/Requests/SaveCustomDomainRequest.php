<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveCustomDomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $profileId = $this->route('profile');

        return [
            'custom_domain' => [
                'required',
                'string',
                'max:253',
                'regex:/^(?!-)[a-zA-Z0-9\-\.]{1,253}(?<!-)$/',
                Rule::unique('profiles', 'custom_domain')->ignore($profileId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'custom_domain.regex' => 'Please enter a valid domain name without the protocol (e.g. yourdomain.com).',
        ];
    }

    public function prepareForValidation(): void
    {
        if ($this->custom_domain) {
            // Strip protocol if accidentally included
            $domain = preg_replace('#^https?://#', '', trim($this->custom_domain));
            // Strip trailing slash and path
            $domain = strtolower(explode('/', $domain)[0]);

            $this->merge(['custom_domain' => $domain]);
        }
    }
}
