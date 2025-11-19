<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Share extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'amount',
        'transaction_type', // purchase, sale
        'payment_method',
        'reference_number',
        'description',
        'processed_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'float',
    ];

    /**
     * Get the user that owns the share.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who processed the share.
     */
    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Check if the share purchase would exceed the maximum allowed.
     */
    public static function wouldExceedMaximum(User $user, float $amount): bool
    {
        if (!$user->member) {
            return true;
        }

        $maxShareContribution = config('business_rules.shares.maximum_contribution', 10000);
        $currentShares = $user->member->total_shares;

        return ($currentShares + $amount) > $maxShareContribution;
    }
}
