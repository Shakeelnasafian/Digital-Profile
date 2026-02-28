<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EducationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'institution'    => $this->institution,
            'degree'         => $this->degree,
            'field_of_study' => $this->field_of_study,
            'start_year'     => $this->start_year,
            'end_year'       => $this->end_year,
            'is_current'     => $this->is_current,
            'description'    => $this->description,
        ];
    }
}
