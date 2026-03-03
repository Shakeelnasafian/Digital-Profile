<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Certification;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Service;
use App\Models\Team;
use App\Models\Testimonial;
use App\Http\Resources\CertificationResource;
use App\Http\Resources\EducationResource;
use App\Http\Resources\ExperienceResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\TestimonialResource;

class PublicProfileService
{
    public function getPageData(Profile $profile): array
    {
        $projects = Project::where('user_id', $profile->user_id)
            ->with('media')
            ->orderBy('created_at', 'desc')
            ->get();

        $experiences = Experience::where('user_id', $profile->user_id)
            ->orderBy('start_date', 'desc')
            ->get();

        $educations = Education::where('user_id', $profile->user_id)
            ->orderByDesc('start_year')
            ->get();

        $certifications = Certification::where('user_id', $profile->user_id)
            ->orderByDesc('issue_date')
            ->get();

        $testimonials = Testimonial::where('profile_id', $profile->id)
            ->where('is_approved', true)
            ->orderByDesc('created_at')
            ->get();

        $services = Service::where('user_id', $profile->user_id)
            ->orderBy('sort_order')
            ->get();

        $team = Team::whereHas('members', fn($q) => $q->where('user_id', $profile->user_id))
            ->first();

        return [
            'profile'        => new ProfileResource($profile),
            'projects'       => ProjectResource::collection($projects),
            'experiences'    => ExperienceResource::collection($experiences),
            'educations'     => EducationResource::collection($educations),
            'certifications' => CertificationResource::collection($certifications),
            'testimonials'   => TestimonialResource::collection($testimonials),
            'services'       => ServiceResource::collection($services),
            'team'           => $team ? ['name' => $team->name, 'slug' => $team->slug, 'logo_url' => $team->logo_url] : null,
        ];
    }
}
