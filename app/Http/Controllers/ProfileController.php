<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Experience;
use Illuminate\Http\Request;
use App\Actions\CreateProfileAction;
use App\Actions\UpdateProfileAction;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $profiles = Profile::where('user_id', auth()->id())->get();

        return Inertia::render('profile/index', [
            'profiles' => $profiles,
        ]);
    }

    public function create()
    {
        $existingProfile = Profile::where('user_id', auth()->id())->first();

        if ($existingProfile) {
            return redirect()->route('profile.show', $existingProfile->slug);
        }

        return Inertia::render('profile/create');
    }

    public function store(ProfileRequest $request, CreateProfileAction $action)
    {
        $profile = $action->handle($request);

        return redirect()->route('profile.show', $profile->slug)
            ->with('success', 'Digital Card created successfully');
    }

    /**
     * Public-facing profile view — no auth required.
     */
    public function publicShow(string $slug)
    {
        $profile = Profile::where('slug', $slug)
            ->where('is_public', true)
            ->firstOrFail();

        // Increment view counter
        $profile->increment('profile_views');

        $projects = Project::where('user_id', $profile->user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $experiences = Experience::where('user_id', $profile->user_id)
            ->orderBy('start_date', 'desc')
            ->get();

        return Inertia::render('profile/public', [
            'profile'     => $profile,
            'projects'    => $projects,
            'experiences' => $experiences,
        ]);
    }

    /**
     * Authenticated owner view of their own profile.
     */
    public function show(string $slug)
    {
        $profile = Profile::where('slug', $slug)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $projects = Project::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $experiences = Experience::where('user_id', auth()->id())
            ->orderBy('start_date', 'desc')
            ->get();

        return Inertia::render('profile/show', [
            'profile'     => $profile,
            'projects'    => $projects,
            'experiences' => $experiences,
        ]);
    }

    public function edit(string $id)
    {
        $profile = Profile::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        return Inertia::render('profile/edit', [
            'profile' => $profile,
        ]);
    }

    public function update(ProfileRequest $request, string $id, UpdateProfileAction $action)
    {
        $profile = $action->handle($request, $id);

        return redirect()->route('profile.show', $profile->slug)
            ->with('success', 'Digital Card updated successfully');
    }

    public function destroy(string $id)
    {
        $profile = Profile::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if ($profile->qr_code_url) {
            Storage::disk('public')->delete($profile->qr_code_url);
        }

        $profile->delete();

        return redirect()->route('profile.index')
            ->with('success', 'Digital Card deleted successfully');
    }
}
