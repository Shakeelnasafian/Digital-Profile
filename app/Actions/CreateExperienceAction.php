<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Experience;

class CreateExperienceAction
{
    public function __invoke(array $data, int $userId): Experience
    {
        return Experience::create([
            ...$data,
            'user_id' => $userId,
        ]);
    }
}
