<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\UserCommodity;

class Member extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'member_number',
        'joined_at',
        'status',
        'entrance_fee_paid',
        'total_loan_amount',
        'total_share_amount',
        'total_saving_amount',
        'total_commodity_amount',
        'total_electronics_amount',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'position',
        'next_of_kin_name',
        'next_of_kin_relationship',
        'next_of_kin_phone',
        'next_of_kin_address',
        'profile_photo',
        'id_document',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'joined_at' => 'datetime',
        'entrance_fee_paid' => 'boolean',
        'total_loan_amount' => 'float',
        'total_share_amount' => 'float',
        'total_saving_amount' => 'float',
        'total_commodity_amount' => 'float',
        'total_electronics_amount' => 'float',
        'date_of_birth' => 'date',
    ];

    /**
     * Get the user that owns the member profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the savings transactions for the member.
     */
    public function savingsTransactions(): HasMany
    {
        return $this->hasMany(SavingTransaction::class, 'user_id', 'user_id');
    }

    /**
     * Get the share transactions for the member.
     */
    public function sharesTransactions(): HasMany
    {
        return $this->hasMany(ShareTransaction::class, 'user_id', 'user_id');
    }

    /**
     * Get the savings records for the member (legacy - kept for compatibility).
     */
    public function savings(): HasMany
    {
        return $this->hasMany(Saving::class, 'user_id', 'user_id');
    }

    /**
     * Get the share records for the member (legacy - kept for compatibility).
     */
    public function shares(): HasMany
    {
        return $this->hasMany(Share::class, 'user_id', 'user_id');
    }

    /**
     * Get the loan records for the member.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class, 'user_id', 'user_id');
    }

    /**
     * Get the electronics records for the member.
     */
    public function electronics(): HasMany
    {
        return $this->hasMany(Electronics::class, 'user_id', 'user_id');
    }

    /**
     * Calculate the total savings amount for the member.
     */
    public function getTotalSavingsAttribute(): float
    {
        $credits = $this->savingsTransactions()
            ->where('type', 'credit')
            ->sum('amount');

        $debits = $this->savingsTransactions()
            ->where('type', 'debit')
            ->sum('amount');

        return $credits - $debits;
    }

    /**
     * Calculate the total shares amount for the member.
     */
    public function getTotalSharesAttribute(): float
    {
        // Shares can only be purchased (credit), never sold/debited
        return $this->sharesTransactions()
            ->where('type', 'credit')
            ->sum('amount');
    }

    /**
     * Calculate the membership duration in months.
     */
    public function getMembershipDurationMonthsAttribute(): int
    {
        return $this->joined_at ? $this->joined_at->diffInMonths(now()) : 0;
    }

    /**
     * Check if the member is eligible for a loan.
     *
     * @param bool $includeReason Whether to include the reason for ineligibility
     * @return bool|array Returns boolean if $includeReason is false, otherwise returns array with 'eligible' and 'reason' keys
     */
    public function isEligibleForLoan(bool $includeReason = false): bool|array
    {
        // Check if joined_at is set
        if (!$this->joined_at) {
            if ($includeReason) {
                return [
                    'eligible' => false,
                    'reason' => "Membership join date is not set.",
                    'joined_at' => null,
                    'months_remaining' => null
                ];
            }
            return false;
        }

        // Check membership duration requirement
        $minMonths = config('business_rules.loan_eligibility.minimum_membership_months', 6);
        if ($this->membership_duration_months < $minMonths) {
            if ($includeReason) {
                return [
                    'eligible' => false,
                    'reason' => "Membership duration is {$this->membership_duration_months} months. Minimum required is {$minMonths} months.",
                    'joined_at' => $this->joined_at->format('M d, Y'),
                    'months_remaining' => $minMonths - $this->membership_duration_months
                ];
            }
            return false;
        }

        // Check if entrance fee has been paid
        $entranceFeeRequired = config('business_rules.loan_eligibility.entrance_fee_required', true);
        if ($entranceFeeRequired && !$this->entrance_fee_paid) {
            if ($includeReason) {
                return [
                    'eligible' => false,
                    'reason' => "Entrance fee has not been paid.",
                ];
            }
            return false;
        }

        if ($includeReason) {
            return [
                'eligible' => true,
                'reason' => null,
            ];
        }
        return true;
    }

    /**
     * Get the total outstanding electronics balance.
     */
    public function getTotalElectronicsBalanceAttribute(): float
    {
        return $this->hasMany(Electronics::class, 'user_id', 'user_id')->sum('amount');
    }

    /**
     * Get the total outstanding loan balance.
     */
    public function getTotalLoanBalanceAttribute(): float
    {
        return $this->loans()->where('status', 'active')->sum('remaining_balance');
    }

    /**
     * Calculate the maximum loan amount the member is eligible for.
     */
    public function getMaxLoanAmountAttribute(): float
    {
        // Even if not eligible, we'll calculate a theoretical max amount
        // This allows the loan application form to show a value

        $multiplier = config('business_rules.loan_eligibility.multiplier', 2);
        $assets = $this->total_savings + $this->total_shares;

        // Calculate separate essential and non-essential commodity balances
        $essentialCommodity = UserCommodity::where('user_id', $this->user_id)
            ->where('commodity_name', 'essential')
            ->sum('balance');
        $nonEssentialCommodity = UserCommodity::where('user_id', $this->user_id)
            ->where('commodity_name', 'non_essential')
            ->sum('balance');

        // Updated business rule: 2×(Savings+Shares-Loan-Commodity-Non essential-Electronics)
        // Where Commodity = Essential Commodity and Non essential = Non-Essential Commodity
        $liabilities = $this->total_loan_balance + $essentialCommodity + $nonEssentialCommodity + $this->total_electronics_balance;

        // Calculate net assets (assets - liabilities)
        $netAssets = max(0, $assets - $liabilities);

        // Return actual calculated amount (can be 0 if no net assets)
        return $multiplier * $netAssets;
    }

    /**
     * Update the total loan amount when a new loan is approved.
     *
     * @param float $loanAmount The amount of the new loan
     * @return void
     */
    public function updateTotalLoanAmount(float $loanAmount): void
    {
        $this->total_loan_amount += $loanAmount;
        $this->save();
    }
}
