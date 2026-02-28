<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CertificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'          => 'required|string|max:255',
            'issuer'         => 'required|string|max:255',
            'issue_date'     => 'required|date',
            'expiry_date'    => 'nullable|date|after_or_equal:issue_date',
            'credential_url' => 'nullable|url',
            'credential_id'  => 'nullable|string|max:255',
        ];
    }
}
