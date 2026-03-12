<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Member;
use App\Models\Transaction;
use App\Models\ShareTransaction;
use App\Models\SavingTransaction;
use App\Models\LoanPayment;
use App\Models\Loan;
use App\Models\CommodityTransaction;
use App\Models\UserCommodity;
use App\Models\Electronics;
use App\Services\TransactionGroupService;
use App\Services\FinancialCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ManualTransactionController extends Controller
{
    /**
     * Display the manual transaction management dashboard
     */
    public function index(Request $request)
    {
        // Get all manual transactions from different tables
        $allTransactions = collect();

        // Get share transactions
        $shareTransactions = ShareTransaction::with('user.member')
            ->where('description', 'like', '%Manual%')
            ->get()
            ->map(function($transaction) {
                return (object)[
                    'id' => $transaction->id,
                    'table_type' => 'shares',
                    'type' => 'shares',
                    'user_name' => $transaction->user->name,
                    'member_number' => $transaction->user->member->member_number ?? 'N/A',
                    'amount' => $transaction->amount,
                    'operation' => $transaction->type === 'credit' ? 'Addition' : 'Subtraction',
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at,
                ];
            });

        // Get saving transactions
        $savingTransactions = SavingTransaction::with('user.member')
            ->where('description', 'like', '%Manual%')
            ->get()
            ->map(function($transaction) {
                return (object)[
                    'id' => $transaction->id,
                    'table_type' => 'savings',
                    'type' => 'savings',
                    'user_name' => $transaction->user->name,
                    'member_number' => $transaction->user->member->member_number ?? 'N/A',
                    'amount' => $transaction->amount,
                    'operation' => $transaction->type === 'credit' ? 'Addition' : 'Subtraction',
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at,
                ];
            });

        // Get entrance fee transactions
        $entranceTransactions = Transaction::where('type', 'entrance_fee')
            ->where('description', 'like', '%Manual%')
            ->with('user.member')
            ->get()
            ->map(function($transaction) {
                return (object)[
                    'id' => $transaction->id,
                    'table_type' => 'entrance',
                    'type' => 'entrance',
                    'user_name' => $transaction->user->name,
                    'member_number' => $transaction->user->member->member_number ?? 'N/A',
                    'amount' => $transaction->amount,
                    'operation' => 'Addition',
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at,
                ];
            });

        // Get commodity transactions
        $commodityTransactions = CommodityTransaction::with('user.member')
            ->where('description', 'like', '%Manual%')
            ->get()
            ->map(function($transaction) {
                return (object)[
                    'id' => $transaction->id,
                    'table_type' => 'commodity',
                    'type' => 'commodity',
                    'user_name' => $transaction->user->name,
                    'member_number' => $transaction->user->member->member_number ?? 'N/A',
                    'amount' => $transaction->amount,
                    'operation' => $transaction->type === 'credit' ? 'Addition' : 'Subtraction',
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at,
                ];
            });

        // Get electronics transactions
        $electronicsTransactions = Transaction::whereIn('type', ['electronics', 'electronics_repayment'])
            ->where('description', 'like', '%Manual%')
            ->with('user.member')
            ->get()
            ->map(function($transaction) {
                return (object)[
                    'id' => $transaction->id,
                    'table_type' => 'electronics',
                    'type' => 'electronics',
                    'user_name' => $transaction->user->name,
                    'member_number' => $transaction->user->member->member_number ?? 'N/A',
                    'amount' => $transaction->amount,
                    'operation' => $transaction->type === 'electronics' ? 'Addition' : 'Subtraction',
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at,
                ];
            });

        // Get loan transactions
        $loanTransactions = Transaction::whereIn('type', ['loan_disbursement', 'loan_repayment', 'loan_interest'])
            ->where('description', 'like', '%Manual%')
            ->with('user.member')
            ->get()
            ->map(function($transaction) {
                $operation = 'Subtraction';
                if ($transaction->type === 'loan_disbursement') {
                    $operation = 'Addition';
                } elseif ($transaction->type === 'loan_interest') {
                    $operation = 'Interest Payment';
                }
                return (object)[
                    'id' => $transaction->id,
                    'table_type' => 'loan',
                    'type' => 'loan',
                    'user_name' => $transaction->user->name,
                    'member_number' => $transaction->user->member->member_number ?? 'N/A',
                    'amount' => $transaction->amount,
                    'operation' => $operation,
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at,
                ];
            });

        // Merge all transactions
        $allTransactions = $allTransactions
            ->merge($shareTransactions)
            ->merge($savingTransactions)
            ->merge($entranceTransactions)
            ->merge($commodityTransactions)
            ->merge($electronicsTransactions)
            ->merge($loanTransactions)
            ->sortByDesc('created_at');

        // Apply search and filters
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $allTransactions = $allTransactions->filter(function($transaction) use ($search) {
                return stripos($transaction->user_name, $search) !== false ||
                       stripos($transaction->member_number, $search) !== false ||
                       stripos($transaction->description, $search) !== false;
            });
        }

        if ($request->has('type') && !empty($request->type)) {
            $type = $request->type;
            $allTransactions = $allTransactions->where('type', $type);
        }

        if ($request->has('operation') && !empty($request->operation)) {
            $operation = $request->operation;
            $allTransactions = $allTransactions->where('operation', $operation);
        }

        if ($request->has('date_from') && !empty($request->date_from)) {
            $dateFrom = $request->date_from;
            $allTransactions = $allTransactions->filter(function($transaction) use ($dateFrom) {
                return $transaction->created_at->gte($dateFrom);
            });
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $dateTo = $request->date_to;
            $allTransactions = $allTransactions->filter(function($transaction) use ($dateTo) {
                return $transaction->created_at->lte($dateTo . ' 23:59:59');
            });
        }

        if ($request->has('amount_min') && !empty($request->amount_min)) {
            $amountMin = $request->amount_min;
            $allTransactions = $allTransactions->where('amount', '>=', $amountMin);
        }

        if ($request->has('amount_max') && !empty($request->amount_max)) {
            $amountMax = $request->amount_max;
            $allTransactions = $allTransactions->where('amount', '<=', $amountMax);
        }

        // Paginate the results
        $perPage = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);
        $total = $allTransactions->count();
        $offset = ($currentPage - 1) * $perPage;
        $paginatedTransactions = $allTransactions->slice($offset, $perPage);

        // Create a LengthAwarePaginator
        $recentTransactions = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedTransactions,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );

        return view('admin.manual_transactions.index', compact('recentTransactions'));
    }

    /**
     * Show individual transaction form
     */
    public function create()
    {
        return view('admin.manual_transactions.create');
    }

    /**
     * Search for members
     */
    public function searchMembers(Request $request)
    {
        $query = $request->get('query');
        
        $members = User::where('role', 'member')
            ->with('member')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhereHas('member', function($memberQuery) use ($query) {
                      $memberQuery->where('member_number', 'like', "%{$query}%");
                  });
            })
            ->limit(10)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'member_number' => $user->member->member_number ?? 'N/A',
                    'email' => $user->email,
                    'balances' => [
                        'shares' => $user->member->total_share_amount ?? 0,
                        'savings' => $user->member->total_saving_amount ?? 0,
                        'loan' => $user->loans()->where('status', 'active')->sum('remaining_balance') ?? 0,
                        'entrance_paid' => $user->member->entrance_fee_paid ?? false,
                    ]
                ];
            });

        return response()->json($members);
    }

    /**
     * Get member financial details
     */
    public function getMemberDetails($userId)
    {
        $user = User::with('member', 'loans', 'userCommodities', 'electronics')->findOrFail($userId);

        // Calculate real-time balances using FinancialCalculationService
        $savingsBalance = FinancialCalculationService::calculateSavingsBalance($user);
        $sharesBalance = FinancialCalculationService::calculateSharesBalance($user);
        $loanInterest = FinancialCalculationService::calculateLoanInterest($user);

        $details = [
            'id' => $user->id,
            'name' => $user->name,
            'member_number' => $user->member->member_number ?? 'N/A',
            'email' => $user->email,
            'balances' => [
                'shares' => $sharesBalance,
                'savings' => $savingsBalance,
                'entrance_paid' => $user->member->entrance_fee_paid ?? false,
                'loan_interest' => $loanInterest,
            ],
            'loans' => $user->loans()->where('status', 'active')->get()->map(function($loan) {
                return [
                    'id' => $loan->id,
                    'loan_number' => $loan->loan_number,
                    'amount' => $loan->amount,
                    'remaining_balance' => $loan->remaining_balance,
                    'status' => $loan->status,
                ];
            }),
            'commodities' => $user->userCommodities->map(function($commodity) {
                return [
                    'type' => $commodity->commodity_name,
                    'balance' => $commodity->balance,
                ];
            }),
            'electronics' => $user->electronics->map(function($electronic) {
                return [
                    'id' => $electronic->id,
                    'name' => $electronic->electronics_name ?? 'Electronics',
                    'total_amount' => $electronic->total_amount ?? $electronic->amount,
                    'total_paid' => $electronic->total_paid ?? 0,
                    'remaining_balance' => $electronic->remaining_balance ?? $electronic->amount,
                    'status' => $electronic->status ?? 'active',
                ];
            }),
        ];

        return response()->json($details);
    }

    /**
     * Process individual manual transaction
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'transaction_type' => 'required|in:entrance,shares,savings,loan_repay,loan_interest,loan_disbursement,essential,non_essential,electronics',
            'operation' => 'required|in:addition,subtraction',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'loan_term' => 'nullable|integer|min:1|max:24',
            'approval_required' => 'boolean',
        ]);

        $user = User::with('member', 'loans')->findOrFail($request->user_id);
        
        // Validate business rules
        $this->validateBusinessRules($request, $user);

        DB::beginTransaction();

        try {
            // Create transaction group for this manual transaction
            $transactionGroupService = new TransactionGroupService();
            $groupTitle = 'Manual ' . ucfirst($request->transaction_type) . ' Transaction - ' . $user->name;
            $transactionGroup = $transactionGroupService->createManualTransactionGroup($groupTitle, Auth::id());

            $result = $this->processTransaction($request, $user, $transactionGroup);

            // Complete the transaction group
            $transactionGroupService->completeGroup($transactionGroup);

            DB::commit();

            Log::info('Manual transaction processed successfully', [
                'user_id' => $user->id,
                'transaction_type' => $request->transaction_type,
                'operation' => $request->operation,
                'amount' => $request->amount,
                'processed_by' => Auth::id(),
                'group_id' => $transactionGroup->id,
            ]);

            return redirect()->route('admin.manual_transactions.index')
                ->with('success', 'Transaction processed successfully. ' . $result['message']);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Manual transaction failed', [
                'user_id' => $user->id,
                'transaction_type' => $request->transaction_type,
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()
                ->with('error', 'Transaction failed: ' . $e->getMessage());
        }
    }

    /**
     * Validate business rules for manual transactions
     */
    private function validateBusinessRules(Request $request, User $user)
    {
        $transactionType = $request->transaction_type;
        $operation = $request->operation;
        $amount = $request->amount;

        switch ($transactionType) {
            case 'entrance':
                if ($operation !== 'addition') {
                    throw ValidationException::withMessages(['operation' => 'Entrance fee can only be addition (payment).']);
                }
                if ($user->member->entrance_fee_paid) {
                    throw ValidationException::withMessages(['user_id' => 'Member has already paid entrance fee.']);
                }
                if ($amount > 1000) {
                    throw ValidationException::withMessages(['amount' => 'Entrance fee cannot exceed ₦1,000.']);
                }
                break;

            case 'shares':
                if ($operation !== 'addition') {
                    throw ValidationException::withMessages(['operation' => 'Shares can only be increased, never reduced.']);
                }
                // Use transaction-based calculation for accurate current shares
                $currentShares = \App\Services\FinancialCalculationService::calculateSharesBalance($user);
                $maxShareContribution = config('business_rules.shares.maximum_contribution', 10000);
                if (($currentShares + $amount) > $maxShareContribution) {
                    throw ValidationException::withMessages(['amount' => 'Share limit is ₦' . number_format($maxShareContribution, 2) . '. Current: ₦' . number_format($currentShares, 2) . '. Maximum additional: ₦' . number_format($maxShareContribution - $currentShares, 2)]);
                }
                break;

            case 'savings':
                if ($operation === 'subtraction') {
                    $currentSavings = FinancialCalculationService::calculateSavingsBalance($user);
                    if ($amount > $currentSavings) {
                        throw ValidationException::withMessages(['amount' => 'Insufficient savings balance. Available: ₦' . number_format($currentSavings, 2)]);
                    }
                }
                break;

            case 'loan_repay':
                // Only allow repayment (subtraction) for loan_repay
                if ($operation !== 'subtraction') {
                    throw ValidationException::withMessages(['operation' => 'Invalid operation for loan repayment.']);
                }
                // Check total remaining balance across all active loans
                $activeLoans = $user->loans()->where('status', 'active')->get();
                if ($activeLoans->isEmpty()) {
                    throw ValidationException::withMessages(['user_id' => 'Member has no active loan for repayment.']);
                }
                $totalRemaining = $activeLoans->sum('remaining_balance');
                if ($amount > $totalRemaining) {
                    throw ValidationException::withMessages(['amount' => 'Amount exceeds total remaining loan balance: ₦' . number_format($totalRemaining, 2)]);
                }
                break;

            case 'loan_interest':
                // Only interest payments (subtraction) are allowed
                if ($operation !== 'subtraction') {
                    throw ValidationException::withMessages(['operation' => 'Invalid operation for loan interest. Only interest payments are allowed.']);
                }
                // Find the loan that will receive the payment (most remaining interest) and validate
                $activeLoans = $user->loans()->where('status', 'active')->get();
                $targetLoan = null;
                $maxRemainingInterest = 0;

                foreach ($activeLoans as $loan) {
                    $storedRate = $loan->interest_rate ?? 0.10;
                    $interestRate = $storedRate > 1 ? $storedRate / 100 : $storedRate;
                    $interestDue = $loan->amount * $interestRate;
                    $interestPaid = $loan->payments()
                        ->where('status', 'paid')
                        ->where('notes', 'LIKE', '%Interest Payment%')
                        ->sum('amount');
                    $remainingInterest = max(0, $interestDue - $interestPaid);

                    if ($remainingInterest > $maxRemainingInterest) {
                        $maxRemainingInterest = $remainingInterest;
                        $targetLoan = $loan;
                    }
                }

                if (!$targetLoan || $maxRemainingInterest <= 0) {
                    throw ValidationException::withMessages(['user_id' => 'Member has no outstanding interest on active loans.']);
                }

                if ($amount > $maxRemainingInterest) {
                    throw ValidationException::withMessages(['amount' => 'Interest payment exceeds remaining interest owed: ₦' . number_format($maxRemainingInterest, 2)]);
                }
                break;

            case 'loan_disbursement':
                // Only allow disbursement (addition) for loan_disbursement
                if ($operation !== 'addition') {
                    throw ValidationException::withMessages(['operation' => 'Invalid operation for loan disbursement.']);
                }

                if (!$user->member) {
                    throw ValidationException::withMessages(['user_id' => 'User is not a member.']);
                }
                
                // Eligibility and max amount checks removed as requested - Admin manages manually
                break;
        }
    }

    /**
     * Process the manual transaction based on type and operation
     */
    private function processTransaction(Request $request, User $user, $transactionGroup)
    {
        $transactionType = $request->transaction_type;
        $operation = $request->operation;
        $amount = $request->amount;
        $description = "Manual {$operation}: " . $request->description;

        switch ($transactionType) {
            case 'entrance':
                return $this->processEntranceTransaction($user, $amount, $description, $transactionGroup);

            case 'shares':
                return $this->processSharesTransaction($user, $operation, $amount, $description, $transactionGroup);

            case 'savings':
                return $this->processSavingsTransaction($user, $operation, $amount, $description, $transactionGroup);

            case 'loan_repay':
                // Only repayment (subtraction) allowed
                return $this->processCascadingLoanRepayment($user, $amount, $description, $transactionGroup);

            case 'loan_interest':
                return $this->processLoanInterestTransaction($user, $operation, $amount, $description, $transactionGroup);

            case 'loan_disbursement':
                // Only disbursement (addition) allowed
                return $this->processLoanDisbursementTransaction($user, $operation, $amount, $description, $request->loan_term, $transactionGroup);

            case 'essential':
            case 'non_essential':
                return $this->processCommodityTransaction($user, $transactionType, $operation, $amount, $description, $transactionGroup);

            case 'electronics':
                return $this->processElectronicsTransaction($user, $operation, $amount, $description, $transactionGroup);

            default:
                throw new \Exception('Invalid transaction type');
        }
    }

    /**
     * Process entrance fee transaction
     */
    private function processEntranceTransaction(User $user, $amount, $description, $transactionGroup)
    {
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'entrance_fee',
            'amount' => $amount,
            'description' => $description,
            'reference' => 'MAN-ENT-' . date('YmdHis') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
            'status' => 'completed',
            'transaction_date' => now(),
            'group_id' => $transactionGroup->id,
        ]);

        // Update group totals
        $transactionGroup->increment('total_amount', $amount);
        $transactionGroup->increment('total_records', 1);

        $user->member->update(['entrance_fee_paid' => true]);

        return ['message' => 'Entrance fee payment recorded successfully.'];
    }

    /**
     * Process shares transaction
     */
    private function processSharesTransaction(User $user, $operation, $amount, $description, $transactionGroup)
    {
        // Shares can only be addition
        $transaction = ShareTransaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'type' => 'credit',
            'description' => $description,
            'transaction_date' => now(),
            'group_id' => $transactionGroup->id,
        ]);

        // Update group totals
        $transactionGroup->increment('total_amount', $amount);
        $transactionGroup->increment('total_records', 1);

        $user->member->increment('total_share_amount', $amount);

        return ['message' => "Shares increased by ₦" . number_format($amount, 2)];
    }

    /**
     * Process savings transaction
     */
    private function processSavingsTransaction(User $user, $operation, $amount, $description, $transactionGroup)
    {
        $type = $operation === 'addition' ? 'credit' : 'debit';

        $transaction = SavingTransaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'type' => $type,
            'description' => $description,
            'transaction_date' => now(),
            'group_id' => $transactionGroup->id,
        ]);

        // Update group totals
        $transactionGroup->increment('total_amount', $amount);
        $transactionGroup->increment('total_records', 1);

        if ($operation === 'addition') {
            $user->member->increment('total_saving_amount', $amount);
            $message = "Savings deposit of ₦" . number_format($amount, 2) . " recorded successfully.";
        } else {
            $user->member->decrement('total_saving_amount', $amount);
            $message = "Savings withdrawal of ₦" . number_format($amount, 2) . " recorded successfully.";
        }

        return ['message' => $message];
    }

    /**
     * Process loan repayment transaction
     */
    private function processLoanRepayTransaction(User $user, $operation, $amount, $description, $customTerm = null, $transactionGroup)
    {
        if ($operation === 'addition') {
            // Loan disbursement - create new loan with 10% interest, no fixed term
            $principal = $amount;
            $interestRate = 0.10; // 10% interest
            $interestAmount = $principal * $interestRate;
            $totalPayment = $principal + $interestAmount;

            $loan = Loan::create([
                'user_id' => $user->id,
                'loan_number' => Loan::generateLoanNumber(),
                'amount' => $principal,
                'interest_rate' => 0.10, // Store as decimal
                'term_months' => 0, // No fixed term
                'monthly_payment' => 0, // No fixed monthly payment
                'total_payment' => round($totalPayment, 2),
                'remaining_balance' => round($totalPayment, 2),
                'purpose' => $description ?: 'Manual loan disbursement',
                'status' => 'active',
                'approved_at' => now(),
                'approved_by' => Auth::id(),
                'disbursed_at' => now(),
                'disbursed_by' => Auth::id(),
            ]);

            // No payment schedule needed - flexible repayment within 24 months

            // Create a transaction record for tracking in recent transactions
            \App\Models\Transaction::create([
                'user_id' => $user->id,
                'type' => 'loan_disbursement',
                'amount' => $amount,
                'description' => $description,
                'reference' => 'MAN-LOAN-' . date('YmdHis') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'status' => 'completed',
                'transaction_date' => now(),
                'group_id' => $transactionGroup->id,
            ]);

            // Create a transaction record for the interest
            \App\Models\Transaction::create([
                'user_id' => $user->id,
                'type' => 'loan_interest',
                'amount' => $interestAmount,
                'description' => "Interest for loan {$loan->loan_number} (10%)",
                'reference' => 'MAN-INT-' . date('YmdHis') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'status' => 'completed',
                'transaction_date' => now(),
                'group_id' => $transactionGroup->id,
            ]);

            return ['message' => "Loan of ₦" . number_format($amount, 2) . " disbursed successfully with ₦" . number_format($interestAmount, 2) . " interest. Loan Number: {$loan->loan_number}. Flexible repayment within 24 months."];
        } else {
            // Loan repayment with cascading logic (oldest loans first)
            return $this->processCascadingLoanRepayment($user, $amount, $description, $transactionGroup);
        }
    }

    /**
     * Process loan interest transaction
     */
    private function processLoanInterestTransaction(User $user, $operation, $amount, $description, $transactionGroup)
    {
        // Only interest payments are allowed (subtraction)
        if ($operation !== 'subtraction') {
            throw new \Exception('Only interest payments are supported for loan interest transactions.');
        }

        // Find the active loan with the most remaining interest to pay
        $activeLoans = $user->loans()->where('status', 'active')->get();
        $activeLoan = null;
        $maxRemainingInterest = 0;

        foreach ($activeLoans as $loan) {
            $storedRate = $loan->interest_rate ?? 0.10;
            $interestRate = $storedRate > 1 ? $storedRate / 100 : $storedRate;
            $interestDue = $loan->amount * $interestRate;
            $interestPaid = $loan->payments()
                ->where('status', 'paid')
                ->where('notes', 'LIKE', '%Interest Payment%')
                ->sum('amount');
            $remainingInterest = max(0, $interestDue - $interestPaid);

            if ($remainingInterest > $maxRemainingInterest) {
                $maxRemainingInterest = $remainingInterest;
                $activeLoan = $loan;
            }
        }

        if (!$activeLoan) {
            throw ValidationException::withMessages(['user_id' => 'Member has no active loan for interest payment.']);
        }

        if (!$activeLoan) {
            throw ValidationException::withMessages(['user_id' => 'Member has no active loan for interest payment.']);
        }

        // Interest payment (separate from principal - does not reduce loan balance)
        LoanPayment::create([
            'loan_id' => $activeLoan->id,
            'amount' => $amount,
            'payment_date' => now(),
            'due_date' => now(),
            'status' => 'paid',
            'payment_method' => 'manual',
            'notes' => 'Interest Payment',
            'group_id' => $transactionGroup->id,
        ]);

        // Create a transaction record for tracking in recent transactions
        \App\Models\Transaction::create([
            'user_id' => $user->id,
            'type' => 'loan_interest',
            'amount' => $amount,
            'description' => $description,
            'reference' => 'MAN-INT-' . date('YmdHis') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
            'status' => 'completed',
            'transaction_date' => now(),
            'group_id' => $transactionGroup->id,
        ]);

        // Interest payments do not reduce the principal balance
        // They are tracked separately for accounting purposes

        // Update group totals
        $transactionGroup->increment('total_amount', $amount);
        $transactionGroup->increment('total_records', 1);

        Log::info("Interest payment processed for loan {$activeLoan->loan_number}: ₦{$amount}");

        $message = "Interest payment of ₦" . number_format($amount, 2) . " recorded successfully. Loan principal balance remains: ₦" . number_format($activeLoan->remaining_balance, 2);

        return ['message' => $message];
    }

    /**
     * Process loan disbursement transaction
     */
    private function processLoanDisbursementTransaction(User $user, $operation, $amount, $description, $customTerm = null, $transactionGroup)
    {
        if ($operation !== 'addition') {
            throw new \Exception('Loan disbursement can only be addition operation.');
        }

        // Calculate loan with 10% interest, no fixed term
        $principal = $amount;
        $interestRate = 0.10; // 10% interest
        $interestAmount = $principal * $interestRate;
        $totalPayment = $principal + $interestAmount;

        $loan = Loan::create([
            'user_id' => $user->id,
            'loan_number' => Loan::generateLoanNumber(),
            'amount' => $principal,
            'interest_rate' => 10.0, // Store as percentage
            'term_months' => 0, // No fixed term
            'monthly_payment' => 0, // No fixed monthly payment
            'total_payment' => round($totalPayment, 2),
            'remaining_balance' => round($principal, 2), // Only principal, interest tracked separately
            'purpose' => $description ?: 'Manual loan disbursement',
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'disbursed_at' => now(),
            'disbursed_by' => Auth::id(),
        ]);

        // No payment schedule needed - flexible repayment within 24 months

        // Create a transaction record for tracking in recent transactions
        \App\Models\Transaction::create([
            'user_id' => $user->id,
            'type' => 'loan_disbursement',
            'amount' => $amount,
            'description' => $description,
            'reference' => 'MAN-LOAN-' . date('YmdHis') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
            'status' => 'completed',
            'transaction_date' => now(),
            'group_id' => $transactionGroup->id,
        ]);

        // Create a transaction record for the interest
        \App\Models\Transaction::create([
            'user_id' => $user->id,
            'type' => 'loan_interest',
            'amount' => $interestAmount,
            'description' => "Interest for loan {$loan->loan_number} (10%)",
            'reference' => 'MAN-INT-' . date('YmdHis') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
            'status' => 'completed',
            'transaction_date' => now(),
            'group_id' => $transactionGroup->id,
        ]);

        // Update group totals
        $transactionGroup->increment('total_amount', $amount);
        $transactionGroup->increment('total_records', 1);

        return ['message' => "Loan of ₦" . number_format($amount, 2) . " disbursed successfully with ₦" . number_format($interestAmount, 2) . " interest. Loan Number: {$loan->loan_number}. Flexible repayment within 24 months."];
    }

    /**
     * Process cascading loan repayment (oldest loans first)
     */
    private function processCascadingLoanRepayment(User $user, $amount, $description, $transactionGroup)
    {
        $remainingAmount = $amount;
        $activeLoans = $user->loans()->where('status', 'active')->orderBy('created_at', 'asc')->get();
        $paymentsProcessed = [];

        foreach ($activeLoans as $loan) {
            if ($remainingAmount <= 0) break;

            // Calculate remaining principal for this loan
            $principalPayments = $loan->payments()
                ->where('status', 'paid')
                ->where('notes', 'LIKE', '%Principal Repayment%')
                ->sum('amount');

            $remainingPrincipal = $loan->amount - $principalPayments;

            if ($remainingPrincipal > 0) {
                $paymentAmount = min($remainingAmount, $remainingPrincipal);

                // Create loan payment record
                LoanPayment::create([
                    'loan_id' => $loan->id,
                    'amount' => $paymentAmount,
                    'payment_date' => now(),
                    'due_date' => now(),
                    'status' => 'paid',
                    'payment_method' => 'manual',
                    'notes' => $description . ' - Principal Repayment',
                    'group_id' => $transactionGroup->id,
                ]);

                $remainingAmount -= $paymentAmount;
                $paymentsProcessed[] = [
                    'loan_number' => $loan->loan_number,
                    'amount' => $paymentAmount,
                    'remaining_principal' => $remainingPrincipal - $paymentAmount
                ];

                // Update loan's remaining balance
                $loan->remaining_balance -= $paymentAmount;
                $loan->save();

                // Check if loan principal is fully paid
                if (($remainingPrincipal - $paymentAmount) <= 0) {
                    // Check if interest is also fully paid
                    $storedRate = $loan->interest_rate ?? 0.10;
                    $interestRate = $storedRate > 1 ? $storedRate / 100 : $storedRate;
                    $interestDue = $loan->amount * $interestRate;
                    $interestPayments = $loan->payments()
                        ->where('status', 'paid')
                        ->where('notes', 'LIKE', '%Interest Payment%')
                        ->sum('amount');

                    if ($interestPayments >= $interestDue) {
                        $loan->update([
                            'status' => 'completed',
                            'completed_at' => now()
                        ]);
                    }
                }
            }
        }

        // Create a transaction record for tracking
        \App\Models\Transaction::create([
            'user_id' => $user->id,
            'type' => 'loan_repayment',
            'amount' => $amount,
            'description' => $description,
            'reference' => 'MAN-LOAN-REPAY-' . date('YmdHis') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
            'status' => 'completed',
            'transaction_date' => now(),
            'group_id' => $transactionGroup->id,
        ]);

        // Update group totals
        $transactionGroup->increment('total_amount', $amount);
        $transactionGroup->increment('total_records', 1);

        $message = "Principal repayment of ₦" . number_format($amount, 2) . " processed across " . count($paymentsProcessed) . " loan(s).";

        return ['message' => $message, 'payments' => $paymentsProcessed];
    }



    /**
     * Process commodity transaction
     */
    private function processCommodityTransaction(User $user, $commodityType, $operation, $amount, $description, $transactionGroup)
    {
        $type = $operation === 'addition' ? 'credit' : 'debit';

        CommodityTransaction::create([
            'user_id' => $user->id,
            'commodity_type' => $commodityType,
            'amount' => $amount,
            'type' => $type,
            'description' => $description,
            'transaction_date' => now(),
            'processed_by' => Auth::id(),
            'group_id' => $transactionGroup->id,
        ]);

        // Update group totals
        $transactionGroup->increment('total_amount', $amount);
        $transactionGroup->increment('total_records', 1);

        // Update user commodity balance
        $userCommodity = UserCommodity::where('user_id', $user->id)
            ->where('commodity_name', $commodityType)
            ->first();

        if ($userCommodity) {
            if ($operation === 'addition') {
                $userCommodity->increment('balance', $amount);
            } else {
                $userCommodity->decrement('balance', $amount);
            }
        } else {
            UserCommodity::create([
                'user_id' => $user->id,
                'commodity_name' => $commodityType,
                'balance' => $operation === 'addition' ? $amount : -$amount,
            ]);
        }

        $operationText = $operation === 'addition' ? 'issued on credit' : 'repayment recorded';
        return ['message' => ucfirst($commodityType) . " commodity of ₦" . number_format($amount, 2) . " {$operationText} successfully."];
    }

    /**
     * Process electronics transaction
     */
    private function processElectronicsTransaction(User $user, $operation, $amount, $description, $transactionGroup)
    {
        if ($operation === 'addition') {
            // Electronics issued on credit
            Electronics::create([
                'user_id' => $user->id,
                'electronics_name' => 'Manual Electronics Issue',
                'category' => 'general',
                'price' => $amount,
                'quantity' => 1,
                'total_amount' => $amount,
                'total_paid' => 0,
                'remaining_balance' => $amount,
                'status' => 'active',
                'description' => $description,
                'processed_by' => Auth::id(),
            ]);

            // Create a transaction record for tracking
            \App\Models\Transaction::create([
                'user_id' => $user->id,
                'type' => 'electronics',
                'amount' => $amount,
                'description' => $description,
                'reference' => 'MAN-ELEC-' . date('YmdHis') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'status' => 'completed',
                'transaction_date' => now(),
                'group_id' => $transactionGroup->id,
            ]);

            // Update group totals
            $transactionGroup->increment('total_amount', $amount);
            $transactionGroup->increment('total_records', 1);

            return ['message' => "Electronics of ₦" . number_format($amount, 2) . " issued on credit successfully."];
        } else {
            // Electronics repayment
            $electronics = Electronics::where('user_id', $user->id)
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->first();

            if ($electronics) {
                $electronics->total_paid = ($electronics->total_paid ?? 0) + $amount;
                $electronics->remaining_balance = max(0, $electronics->total_amount - $electronics->total_paid);

                if ($electronics->remaining_balance <= 0) {
                    $electronics->status = 'paid';
                }

                $electronics->save();

                // Create a transaction record for tracking
                \App\Models\Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'electronics_repayment',
                    'amount' => $amount,
                    'description' => $description,
                    'reference' => 'MAN-ELEC-REPAY-' . date('YmdHis') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                    'status' => 'completed',
                    'transaction_date' => now(),
                    'group_id' => $transactionGroup->id,
                ]);

                // Update group totals
                $transactionGroup->increment('total_amount', $amount);
                $transactionGroup->increment('total_records', 1);
            }

            return ['message' => "Electronics repayment of ₦" . number_format($amount, 2) . " recorded successfully."];
        }
    }

    /**
     * Show bulk transaction upload form
     */
    public function bulkCreate()
    {
        return view('admin.manual_transactions.bulk');
    }

    /**
     * Process bulk transaction upload
     */
    public function bulkStore(Request $request)
    {


        // Check if file is uploaded
        if (!$request->hasFile('excel_file')) {
            return redirect()->back()
                ->with('error', 'Please select a file to upload.')
                ->withInput();
        }

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
            'transaction_type' => 'required|in:entrance,shares,savings,loan_repay,loan_interest,essential,non_essential,electronics',
            'operation' => 'required|in:addition,subtraction',
            'description' => 'required|string|max:255',
        ]);

        try {
            // Store the file temporarily
            $file = $request->file('excel_file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('temp', Str::random(40) . '.' . $file->getClientOriginalExtension());
            $fullPath = Storage::path($filePath);

            // Verify file exists
            if (!file_exists($fullPath)) {
                return redirect()->back()
                    ->with('error', 'Uploaded file could not be found. Please try again.')
                    ->withInput();
            }

            // Process the file
            $rows = [];
            $extension = strtolower($file->getClientOriginalExtension());

            if ($extension === 'csv') {
                $fileContent = file_get_contents($fullPath);
                $lines = explode("\n", $fileContent);
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (!empty($line)) {
                        // Try comma first, then tab if comma doesn't work
                        $parsed = str_getcsv($line);
                        if (count($parsed) === 1 && strpos($line, "\t") !== false) {
                            // If only one column and contains tabs, try tab-separated
                            $parsed = explode("\t", $line);
                            $parsed = array_map('trim', $parsed);
                        }
                        $rows[] = $parsed;
                    }
                }
            } else {
                $spreadsheet = IOFactory::load($fullPath);
                $worksheet = $spreadsheet->getActiveSheet();
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 1; $row <= $highestRow; $row++) {
                    $rowData = [];
                    for ($col = 'A'; $col <= $highestColumn; $col++) {
                        $cellValue = $worksheet->getCell($col . $row)->getCalculatedValue();
                        $rowData[] = $cellValue;
                    }
                    $rows[] = $rowData;
                }
            }

            // Remove header row
            $headers = array_shift($rows);

            Log::info('File parsed', [
                'total_rows' => count($rows),
                'headers_found' => $headers,
            ]);

            // Validate headers - make it more flexible
            $memberNumberIndex = null;
            $amountIndex = null;
            $descriptionIndex = null;

            foreach ($headers as $index => $header) {
                $header = trim(strtolower($header));
                if (strpos($header, 'member') !== false && strpos($header, 'number') !== false) {
                    $memberNumberIndex = $index;
                } elseif (strpos($header, 'amount') !== false) {
                    $amountIndex = $index;
                } elseif (strpos($header, 'description') !== false) {
                    $descriptionIndex = $index;
                }
            }

            if ($memberNumberIndex === null || $amountIndex === null) {
                Log::error('Invalid headers', ['headers' => $headers]);
                return redirect()->back()
                    ->with('error', 'Invalid file format. Please ensure the file has columns containing "member number" and "amount". Found headers: ' . implode(', ', $headers))
                    ->withInput();
            }

            Log::info('Columns mapped', [
                'member_col' => $memberNumberIndex,
                'amount_col' => $amountIndex,
                'desc_col' => $descriptionIndex,
            ]);

            // Process transactions
            $processed = 0;
            $errors = [];
            $transactionGroupService = new TransactionGroupService();
            $groupTitle = 'Bulk ' . ucfirst($request->transaction_type) . ' Transactions - ' . now()->format('Y-m-d H:i:s');
            $transactionGroup = $transactionGroupService->createManualTransactionGroup($groupTitle, Auth::id());

            Log::info('Starting bulk transaction processing', [
                'total_rows' => count($rows),
                'transaction_type' => $request->transaction_type,
                'operation' => $request->operation,
                'group_id' => $transactionGroup->id,
            ]);

            DB::beginTransaction();

            try {
                foreach ($rows as $rowIndex => $row) {
                    $rowNumber = $rowIndex + 2; // +2 because we removed header and arrays are 0-indexed

                    // Skip empty rows
                    if (empty($row[$memberNumberIndex]) && empty($row[$amountIndex])) {
                        continue;
                    }

                    try {
                        $memberNumber = trim($row[$memberNumberIndex]);
                        $amount = trim($row[$amountIndex]);
                        $rowDescription = $descriptionIndex !== false ? trim($row[$descriptionIndex]) : $request->description;

                        Log::debug('Processing row', [
                            'row' => $rowNumber,
                            'member' => $memberNumber,
                            'amount' => $amount,
                        ]);

                        // Validate member number
                        if (empty($memberNumber)) {
                            $errors[] = "Row {$rowNumber}: Member number is required";
                            continue;
                        }

                        // Find user by member number
                        $user = User::whereHas('member', function($query) use ($memberNumber) {
                            $query->where('member_number', $memberNumber);
                        })->first();

                        Log::debug('User lookup', [
                            'member' => $memberNumber,
                            'found' => $user ? 'yes' : 'no',
                        ]);

                        if (!$user) {
                            // Try case-insensitive search
                            $user = User::whereHas('member', function($query) use ($memberNumber) {
                                $query->whereRaw('LOWER(member_number) = ?', [strtolower($memberNumber)]);
                            })->first();

                            Log::debug('Case-insensitive lookup', [
                                'found' => $user ? 'yes' : 'no',
                            ]);
                        }

                        if (!$user) {
                            // Try partial match search
                            $user = User::whereHas('member', function($query) use ($memberNumber) {
                                $query->where('member_number', 'LIKE', '%' . $memberNumber . '%');
                            })->first();

                            Log::info('Partial match user lookup result', [
                                'user_found' => $user ? 'yes' : 'no',
                            ]);
                        }

                        if (!$user) {
                            // Log all members that contain parts of the member number
                            $possibleMembers = User::whereHas('member', function($query) use ($memberNumber) {
                                $parts = explode('/', $memberNumber);
                                foreach ($parts as $part) {
                                    if (!empty($part)) {
                                        $query->orWhere('member_number', 'LIKE', '%' . $part . '%');
                                    }
                                }
                            })->with('member')->take(10)->get();

                            Log::info('Possible member matches', [
                                'searched_for' => $memberNumber,
                                'found_members' => $possibleMembers->map(function($u) {
                                    return [
                                        'id' => $u->id,
                                        'name' => $u->name,
                                        'member_number' => $u->member->member_number,
                                    ];
                                })->toArray(),
                            ]);
                        }

                        if (!$user) {
                            $errors[] = "Row {$rowNumber}: Member with number '{$memberNumber}' not found";
                            continue;
                        }

                        // Validate amount
                        if (!is_numeric($amount) || $amount <= 0) {
                            $errors[] = "Row {$rowNumber}: Invalid amount '{$amount}'";
                            continue;
                        }

                        // Process the transaction
                        $transactionData = [
                            'user_id' => $user->id,
                            'transaction_type' => $request->transaction_type,
                            'operation' => $request->operation,
                            'amount' => $amount,
                            'description' => $rowDescription ?: $request->description,
                        ];

                        $this->processTransaction(new Request($transactionData), $user, $transactionGroup);
                        $processed++;

                        Log::info('Transaction processed successfully', [
                            'row_number' => $rowNumber,
                            'user_id' => $user->id,
                            'amount' => $amount,
                        ]);

                    } catch (\Exception $e) {
                        Log::error('Transaction processing failed', [
                            'row_number' => $rowNumber,
                            'error' => $e->getMessage(),
                            'user_id' => $user ? $user->id : null,
                        ]);
                        $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                    }
                }

                Log::info('Bulk processing completed', [
                    'processed' => $processed,
                    'errors_count' => count($errors),
                    'errors' => $errors,
                ]);

                if ($processed > 0) {
                    $transactionGroupService->completeGroup($transactionGroup);
                    DB::commit();

                    $message = "Bulk transactions processed successfully. {$processed} transactions completed.";
                    if (!empty($errors)) {
                        $message .= " However, " . count($errors) . " errors occurred: " . implode('; ', array_slice($errors, 0, 5));
                        if (count($errors) > 5) {
                            $message .= " (and " . (count($errors) - 5) . " more)";
                        }
                    }

                    return redirect()->route('admin.manual_transactions.index')
                        ->with('success', $message);
                } else {
                    DB::rollback();
                    $errorMessage = 'No transactions were processed successfully.';
                    if (!empty($errors)) {
                        $errorMessage .= ' Errors: ' . implode('; ', array_slice($errors, 0, 10));
                    }

                    return redirect()->back()
                        ->with('error', $errorMessage)
                        ->withInput();
                }

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Bulk transaction upload failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'transaction_type' => $request->transaction_type,
            ]);

            return redirect()->back()
                ->with('error', 'Bulk upload failed: ' . $e->getMessage())
                ->withInput();
        } finally {
            // Clean up temp file
            if (isset($filePath) && Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
        }
    }

    /**
     * Download bulk transaction template
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="manual_transaction_template.xlsx"',
        ];

        // Create template data - approximately 10 sample records
        $templateData = [
            ['Member Number', 'Amount', 'Description'],
            ['MB001', '1000.00', 'Sample transaction description'],
            ['MB002', '2500.50', 'Another sample transaction'],
            ['MB003', '750.25', 'Monthly contribution payment'],
            ['MB004', '1500.00', 'Loan repayment installment'],
            ['MB005', '500.50', 'Share purchase'],
            ['MB006', '2000.00', 'Savings deposit'],
            ['MB007', '300.75', 'Interest payment'],
            ['MB008', '1200.00', 'Commodity purchase'],
            ['MB009', '850.25', 'Electronics installment'],
            ['MB010', '600.00', 'Miscellaneous fee'],
            ['', '', 'Add more rows as needed...'],
        ];

        // Create a simple CSV for now (can be enhanced to Excel later)
        $csvContent = '';
        foreach ($templateData as $row) {
            $csvContent .= implode(',', $row) . "\n";
        }

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="manual_transaction_template.csv"',
        ]);
    }
}

