<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Profile;

class RemoveCustomDomainAction
{
    public function __invoke(Profile $profile): void
    {
        $profile->update([
            'custom_domain'            => null,
            'domain_verification_token' => null,
            'domain_verified_at'       => null,
        ]);
    }
}
