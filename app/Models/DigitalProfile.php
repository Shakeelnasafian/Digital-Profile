<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigitalProfile extends Model
{
    protected $fillable = [
        'user_id', 'slug', 'full_name', 'job_title', 'email', 'phone', 'whatsapp',
        'website', 'linkedin', 'github', 'location', 'profile_image', 'template',
        'is_public', 'qr_code_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
