<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionReportController extends Controller
{
    /**
     * Display the transaction report page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Define transaction types and statuses for filter dropdowns
        $transactionTypes = [
            'deposit', 'withdrawal', 'loan_disbursement', 'loan_payment', 'loan_repayment', 'loan_interest',
            'share_credit', 'share_debit', 'share_purchase',
            'saving_credit', 'saving_debit',
            'commodity_essential', 'commodity_non_essential', 'commodity_purchase',
            'electronics',
            'entrance_fee'
        ];
        $transactionStatuses = ['completed', 'pending', 'failed', 'cancelled'];

        // Get all transactions from different sources
        $allTransactions = $this->getAllTransactions($user, $request);

        // Apply type filter
        if ($request->filled('type')) {
            $allTransactions = $allTransactions->filter(function($transaction) use ($request) {
                return $transaction->type === $request->type;
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $allTransactions = $allTransactions->filter(function($transaction) use ($request) {
                return $transaction->status === $request->status;
            });
        }

        // Apply date range filter
        if ($request->filled('start_date')) {
            $allTransactions = $allTransactions->filter(function($transaction) use ($request) {
                return $transaction->created_at->gte($request->start_date);
            });
        }

        if ($request->filled('end_date')) {
            $allTransactions = $allTransactions->filter(function($transaction) use ($request) {
                return $transaction->created_at->lte($request->end_date . ' 23:59:59');
            });
        }

        // Sort by created_at desc
        $allTransactions = $allTransactions->sortByDesc('created_at');

        // Manual pagination
        $perPage = 10;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedTransactions = $allTransactions->slice($offset, $perPage);

        // Create pagination object
        $transactions = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedTransactions,
            $allTransactions->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Calculate totals for summary
        $totalDeposits = $this->calculateTotalDeposits($user->id, $request);
        $totalWithdrawals = $this->calculateTotalWithdrawals($user->id, $request);

        return view('user.transaction_report', [
            'user' => $user,
            'transactions' => $transactions,
            'transactionTypes' => $transactionTypes,
            'transactionStatuses' => $transactionStatuses,
            'totalDeposits' => $totalDeposits,
            'totalWithdrawals' => $totalWithdrawals,
        ]);
    }
    
    /**
     * Calculate total deposits with filters.
     *
     * @param  int  $userId
     * @param  \Illuminate\Http\Request  $request
     * @return float
     */
    private function calculateTotalDeposits($userId, Request $request)
    {
        $query = Transaction::where('user_id', $userId)
            ->whereIn('type', ['deposit', 'loan_disbursement'])
            ->where('status', 'completed');
        
        // Apply date range filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        return $query->sum('amount');
    }
    
    /**
     * Calculate total withdrawals with filters.
     *
     * @param  int  $userId
     * @param  \Illuminate\Http\Request  $request
     * @return float
     */
    private function calculateTotalWithdrawals($userId, Request $request)
    {
        $query = Transaction::where('user_id', $userId)
            ->whereIn('type', ['withdrawal', 'loan_repayment', 'share_purchase'])
            ->where('status', 'completed');

        // Apply date range filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        return $query->sum('amount');
    }

    /**
     * Get all transactions from different sources.
     *
     * @param  \App\Models\User  $user
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    private function getAllTransactions($user, Request $request)
    {
        // Get regular transactions
        $regularTransactions = $user->transactions()->get()->map(function($transaction) {
            return (object)[
                'id' => $transaction->id,
                'type' => $transaction->type,
                'amount' => $transaction->amount,
                'description' => $transaction->description,
                'created_at' => $transaction->created_at,
                'status' => $transaction->status,
                'reference' => $transaction->reference,
                'charges' => $transaction->charges ?? 0,
                'net_amount' => $transaction->net_amount ?? $transaction->amount,
                'formatted_amount' => '₦' . number_format($transaction->amount, 2),
                'status_badge_class' => $this->getStatusBadgeClass($transaction->status),
                'icon_class' => $this->getIconClass($transaction->type)
            ];
        });

        // Get share transactions
        $shareTransactions = $user->shareTransactions()->get()->map(function($transaction) {
            $type = 'share_' . $transaction->type;
            return (object)[
                'id' => $transaction->id,
                'type' => $type,
                'amount' => $transaction->amount,
                'description' => $transaction->description,
                'created_at' => $transaction->created_at,
                'status' => 'completed',
                'reference' => 'SHARE-' . $transaction->id,
                'charges' => 0,
                'net_amount' => $transaction->amount,
                'formatted_amount' => '₦' . number_format($transaction->amount, 2),
                'status_badge_class' => $this->getStatusBadgeClass('completed'),
                'icon_class' => $this->getIconClass($type)
            ];
        });

        // Get saving transactions
        $savingTransactions = $user->savingTransactions()->get()->map(function($transaction) {
            $type = 'saving_' . $transaction->type;
            return (object)[
                'id' => $transaction->id,
                'type' => $type,
                'amount' => $transaction->amount,
                'description' => $transaction->description,
                'created_at' => $transaction->created_at,
                'status' => 'completed',
                'reference' => 'SAVING-' . $transaction->id,
                'charges' => 0,
                'net_amount' => $transaction->amount,
                'formatted_amount' => '₦' . number_format($transaction->amount, 2),
                'status_badge_class' => $this->getStatusBadgeClass('completed'),
                'icon_class' => $this->getIconClass($type)
            ];
        });

        // Get loan payments
        $loanPayments = $user->loanPayments()->get()->map(function($payment) {
            return (object)[
                'id' => $payment->id,
                'type' => 'loan_payment',
                'amount' => $payment->amount,
                'description' => $payment->notes ?? 'Loan Payment',
                'created_at' => $payment->created_at,
                'status' => $payment->status,
                'reference' => 'LOAN-' . $payment->id,
                'charges' => 0,
                'net_amount' => $payment->amount,
                'formatted_amount' => '₦' . number_format($payment->amount, 2),
                'status_badge_class' => $this->getStatusBadgeClass($payment->status),
                'icon_class' => $this->getIconClass('loan_payment')
            ];
        });

        // Get commodity transactions (essential and non-essential)
        $commodityTransactions = \App\Models\CommodityTransaction::where('user_id', $user->id)->get()->map(function($transaction) {
            $type = $transaction->commodity_type === 'essential' ? 'commodity_essential' : 'commodity_non_essential';
            return (object)[
                'id' => $transaction->id,
                'type' => $type,
                'amount' => $transaction->amount,
                'description' => $transaction->description ?? ucfirst($transaction->commodity_type) . ' Commodity Purchase',
                'created_at' => $transaction->created_at,
                'status' => 'completed',
                'reference' => 'COMMODITY-' . $transaction->id,
                'charges' => 0,
                'net_amount' => $transaction->amount,
                'formatted_amount' => '₦' . number_format($transaction->amount, 2),
                'status_badge_class' => $this->getStatusBadgeClass('completed'),
                'icon_class' => $this->getIconClass($type)
            ];
        });

        // Get electronics transactions
        $electronicsTransactions = \App\Models\Electronics::where('user_id', $user->id)->get()->map(function($transaction) {
            return (object)[
                'id' => $transaction->id,
                'type' => 'electronics',
                'amount' => $transaction->amount,
                'description' => $transaction->description ?? 'Electronics Purchase',
                'created_at' => $transaction->created_at,
                'status' => 'completed',
                'reference' => 'ELECTRONICS-' . $transaction->id,
                'charges' => 0,
                'net_amount' => $transaction->amount,
                'formatted_amount' => '₦' . number_format($transaction->amount, 2),
                'status_badge_class' => $this->getStatusBadgeClass('completed'),
                'icon_class' => $this->getIconClass('electronics')
            ];
        });

        // Get loan disbursements (from loans table)
        $loanDisbursements = \App\Models\Loan::where('user_id', $user->id)->get()->map(function($loan) {
            return (object)[
                'id' => $loan->id,
                'type' => 'loan_disbursement',
                'amount' => $loan->amount,
                'description' => 'Loan Disbursement',
                'created_at' => $loan->created_at,
                'status' => 'completed',
                'reference' => 'LOAN-DISB-' . $loan->id,
                'charges' => 0,
                'net_amount' => $loan->amount,
                'formatted_amount' => '₦' . number_format($loan->amount, 2),
                'status_badge_class' => $this->getStatusBadgeClass('completed'),
                'icon_class' => $this->getIconClass('loan_disbursement')
            ];
        });

        // Get loan interest payments from both LoanPayments and Transactions
        $loanInterestFromPayments = \App\Models\LoanPayment::where('user_id', $user->id)
            ->where('status', 'paid')
            ->where(function($q) {
                $q->where('notes', 'like', '%interest%');
            })
            ->get()
            ->map(function($payment) {
                return (object)[
                    'id' => $payment->id,
                    'type' => 'loan_interest',
                    'amount' => $payment->amount,
                    'description' => 'Loan Interest Payment',
                    'created_at' => $payment->created_at,
                    'status' => 'completed',
                    'reference' => 'LOAN-INT-' . $payment->id,
                    'charges' => 0,
                    'net_amount' => $payment->amount,
                    'formatted_amount' => '₦' . number_format($payment->amount, 2),
                    'status_badge_class' => $this->getStatusBadgeClass('completed'),
                    'icon_class' => $this->getIconClass('loan_interest')
                ];
            });

        $loanInterestFromTransactions = \App\Models\Transaction::where('user_id', $user->id)
            ->where('type', 'loan_interest')
            ->where('status', 'completed')
            ->get()
            ->map(function($transaction) {
                return (object)[
                    'id' => $transaction->id,
                    'type' => $transaction->type,
                    'amount' => $transaction->amount,
                    'description' => $transaction->description ?? 'Loan Interest Payment',
                    'created_at' => $transaction->created_at,
                    'status' => $transaction->status,
                    'reference' => $transaction->reference,
                    'charges' => $transaction->charges ?? 0,
                    'net_amount' => $transaction->net_amount ?? $transaction->amount,
                    'formatted_amount' => '₦' . number_format($transaction->amount, 2),
                    'status_badge_class' => $this->getStatusBadgeClass($transaction->status),
                    'icon_class' => $this->getIconClass($transaction->type)
                ];
            });

        // Merge all transactions
        return collect()
            ->merge($regularTransactions)
            ->merge($shareTransactions)
            ->merge($savingTransactions)
            ->merge($loanPayments)
            ->merge($commodityTransactions)
            ->merge($electronicsTransactions)
            ->merge($loanDisbursements)
            ->merge($loanInterestFromPayments)
            ->merge($loanInterestFromTransactions);
    }

    /**
     * Get the icon class for a transaction type.
     */
    private function getIconClass($type)
    {
        $iconClasses = [
            'deposit' => 'text-green-500',
            'withdrawal' => 'text-red-500',
            'loan_disbursement' => 'text-blue-500',
            'loan_payment' => 'text-blue-500',
            'loan_interest' => 'text-orange-500',
            'share_credit' => 'text-purple-500',
            'share_debit' => 'text-purple-500',
            'saving_credit' => 'text-green-500',
            'saving_debit' => 'text-red-500',
            'commodity_purchase' => 'text-yellow-500',
            'commodity_essential' => 'text-green-500',
            'commodity_non_essential' => 'text-blue-500',
            'electronics' => 'text-orange-500',
            'entrance_fee' => 'text-indigo-500',
        ];

        return $iconClasses[$type] ?? 'text-gray-500';
    }

    /**
     * Get the status badge class for a transaction status.
     */
    private function getStatusBadgeClass($status)
    {
        $badgeClasses = [
            'completed' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'failed' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
        ];

        return $badgeClasses[$status] ?? 'bg-gray-100 text-gray-800';
    }
    
    /**
     * Export transaction report as PDF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        // This would be implemented with a PDF generation library
        return redirect()->route('user.transaction_report')
            ->with('success', 'PDF export functionality will be implemented soon.');
    }
    
    /**
     * Export transaction report as Excel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel(Request $request)
    {
        // This would be implemented with an Excel generation library
        return redirect()->route('user.transaction_report')
            ->with('success', 'Excel export functionality will be implemented soon.');
    }
}
