<?php

namespace App\Actions;

use App\Models\Profile;
use App\Http\Requests\ProfileRequest;
use App\Actions\Traits\GeneratesQrCode;

class UpdateProfileAction
{
    use GeneratesQrCode;

    /**
     * Update a user's profile with validated request data and regenerate its QR code.
     *
     * If the request includes a `profile_image` file, the file is stored on the `public` disk under the `profiles`
     * directory and the stored path is saved to the profile.
     *
     * @param ProfileRequest $request The validated profile input.
     * @param string $id The ID of the profile to update, restricted to the current authenticated user.
     * @return Profile The updated Profile instance.
     */
    public function handle(ProfileRequest $request, string $id): Profile
    {
        $profile = Profile::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $data = $request->validated();

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $data['profile_image'] = $path;
        }

        $profile->update($data);

        // Regenerate QR code after update
        $this->generateQrCode($profile);

        return $profile;
    }
}
