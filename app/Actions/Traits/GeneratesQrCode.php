<?php

namespace App\Actions\Traits;

use App\Models\Profile;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

trait GeneratesQrCode
{
    /**
     * Generate and store an SVG QR code for the given profile and update the profile's qr_code_url.
     *
     * Generates an SVG QR code that encodes the profile's public URL, saves it to "public/qr_codes/{slug}.svg",
     * and updates the Profile's `qr_code_url` attribute with the storage path.
     *
     * @param Profile $profile The profile for which to generate and save the QR code.
     */
    public function generateQrCode(Profile $profile): void
    {
        $url = route('profile.show', $profile->slug);
        $qrImage = QrCode::format('svg')->size(300)->generate($url);

        $qrPath = "qr_codes/{$profile->slug}.svg";
        Storage::disk('public')->put($qrPath, $qrImage);

        $profile->update(['qr_code_url' => $qrPath]);
    }
}
