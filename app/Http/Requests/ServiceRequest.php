<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string', 'max:2000'],
            'starting_price' => ['nullable', 'numeric', 'min:0'],
            'currency'       => ['required', 'string', 'size:3'],
            'cta_label'      => ['nullable', 'string', 'max:100'],
            'cta_url'        => ['nullable', 'url'],
            'sort_order'     => ['integer', 'min:0'],
        ];
    }
}
