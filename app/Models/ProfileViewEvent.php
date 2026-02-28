<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileViewEvent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'profile_id',
        'device_type',
        'referrer',
        'is_qr_scan',
        'viewed_at',
    ];

    protected $casts = [
        'is_qr_scan' => 'boolean',
        'viewed_at'  => 'datetime',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
