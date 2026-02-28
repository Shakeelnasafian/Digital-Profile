<?php

namespace App\Actions\Traits;

use App\Models\Profile;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

trait GeneratesQrCode
{
    public function generateQrCode(Profile $profile): void
    {
        $url = route('profile.public', $profile->slug) . '?ref=qr';
        $qrImage = QrCode::format('svg')->size(300)->generate($url);

        $qrPath = "qr_codes/{$profile->slug}.svg";
        Storage::disk('public')->put($qrPath, $qrImage);

        $profile->update(['qr_code_url' => $qrPath]);
    }
}
