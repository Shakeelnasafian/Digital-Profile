<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Certification;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Http\Resources\CertificationResource;
use App\Http\Resources\EducationResource;
use App\Http\Resources\ExperienceResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\ProjectResource;

class ProfileShowService
{
    public function getPageData(Profile $profile, int $userId): array
    {
        $projects = Project::where('user_id', $userId)
            ->with('media')
            ->orderBy('created_at', 'desc')
            ->get();

        $experiences = Experience::where('user_id', $userId)
            ->orderBy('start_date', 'desc')
            ->get();

        $educations = Education::where('user_id', $userId)
            ->orderByDesc('start_year')
            ->get();

        $certifications = Certification::where('user_id', $userId)
            ->orderByDesc('issue_date')
            ->get();

        return [
            'profile'        => new ProfileResource($profile),
            'projects'       => ProjectResource::collection($projects),
            'experiences'    => ExperienceResource::collection($experiences),
            'educations'     => EducationResource::collection($educations),
            'certifications' => CertificationResource::collection($certifications),
        ];
    }
}
