<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'description'    => $this->description,
            'starting_price' => $this->starting_price,
            'currency'       => $this->currency,
            'cta_label'      => $this->cta_label,
            'cta_url'        => $this->cta_url,
            'sort_order'     => $this->sort_order,
        ];
    }
}
