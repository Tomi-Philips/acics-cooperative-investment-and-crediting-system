<?php

namespace App\Services;

use App\Models\User;

/**
 * Centralized service for consistent financial balance calculations
 * across all controllers and views in the application.
 * 
 * This service implements the transaction-based calculation method
 * using saving_transactions and share_transactions tables for
 * proper audit trail and data integrity.
 */
class FinancialCalculationService
{
    /**
     * Calculate user's savings balance using transaction-based method.
     * 
     * @param User $user
     * @return float
     */
    public static function calculateSavingsBalance(User $user): float
    {
        $savingsCredits = $user->savingTransactions()->where('type', 'credit')->sum('amount');
        $savingsDebits = $user->savingTransactions()->where('type', 'debit')->sum('amount');
        
        return $savingsCredits - $savingsDebits;
    }

    /**
     * Calculate user's shares balance using transaction-based method.
     * 
     * @param User $user
     * @return float
     */
    public static function calculateSharesBalance(User $user): float
    {
        $sharesCredits = $user->shareTransactions()->where('type', 'credit')->sum('amount');
        $sharesDebits = $user->shareTransactions()->where('type', 'debit')->sum('amount');
        
        return $sharesCredits - $sharesDebits;
    }

    /**
     * Calculate user's total loan balance (principal only, excluding interest).
     * This should be: Total Principal - Principal Payments Made
     *
     * @param User $user
     * @return float
     */
    public static function calculateLoanBalance(User $user): float
    {
        $totalPrincipal = 0;
        $activeLoans = $user->loans()->whereIn('status', ['active', 'approved'])->get();

        foreach ($activeLoans as $loan) {
            // Calculate remaining principal for this loan
            // Include both regular repayments and adjustments (which can be negative)
            $principalPayments = $loan->payments()
                ->where('status', 'paid')
                ->where(function($query) {
                    $query->where('notes', 'LIKE', '%Principal Repayment%')
                          ->orWhere('notes', 'LIKE', '%Principal Balance Adjustment%');
                })
                ->sum('amount');

            $remainingPrincipal = $loan->amount - $principalPayments;
            $totalPrincipal += max(0, $remainingPrincipal); // Don't allow negative
        }

        return $totalPrincipal;
    }

    /**
     * Calculate user's total loan interest owed.
     *
     * Uses the stored loan_interest transactions as the authoritative source
     * (values come from bulk upload spreadsheet or manual transactions),
     * then subtracts any interest already paid via loan_payments.
     *
     * @param User $user
     * @return float
     */
    public static function calculateLoanInterest(User $user): float
    {
        // Sum all loan_interest transactions recorded for this user.
        // These are created during bulk upload (from the spreadsheet column)
        // or via manual transactions — they represent the actual interest balance.
        $loanInterestRecorded = $user->transactions()
            ->where('type', 'loan_interest')
            ->where('status', 'completed')
            ->sum('amount');

        // Subtract any interest already paid via loan_payments
        // (manual "Interest Payment" entries processed by the admin)
        $interestPaidViaPayments = \App\Models\LoanPayment::where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereIn('loan_id', $user->loans()->pluck('id'));
            })
            ->where('status', 'paid')
            ->where(function ($query) {
                $query->where('notes', 'LIKE', '%Interest Payment%')
                      ->orWhere('notes', 'LIKE', '%Interest Balance Adjustment%');
            })
            ->sum('amount');

        $remainingInterest = $loanInterestRecorded - $interestPaidViaPayments;

        return max(0, $remainingInterest); // Don't allow negative
    }

    /**
     * Calculate user's commodity balance.
     * 
     * @param User $user
     * @return float
     */
    public static function calculateCommodityBalance(User $user): float
    {
        return $user->userCommodities()->sum('balance') ?? 0;
    }

    /**
     * Calculate user's electronics balance using commodity transactions.
     *
     * @param User $user
     * @return float
     */
    public static function calculateElectronicsBalance(User $user): float
    {
        // Electronics balance is calculated from Electronics model
        $purchases = $user->electronics()
            ->where('transaction_type', 'purchase')
            ->sum('amount');

        $repayments = $user->electronics()
            ->where('transaction_type', 'repayment')
            ->sum('amount');

        return $purchases - $repayments;
    }

    /**
     * Calculate user's essential commodity balance.
     *
     * @param User $user
     * @return float
     */
    public static function calculateEssentialCommodityBalance(User $user): float
    {
        return $user->userCommodities()->where('commodity_name', 'essential')->sum('balance') ?? 0;
    }

    /**
     * Calculate user's non-essential commodity balance.
     *
     * @param User $user
     * @return float
     */
    public static function calculateNonEssentialCommodityBalance(User $user): float
    {
        return $user->userCommodities()->where('commodity_name', 'non_essential')->sum('balance') ?? 0;
    }

    /**
     * Get all financial balances for a user in a single array.
     * 
     * @param User $user
     * @return array
     */
    public static function getAllBalances(User $user): array
    {
        return [
            'savings' => self::calculateSavingsBalance($user),
            'shares' => self::calculateSharesBalance($user),
            'loans' => self::calculateLoanBalance($user),
            'commodity' => self::calculateCommodityBalance($user),
            'electronics' => self::calculateElectronicsBalance($user),
        ];
    }

    /**
     * Calculate maximum loan amount based on transaction-based balances.
     * 
     * @param User $user
     * @return float
     */
    public static function calculateMaxLoanAmount(User $user): float
    {
        $multiplier = config('business_rules.loan_eligibility.multiplier', 2);

        $savings = self::calculateSavingsBalance($user);
        $shares = self::calculateSharesBalance($user);
        $loans = self::calculateLoanBalance($user);
        $essentialCommodity = self::calculateEssentialCommodityBalance($user);
        $nonEssentialCommodity = self::calculateNonEssentialCommodityBalance($user);
        $electronics = self::calculateElectronicsBalance($user);

        $assets = $savings + $shares;
        $liabilities = $loans + $essentialCommodity + $nonEssentialCommodity + $electronics;

        // NEW Formula: 2×(Savings+Shares) - (Loan+Commodity+Non-essential+Electronics)
        $maxLoan = ($multiplier * $assets) - $liabilities;

        return max(0, $maxLoan);
    }

    /**
     * Get formatted financial summary for display.
     *
     * @param User $user
     * @return array
     */
    public static function getFormattedFinancialSummary(User $user): array
    {
        $balances = self::getAllBalances($user);
        $essentialBalance = self::calculateEssentialCommodityBalance($user);
        $nonEssentialBalance = self::calculateNonEssentialCommodityBalance($user);

        return [
            'savings_balance' => $balances['savings'],
            'shares_balance' => $balances['shares'],
            'loan_balance' => $balances['loans'],
            'commodity_balance' => $balances['commodity'],
            'electronics_balance' => $balances['electronics'],
            'essential_commodity' => $essentialBalance,
            'non_essential_commodity' => $nonEssentialBalance,
            'savings_formatted' => '₦' . number_format($balances['savings'], 2),
            'shares_formatted' => '₦' . number_format($balances['shares'], 2),
            'loan_formatted' => '₦' . number_format($balances['loans'], 2),
            'commodity_formatted' => '₦' . number_format($balances['commodity'], 2),
            'electronics_formatted' => '₦' . number_format($balances['electronics'], 2),
            'essential_commodity_formatted' => '₦' . number_format($essentialBalance, 2),
            'non_essential_commodity_formatted' => '₦' . number_format($nonEssentialBalance, 2),
            'max_loan_amount' => self::calculateMaxLoanAmount($user),
            'max_loan_formatted' => '₦' . number_format(self::calculateMaxLoanAmount($user), 2),
        ];
    }
}
