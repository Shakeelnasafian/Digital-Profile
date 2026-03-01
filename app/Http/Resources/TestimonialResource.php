<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'reviewer_name'    => $this->reviewer_name,
            'reviewer_title'   => $this->reviewer_title,
            'reviewer_company' => $this->reviewer_company,
            'content'          => $this->content,
            'rating'           => $this->rating,
            'is_approved'      => $this->is_approved,
            'created_at'       => $this->created_at?->format('d M Y'),
        ];
    }
}
