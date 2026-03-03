<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\ProjectMedia;
use Illuminate\Support\Facades\Storage;

class DeleteProjectMediaAction
{
    public function __invoke(ProjectMedia $media): void
    {
        Storage::disk('public')->delete($media->file_path);
        $media->delete();
    }
}
