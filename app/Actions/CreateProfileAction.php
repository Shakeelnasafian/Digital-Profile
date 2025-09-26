<?php

namespace App\Actions;

use App\Models\Profile;
use App\Http\Requests\ProfileRequest;
use App\Actions\Traits\GeneratesQrCode;

class CreateProfileAction
{
    use GeneratesQrCode;
    
    /**
     * Create a new Profile from the validated request data, persist it, and generate its QR code.
     *
     * If a 'profile_image' file is present it is stored to the 'public' disk under the 'profiles' directory and the stored path is saved on the created Profile; the created Profile is associated with the currently authenticated user.
     *
     * @param ProfileRequest $request Validated profile input; may include an uploaded 'profile_image' file.
     * @return Profile The newly created Profile model.
     */
    public function handle(ProfileRequest $request): Profile
    {
        $data = $request->validated();
        
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $data['profile_image'] = $path;
        }

        // Set the user_id to the currently authenticated user
        $data['user_id'] = auth()->user()->id();

        $profile = Profile::create($data);

        $this->generateQrCode($profile);

        return $profile;
    }

}
