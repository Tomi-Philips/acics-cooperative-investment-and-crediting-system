<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ElectronicsItem extends Model
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
        'category',
        'price',
        'stock_quantity',
        'image',
        'status',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'float',
        'stock_quantity' => 'integer',
    ];

    /**
     * Get the electronics for the item.
     */
    public function electronics(): HasMany
    {
        return $this->hasMany(Electronics::class, 'electronics_name', 'name');
    }

    /**
     * Get the formatted price with currency symbol.
     *
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        return '₦' . number_format($this->price, 2);
    }

    /**
     * Check if the item is available.
     *
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available' && $this->stock_quantity > 0;
    }

    /**
     * Check if the item is out of stock.
     *
     * @return bool
     */
    public function isOutOfStock(): bool
    {
        return $this->stock_quantity <= 0;
    }

    /**
     * Reduce the stock quantity.
     *
     * @param int $quantity
     * @return void
     */
    public function reduceStock(int $quantity): void
    {
        $this->stock_quantity = max(0, $this->stock_quantity - $quantity);
        $this->save();
    }

    /**
     * Increase the stock quantity.
     *
     * @param int $quantity
     * @return void
     */
    public function increaseStock(int $quantity): void
    {
        $this->stock_quantity += $quantity;
        $this->save();
    }
}