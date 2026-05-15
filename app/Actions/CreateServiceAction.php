<?php

namespace App\Actions;

use App\Http\Requests\ServiceRequest;
use App\Models\Service;

class CreateServiceAction
{
    public function execute(ServiceRequest $request): Service
    {
        return Service::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);
    }
}
