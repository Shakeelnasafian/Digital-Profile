<?php

namespace App\Actions;

use App\Models\Lead;
use App\Models\Profile;
use App\Http\Requests\LeadRequest;
use App\Notifications\NewLeadNotification;

class SubmitLeadAction
{
    public function execute(LeadRequest $request, Profile $profile): Lead
    {
        $lead = Lead::create([
            ...$request->validated(),
            'profile_id' => $profile->id,
        ]);

        $profile->user->notify(new NewLeadNotification($lead));

        return $lead;
    }
}
