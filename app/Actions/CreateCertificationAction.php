<?php

namespace App\Actions;

use App\Models\Certification;
use App\Http\Requests\CertificationRequest;

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
