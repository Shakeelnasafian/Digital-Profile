<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $profileId = $this->route('profile');

        return [
            'display_name' => 'required|string|max:255',
            'job_title'    => 'nullable|string|max:255',
            'email'        => [
                'required',
                'email',
                Rule::unique('profiles', 'email')->ignore($profileId),
            ],
            'phone'         => 'nullable|string|max:25',
            'whatsapp'      => 'nullable|string|max:25',
            'website'       => 'nullable|url',
            'linkedin'      => 'nullable|url',
            'github'        => 'nullable|url',
            'location'      => 'nullable|string|max:255',
            'profile_image' => $this->isMethod('POST') ? 'nullable|image|max:2048' : 'nullable|image|max:2048',
            'template'      => 'nullable|string|max:100',
            'short_bio'     => 'nullable|string|max:1000',
            'is_public'     => 'boolean',
            'skills'        => 'nullable|string|max:2000',
        ];
    }
}
