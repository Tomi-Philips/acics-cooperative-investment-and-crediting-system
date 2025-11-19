<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    /**
     * Display the transaction report page with user's transactions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get filter parameters
        $type = $request->input('type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');
        
        // Build the query
        $query = $user->transactions()->orderBy('created_at', 'desc');
        
        // Apply filters
        if ($type) {
            $query->where('type', $type);
        }
        
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        // Get regular transactions
        $regularTransactions = $query->get()->map(function($transaction) {
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
                'icon_class' => $this->getIconClass($transaction->type),
                'formatted_amount' => '₦' . number_format($transaction->amount, 2),
                'status_badge_class' => $this->getStatusBadgeClass($transaction->status)
            ];
        });

        // Get share transactions
        $shareTransactions = $user->shareTransactions()
            ->when($startDate, function($q) use ($startDate) {
                return $q->whereDate('transaction_date', '>=', $startDate);
            })
            ->when($endDate, function($q) use ($endDate) {
                return $q->whereDate('transaction_date', '<=', $endDate);
            })
            ->get()
            ->map(function($transaction) {
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
                    'icon_class' => $this->getIconClass($type),
                    'formatted_amount' => '₦' . number_format($transaction->amount, 2),
                    'status_badge_class' => $this->getStatusBadgeClass('completed')
                ];
            });

        // Get saving transactions
        $savingTransactions = $user->savingTransactions()
            ->when($startDate, function($q) use ($startDate) {
                return $q->whereDate('transaction_date', '>=', $startDate);
            })
            ->when($endDate, function($q) use ($endDate) {
                return $q->whereDate('transaction_date', '<=', $endDate);
            })
            ->get()
            ->map(function($transaction) {
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
                    'icon_class' => $this->getIconClass($type),
                    'formatted_amount' => '₦' . number_format($transaction->amount, 2),
                    'status_badge_class' => $this->getStatusBadgeClass('completed')
                ];
            });

        // Get loan payments
        $loanPayments = $user->loanPayments()
            ->when($startDate, function($q) use ($startDate) {
                return $q->whereDate('payment_date', '>=', $startDate);
            })
            ->when($endDate, function($q) use ($endDate) {
                return $q->whereDate('payment_date', '<=', $endDate);
            })
            ->get()
            ->map(function($payment) {
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
                    'icon_class' => $this->getIconClass('loan_payment'),
                    'formatted_amount' => '₦' . number_format($payment->amount, 2),
                    'status_badge_class' => $this->getStatusBadgeClass($payment->status)
                ];
            });

        // Get commodity transactions
        $commodityTransactions = $user->commodityTransactions()
            ->when($startDate, function($q) use ($startDate) {
                return $q->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function($q) use ($endDate) {
                return $q->whereDate('created_at', '<=', $endDate);
            })
            ->get()
            ->map(function($transaction) {
                return (object)[
                    'id' => $transaction->id,
                    'type' => 'commodity_' . $transaction->commodity_type,
                    'amount' => $transaction->amount,
                    'description' => ucfirst($transaction->commodity_type) . ' commodity transaction',
                    'created_at' => $transaction->created_at,
                    'status' => 'completed',
                    'reference' => 'COM-' . $transaction->id,
                    'charges' => 0,
                    'net_amount' => $transaction->amount,
                    'icon_class' => $this->getIconClass('commodity_' . $transaction->commodity_type),
                    'formatted_amount' => '₦' . number_format($transaction->amount, 2),
                    'status_badge_class' => $this->getStatusBadgeClass('completed')
                ];
            });

        // Get electronics transactions
        $electronicsTransactions = $user->electronics()
            ->when($startDate, function($q) use ($startDate) {
                return $q->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function($q) use ($endDate) {
                return $q->whereDate('created_at', '<=', $endDate);
            })
            ->get()
            ->map(function($transaction) {
                return (object)[
                    'id' => $transaction->id,
                    'type' => 'electronics',
                    'amount' => $transaction->amount,
                    'description' => 'Electronics purchase',
                    'created_at' => $transaction->created_at,
                    'status' => 'completed',
                    'reference' => 'ELE-' . $transaction->id,
                    'charges' => 0,
                    'net_amount' => $transaction->amount,
                    'icon_class' => $this->getIconClass('electronics'),
                    'formatted_amount' => '₦' . number_format($transaction->amount, 2),
                    'status_badge_class' => $this->getStatusBadgeClass('completed')
                ];
            });

        // Get loan disbursements
        $loanDisbursements = $user->loans()
            ->when($startDate, function($q) use ($startDate) {
                return $q->whereDate('disbursed_at', '>=', $startDate);
            })
            ->when($endDate, function($q) use ($endDate) {
                return $q->whereDate('disbursed_at', '<=', $endDate);
            })
            ->whereNotNull('disbursed_at')
            ->get()
            ->map(function($loan) {
                return (object)[
                    'id' => $loan->id,
                    'type' => 'loan_disbursement',
                    'amount' => $loan->amount,
                    'description' => 'Loan disbursement',
                    'created_at' => $loan->disbursed_at,
                    'status' => 'completed',
                    'reference' => $loan->loan_number,
                    'charges' => 0,
                    'net_amount' => $loan->amount,
                    'icon_class' => $this->getIconClass('loan_disbursement'),
                    'formatted_amount' => '₦' . number_format($loan->amount, 2),
                    'status_badge_class' => $this->getStatusBadgeClass('completed')
                ];
            });



        // Merge all transactions
        $allTransactions = collect()
            ->merge($regularTransactions)
            ->merge($shareTransactions)
            ->merge($savingTransactions)
            ->merge($loanPayments)
            ->merge($commodityTransactions)
            ->merge($electronicsTransactions)
            ->merge($loanDisbursements)
            ->sortByDesc('created_at');



        // Apply type filter
        if ($type) {
            $allTransactions = $allTransactions->filter(function($transaction) use ($type) {
                return $transaction->type === $type;
            });
        }

        // Apply status filter
        if ($status) {
            $allTransactions = $allTransactions->filter(function($transaction) use ($status) {
                return $transaction->status === $status;
            });
        }

        // Paginate manually
        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedTransactions = $allTransactions->slice($offset, $perPage);

        // Create pagination object
        $transactions = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedTransactions,
            $allTransactions->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        // Get transaction types for the filter
        $transactionTypes = $allTransactions->pluck('type')->unique()->values()->toArray();

        // Get transaction statuses for the filter
        $transactionStatuses = $allTransactions->pluck('status')->unique()->values()->toArray();
        
        // Calculate totals
        $totalDeposits = $allTransactions->filter(function($transaction) {
            return in_array($transaction->type, ['deposit', 'loan_payment', 'share_purchase', 'share_credit', 'saving_credit']);
        })->sum('amount');

        $totalWithdrawals = $allTransactions->filter(function($transaction) {
            return in_array($transaction->type, ['withdrawal', 'loan_disbursement', 'share_debit', 'saving_debit']);
        })->sum('amount');
        
        return view('user.transaction_report', [
            'user' => $user,
            'transactions' => $transactions,
            'transactionTypes' => $transactionTypes,
            'transactionStatuses' => $transactionStatuses,
            'totalDeposits' => $totalDeposits,
            'totalWithdrawals' => $totalWithdrawals,
            'filters' => [
                'type' => $type,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $status,
            ]
        ]);
    }
    
    /**
     * Display the transaction details.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = Auth::user();
        $transaction = Transaction::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        return view('user.transaction_details', [
            'user' => $user,
            'transaction' => $transaction
        ]);
    }
    
    /**
     * Generate a PDF report of transactions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generatePdf(Request $request)
    {
        $user = Auth::user();
        
        // Get filter parameters
        $type = $request->input('type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');
        
        // Build the query
        $query = $user->transactions()->orderBy('created_at', 'desc');
        
        // Apply filters
        if ($type) {
            $query->where('type', $type);
        }
        
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        // Get transactions
        $transactions = $query->get();
        
        // Generate PDF
        $pdf = PDF::loadView('user.transaction_pdf', [
            'user' => $user,
            'transactions' => $transactions,
            'filters' => [
                'type' => $type,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $status,
            ]
        ]);
        
        return $pdf->download('transaction_report_' . date('Y-m-d') . '.pdf');
    }
    
    /**
     * Export transactions to Excel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        
        // Get filter parameters
        $type = $request->input('type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');
        
        // Build the query
        $query = $user->transactions()->orderBy('created_at', 'desc');
        
        // Apply filters
        if ($type) {
            $query->where('type', $type);
        }
        
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        // Get transactions
        $transactions = $query->get();
        
        // Generate Excel
        return Excel::download(new \App\Exports\TransactionsExport($transactions), 'transaction_report_' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Get the icon class for a transaction type.
     *
     * @param string $type
     * @return string
     */
    private function getIconClass($type)
    {
        $iconClasses = [
            'deposit' => 'text-green-500',
            'withdrawal' => 'text-red-500',
            'loan_disbursement' => 'text-blue-500',
            'loan_payment' => 'text-blue-500',
            'share_credit' => 'text-purple-500',
            'share_debit' => 'text-purple-500',
            'saving_credit' => 'text-green-500',
            'saving_debit' => 'text-red-500',
            'commodity_purchase' => 'text-yellow-500',
            'commodity_essential' => 'text-green-500',
            'commodity_non_essential' => 'text-blue-500',
        ];

        return $iconClasses[$type] ?? 'text-gray-500';
    }

    /**
     * Get the status badge class for a transaction status.
     *
     * @param string $status
     * @return string
     */
    private function getStatusBadgeClass($status)
    {
        $statusClasses = [
            'completed' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'failed' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            'paid' => 'bg-green-100 text-green-800',
            'unpaid' => 'bg-red-100 text-red-800',
        ];

        return $statusClasses[$status] ?? 'bg-gray-100 text-gray-800';
    }
}
