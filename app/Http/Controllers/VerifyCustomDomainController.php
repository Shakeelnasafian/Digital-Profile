<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\VerifyCustomDomainAction;
use App\Models\Profile;
use Illuminate\Http\RedirectResponse;

class VerifyCustomDomainController extends Controller
{
    public function __invoke(string $profile, VerifyCustomDomainAction $action): RedirectResponse
    {
        $profile = Profile::where('id', $profile)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $action($profile);

        return redirect()->back()->with('success', 'Domain verified successfully! Your profile is now live at '.$profile->custom_domain);
    }
}
