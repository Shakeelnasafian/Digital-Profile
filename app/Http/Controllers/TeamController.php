<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Team;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function create()
    {
        return Inertia::render('team/create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'website'     => 'nullable|url|max:255',
            'logo'        => 'nullable|image|max:2048',
        ]);

        $data['owner_user_id'] = auth()->id();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('teams', 'public');
        }

        $team = Team::create($data);

        // Owner is also a member
        $team->members()->attach(auth()->id(), ['role' => 'owner']);

        return redirect()->route('teams.manage', $team->slug)
            ->with('success', 'Team created successfully.');
    }

    public function manage(string $slug)
    {
        $team = Team::where('slug', $slug)
            ->where('owner_user_id', auth()->id())
            ->firstOrFail();

        $members = $team->members()->get()->map(fn($u) => [
            'id'       => $u->id,
            'name'     => $u->name,
            'email'    => $u->email,
            'role'     => $u->pivot->role,
            'profile'  => Profile::where('user_id', $u->id)->select('slug', 'display_name', 'job_title', 'profile_image')->first(),
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

    public function addMember(Request $request, string $slug)
    {
        $team = Team::where('slug', $slug)
            ->where('owner_user_id', auth()->id())
            ->firstOrFail();

        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return redirect()->back()->withErrors(['email' => 'No account found with that email address.']);
        }

        if ($team->members()->where('user_id', $user->id)->exists()) {
            return redirect()->back()->withErrors(['email' => 'This user is already a member.']);
        }

        $team->members()->attach($user->id, ['role' => 'member']);

        return redirect()->back()->with('success', "{$user->name} added to the team.");
    }

    public function removeMember(string $slug, int $userId)
    {
        $team = Team::where('slug', $slug)
            ->where('owner_user_id', auth()->id())
            ->firstOrFail();

        abort_if($userId === auth()->id(), 422, 'You cannot remove yourself as the owner.');

        $team->members()->detach($userId);

        return redirect()->back()->with('success', 'Member removed.');
    }

    public function show(string $slug)
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
                    'slug'          => $profile->slug,
                    'display_name'  => $profile->display_name,
                    'job_title'     => $profile->job_title,
                    'profile_image' => $profile->profile_image,
                    'location'      => $profile->location,
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

    public function index()
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

    public function destroy(string $slug)
    {
        $team = Team::where('slug', $slug)
            ->where('owner_user_id', auth()->id())
            ->firstOrFail();

        if ($team->logo) {
            Storage::disk('public')->delete($team->logo);
        }

        $team->delete();

        return redirect()->route('teams.index')->with('success', 'Team deleted.');
    }
}
