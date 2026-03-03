<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Profile;
use Illuminate\Support\Str;

class InitiateCustomDomainAction
{
    public function __invoke(Profile $profile, string $domain): Profile
    {
        $profile->update([
            'custom_domain'            => $domain,
            'domain_verification_token' => 'dp-verify-' . Str::random(32),
            'domain_verified_at'       => null,
        ]);

        return $profile->fresh();
    }
}
