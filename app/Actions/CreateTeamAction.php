<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Team;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class CreateTeamAction
{
    public function __invoke(array $data, int $userId, ?UploadedFile $logo = null): Team
    {
        return DB::transaction(function () use ($data, $userId, $logo): Team {
            if ($logo) {
                $data['logo'] = $logo->store('teams', 'public');
            }

            $data['owner_user_id'] = $userId;

            $team = Team::create($data);
            $team->members()->attach($userId, ['role' => 'owner']);

            return $team;
        });
    }
}
