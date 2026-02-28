<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'issuer'         => $this->issuer,
            'issue_date'     => $this->issue_date?->format('Y-m-d'),
            'expiry_date'    => $this->expiry_date?->format('Y-m-d'),
            'credential_url' => $this->credential_url,
            'credential_id'  => $this->credential_id,
        ];
    }
}
