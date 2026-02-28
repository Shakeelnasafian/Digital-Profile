<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'slug'                => $this->slug,
            'display_name'        => $this->display_name,
            'job_title'           => $this->job_title,
            'short_bio'           => $this->short_bio,
            'email'               => $this->email,
            'phone'               => $this->phone,
            'whatsapp'            => $this->whatsapp,
            'website'             => $this->website,
            'linkedin'            => $this->linkedin,
            'github'              => $this->github,
            'twitter'             => $this->twitter,
            'instagram'           => $this->instagram,
            'youtube'             => $this->youtube,
            'tiktok'              => $this->tiktok,
            'dribbble'            => $this->dribbble,
            'behance'             => $this->behance,
            'medium'              => $this->medium,
            'location'            => $this->location,
            'skills'              => $this->skills,
            'template'            => $this->template ?? 'default',
            'is_public'           => $this->is_public,
            'profile_views'       => $this->profile_views,
            'profile_image'       => $this->profile_image,
            'qr_code_url'         => $this->qr_code_url,
            'availability_status' => $this->availability_status,
            'scheduling_url'      => $this->scheduling_url,
        ];
    }
}
