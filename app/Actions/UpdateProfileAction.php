<?php

namespace App\Actions;

use App\Models\Profile;
use App\Http\Requests\ProfileRequest;
use App\Actions\Traits\GeneratesQrCode;

class UpdateProfileAction
{
    use GeneratesQrCode;

    public function handle(ProfileRequest $request, string $id): Profile
    {
        $profile = Profile::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $data = $request->validated();

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $data['profile_image'] = $path;
        } else {
            // Don't overwrite existing image if none uploaded
            unset($data['profile_image']);
        }

        // If user provided a custom slug, apply it as the profile's URL slug
        if (!empty($data['custom_slug'])) {
            $data['slug'] = strtolower($data['custom_slug']);
        }
        unset($data['custom_slug']);

        $profile->update($data);

        // Regenerate QR code after update (slug may have changed)
        $this->generateQrCode($profile);

        return $profile;
    }
}
