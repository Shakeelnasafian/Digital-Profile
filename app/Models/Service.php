<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'starting_price',
        'currency',
        'cta_label',
        'cta_url',
        'sort_order',
    ];

    protected $casts = [
        'starting_price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
