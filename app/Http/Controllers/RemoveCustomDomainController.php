<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Models\Profile;
use App\Actions\RemoveCustomDomainAction;

class RemoveCustomDomainController extends Controller
{
    public function __invoke(string $profile, RemoveCustomDomainAction $action): RedirectResponse
    {
        $profile = Profile::where('id', $profile)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $action($profile);

        return redirect()->back()->with('success', 'Custom domain removed.');
    }
}
