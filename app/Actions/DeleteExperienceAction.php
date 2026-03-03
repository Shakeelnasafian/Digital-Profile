<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Experience;

class DeleteExperienceAction
{
    public function __invoke(Experience $experience): void
    {
        $experience->delete();
    }
}
