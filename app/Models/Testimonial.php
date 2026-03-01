<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'profile_id',
        'reviewer_name',
        'reviewer_title',
        'reviewer_company',
        'content',
        'rating',
        'is_approved',
    ];

    protected $casts = [
        'created_at'  => 'datetime',
        'is_approved' => 'boolean',
        'rating'      => 'integer',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
