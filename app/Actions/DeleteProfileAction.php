<?php 

namespace App\Actions;

class DeleteProfileAction
{
    public function handle($profile): void
    {
        // Delete the profile image if it exists
        if ($profile->profile_image) {
            Storage::disk('public')->delete($profile->profile_image);
        }

        // Delete the QR code if it exists
        if ($profile->qr_code_url) {
            Storage::disk('public')->delete($profile->qr_code_url);
        }

        // Finally, delete the profile record from the database
        $profile->delete();
    }
}