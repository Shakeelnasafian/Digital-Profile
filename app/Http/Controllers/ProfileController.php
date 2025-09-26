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
     * Show the form for creating a new resource.
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
     * Store a newly created resource in storage.
     */
    public function store(ProfileRequest $request, CreateProfileAction $action)
    {
        $profile = $action->handle($request);
        
        return redirect()->route('profile.show', $profile->slug)
            ->with('success', 'Digital Card created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $profile = Profile::where('slug', $slug)->firstOrFail();

        return Inertia::render('profile/show', [
            'profile' => $profile,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $profile = Profile::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        return Inertia::render('profile/edit', [
            'profile' => $profile,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileRequest $request, string $id, UpdateProfileAction $action)
    {
        $profile = $action->handle($request, $id);

        return redirect()->route('profile.show', $profile->slug)
            ->with('success', 'Digital Card updated successfully');
    }

    /**
     * Remove the specified resource from storage.
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
