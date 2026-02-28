<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'project_url' => $this->project_url,
            'image'       => $this->image,
            'start_date'  => $this->start_date,
            'end_date'    => $this->end_date,
            'status'      => $this->status,
            'created_at'  => $this->created_at,
        ];
    }
}
