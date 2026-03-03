<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'files'   => 'required|array',
            'files.*' => 'required|file|mimes:jpg,jpeg,png,gif,webp,mp4,webm|max:10240',
        ];
    }
}
