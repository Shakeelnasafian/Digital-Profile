<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Models\Profile;
use App\Actions\InitiateCustomDomainAction;
use App\Http\Requests\SaveCustomDomainRequest;

class SaveCustomDomainController extends Controller
{
    public function __invoke(SaveCustomDomainRequest $request, string $profile, InitiateCustomDomainAction $action): RedirectResponse
    {
        $profile = Profile::where('id', $profile)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $action($profile, $request->validated('custom_domain'));

        return redirect()->back()->with('success', 'Domain saved. Follow the instructions below to verify ownership.');
    }
}
