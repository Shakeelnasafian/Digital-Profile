<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Experience;

class UpdateExperienceAction
{
    public function __invoke(Experience $experience, array $data): Experience
    {
        $experience->update($data);

        return $experience;
    }
}
