<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DigitalProfile;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DigitalProfileController extends Controller
{
    public function create()
    {
        return Inertia::render('digital-profile/create');
    }

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
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $data['profile_image'] = $path;
        }


        // Set the user_id to the currently authenticated user
        $data['user_id'] = auth()->id();
        // Set a default account_type
        $data['account_type'] = 'individual';

        $profile = DigitalProfile::create($data);

        $this->generateQrCode($profile);

        return redirect()->route('digital-profiles.show', $profile->slug)
            ->with('success', 'Digital Card created successfully');
    }

    public function show($slug)
    {
        $profile = DigitalProfile::where('slug', $slug)->firstOrFail();

        return Inertia::render('digital-profile/show', [
            'profile' => $profile,
        ]);
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
