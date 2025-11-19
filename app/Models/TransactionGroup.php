<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionGroup extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'group_type',
        'group_reference',
        'title',
        'description',
        'total_amount',
        'total_records',
        'status',
        'processed_by',
        'processed_at',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'processed_at' => 'datetime',
        'metadata' => 'array',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the user who processed this transaction group.
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get all transactions in this group.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'group_id');
    }

    /**
     * Get all share transactions in this group.
     */
    public function shareTransactions(): HasMany
    {
        return $this->hasMany(ShareTransaction::class, 'group_id');
    }

    /**
     * Get all saving transactions in this group.
     */
    public function savingTransactions(): HasMany
    {
        return $this->hasMany(SavingTransaction::class, 'group_id');
    }

    /**
     * Get all commodity transactions in this group.
     */
    public function commodityTransactions(): HasMany
    {
        return $this->hasMany(CommodityTransaction::class, 'group_id');
    }

    /**
     * Get all loan payments in this group.
     */
    public function loanPayments(): HasMany
    {
        return $this->hasMany(LoanPayment::class, 'group_id');
    }

    /**
     * Scope a query to only include completed groups.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include pending groups.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Get the group type display name.
     */
    public function getGroupTypeDisplayAttribute(): string
    {
        $types = [
            'mab_bulk_upload' => 'MAB Bulk Upload',
            'user_bulk_upload' => 'User Bulk Upload',
            'manual_transaction' => 'Manual Transaction',
            'admin_approval' => 'Admin Approval',
            'system_transaction' => 'System Transaction',
            'bulk_operation' => 'Bulk Operation',
            'loan_disbursement' => 'Loan Disbursement',
        ];

        return $types[$this->group_type] ?? ucwords(str_replace('_', ' ', $this->group_type));
    }

    /**
     * Get the status color for display.
     */
    public function getStatusColorAttribute(): string
    {
        $colors = [
            'completed' => 'green',
            'pending' => 'yellow',
            'failed' => 'red',
            'processing' => 'blue',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    /**
     * Get the group icon based on type.
     */
    public function getGroupIconAttribute(): string
    {
        $icons = [
            'mab_bulk_upload' => 'upload',
            'user_bulk_upload' => 'users',
            'manual_transaction' => 'hand',
            'admin_approval' => 'check-circle',
            'system_transaction' => 'cog',
            'bulk_operation' => 'collection',
        ];

        return $icons[$this->group_type] ?? 'document';
    }
}
