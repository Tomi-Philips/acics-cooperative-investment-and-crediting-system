<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'category_id',
        'is_important',
    ];

    /**
     * Get the category that the faq belongs to.
     */
    public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'category_id');
    }
}
