<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Controller;
use Illuminate\Http\Request;
use App\Models\DigitalProfile;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DigitalProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profiles = DigitalProfile::where('user_id', auth()->id())->get();

        return Inertia::render('digital-profile/index', [
            'profiles' => $profiles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $existingProfile = DigitalProfile::where('user_id', auth()->id())->first();

        if ($existingProfile) {
            // Redirect to their profile instead of showing the form again
            return redirect()->route('digital-profiles.show', $existingProfile->slug);
        }

        return Inertia::render('digital-profile/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'display_name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'email' => 'required|email|unique:digital_profiles,email',
            'phone' => 'required|string|max:25',
            'whatsapp' => 'nullable|string|max:25',
            'website' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'github' => 'nullable|url',
            'location' => 'required|string|max:255',
            'profile_image' => 'required|image',
            'template' => 'required|string|max:100',
            'short_bio' => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $data['profile_image'] = $path;
        }

        // Set the user_id to the currently authenticated user
        $data['user_id'] = auth()->user()->id();

        // Set a default account_type
        $data['account_type'] = 'individual';

        $profile = DigitalProfile::create($data);

        $this->generateQrCode($profile);

        return redirect()->route('digital-profiles.show', $profile->slug)
            ->with('success', 'Digital Card created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $profile = DigitalProfile::where('slug', $slug)->firstOrFail();

        return Inertia::render('digital-profile/show', [
            'profile' => $profile,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $profile = DigitalProfile::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        return Inertia::render('digital-profile/edit', [
            'profile' => $profile,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $profile = DigitalProfile::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $data = $request->validate([
            'display_name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'email' => 'required|email|unique:digital_profiles,email,' . $profile->id,
            'phone' => 'required|string|max:25',
            'whatsapp' => 'nullable|string|max:25',
            'website' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'github' => 'nullable|url',
            'location' => 'required|string|max:255',
            'profile_image' => 'nullable|image',
            'template' => 'required|string|max:100',
            'short_bio' => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $data['profile_image'] = $path;
        }

        $profile->update($data);

        // Regenerate QR code after update
        $this->generateQrCode($profile);

        return redirect()->route('digital-profiles.show', $profile->slug)
            ->with('success', 'Digital Card updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $profile = DigitalProfile::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        // Delete the QR code file if it exists
        if ($profile->qr_code_url) {
            Storage::disk('public')->delete($profile->qr_code_url);
        }

        $profile->delete();

        return redirect()->route('digital-profiles.index')
            ->with('success', 'Digital Card deleted successfully');
    }

    private function generateQrCode(DigitalProfile $profile): void
    {
        $url = route('digital-profiles.show', $profile->slug);
        $qrImage = QrCode::format('svg')->size(300)->generate($url);

        $qrPath = "qr_codes/{$profile->slug}.svg";
        Storage::disk('public')->put($qrPath, $qrImage);

        $profile->update(['qr_code_url' => $qrPath]);
    }
}
