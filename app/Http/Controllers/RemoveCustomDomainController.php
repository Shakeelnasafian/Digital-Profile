<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\RemoveCustomDomainAction;
use App\Models\Profile;
use Illuminate\Http\RedirectResponse;

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
