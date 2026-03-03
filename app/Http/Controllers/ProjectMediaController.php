<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Models\Project;
use App\Models\ProjectMedia;
use App\Actions\DeleteProjectMediaAction;
use App\Actions\StoreProjectMediaAction;
use App\Http\Requests\StoreProjectMediaRequest;

class ProjectMediaController extends Controller
{
    public function store(StoreProjectMediaRequest $request, string $projectId, StoreProjectMediaAction $action): RedirectResponse
    {
        $project = Project::where('id', $projectId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $action($project, $request->file('files'));

        return redirect()->back()->with('success', 'Media uploaded successfully.');
    }

    public function destroy(string $projectId, ProjectMedia $media, DeleteProjectMediaAction $action): RedirectResponse
    {
        $project = Project::where('id', $projectId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        abort_if($media->project_id !== $project->id, 403);

        $action($media);

        return redirect()->back()->with('success', 'Media deleted.');
    }
}
