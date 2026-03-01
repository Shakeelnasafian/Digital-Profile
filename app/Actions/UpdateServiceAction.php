<?php

namespace App\Actions;

use App\Models\Service;
use App\Http\Requests\ServiceRequest;

class UpdateServiceAction
{
    public function execute(ServiceRequest $request, Service $service): Service
    {
        abort_if($service->user_id !== auth()->id(), 403);

        $service->update($request->validated());

        return $service->fresh();
    }
}
