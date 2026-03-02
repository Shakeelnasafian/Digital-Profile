<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProjectMedia extends Model
{
    protected $table = 'project_media';

    protected $fillable = ['project_id', 'file_path', 'media_type', 'sort_order'];

    protected $appends = ['url'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }
}
