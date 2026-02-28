<?php

namespace App\Actions;

use App\Models\Education;
use App\Http\Requests\EducationRequest;

class CreateEducationAction
{
    public function handle(EducationRequest $request): Education
    {
        return Education::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);
    }
}
