<?php

namespace App\Actions;

use App\Http\Requests\CertificationRequest;
use App\Models\Certification;

class UpdateCertificationAction
{
    public function handle(CertificationRequest $request, Certification $certification): Certification
    {
        abort_if($certification->user_id !== auth()->id(), 403);

        $certification->update($request->validated());

        return $certification;
    }
}
