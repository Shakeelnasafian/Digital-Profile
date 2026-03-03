<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Project;

class DeleteProjectAction
{
    public function __invoke(Project $project): void
    {
        $project->delete();
    }
}
