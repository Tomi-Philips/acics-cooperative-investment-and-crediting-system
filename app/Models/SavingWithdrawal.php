<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavingWithdrawal extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'request_date',
        'processed_at',
        'processed_by',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected $casts = [
        'request_date' => 'datetime',
        'processed_at' => 'datetime',
    ];
}
