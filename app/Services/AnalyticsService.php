<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\ProfileViewEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AnalyticsService
{
    public function logView(Profile $profile, Request $request): void
    {
        $profile->increment('profile_views');

        ProfileViewEvent::create([
            'profile_id'  => $profile->id,
            'device_type' => $this->detectDevice($request->userAgent() ?? ''),
            'referrer'    => $this->categoriseReferrer($request),
            'is_qr_scan'  => $request->query('ref') === 'qr',
            'viewed_at'   => now(),
        ]);
    }

    public function getViewsLast30Days(int $profileId): array
    {
        $rows = ProfileViewEvent::where('profile_id', $profileId)
            ->where('viewed_at', '>=', now()->subDays(29)->startOfDay())
            ->selectRaw('DATE(viewed_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $result = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $result[] = ['date' => $date, 'count' => $rows[$date] ?? 0];
        }

        return $result;
    }

    public function getDeviceBreakdown(int $profileId): array
    {
        $rows = ProfileViewEvent::where('profile_id', $profileId)
            ->selectRaw('device_type, COUNT(*) as count')
            ->groupBy('device_type')
            ->pluck('count', 'device_type')
            ->toArray();

        return [
            'mobile'  => $rows['mobile'] ?? 0,
            'tablet'  => $rows['tablet'] ?? 0,
            'desktop' => $rows['desktop'] ?? 0,
        ];
    }

    public function getTopReferrers(int $profileId, int $limit = 5): array
    {
        return ProfileViewEvent::where('profile_id', $profileId)
            ->selectRaw('COALESCE(referrer, \'direct\') as referrer, COUNT(*) as count')
            ->groupBy('referrer')
            ->orderByDesc('count')
            ->limit($limit)
            ->get()
            ->map(fn($r) => ['referrer' => $r->referrer, 'count' => $r->count])
            ->toArray();
    }

    private function detectDevice(string $userAgent): string
    {
        $ua = strtolower($userAgent);

        if (preg_match('/tablet|ipad|playbook|silk/i', $ua)) {
            return 'tablet';
        }

        if (preg_match('/mobile|android|iphone|ipod|blackberry|opera mini|windows phone/i', $ua)) {
            return 'mobile';
        }

        return 'desktop';
    }

    private function categoriseReferrer(Request $request): ?string
    {
        if ($request->query('ref') === 'qr') {
            return 'qr';
        }

        $referer = $request->header('referer');

        if (empty($referer)) {
            return 'direct';
        }

        $host = strtolower(parse_url($referer, PHP_URL_HOST) ?? '');

        $map = [
            'linkedin'  => 'linkedin',
            'whatsapp'  => 'whatsapp',
            'twitter'   => 'twitter',
            't.co'      => 'twitter',
            'instagram' => 'instagram',
            'facebook'  => 'facebook',
            'youtube'   => 'youtube',
        ];

        foreach ($map as $keyword => $label) {
            if (str_contains($host, $keyword)) {
                return $label;
            }
        }

        return $host ?: 'direct';
    }
}
