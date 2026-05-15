<?php

namespace App\Actions;

use App\Http\Requests\EducationRequest;
use App\Models\Education;

class UpdateEducationAction
{
    public function handle(EducationRequest $request, Education $education): Education
    {
        abort_if($education->user_id !== auth()->id(), 403);

        $education->update($request->validated());

        return $education;
    }
}
