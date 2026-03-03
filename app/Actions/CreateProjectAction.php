<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Project;

class CreateProjectAction
{
    public function __invoke(array $data, int $userId): Project
    {
        return Project::create([
            ...$data,
            'user_id' => $userId,
        ]);
    }
}
