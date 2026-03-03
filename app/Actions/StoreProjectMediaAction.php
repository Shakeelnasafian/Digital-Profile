<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Project;
use Illuminate\Support\Facades\DB;

class StoreProjectMediaAction
{
    public function __invoke(Project $project, array $files): void
    {
        DB::transaction(function () use ($project, $files): void {
            $currentCount = $project->media()->count();
            $remaining    = max(0, 5 - $currentCount);
            $files        = array_slice($files, 0, $remaining);

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
        });
    }
}
