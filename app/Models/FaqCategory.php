<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqCategory extends Model
{
    protected $table = 'faq_categories';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];

    /**
     * Get the faqs for the category.
     */
    public function faqs()
    {
        return $this->hasMany(Faq::class, 'category_id');
    }
}
