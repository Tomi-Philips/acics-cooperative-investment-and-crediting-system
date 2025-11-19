<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'loan_number',
        'amount',
        'interest_rate',
        'term_months',
        'monthly_payment',
        'total_payment',
        'remaining_balance',
        'status',
        'purpose',
        'repayment_method',
        'submitted_at',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'rejection_reason',
        'disbursed_at',
        'disbursed_by',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'float',
        'interest_rate' => 'float',
        'monthly_payment' => 'float',
        'total_payment' => 'float',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'disbursed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the loan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the approver of the loan.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the rejecter of the loan.
     */
    public function rejecter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Get the disburser of the loan.
     */
    public function disburser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disbursed_by');
    }

    /**
     * Get the repayments for the loan.
     */
    public function repayments(): HasMany
    {
        return $this->hasMany(LoanRepayment::class);
    }

    /**
     * Get the payments for the loan.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(LoanPayment::class);
    }

    /**
     * Update the remaining balance of the loan.
     */
    public function updateRemainingBalance(): void
    {
        $paidAmount = $this->repayments()->where('status', 'paid')->sum('amount');
        $this->remaining_balance = $this->total_payment - $paidAmount;
        $this->save();
    }

    /**
     * Calculate the paid amount of the loan.
     */
    public function getPaidAmountAttribute(): float
    {
        return $this->repayments()->where('status', 'paid')->sum('amount');
    }

    /**
     * Calculate the progress percentage of the loan repayment.
     */
    public function getProgressPercentageAttribute(): float
    {
        if ($this->total_payment <= 0) {
            return 0;
        }

        return min(100, ($this->paid_amount / $this->total_payment) * 100);
    }

    /**
     * Generate a unique loan number.
     */
    public static function generateLoanNumber(): string
    {
        $prefix = 'LN';
        $year = date('Y');
        $month = date('m');

        $latestLoan = self::where('loan_number', 'like', "{$prefix}{$year}{$month}%")
            ->orderBy('id', 'desc')
            ->first();

        $sequence = 1;

        if ($latestLoan) {
            $lastSequence = (int) substr($latestLoan->loan_number, -4);
            $sequence = $lastSequence + 1;
        }

        return $prefix . $year . $month . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}