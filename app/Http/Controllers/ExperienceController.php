<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Experience;
use App\Actions\CreateExperienceAction;
use App\Actions\DeleteExperienceAction;
use App\Actions\UpdateExperienceAction;
use App\Http\Requests\StoreExperienceRequest;
use App\Http\Requests\UpdateExperienceRequest;
use App\Http\Resources\ExperienceResource;

class ExperienceController extends Controller
{
    public function index(): Response
    {
        $experiences = Experience::where('user_id', auth()->id())
            ->orderBy('start_date', 'desc')
            ->get();

        return Inertia::render('experience/index', [
            'experiences' => ExperienceResource::collection($experiences),
        ]);
    }

    public function store(StoreExperienceRequest $request, CreateExperienceAction $action): RedirectResponse
    {
        $action($request->validated(), auth()->id());

        return to_route('experience.index')->with('success', 'Experience added successfully.');
    }

    public function update(UpdateExperienceRequest $request, Experience $experience, UpdateExperienceAction $action): RedirectResponse
    {
        abort_if($experience->user_id !== auth()->id(), 403);

        $action($experience, $request->validated());

        return to_route('experience.index')->with('success', 'Experience updated successfully.');
    }

    public function destroy(Experience $experience, DeleteExperienceAction $action): RedirectResponse
    {
        abort_if($experience->user_id !== auth()->id(), 403);

        $action($experience);

        return to_route('experience.index')->with('success', 'Experience deleted successfully.');
    }
}
