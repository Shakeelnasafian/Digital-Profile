<?php

namespace App\Actions;

use App\Http\Requests\EducationRequest;
use App\Models\Education;

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
