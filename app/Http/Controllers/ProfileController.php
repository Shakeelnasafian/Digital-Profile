<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Profile;
use Inertia\Controller;
use Illuminate\Http\Request;
use App\Actions\CreateProfileAction;
use App\Actions\UpdateProfileAction;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profiles = Profile::where('user_id', auth()->id())->get();

        return Inertia::render('profile/index', [
            'profiles' => $profiles,
        ]);
    }

    /**
     * Display the profile creation form or redirect the user to their existing profile.
     *
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse An Inertia response rendering the creation page, or a redirect response to the existing profile's show route.
     */
    public function create()
    {
        $existingProfile = Profile::where('user_id', auth()->id())->first();

        if ($existingProfile) {
            // Redirect to their profile instead of showing the form again
            return redirect()->route('profile.show', $existingProfile->slug);
        }

        return Inertia::render('profile/create');
    }

    /**
         * Create a new Profile from the validated request data and redirect to the profile's show page.
         *
         * @param \App\Http\Requests\ProfileRequest $request Validated request containing profile attributes.
         * @param \App\Actions\CreateProfileAction $action Action that handles creation of the Profile.
         * @return \Illuminate\Http\RedirectResponse Redirect to the created profile's show route with a success flash message.
         */
    public function store(ProfileRequest $request, CreateProfileAction $action)
    {
        $profile = $action->handle($request);
        
        return redirect()->route('profile.show', $profile->slug)
            ->with('success', 'Digital Card created successfully');
    }

    /**
     * Render the profile detail page for the profile identified by the given slug.
     *
     * @param string $slug The profile's slug used to locate the record.
     * @return \Inertia\Response An Inertia response rendering the profile/show view with the located Profile model.
     */
    public function show(string $slug)
    {
        $profile = Profile::where('slug', $slug)->firstOrFail();

        return Inertia::render('profile/show', [
            'profile' => $profile,
        ]);
    }

    /**
         * Display the edit form for the specified profile belonging to the authenticated user.
         *
         * @param string $id The profile's ID.
         * @return \Inertia\Response The Inertia response rendering the profile edit page with the profile data.
         */
    public function edit(string $id)
    {
        $profile = Profile::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        return Inertia::render('profile/edit', [
            'profile' => $profile,
        ]);
    }

    /**
     * Update the specified profile with validated data and redirect to its show page.
     *
     * @param \App\Http\Requests\ProfileRequest $request Validated profile data.
     * @param string $id The ID of the profile to update.
     * @return \Illuminate\Http\RedirectResponse Redirect response to the updated profile's show route.
     */
    public function update(ProfileRequest $request, string $id, UpdateProfileAction $action)
    {
        $profile = $action->handle($request, $id);

        return redirect()->route('profile.show', $profile->slug)
            ->with('success', 'Digital Card updated successfully');
    }

    /**
         * Delete the authenticated user's profile and its associated QR code file.
         *
         * Deletes the profile identified by `$id` only if it belongs to the currently
         * authenticated user. If the profile has a `qr_code_url`, the corresponding
         * file is removed from the `public` storage disk before the profile record is deleted.
         *
         * @param string $id The ID of the profile to delete.
         * @return \Illuminate\Http\RedirectResponse Redirect to the profile index with a success flash message.
         */
    public function destroy(string $id)
    {
        $profile = Profile::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        // Delete the QR code file if it exists
        if ($profile->qr_code_url) {
            Storage::disk('public')->delete($profile->qr_code_url);
        }

        $profile->delete();

        return redirect()->route('profile.index')
            ->with('success', 'Digital Card deleted successfully');
    }

    
}
