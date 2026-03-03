<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Team;

class RemoveTeamMemberAction
{
    public function __invoke(Team $team, int $targetUserId, int $ownerId): void
    {
        abort_if($targetUserId === $ownerId, 422, 'You cannot remove yourself as the owner.');

        $team->members()->detach($targetUserId);
    }
}
