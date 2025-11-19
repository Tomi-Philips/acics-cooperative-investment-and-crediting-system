<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCommodity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'commodity_name', // Or appropriate type for commodity identifier
        'balance',
        // Add other fillable attributes based on your migration for user_commodities table
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_commodities';

    /**
     * Get the user that owns the commodity balance.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
