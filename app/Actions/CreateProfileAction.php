<?php

namespace App\Actions;

use App\Models\Profile;
use App\Http\Requests\ProfileRequest;
use App\Actions\Traits\GeneratesQrCode;

class CreateProfileAction
{
    use GeneratesQrCode;
    
    public function handle(ProfileRequest $request): Profile
    {
        $data = $request->validated();
        
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $data['profile_image'] = $path;
        }

        // Set the user_id to the currently authenticated user
        $data['user_id'] = auth()->id();

        $profile = Profile::create($data);

        $this->generateQrCode($profile);

        return $profile;
    }

}
