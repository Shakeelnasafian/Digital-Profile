<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Lead;
use App\Models\Profile;
use App\Actions\SubmitLeadAction;
use App\Http\Requests\LeadRequest;
use App\Http\Resources\LeadResource;

class LeadController extends Controller
{
    public function store(LeadRequest $request, string $slug, SubmitLeadAction $action)
    {
        $profile = Profile::where('slug', $slug)
            ->where('is_public', true)
            ->firstOrFail();

        $action->execute($request, $profile);

        return redirect()->back()->with('success', 'Message sent! The profile owner will be in touch.');
    }

    public function index()
    {
        $profile = Profile::where('user_id', auth()->id())->firstOrFail();

        $leads = Lead::where('profile_id', $profile->id)
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('leads/index', [
            'leads' => LeadResource::collection($leads),
        ]);
    }
}
