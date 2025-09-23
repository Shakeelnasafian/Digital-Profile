<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $profileId = $this->route('profile');

        return [
            'display_name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('digital_profiles', 'email')->ignore($profileId),
            ],
            'phone' => 'required|string|max:25',
            'whatsapp' => 'nullable|string|max:25',
            'website' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'github' => 'nullable|url',
            'location' => 'required|string|max:255',
            'profile_image' => 'required|image',
            'template' => 'required|string|max:100',
            'short_bio' => 'nullable|string|max:500',
        ];
    }
}
