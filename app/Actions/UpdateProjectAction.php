<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Project;

class UpdateProjectAction
{
    public function __invoke(Project $project, array $data): Project
    {
        $project->update($data);

        return $project;
    }
}
