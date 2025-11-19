<?php

namespace App\Services;

use App\Models\TransactionGroup;
use App\Models\Transaction;
use App\Models\ShareTransaction;
use App\Models\SavingTransaction;
use App\Models\CommodityTransaction;
use App\Models\LoanPayment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionGroupService
{
    /**
     * Create a new transaction group.
     *
     * @param string $groupType
     * @param string $title
     * @param string|null $description
     * @param array $metadata
     * @param int|null $processedBy
     * @return TransactionGroup
     */
    public function createGroup(
        string $groupType,
        string $title,
        ?string $description = null,
        array $metadata = [],
        ?int $processedBy = null
    ): TransactionGroup {
        return TransactionGroup::create([
            'group_type' => $groupType,
            'group_reference' => $this->generateGroupReference($groupType),
            'title' => $title,
            'description' => $description,
            'metadata' => $metadata,
            'processed_by' => $processedBy,
            'processed_at' => now(),
            'status' => 'pending',
        ]);
    }

    /**
     * Add transactions to a group and update group totals.
     *
     * @param TransactionGroup $group
     * @param array $transactionIds
     * @param string $transactionType
     * @return void
     */
    public function addTransactionsToGroup(TransactionGroup $group, array $transactionIds, string $transactionType): void
    {
        DB::transaction(function () use ($group, $transactionIds, $transactionType) {
            $totalAmount = 0;
            $totalRecords = count($transactionIds);

            switch ($transactionType) {
                case 'transactions':
                    Transaction::whereIn('id', $transactionIds)->update(['group_id' => $group->id]);
                    $totalAmount = Transaction::whereIn('id', $transactionIds)->sum('amount');
                    break;

                case 'share_transactions':
                    ShareTransaction::whereIn('id', $transactionIds)->update(['group_id' => $group->id]);
                    $totalAmount = ShareTransaction::whereIn('id', $transactionIds)->sum('amount');
                    break;

                case 'saving_transactions':
                    SavingTransaction::whereIn('id', $transactionIds)->update(['group_id' => $group->id]);
                    $totalAmount = SavingTransaction::whereIn('id', $transactionIds)->sum('amount');
                    break;

                case 'commodity_transactions':
                    CommodityTransaction::whereIn('id', $transactionIds)->update(['group_id' => $group->id]);
                    $totalAmount = CommodityTransaction::whereIn('id', $transactionIds)->sum('amount');
                    break;

                case 'loan_payments':
                    LoanPayment::whereIn('id', $transactionIds)->update(['group_id' => $group->id]);
                    $totalAmount = LoanPayment::whereIn('id', $transactionIds)->sum('amount');
                    break;
            }

            // Update group totals
            $group->update([
                'total_amount' => $group->total_amount + $totalAmount,
                'total_records' => $group->total_records + $totalRecords,
            ]);
        });
    }

    /**
     * Complete a transaction group.
     *
     * @param TransactionGroup $group
     * @return void
     */
    public function completeGroup(TransactionGroup $group): void
    {
        $group->update([
            'status' => 'completed',
            'processed_at' => now(),
        ]);
    }

    /**
     * Get grouped transactions for dashboard display.
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getGroupedTransactionsForDashboard(int $perPage = 10)
    {
        // Get recent transaction groups with pagination - ONLY grouped transactions
        $groups = TransactionGroup::with('processedBy')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Return only grouped transactions (no individual transactions)
        return $groups;
    }

    /**
     * Generate a unique group reference.
     *
     * @param string $groupType
     * @return string
     */
    private function generateGroupReference(string $groupType): string
    {
        $prefix = strtoupper(substr($groupType, 0, 3));
        $timestamp = now()->format('YmdHis');
        $random = Str::upper(Str::random(4));
        
        return "{$prefix}-{$timestamp}-{$random}";
    }

    /**
     * Create a MAB bulk upload group.
     *
     * @param array $uploadData
     * @param int $processedBy
     * @return TransactionGroup
     */
    public function createMABBulkUploadGroup(array $uploadData, int $processedBy): TransactionGroup
    {
        return $this->createGroup(
            'mab_bulk_upload',
            'MAB Bulk Upload - ' . ($uploadData['filename'] ?? 'Unknown File'),
            "Bulk upload processed with {$uploadData['total_records']} records",
            $uploadData,
            $processedBy
        );
    }

    /**
     * Create a manual transaction group.
     *
     * @param string $title
     * @param int $processedBy
     * @return TransactionGroup
     */
    public function createManualTransactionGroup(string $title, int $processedBy): TransactionGroup
    {
        return $this->createGroup(
            'manual_transaction',
            $title,
            'Manual transaction processed by admin',
            [],
            $processedBy
        );
    }

    /**
     * Create an admin approval group.
     *
     * @param string $title
     * @param int $processedBy
     * @return TransactionGroup
     */
    public function createAdminApprovalGroup(string $title, int $processedBy): TransactionGroup
    {
        return $this->createGroup(
            'admin_approval',
            $title,
            'Transaction approved by admin',
            [],
            $processedBy
        );
    }
}
