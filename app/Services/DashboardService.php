<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;

class DashboardService
{
    public function __construct(
        private readonly AnalyticsService $analytics,
        private readonly ProfileCompletionService $completion,
    ) {}

    public function getStats(int $userId): array
    {
        $profile         = Profile::where('user_id', $userId)->first();
        $projectCount    = Project::where('user_id', $userId)->count();
        $experienceCount = Experience::where('user_id', $userId)->count();
        $profileViews    = $profile?->profile_views ?? 0;

        $analyticsData = [
            'views_last_30_days' => [],
            'device_breakdown'   => ['mobile' => 0, 'tablet' => 0, 'desktop' => 0],
            'top_referrers'      => [],
        ];
        $completionScore = 0;
        $checklist       = [];

        if ($profile) {
            $analyticsData = [
                'views_last_30_days' => $this->analytics->getViewsLast30Days($profile->id),
                'device_breakdown'   => $this->analytics->getDeviceBreakdown($profile->id),
                'top_referrers'      => $this->analytics->getTopReferrers($profile->id),
            ];
            $completionScore = $this->completion->getScore($profile);
            $checklist       = $this->completion->getChecklist($profile);
        }

        return [
            'profile_views'        => $profileViews,
            'project_count'        => $projectCount,
            'experience_count'     => $experienceCount,
            'has_profile'          => (bool) $profile,
            'profile_slug'         => $profile?->slug,
            'profile_id'           => $profile?->id,
            'completion_score'     => $completionScore,
            'completion_checklist' => $checklist,
            ...$analyticsData,
        ];
    }
}
