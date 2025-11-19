<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];
}
