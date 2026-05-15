<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\InitiateCustomDomainAction;
use App\Http\Requests\SaveCustomDomainRequest;
use App\Models\Profile;
use Illuminate\Http\RedirectResponse;

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
