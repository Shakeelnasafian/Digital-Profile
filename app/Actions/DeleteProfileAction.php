<?php 

namespace App\Actions;

class DeleteProfileAction
{
    /****
     * Deletes a profile's associated storage files (profile image and QR code) if present and removes the profile record.
     *
     * @param object $profile The profile model instance to delete; its `profile_image` and `qr_code_url` properties are used to locate files on the public storage disk.
     */
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