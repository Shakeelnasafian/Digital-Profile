<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectMediaController extends Controller
{
    public function store(Request $request, string $projectId)
    {
        $project = Project::where('id', $projectId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $request->validate([
            'files'   => 'required|array',
            'files.*' => 'required|file|mimes:jpg,jpeg,png,gif,webp,mp4,webm|max:10240',
        ]);

        $currentCount = $project->media()->count();
        $remaining    = max(0, 5 - $currentCount);
        $files        = array_slice($request->file('files'), 0, $remaining);

        foreach ($files as $index => $file) {
            $ext       = strtolower($file->getClientOriginalExtension());
            $mediaType = in_array($ext, ['mp4', 'webm']) ? 'video' : 'image';
            $path      = $file->store("project-media/{$project->id}", 'public');

            $project->media()->create([
                'file_path'  => $path,
                'media_type' => $mediaType,
                'sort_order' => $currentCount + $index,
            ]);
        }

        return redirect()->back()->with('success', 'Media uploaded successfully.');
    }

    public function destroy(string $projectId, ProjectMedia $media)
    {
        $project = Project::where('id', $projectId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        abort_if($media->project_id !== $project->id, 403);

        Storage::disk('public')->delete($media->file_path);
        $media->delete();

        return redirect()->back()->with('success', 'Media deleted.');
    }
}
