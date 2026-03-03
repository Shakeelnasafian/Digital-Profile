<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Profile;
use App\Services\AnalyticsService;
use App\Services\PublicProfileService;
use Inertia\Inertia;

class CustomDomainMiddleware
{
    public function __construct(
        private readonly AnalyticsService $analytics,
        private readonly PublicProfileService $publicProfileService,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $host    = $request->getHost();
        $appHost = parse_url(config('app.url'), PHP_URL_HOST);

        // Only intercept requests for hosts that differ from the app's own domain
        if ($host === $appHost || str_ends_with($host, '.' . $appHost)) {
            return $next($request);
        }

        $profile = Profile::where('custom_domain', $host)
            ->whereNotNull('domain_verified_at')
            ->where('is_public', true)
            ->first();

        if (! $profile) {
            return $next($request);
        }

        $this->analytics->logView($profile, $request);

        return Inertia::render('profile/public', $this->publicProfileService->getPageData($profile))
            ->toResponse($request);
    }
}
