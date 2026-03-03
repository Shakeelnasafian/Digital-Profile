<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Profile;

class GenerateVCardAction
{
    public function __invoke(Profile $profile): array
    {
        $name  = $profile->display_name;
        $lines = [
            'BEGIN:VCARD',
            'VERSION:3.0',
            'FN:' . $name,
        ];

        if ($profile->job_title) $lines[] = 'TITLE:' . $profile->job_title;
        if ($profile->email)     $lines[] = 'EMAIL;TYPE=INTERNET:' . $profile->email;
        if ($profile->phone)     $lines[] = 'TEL;TYPE=CELL:' . $profile->phone;
        if ($profile->website)   $lines[] = 'URL:' . $profile->website;
        if ($profile->linkedin)  $lines[] = 'URL;TYPE=linkedin:' . $profile->linkedin;
        if ($profile->github)    $lines[] = 'URL;TYPE=github:' . $profile->github;
        if ($profile->location)  $lines[] = 'ADR;TYPE=WORK:;;' . $profile->location . ';;;;';
        if ($profile->short_bio) $lines[] = 'NOTE:' . str_replace(["\r", "\n"], ' ', $profile->short_bio);

        $lines[] = 'X-DIGITALPROFILE:' . route('profile.public', $profile->slug);
        $lines[] = 'END:VCARD';

        return [
            'content'  => implode("\r\n", $lines) . "\r\n",
            'filename' => preg_replace('/[^a-z0-9_-]/i', '_', $name) . '.vcf',
        ];
    }
}
