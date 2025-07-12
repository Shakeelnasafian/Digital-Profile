<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DigitalProfile extends Model
{
    protected $fillable = [
        'user_id',
        'slug',
        'display_name',
        'job_title',
        'email',
        'phone',
        'whatsapp',
        'website',
        'linkedin',
        'github',
        'location',
        'profile_image',
        'template',
        'is_public',
        'qr_code_path',
        'account_type',
    ];

    // In App\Models\YourModel.php

    protected static function booted()
    {
        static::creating(function ($model) {
            $emailPart = explode('@', $model->email)[0];
            $baseSlug = Str::slug($model->display_name . '-' . $emailPart);

            do {
                $slug = $baseSlug . '-' . Str::random(3);
            } while (self::where('slug', $slug)->exists());

            $model->slug = strtolower($slug);
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Override profile_image accessor
    public function getProfileImageAttribute($value): ?string
    {
        return $value ? Storage::disk('public')->url($value) : null;
    }

    // public function getQrCodeUrlAttribute($value): ?string
    // {
    //     //dd($value);
    //     return $value ? Storage::disk('public')->url($value) : null;
    // }
}
