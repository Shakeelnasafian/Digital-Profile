<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'educations';

    protected $fillable = [
        'user_id',
        'institution',
        'degree',
        'field_of_study',
        'start_year',
        'end_year',
        'is_current',
        'description',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'start_year'  => 'integer',
        'end_year'    => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
