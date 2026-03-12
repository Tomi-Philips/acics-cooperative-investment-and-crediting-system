<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AvailableCommodity;
use App\Models\UserCommodity;
use App\Services\FinancialCalculationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard with dynamic data.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the authenticated user with member relationship
        $user = Auth::user()->load('member');

        // Get user's financial data from the database

        // Get financial balances using the standardized service
        $loanBalance = FinancialCalculationService::calculateLoanBalance($user);
        $savingsBalance = FinancialCalculationService::calculateSavingsBalance($user);
        $sharesBalance = FinancialCalculationService::calculateSharesBalance($user);
        $commodityBalance = FinancialCalculationService::calculateCommodityBalance($user);
        $electronicsBalance = FinancialCalculationService::calculateElectronicsBalance($user);

        // Additional breakdowns for dashboard visibility
        $essentialBalance = FinancialCalculationService::calculateEssentialCommodityBalance($user);
        $nonEssentialBalance = FinancialCalculationService::calculateNonEssentialCommodityBalance($user);
        $entrancePaid = (bool) optional($user->member)->entrance_fee_paid;

        // Loan interest paid (all-time) from both LoanPayments and Transactions
        $loanInterestFromPayments = $user->loanPayments()
            ->where('status', 'paid')
            ->where(function($q) {
                $q->where('notes', 'like', '%interest%');
            })
            ->sum('amount');

        $loanInterestFromTransactions = $user->transactions()
            ->where('type', 'loan_interest')
            ->where('status', 'completed')
            ->sum('amount');

        $loanInterestPaid = $loanInterestFromPayments + $loanInterestFromTransactions;

        // Get maximum loan amount (eligible amount)
        $maxLoanAmount = $user->member ? $user->member->getMaxLoanAmountAttribute() : 0;

        // Get count of available commodities for users to see
        $commodityItemsCount = AvailableCommodity::count();

        // Get active loans count
        $activeLoansCount = $user->loans()->where('status', 'active')->count();

        // Get pending support tickets count
        $pendingFeedbackCount = $user->supportTickets()->where('status', 'open')->count();

        // Get upcoming loan payments
        $upcomingPayments = $user->loanPayments()
            ->where('due_date', '>=', now())
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->take(3)
            ->get();

        // Get recent transactions from all sources
        $recentTransactions = collect();

        // Get share transactions
        $shareTransactions = $user->shareTransactions()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($transaction) {
                return (object)[
                    'id' => $transaction->id,
                    'type' => 'share_' . $transaction->type,
                    'amount' => $transaction->amount,
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at,
                    'status' => 'completed',
                    'charges' => 0,
                    'net_amount' => $transaction->amount
                ];
            });

        // Get saving transactions
        $savingTransactions = $user->savingTransactions()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($transaction) {
                return (object)[
                    'id' => $transaction->id,
                    'type' => 'saving_' . $transaction->type,
                    'amount' => $transaction->amount,
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at,
                    'status' => 'completed',
                    'charges' => 0,
                    'net_amount' => $transaction->amount
                ];
            });

        // Get loan payments
        $loanPayments = $user->loanPayments()
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($payment) {
                return (object)[
                    'id' => $payment->id,
                    'type' => 'loan_payment',
                    'amount' => $payment->amount,
                    'description' => $payment->notes ?? 'Loan Payment',
                    'created_at' => $payment->created_at,
                    'status' => $payment->status,
                    'charges' => 0,
                    'net_amount' => $payment->amount
                ];
            });

        // Get regular transactions
        $regularTransactions = $user->transactions()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($transaction) {
                return (object)[
                    'id' => $transaction->id,
                    'type' => $transaction->type,
                    'amount' => $transaction->amount,
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at,
                    'status' => $transaction->status,
                    'charges' => $transaction->charges ?? 0,
                    'net_amount' => $transaction->net_amount ?? $transaction->amount
                ];
            });

        // Get commodity transactions
        $commodityTransactions = \App\Models\CommodityTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($transaction) {
                $type = $transaction->commodity_type === 'essential' ? 'commodity_essential' : 'commodity_non_essential';
                return (object)[
                    'id' => $transaction->id,
                    'type' => $type,
                    'amount' => $transaction->amount,
                    'description' => $transaction->description ?? ucfirst($transaction->commodity_type) . ' Commodity Purchase',
                    'created_at' => $transaction->created_at,
                    'status' => 'completed',
                    'charges' => 0,
                    'net_amount' => $transaction->amount
                ];
            });

        // Get electronics transactions
        $electronicsTransactions = \App\Models\Electronics::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($transaction) {
                return (object)[
                    'id' => $transaction->id,
                    'type' => 'electronics',
                    'amount' => $transaction->amount,
                    'description' => $transaction->description ?? 'Electronics Purchase',
                    'created_at' => $transaction->created_at,
                    'status' => 'completed',
                    'charges' => 0,
                    'net_amount' => $transaction->amount
                ];
            });

        // Get loan disbursements
        $loanDisbursements = \App\Models\Loan::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($loan) {
                return (object)[
                    'id' => $loan->id,
                    'type' => 'loan_disbursement',
                    'amount' => $loan->amount,
                    'description' => 'Loan Disbursement',
                    'created_at' => $loan->created_at,
                    'status' => 'completed',
                    'charges' => 0,
                    'net_amount' => $loan->amount
                ];
            });



        // Merge and sort all transactions
        $allTransactions = collect()
            ->merge($shareTransactions)
            ->merge($savingTransactions)
            ->merge($loanPayments)
            ->merge($regularTransactions)
            ->merge($commodityTransactions)
            ->merge($electronicsTransactions)
            ->merge($loanDisbursements)
            ->sortByDesc('created_at');



        // Manual pagination for dashboard
        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedTransactions = $allTransactions->slice($offset, $perPage);

        // Create pagination object
        $recentTransactions = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedTransactions,
            $allTransactions->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('user.index', [
            'user' => $user,
            'loanBalance' => $loanBalance,
            'savingsBalance' => $savingsBalance,
            'sharesBalance' => $sharesBalance,
            'commodityBalance' => $commodityBalance,
            'electronicsBalance' => $electronicsBalance,
            'essentialBalance' => $essentialBalance,
            'nonEssentialBalance' => $nonEssentialBalance,
            'entrancePaid' => $entrancePaid,
            'loanInterestPaid' => $loanInterestPaid,
            'maxLoanAmount' => $maxLoanAmount,
            'commodityItemsCount' => $commodityItemsCount,
            'activeLoansCount' => $activeLoansCount,
            'pendingFeedbackCount' => $pendingFeedbackCount,
            'upcomingPayments' => $upcomingPayments,
            'recentTransactions' => $recentTransactions
        ]);
    }
/**
     * Get the user's financial status.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFinancialStatus()
    {
        $user = Auth::user();

        $savings = $user->savings()->sum('amount') ?? 0;
        $shares = $user->shares()->sum('amount') ?? 0;
        $loan = $user->loans()->where('status', 'active')->sum('remaining_balance') ?? 0;
        $electronics = $user->electronics()->sum('amount') ?? 0;
        $commodity = $user->userCommodities()->sum('balance') ?? 0;

        Log::info('Financial Status for user ' . Auth::id(), [
            'savings' => $savings,
            'shares' => $shares,
            'loan' => $loan,
            'electronics' => $electronics,
            'commodity' => $commodity,
        ]);

        return response()->json([
            'savings' => $savings,
            'shares' => $shares,
            'loan' => $loan,
            'electronics' => $electronics,
            'commodity' => $commodity,
        ]);
    }
}
