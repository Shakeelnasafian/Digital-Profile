<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DigitalProfile;

class DigitalProfileController extends Controller
{
   public function create()
    {
        return Inertia::render('digital-profile/create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:25',
            'whatsapp' => 'nullable|string|max:25',
            'website' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'github' => 'nullable|url',
            'location' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image',
            'template' => 'nullable|string|max:100',
        ]);

        $slug = Str::slug($data['full_name']) . '-' . Str::random(5);

        // Optional: handle image and QR here
        $data['user_id'] = auth()->id();
        $data['slug'] = $slug;

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $data['profile_image'] = $path;
        }

        $card = DigitalProfile::create($data);

        return redirect()->route('digital-profiles.show', $card->slug)
            ->with('success', 'Digital Card created successfully');
    }
}
