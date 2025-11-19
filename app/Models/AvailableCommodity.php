<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableCommodity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'commodity_type',
        'status',
        'image',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'available_commodities';
}
