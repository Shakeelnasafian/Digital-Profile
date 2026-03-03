<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Profile;
use Illuminate\Validation\ValidationException;

class VerifyCustomDomainAction
{
    public function __invoke(Profile $profile): Profile
    {
        if (! $profile->custom_domain || ! $profile->domain_verification_token) {
            throw ValidationException::withMessages([
                'custom_domain' => 'No domain is pending verification for this profile.',
            ]);
        }

        $lookupHost = '_digital-profile.' . $profile->custom_domain;
        $records    = @dns_get_record($lookupHost, DNS_TXT) ?: [];

        $verified = collect($records)->contains(function (array $record) use ($profile): bool {
            $txt = $record['txt'] ?? $record['entries'][0] ?? '';
            return $txt === $profile->domain_verification_token;
        });

        if (! $verified) {
            throw ValidationException::withMessages([
                'custom_domain' => 'TXT record not found or does not match. DNS changes can take up to 48 hours to propagate.',
            ]);
        }

        $profile->update(['domain_verified_at' => now()]);

        return $profile->fresh();
    }
}
