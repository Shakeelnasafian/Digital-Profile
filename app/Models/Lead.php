<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    public $timestamps = false;

    protected $casts = ['created_at' => 'datetime'];

    protected $fillable = [
        'profile_id',
        'visitor_name',
        'visitor_email',
        'visitor_phone',
        'message',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
