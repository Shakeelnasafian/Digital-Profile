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
        }

        $profile->update($data);

        // Regenerate QR code after update
        $this->generateQrCode($profile);

        return $profile;
    }
}
