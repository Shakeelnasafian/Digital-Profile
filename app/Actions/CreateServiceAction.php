<?php

namespace App\Actions;

use App\Models\Service;
use App\Http\Requests\ServiceRequest;

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
