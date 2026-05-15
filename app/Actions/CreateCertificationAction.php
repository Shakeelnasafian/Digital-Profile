<?php

namespace App\Actions;

use App\Http\Requests\CertificationRequest;
use App\Models\Certification;

class CreateCertificationAction
{
    public function handle(CertificationRequest $request): Certification
    {
        return Certification::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);
    }
}
