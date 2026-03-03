<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use App\Models\Profile;
use App\Actions\CreateProfileAction;
use App\Actions\DeleteProfileAction;
use App\Actions\GenerateVCardAction;
use App\Actions\UpdateProfileAction;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Services\AnalyticsService;
use App\Services\DashboardService;
use App\Services\PdfExportService;
use App\Services\ProfileShowService;
use App\Services\PublicProfileService;

class ProfileController extends Controller
{
    public function index(): InertiaResponse
    {
        $profiles = Profile::where('user_id', auth()->id())->get();

        return Inertia::render('profile/index', [
            'profiles' => ProfileResource::collection($profiles),
        ]);
    }

    public function create(): InertiaResponse|RedirectResponse
    {
        $existingProfile = Profile::where('user_id', auth()->id())->first();

        if ($existingProfile) {
            return redirect()->route('profile.show', $existingProfile->slug);
        }

        return Inertia::render('profile/create');
    }

    public function store(ProfileRequest $request, CreateProfileAction $action): RedirectResponse
    {
        $profile = $action->handle($request);

        return to_route('profile.show', $profile->slug)
            ->with('success', 'Digital Card created successfully');
    }

    public function publicShow(string $slug, Request $request, AnalyticsService $analytics, PublicProfileService $service): InertiaResponse
    {
        $profile = Profile::where('slug', $slug)
            ->where('is_public', true)
            ->firstOrFail();

        $analytics->logView($profile, $request);

        return Inertia::render('profile/public', $service->getPageData($profile));
    }

    public function show(string $slug, ProfileShowService $service): InertiaResponse
    {
        $profile = Profile::where('slug', $slug)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return Inertia::render('profile/show', $service->getPageData($profile, auth()->id()));
    }

    public function edit(string $id): InertiaResponse
    {
        $profile = Profile::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return Inertia::render('profile/edit', [
            'profile' => new ProfileResource($profile),
        ]);
    }

    public function update(ProfileRequest $request, string $id, UpdateProfileAction $action): RedirectResponse
    {
        $profile = $action->handle($request, $id);

        return to_route('profile.show', $profile->slug)
            ->with('success', 'Digital Card updated successfully');
    }

    public function destroy(string $id, DeleteProfileAction $action): RedirectResponse
    {
        $profile = Profile::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $action->handle($profile);

        return to_route('profile.index')
            ->with('success', 'Digital Card deleted successfully');
    }

    public function downloadVCard(string $slug, GenerateVCardAction $action): Response
    {
        $profile = Profile::where('slug', $slug)
            ->where('is_public', true)
            ->firstOrFail();

        $vcard = $action($profile);

        return response($vcard['content'], 200, [
            'Content-Type'        => 'text/vcard; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $vcard['filename'] . '"',
        ]);
    }

    public function embed(string $slug): InertiaResponse
    {
        $profile = Profile::where('slug', $slug)
            ->where('is_public', true)
            ->firstOrFail();

        return Inertia::render('profile/embed', [
            'profile' => new ProfileResource($profile),
        ]);
    }

    public function checkSlug(Request $request, string $slug): JsonResponse
    {
        $profileId = $request->query('profile_id');

        $exists = Profile::where('slug', $slug)
            ->when($profileId, fn($q) => $q->where('id', '!=', $profileId))
            ->exists();

        return response()->json(['available' => ! $exists]);
    }

    public function exportPdf(string $id, PdfExportService $service): Response
    {
        $profile = Profile::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return $service->export($profile, auth()->id());
    }

    public function dashboard(DashboardService $service): InertiaResponse
    {
        return Inertia::render('dashboard', [
            'stats' => $service->getStats(auth()->id()),
        ]);
    }
}
