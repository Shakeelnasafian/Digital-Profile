<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Team;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AddTeamMemberAction
{
    public function __invoke(Team $team, string $email): User
    {
        $user = User::where('email', $email)->first();

        if (! $user) {
            throw ValidationException::withMessages(['email' => 'No account found with that email address.']);
        }

        if ($team->members()->where('user_id', $user->id)->exists()) {
            throw ValidationException::withMessages(['email' => 'This user is already a member.']);
        }

        $team->members()->attach($user->id, ['role' => 'member']);

        return $user;
    }
}
