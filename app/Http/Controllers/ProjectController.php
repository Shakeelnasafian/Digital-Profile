<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Project;
use App\Actions\CreateProjectAction;
use App\Actions\DeleteProjectAction;
use App\Actions\UpdateProjectAction;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;

class ProjectController extends Controller
{
    public function index(): Response
    {
        $projects = Project::where('user_id', auth()->id())
            ->with('media')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('project/index', [
            'projects' => ProjectResource::collection($projects),
        ]);
    }

    public function store(StoreProjectRequest $request, CreateProjectAction $action): RedirectResponse
    {
        $action($request->validated(), auth()->id());

        return to_route('projects.index')->with('success', 'Project created successfully.');
    }

    public function update(UpdateProjectRequest $request, Project $project, UpdateProjectAction $action): RedirectResponse
    {
        abort_if($project->user_id !== auth()->id(), 403);

        $action($project, $request->validated());

        return to_route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project, DeleteProjectAction $action): RedirectResponse
    {
        abort_if($project->user_id !== auth()->id(), 403);

        $action($project);

        return to_route('projects.index')->with('success', 'Project deleted successfully.');
    }
}
