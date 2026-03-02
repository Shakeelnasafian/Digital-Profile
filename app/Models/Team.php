<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Team extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'logo', 'website', 'owner_user_id'];

    protected static function booted(): void
    {
        static::creating(function (Team $team) {
            $base = Str::slug($team->name);
            do {
                $slug = $base . '-' . Str::random(4);
            } while (self::where('slug', $slug)->exists());

            $team->slug = $slug;
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'team_members')->withPivot('role')->withTimestamps();
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? Storage::disk('public')->url($this->logo) : null;
    }
}
