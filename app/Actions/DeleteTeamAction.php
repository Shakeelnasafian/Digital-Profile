<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Team;
use Illuminate\Support\Facades\Storage;

class DeleteTeamAction
{
    public function __invoke(Team $team): void
    {
        if ($team->logo) {
            Storage::disk('public')->delete($team->logo);
        }

        $team->delete();
    }
}
