<?php

namespace App\Actions;

use App\Models\Lead;
use App\Models\Profile;
use App\Http\Requests\LeadRequest;

class SubmitLeadAction
{
    public function execute(LeadRequest $request, Profile $profile): Lead
    {
        return Lead::create([
            ...$request->validated(),
            'profile_id' => $profile->id,
        ]);
    }
}
