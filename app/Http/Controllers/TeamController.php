<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Profile;
use App\Models\Team;
use App\Actions\AddTeamMemberAction;
use App\Actions\CreateTeamAction;
use App\Actions\DeleteTeamAction;
use App\Actions\RemoveTeamMemberAction;
use App\Http\Requests\AddTeamMemberRequest;
use App\Http\Requests\StoreTeamRequest;

class TeamController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('team/create');
    }

    public function store(StoreTeamRequest $request, CreateTeamAction $action): RedirectResponse
    {
        $team = $action($request->safe()->except('logo'), auth()->id(), $request->file('logo'));

        return to_route('teams.manage', $team->slug)
            ->with('success', 'Team created successfully.');
    }

    public function manage(string $slug): Response
    {
        $team = Team::where('slug', $slug)
            ->where('owner_user_id', auth()->id())
            ->firstOrFail();

        $members = $team->members()->get()->map(fn($u) => [
            'id'      => $u->id,
            'name'    => $u->name,
            'email'   => $u->email,
            'role'    => $u->pivot->role,
            'profile' => Profile::where('user_id', $u->id)
                ->select('slug', 'display_name', 'job_title', 'profile_image')
                ->first(),
        ]);

        return Inertia::render('team/manage', [
            'team'    => [
                'id'          => $team->id,
                'name'        => $team->name,
                'slug'        => $team->slug,
                'description' => $team->description,
                'website'     => $team->website,
                'logo_url'    => $team->logo_url,
            ],
            'members' => $members,
        ]);
    }

    public function addMember(AddTeamMemberRequest $request, string $slug, AddTeamMemberAction $action): RedirectResponse
    {
        $team = Team::where('slug', $slug)
            ->where('owner_user_id', auth()->id())
            ->firstOrFail();

        $user = $action($team, $request->validated('email'));

        return redirect()->back()->with('success', "{$user->name} added to the team.");
    }

    public function removeMember(string $slug, int $userId, RemoveTeamMemberAction $action): RedirectResponse
    {
        $team = Team::where('slug', $slug)
            ->where('owner_user_id', auth()->id())
            ->firstOrFail();

        $action($team, $userId, auth()->id());

        return redirect()->back()->with('success', 'Member removed.');
    }

    public function show(string $slug): Response
    {
        $team = Team::where('slug', $slug)->firstOrFail();

        $members = $team->members()->get()->map(function ($user) {
            $profile = Profile::where('user_id', $user->id)
                ->where('is_public', true)
                ->first();

            return [
                'id'      => $user->id,
                'name'    => $user->name,
                'role'    => $user->pivot->role,
                'profile' => $profile ? [
                    'slug'                => $profile->slug,
                    'display_name'        => $profile->display_name,
                    'job_title'           => $profile->job_title,
                    'profile_image'       => $profile->profile_image,
                    'location'            => $profile->location,
                    'availability_status' => $profile->availability_status,
                ] : null,
            ];
        })->filter(fn($m) => $m['profile'] !== null)->values();

        return Inertia::render('team/show', [
            'team'    => [
                'name'        => $team->name,
                'slug'        => $team->slug,
                'description' => $team->description,
                'website'     => $team->website,
                'logo_url'    => $team->logo_url,
            ],
            'members' => $members,
        ]);
    }

    public function index(): Response
    {
        $userId = auth()->id();

        $ownedTeams = Team::where('owner_user_id', $userId)->get()->map(fn($t) => [
            'id'       => $t->id,
            'name'     => $t->name,
            'slug'     => $t->slug,
            'logo_url' => $t->logo_url,
            'role'     => 'owner',
            'members'  => $t->members()->count(),
        ]);

        $memberTeams = Team::whereHas('members', fn($q) => $q->where('user_id', $userId))
            ->where('owner_user_id', '!=', $userId)
            ->get()
            ->map(fn($t) => [
                'id'       => $t->id,
                'name'     => $t->name,
                'slug'     => $t->slug,
                'logo_url' => $t->logo_url,
                'role'     => 'member',
                'members'  => $t->members()->count(),
            ]);

        return Inertia::render('team/index', [
            'teams' => $ownedTeams->merge($memberTeams)->values(),
        ]);
    }

    public function destroy(string $slug, DeleteTeamAction $action): RedirectResponse
    {
        $team = Team::where('slug', $slug)
            ->where('owner_user_id', auth()->id())
            ->firstOrFail();

        $action($team);

        return to_route('teams.index')->with('success', 'Team deleted.');
    }
}
