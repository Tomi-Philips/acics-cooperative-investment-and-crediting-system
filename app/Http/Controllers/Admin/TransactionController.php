<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionGroup;
use App\Models\User;
use App\Models\ShareTransaction;
use App\Models\SavingTransaction;
use App\Models\LoanPayment;
use App\Services\TransactionGroupService;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Use the same logic as the dashboard - only show grouped transactions
        $transactionGroupService = new TransactionGroupService();

        // Get per page from request or default to 10 (with pagination)
        $perPage = $request->get('per_page', 10);

        // Get grouped transactions using the service (same as dashboard)
        $transactions = $transactionGroupService->getGroupedTransactionsForDashboard($perPage);

        return view('admin.transaction', compact('transactions'));
    }



    /**
     * Show the form for creating a new transaction.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $users = User::where('role', 'member')->get();
        return view('admin.transactions.create', compact('users'));
    }

    /**
     * Store a newly created transaction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:deposit,withdrawal,loan_payment,loan_disbursement,share_purchase,commodity_purchase,entrance_fee',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
        ]);

        Transaction::create([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'reference' => 'TRANS-' . time(),
            'status' => 'completed',
        ]);

        return redirect()->route('admin.transactions')->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the specified transaction or transaction group.
     *
     * @param  string  $id - Format: "type-id" (e.g., "GROUP-123", "SHARE-123", "SAVING-456", "LOAN-789", or just "123" for regular)
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $transaction = null;

        // Handle transaction groups
        if (strpos($id, 'GROUP-') === 0) {
            $actualId = str_replace('GROUP-', '', $id);
            $transactionGroup = TransactionGroup::with(['processedBy'])
                ->findOrFail($actualId);

            // Get paginated individual transactions
            $shareTransactions = $transactionGroup->shareTransactions()->with('user')->paginate(10, ['*'], 'share_page');
            $savingTransactions = $transactionGroup->savingTransactions()->with('user')->paginate(10, ['*'], 'saving_page');
            $regularTransactions = $transactionGroup->transactions()->with('user')->paginate(10, ['*'], 'transaction_page');
            $commodityTransactions = $transactionGroup->commodityTransactions()->with('user')->paginate(10, ['*'], 'commodity_page');
            $loanPayments = $transactionGroup->loanPayments()->with('user')->paginate(10, ['*'], 'loan_page');

            return view('admin.transactions.show-group', compact(
                'transactionGroup',
                'shareTransactions',
                'savingTransactions',
                'regularTransactions',
                'commodityTransactions',
                'loanPayments'
            ));
        }

        // Parse the transaction ID to determine type and actual ID
        if (strpos($id, 'SHARE-') === 0) {
            $actualId = str_replace('SHARE-', '', $id);
            $shareTransaction = ShareTransaction::with('user')->findOrFail($actualId);
            $transaction = (object)[
                'id' => $shareTransaction->id,
                'user' => $shareTransaction->user,
                'type' => 'share_' . $shareTransaction->type,
                'amount' => $shareTransaction->amount,
                'description' => $shareTransaction->description,
                'created_at' => $shareTransaction->created_at,
                'updated_at' => $shareTransaction->updated_at,
                'status' => 'completed',
                'reference' => 'SHARE-' . $shareTransaction->id,
                'source' => 'share',
                'transaction_date' => $shareTransaction->transaction_date
            ];
        } elseif (strpos($id, 'SAVING-') === 0) {
            $actualId = str_replace('SAVING-', '', $id);
            $savingTransaction = SavingTransaction::with('user')->findOrFail($actualId);
            $transaction = (object)[
                'id' => $savingTransaction->id,
                'user' => $savingTransaction->user,
                'type' => 'saving_' . $savingTransaction->type,
                'amount' => $savingTransaction->amount,
                'description' => $savingTransaction->description,
                'created_at' => $savingTransaction->created_at,
                'updated_at' => $savingTransaction->updated_at,
                'status' => 'completed',
                'reference' => 'SAVING-' . $savingTransaction->id,
                'source' => 'saving',
                'transaction_date' => $savingTransaction->transaction_date
            ];
        } elseif (strpos($id, 'LOAN-') === 0) {
            $actualId = str_replace('LOAN-', '', $id);
            $loanPayment = LoanPayment::with(['loan.user'])->findOrFail($actualId);
            $transaction = (object)[
                'id' => $loanPayment->id,
                'user' => $loanPayment->loan ? $loanPayment->loan->user : null,
                'type' => 'loan_payment',
                'amount' => $loanPayment->amount,
                'description' => $loanPayment->notes ?? 'Loan Payment',
                'created_at' => $loanPayment->created_at,
                'updated_at' => $loanPayment->updated_at,
                'status' => $loanPayment->status,
                'reference' => 'LOAN-' . $loanPayment->id,
                'source' => 'loan',
                'transaction_date' => $loanPayment->payment_date,
                'due_date' => $loanPayment->due_date,
                'payment_method' => $loanPayment->payment_method
            ];
        } else {
            // Regular transaction
            $regularTransaction = Transaction::with('user')->findOrFail($id);
            $transaction = (object)[
                'id' => $regularTransaction->id,
                'user' => $regularTransaction->user,
                'type' => $regularTransaction->type,
                'amount' => $regularTransaction->amount,
                'description' => $regularTransaction->description,
                'created_at' => $regularTransaction->created_at,
                'updated_at' => $regularTransaction->updated_at,
                'status' => $regularTransaction->status,
                'reference' => $regularTransaction->reference ?? 'N/A',
                'source' => 'regular',
                'transaction_date' => $regularTransaction->created_at
            ];
        }

        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Generate a transaction report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'nullable|in:deposit,withdrawal,loan_payment,loan_disbursement,share_purchase,commodity_purchase',
        ]);

        $query = Transaction::with('user')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date);
        
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        $transactions = $query->orderBy('created_at', 'desc')->get();
        
        // This is a placeholder - implement actual report generation logic
        // You might want to use a package like barryvdh/laravel-dompdf for PDF generation
        
        return view('admin.transactions.report', compact('transactions', 'request'));
    }
}
