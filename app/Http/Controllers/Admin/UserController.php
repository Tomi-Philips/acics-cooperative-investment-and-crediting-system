<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Loan;
use App\Models\Saving;
use App\Models\Share;
use App\Models\User;
use App\Models\Member;
use App\Models\ShareTransaction;
use App\Models\SavingTransaction;
use App\Models\LoanPayment;
use App\Models\Transaction;
use App\Models\CommodityTransaction;
use App\Models\UserCommodity;
use App\Services\FinancialCalculationService;
use App\Services\TransactionGroupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get query parameters for filtering and sorting
        $search = $request->input('search');
        $role = $request->input('role', 'all');
        $sort = $request->input('sort', 'newest');
        $perPage = $request->input('per_page', 10);

        // Start building the query
        $query = User::query();

        // Apply search filter if provided
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('member_number', 'like', "%{$search}%");
            });
        }

        // Apply role filter if provided and not 'all'
        if ($role !== 'all') {
            $query->where('role', $role);
        }

        // Apply sorting
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        // Get paginated results
        $users = $query->paginate($perPage);

        // Get counts for different roles
        $adminCount = User::where('role', 'admin')->count();
        $memberCount = User::where('role', 'member')->count();

        return view('admin.users.all_user', [
            'users' => $users,
            'adminCount' => $adminCount,
            'memberCount' => $memberCount,
            'search' => $search,
            'role' => $role,
            'sort' => $sort,
        ]);
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Get all departments for the dropdown
        $departments = Department::orderBy('title')->get();

        // Get recent user bulk uploads for display (similar to MonthlyUpload pattern)
        $recentUploads = \App\Models\UserBulkUpload::with('uploader')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.users.add_new_user', [
            'departments' => $departments,
            'recentUploads' => $recentUploads,
        ]);
    }

    /**
     * Show the form for creating new users with financial records.
     *
     * @return \Illuminate\View\View
     */
    public function createFinances()
    {
        // Get all departments for the dropdown
        $departments = Department::orderBy('title')->get();

        return view('admin.users.add_finances', [
            'departments' => $departments,
        ]);
    }

    /**
     * Store a new user with financial records.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeFinances(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'member_number' => 'nullable|string|max:20|unique:users',
            'department_id' => 'required|exists:departments,id',
            'role' => 'required|in:admin,member',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'joined_at' => 'required|date|before_or_equal:today',
            'entrance_fee_paid' => 'required|boolean',
            'shares' => 'nullable|numeric|min:0',
            'savings' => 'nullable|numeric|min:0',
            'loan_amount' => 'nullable|numeric|min:0',
            'electronics_amount' => 'nullable|numeric|min:0',
            'essential_commodity_amount' => 'nullable|numeric|min:0',
            'non_essential_commodity_amount' => 'nullable|numeric|min:0',
            'loan_interest_amount' => 'nullable|numeric|min:0',
        ]);

        // Custom validation: User must have either email OR member number
        if (empty($request->email) && empty($request->member_number)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'User must have either an email address or member number (or both).');
        }

        // Business rule validation: Maximum share contribution limit
        $maxShareContribution = config('business_rules.shares.maximum_contribution', 10000);
        if ($request->shares > $maxShareContribution) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Share amount (' . number_format($request->shares, 2) . ') cannot exceed the maximum share contribution limit of ₦' . number_format($maxShareContribution, 2) . '.');
        }

        // Business rule validation: Shares should not exceed total general amount
        $totalGeneralAmount = ($request->savings ?? 0) + ($request->loan_amount ?? 0) +
                             ($request->electronics_amount ?? 0) + ($request->essential_commodity_amount ?? 0) +
                             ($request->non_essential_commodity_amount ?? 0) + ($request->loan_interest_amount ?? 0);

        if ($request->shares > 0 && $totalGeneralAmount > 0 && $request->shares > $totalGeneralAmount) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Share amount (' . number_format($request->shares, 2) . ') cannot exceed the total general amount (' . number_format($totalGeneralAmount, 2) . ').');
        }

        try {
            DB::beginTransaction();

            // Create user data array
            $userData = [
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'member_number' => $request->member_number ? strtoupper($request->member_number) : null,
                'department_id' => $request->department_id,
                'status' => 'active',
                'password_change_required' => false,
            ];

            // Add email and verification if provided
            if (!empty($request->email)) {
                $userData['email'] = $request->email;
                $userData['email_verified_at'] = now();
            }

            // Create the user
            $user = User::create($userData);

            // Create member profile
            $user->member()->create([
                'member_number' => $request->member_number ? strtoupper($request->member_number) : null,
                'status' => 'active',
                'joined_at' => $request->joined_at,
                'entrance_fee_paid' => $request->entrance_fee_paid,
            ]);

            $adminId = Auth::id();

            // Create share transaction if amount > 0
            if ($request->shares > 0) {
                ShareTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'credit',
                    'amount' => $request->shares,
                    'description' => 'Financial record added by admin',
                    'transaction_date' => now(),
                ]);
            }

            // Create saving transaction if amount > 0
            if ($request->savings > 0) {
                SavingTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'credit',
                    'amount' => $request->savings,
                    'description' => 'Financial record added by admin',
                    'transaction_date' => now(),
                ]);
            }

            // Create loan if amount > 0
            if ($request->loan_amount > 0) {
                $user->loans()->create([
                    'loan_number' => \App\Models\Loan::generateLoanNumber(),
                    'amount' => $request->loan_amount, // Current outstanding balance
                    'interest_rate' => 0.10, // 10% interest rate for future calculations
                    'term_months' => 0, // No fixed term
                    'monthly_payment' => 0, // No fixed monthly payment
                    'total_payment' => $request->loan_amount, // Current balance (no automatic interest added)
                    'remaining_balance' => $request->loan_amount, // Current outstanding balance
                    'status' => 'active',
                    'purpose' => 'Financial record added by admin',
                    'repayment_method' => 'bursary_deduction',
                    'submitted_at' => now(),
                    'approved_at' => now(),
                    'approved_by' => $adminId,
                    'disbursed_at' => now(),
                    'disbursed_by' => $adminId,
                ]);
            }

            // Create electronics record if amount > 0
            if ($request->electronics_amount > 0) {
                $user->electronics()->create([
                    'amount' => $request->electronics_amount,
                    'description' => 'Financial record added by admin',
                    'transaction_type' => 'purchase',
                ]);
            }

            // Create essential commodity balance if amount > 0
            if ($request->essential_commodity_amount > 0) {
                // Create commodity transaction
                CommodityTransaction::create([
                    'user_id' => $user->id,
                    'commodity_type' => 'essential',
                    'amount' => $request->essential_commodity_amount,
                    'type' => 'credit',
                    'description' => 'Essential commodity financial record added by admin',
                    'transaction_date' => now(),
                ]);

                // Create or update user commodity balance
                UserCommodity::updateOrCreate([
                    'user_id' => $user->id,
                    'commodity_name' => 'essential',
                    'commodity_type' => 'essential'
                ], [
                    'balance' => $request->essential_commodity_amount
                ]);
            }

            // Create non-essential commodity balance if amount > 0
            if ($request->non_essential_commodity_amount > 0) {
                // Create commodity transaction
                CommodityTransaction::create([
                    'user_id' => $user->id,
                    'commodity_type' => 'non_essential',
                    'amount' => $request->non_essential_commodity_amount,
                    'type' => 'credit',
                    'description' => 'Non-essential commodity financial record added by admin',
                    'transaction_date' => now(),
                ]);

                // Create or update user commodity balance
                UserCommodity::updateOrCreate([
                    'user_id' => $user->id,
                    'commodity_name' => 'non_essential',
                    'commodity_type' => 'non_essential'
                ], [
                    'balance' => $request->non_essential_commodity_amount
                ]);
            }

            // Create loan interest payment if amount > 0
            if ($request->loan_interest_amount > 0) {
                // Create transaction record for loan interest payment
                Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'loan_interest',
                    'amount' => $request->loan_interest_amount,
                    'description' => 'Loan interest payment financial record added by admin',
                    'reference' => 'ADM-INT-' . date('Ymd') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT) . '-' . mt_rand(100, 999),
                    'status' => 'completed',
                ]);
            }

            DB::commit();

            return redirect()->route('admin.users.add_finances')
                ->with('success', 'User "' . $user->name . '" created successfully with financial records!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding financial records', [
                'admin_id' => Auth::id(),
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to add financial records. Please try again.');
        }
    }

    /**
     * Show the bulk upload form.
     *
     * @return \Illuminate\View\View
     */
    public function createBulkUpload()
    {
        // Get recent user bulk uploads for display
        $recentUploads = \App\Models\UserBulkUpload::with('uploader')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.users.bulk_upload', [
            'recentUploads' => $recentUploads,
        ]);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Log the request data for debugging
        Log::info('User creation request data', [
            'request_data' => $request->all()
        ]);

        // Check if this is an existing user with financial records
        $isExistingUser = $request->input('user_type') === 'existing' ||
                         ($request->has('existing_user') && $request->input('existing_user') == '1');

        // Log the existing user status
        Log::info('Existing user status', [
            'has_existing_user' => $request->has('existing_user'),
            'existing_user_value' => $request->input('existing_user'),
            'is_existing_user' => $isExistingUser,
            'user_type' => $request->input('user_type')
        ]);

        // Set up validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'member_number' => 'required|string|max:20|unique:users',
            'department_id' => 'required|exists:departments,id',
            'role' => 'required|in:admin,member',
            'joined_at' => 'required|date|before_or_equal:today',
            'entrance_fee_paid' => 'nullable',
            'loan_amount' => 'nullable|numeric|min:0',
            'share_amount' => 'nullable|numeric|min:0',
            'saving_amount' => 'nullable|numeric|min:0',
            'electronics_amount' => 'nullable|numeric|min:0',
            'commodity_amount' => 'nullable|numeric|min:0',
        ];

        // Password is required for new users, but optional for existing users
        if ($isExistingUser) {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        } else {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        // Validate the request data
        try {
            $validated = $request->validate($rules);
        } catch (ValidationException $e) {
            // Log validation errors
            Log::error('Validation errors', [
                'errors' => $e->errors()
            ]);
            throw $e;
        }

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Log the validated data
            Log::info('Validated data for user creation', [
                'validated_data' => $validated,
                'is_existing_user' => $isExistingUser
            ]);

            // Create user data array
            $userData = [
                'name' => $validated['name'],
                'member_number' => $validated['member_number'],
                'department_id' => $validated['department_id'],
                'role' => $validated['role'],
                'status' => 'active',
            ];

            // Add email only if provided
            if (!empty($validated['email'])) {
                $userData['email'] = $validated['email'];
                $userData['email_verified_at'] = now();
            }

            // Add password if provided
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            } else {
                // Generate a random password for existing users if none provided
                $userData['password'] = Hash::make(Str::random(12));
                $userData['password_change_required'] = true;
            }

            // Create the user
            $user = User::create($userData);

            // Create member record for all users with role 'member'
            if ($user->role === 'member') {
                // Set initial total loan amount if provided
                $totalLoanAmount = isset($validated['loan_amount']) && $validated['loan_amount'] > 0
                    ? $validated['loan_amount']
                    : 0;

                $user->member()->create([
                    'member_number' => $user->member_number,
                    'joined_at' => $validated['joined_at'],
                    'status' => 'active',
                    'entrance_fee_paid' => $request->has('entrance_fee_paid') ? true : false,
                    'total_loan_amount' => $totalLoanAmount,
                ]);
            }

            // Handle financial records if this is an existing user
            if ($isExistingUser) {
                // Create loan record if amount > 0
                if (isset($validated['loan_amount']) && $validated['loan_amount'] > 0) {
                    $amount = $validated['loan_amount']; // This is the current outstanding balance
                    $interestRate = 10; // 10% interest rate for future calculations

                    // Generate a loan number
                    $loanNumber = 'LN' . date('Ym') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

                    // Create a loan record with status 'active'
                    $loan = new Loan([
                        'loan_number' => $loanNumber,
                        'amount' => $amount, // Current outstanding balance
                        'term_months' => 0, // No fixed term
                        'interest_rate' => $interestRate,
                        'monthly_payment' => 0, // No fixed monthly payment
                        'total_payment' => $amount, // Current balance (no automatic interest added)
                        'status' => 'active',
                        'remaining_balance' => $amount, // Current outstanding balance
                        'approved_at' => now(),
                        'disbursed_at' => now(),
                        'purpose' => 'Initial loan balance',
                        'repayment_method' => 'Bursary Deduction',
                    ]);
                    $user->loans()->save($loan);

                    Log::info('Loan created for user', [
                        'user_id' => $user->id,
                        'loan_id' => $loan->id,
                        'loan_amount' => $validated['loan_amount']
                    ]);
                }

                // Create share record if amount > 0
                if (isset($validated['share_amount']) && $validated['share_amount'] > 0) {
                    $share = new Share([
                        'amount' => $validated['share_amount'],
                        'transaction_type' => 'purchase',
                        'payment_method' => 'cash',
                        'reference_number' => 'INIT-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                        'description' => 'Initial share contribution',
                        'processed_by' => Auth::id(),
                    ]);
                    $user->shares()->save($share);

                    Log::info('Share created for user', [
                        'user_id' => $user->id,
                        'share_id' => $share->id,
                        'share_amount' => $validated['share_amount']
                    ]);
                }

                // Create saving record if amount > 0
                if (isset($validated['saving_amount']) && $validated['saving_amount'] > 0) {
                    $saving = new Saving([
                        'amount' => $validated['saving_amount'],
                        'transaction_type' => 'deposit',
                        'payment_method' => 'cash',
                        'reference_number' => 'INIT-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                        'description' => 'Initial savings deposit',
                        'processed_by' => Auth::id(),
                    ]);
                    $user->savings()->save($saving);

                    Log::info('Saving created for user', [
                        'user_id' => $user->id,
                        'saving_id' => $saving->id,
                        'saving_amount' => $validated['saving_amount']
                    ]);
                }

                // Create commodity record if amount > 0
                if (isset($validated['commodity_amount']) && $validated['commodity_amount'] > 0) {
                    $commodity = new \App\Models\UserCommodity([
                        'commodity_name' => 'General Commodity',
                        'balance' => $validated['commodity_amount'],
                    ]);
                    $user->userCommodities()->save($commodity);

                    Log::info('Commodity balance created for user', [
                        'user_id' => $user->id,
                        'commodity_id' => $commodity->id,
                        'commodity_amount' => $validated['commodity_amount']
                    ]);
                }

                // Create electronics record if amount > 0
                if (isset($validated['electronics_amount']) && $validated['electronics_amount'] > 0) {
                    $electronics = new \App\Models\Electronics([
                        'amount' => $validated['electronics_amount'],
                        'description' => 'Initial electronics balance',
                        'processed_by' => Auth::id(),
                    ]);
                    $user->electronics()->save($electronics);

                    Log::info('Electronics created for user', [
                        'user_id' => $user->id,
                        'electronics_id' => $electronics->id,
                        'electronics_amount' => $validated['electronics_amount']
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();

            // Log the user creation
            Log::info('User created by admin', [
                'admin_id' => Auth::id(),
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'is_existing_user' => $isExistingUser,
            ]);

            return redirect()->route('admin.users.all')
                ->with('success', 'User created successfully!');
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            // Log the error with detailed information
            Log::error('Error creating user', [
                'admin_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating user: ' . $e->getMessage() . ' (Line: ' . $e->getLine() . ' in ' . basename($e->getFile()) . ')');
        }
    }

    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::with(['department', 'member'])->findOrFail($id);

        // Get financial data using transaction-based calculations
        $loanBalance = FinancialCalculationService::calculateLoanBalance($user);
        $savingsBalance = FinancialCalculationService::calculateSavingsBalance($user);
        $sharesBalance = FinancialCalculationService::calculateSharesBalance($user);
        $commodityBalance = FinancialCalculationService::calculateCommodityBalance($user);
        $electronicsBalance = FinancialCalculationService::calculateElectronicsBalance($user);

        // Additional breakdowns
        $essentialBalance = FinancialCalculationService::calculateEssentialCommodityBalance($user);
        $nonEssentialBalance = FinancialCalculationService::calculateNonEssentialCommodityBalance($user);
        $entrancePaid = (bool) optional($user->member)->entrance_fee_paid;

        // Calculate remaining loan interest owed (consistent with user dashboard)
        $loanInterestOwed = FinancialCalculationService::calculateLoanInterest($user);

        // Get recent transactions from all sources
        $recentTransactions = collect();

        // Get share transactions
        $shareTransactions = $user->shareTransactions()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function($transaction) {
                return (object)[
                    'id' => $transaction->id,
                    'type' => 'share_' . $transaction->type,
                    'amount' => $transaction->amount,
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at,
                    'date' => $transaction->created_at->format('M d, Y'),
                    'amount_formatted' => '₦' . number_format($transaction->amount, 2),
                    'amount_class' => $transaction->type === 'credit' ? 'text-green-600' : 'text-red-600',
                    'status' => 'completed',
                    'type_class' => $transaction->type === 'credit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800',
                    'charges' => 0,
                    'net_amount' => $transaction->amount
                ];
            });

        // Get saving transactions
        $savingTransactions = $user->savingTransactions()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function($transaction) {
                return (object)[
                    'id' => $transaction->id,
                    'type' => 'saving_' . $transaction->type,
                    'amount' => $transaction->amount,
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at,
                    'date' => $transaction->created_at->format('M d, Y'),
                    'amount_formatted' => '₦' . number_format($transaction->amount, 2),
                    'amount_class' => $transaction->type === 'credit' ? 'text-green-600' : 'text-red-600',
                    'status' => 'completed',
                    'type_class' => $transaction->type === 'credit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800',
                    'charges' => 0,
                    'net_amount' => $transaction->amount
                ];
            });

        // Get loan disbursements
        $loanDisbursements = $user->loans()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function($loan) {
                return (object)[
                    'id' => $loan->id,
                    'type' => 'loan_disbursement',
                    'amount' => $loan->amount,
                    'description' => 'Loan Disbursement',
                    'created_at' => $loan->created_at,
                    'date' => $loan->created_at->format('M d, Y'),
                    'amount_formatted' => '₦' . number_format($loan->amount, 2),
                    'amount_class' => 'text-red-600',
                    'status' => $loan->status,
                    'type_class' => 'bg-red-100 text-red-800',
                    'charges' => 0,
                    'net_amount' => $loan->amount
                ];
            });

        // Get loan payments
        $loanPayments = $user->loanPayments()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function($payment) {
                return (object)[
                    'id' => $payment->id,
                    'type' => 'loan_payment',
                    'amount' => $payment->amount,
                    'description' => $payment->notes ?? 'Loan Payment',
                    'created_at' => $payment->created_at,
                    'date' => $payment->created_at->format('M d, Y'),
                    'amount_formatted' => '₦' . number_format($payment->amount, 2),
                    'amount_class' => 'text-blue-600',
                    'status' => $payment->status,
                    'type_class' => 'bg-blue-100 text-blue-800',
                    'charges' => 0,
                    'net_amount' => $payment->amount
                ];
            });

        // Get regular transactions
        $regularTransactions = $user->transactions()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function($transaction) {
                return (object)[
                    'id' => $transaction->id,
                    'type' => $transaction->type,
                    'amount' => $transaction->amount,
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at,
                    'date' => $transaction->created_at->format('M d, Y'),
                    'amount_formatted' => '₦' . number_format($transaction->amount, 2),
                    'amount_class' => 'text-gray-600',
                    'status' => $transaction->status,
                    'type_class' => 'bg-gray-100 text-gray-800',
                    'charges' => $transaction->charges ?? 0,
                    'net_amount' => $transaction->net_amount ?? $transaction->amount
                ];
            });

        // Get commodity transactions
        $commodityTransactions = $user->commodityTransactions()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function($transaction) {
                return (object)[
                    'id' => $transaction->id,
                    'type' => 'commodity_' . $transaction->commodity_type,
                    'amount' => $transaction->amount,
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at,
                    'date' => $transaction->created_at->format('M d, Y'),
                    'amount_formatted' => '₦' . number_format($transaction->amount, 2),
                    'amount_class' => $transaction->type === 'credit' ? 'text-green-600' : 'text-red-600',
                    'status' => 'completed',
                    'type_class' => $transaction->commodity_type === 'essential' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800',
                    'charges' => 0,
                    'net_amount' => $transaction->amount
                ];
            });

        // Separate transactions by type for different tabs
        $savingsTransactions = $savingTransactions->sortByDesc('created_at')->take(10);
        $sharesTransactions = $shareTransactions->sortByDesc('created_at')->take(10);
        $loansTransactions = $loanDisbursements->merge($loanPayments)->sortByDesc('created_at')->take(10);
        $commoditiesTransactions = $commodityTransactions->sortByDesc('created_at')->take(10);

        // Merge all for general recent transactions (backward compatibility)
        $recentTransactions = $recentTransactions
            ->merge($shareTransactions)
            ->merge($savingTransactions)
            ->merge($loanDisbursements)
            ->merge($loanPayments)
            ->merge($regularTransactions)
            ->merge($commodityTransactions)
            ->sortByDesc('created_at')
            ->take(10);

        return view('admin.users.view_user', [
            'user' => $user,
            'loanBalance' => $loanBalance,
            'savingsBalance' => $savingsBalance,
            'sharesBalance' => $sharesBalance,
            'commodityBalance' => $commodityBalance,
            'electronicsBalance' => $electronicsBalance,
            'essentialBalance' => $essentialBalance,
            'nonEssentialBalance' => $nonEssentialBalance,
            'entrancePaid' => $entrancePaid,
            'loanInterestOwed' => $loanInterestOwed,
            'recentTransactions' => $recentTransactions,
            'savingsTransactions' => $savingsTransactions,
            'sharesTransactions' => $sharesTransactions,
            'loansTransactions' => $loansTransactions,
            'commoditiesTransactions' => $commoditiesTransactions,
        ]);
    }

    /**
     * Show the form for editing user's financial records.
     */
    public function editFinances($id)
    {
        $user = User::with(['member', 'shareTransactions', 'savingTransactions', 'loanPayments'])
            ->findOrFail($id);

        // Get current financial balances using transaction-based calculations
        $sharesBalance = FinancialCalculationService::calculateSharesBalance($user);
        $savingsBalance = FinancialCalculationService::calculateSavingsBalance($user);
        $loanBalance = FinancialCalculationService::calculateLoanBalance($user);

        // Get electronics and commodity balances (split essential vs non-essential)
        $electronicsBalance = FinancialCalculationService::calculateElectronicsBalance($user);
        $essentialBalance = $user->userCommodities()->where('commodity_name', 'essential')->sum('balance') ?? 0;
        $nonEssentialBalance = $user->userCommodities()->where('commodity_name', 'non_essential')->sum('balance') ?? 0;

        // Get entrance fee status
        $entrancePaid = $user->member ? $user->member->entrance_fee_paid : false;

        // Calculate total loan interest paid from both LoanPayments and Transactions
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

        return view('admin.users.edit_finances', [
            'user' => $user,
            'sharesBalance' => $sharesBalance,
            'savingsBalance' => $savingsBalance,
            'loanBalance' => $loanBalance,
            'essentialBalance' => $essentialBalance,
            'nonEssentialBalance' => $nonEssentialBalance,
            'electronicsBalance' => $electronicsBalance,
            'entrancePaid' => $entrancePaid,
            'loanInterestPaid' => $loanInterestPaid,
        ]);
    }

    /**
     * Update user's financial records with audit trail.
     */
    public function updateFinances(Request $request, $id)
    {
        $user = User::with('member')->findOrFail($id);

        $request->validate([
            'shares_balance' => 'required|numeric|min:0',
            'savings_balance' => 'required|numeric|min:0',
            'loan_balance' => 'required|numeric|min:0',
            'essential_balance' => 'required|numeric|min:0',
            'non_essential_balance' => 'required|numeric|min:0',
            'electronics_balance' => 'required|numeric|min:0',

            'loan_interest_payment' => 'nullable|numeric|min:0',
            'adjustment_reason' => 'required|string|max:500',
            'entrance_fee_paid' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            $adminId = Auth::id();
            $adjustmentDate = now();
            $reason = $request->adjustment_reason;

            // Create transaction group for this financial adjustment
            $transactionGroupService = new TransactionGroupService();
            $transactionGroup = $transactionGroupService->createGroup(
                'admin_approval',
                'Financial Adjustment - ' . $user->name,
                'Manual financial adjustment by admin: ' . $reason,
                [
                    'user_id' => $user->id,
                    'adjustment_reason' => $reason,
                    'admin_id' => $adminId
                ],
                $adminId
            );

            // Handle Entrance Fee toggle (status only with audit note)
            $beforeEntrance = (bool) optional($user->member)->entrance_fee_paid;
            $afterEntrance = $request->boolean('entrance_fee_paid');
            if ($beforeEntrance !== $afterEntrance) {
                // Ensure member profile exists
                if (!$user->member) {
                    $user->member()->create([
                        'member_number' => $user->member_number,
                        'status' => 'active',
                        'joined_at' => now(),
                        'entrance_fee_paid' => $afterEntrance,
                    ]);
                } else {
                    $user->member->update(['entrance_fee_paid' => $afterEntrance]);
                }

                // Record a zero-amount audit transaction in this group
                Transaction::create([
                    'user_id' => $user->id,
                    'type' => $afterEntrance ? 'entrance_fee_mark_paid' : 'entrance_fee_mark_unpaid',
                    'amount' => 0,
                    'description' => 'Entrance fee status changed by admin from ' . ($beforeEntrance ? 'PAID' : 'UNPAID') . ' to ' . ($afterEntrance ? 'PAID' : 'UNPAID') . ($reason ? ' | Reason: ' . $reason : ''),
                    'reference' => 'ENT-TGL-' . date('YmdHis') . '-' . Str::random(5),
                    'status' => 'completed',
                    'group_id' => $transactionGroup->id,
                ]);
            }

            // Update Shares - Use transaction-based current balance
            $currentShares = FinancialCalculationService::calculateSharesBalance($user);
            $newShares = $request->shares_balance;

            // Add share contribution limit validation
            $maxShareContribution = config('business_rules.shares.maximum_contribution', 10000);
            if ($newShares > $maxShareContribution) {
                throw new \Exception("Share amount cannot exceed the maximum contribution limit of ₦" . number_format($maxShareContribution, 2));
            }

            if ($currentShares != $newShares) {
                $difference = $newShares - $currentShares;

                // CRITICAL: Shares can only be increased, never decreased
                if ($difference < 0) {
                    Log::warning("Attempted to reduce shares for user {$user->id} from {$currentShares} to {$newShares}. Share reductions are not allowed.");
                    throw new \Exception("Share reductions are not allowed. Current balance: ₦" . number_format($currentShares, 2));
                } else {
                    // Only allow additions
                    ShareTransaction::create([
                        'user_id' => $user->id,
                        'amount' => $difference,
                        'type' => 'credit',
                        'description' => "Manual adjustment by admin: {$reason}",
                        'transaction_date' => $adjustmentDate,
                        'group_id' => $transactionGroup->id,
                    ]);

                    // Update member table for consistency (optional, since we use transaction-based calculations)
                    $user->member->update(['total_share_amount' => $newShares]);
                }
            }

            // Update Savings - Use transaction-based current balance
            $currentSavings = FinancialCalculationService::calculateSavingsBalance($user);
            $newSavings = $request->savings_balance;
            if ($currentSavings != $newSavings) {
                $difference = $newSavings - $currentSavings;
                $type = $difference > 0 ? 'credit' : 'debit';

                SavingTransaction::create([
                    'user_id' => $user->id,
                    'amount' => abs($difference),
                    'type' => $type,
                    'description' => "Manual adjustment by admin: {$reason}",
                    'transaction_date' => $adjustmentDate,
                    'group_id' => $transactionGroup->id,
                ]);

                // Update member table for consistency (optional, since we use transaction-based calculations)
                $user->member->update(['total_saving_amount' => $newSavings]);
            }

            // Common active loan reference for balance/interest adjustments
            $activeLoan = Loan::where('user_id', $user->id)
                ->whereIn('status', ['active', 'approved'])
                ->latest()
                ->first();

            // Update Loan Balance - Use transaction-based current balance
            $currentLoan = FinancialCalculationService::calculateLoanBalance($user);
            $newLoan = (float) $request->loan_balance;

            if ($currentLoan != $newLoan) {
                // Get all active/approved loans to handle cases where multiple exist (data inconsistency)
                $activeLoans = Loan::where('user_id', $user->id)
                    ->whereIn('status', ['active', 'approved'])
                    ->orderBy('created_at', 'asc') // Start with oldest
                    ->get();

                if ($activeLoans->count() > 0) {
                    $difference = $currentLoan - $newLoan;
                    
                    if ($difference > 0) {
                        // DECREASE balance: Apply repayments across active loans until target reached
                        $toReduce = abs($difference);
                        foreach ($activeLoans as $loan) {
                            if ($toReduce <= 0) break;
                            
                            // Calculate this specific loan's contribution to current total
                            $curPrincipalPayments = $loan->payments()
                                ->where('status', 'paid')
                                ->where(function($query) {
                                    $query->where('notes', 'LIKE', '%Principal Repayment%')
                                          ->orWhere('notes', 'LIKE', '%Principal Balance Adjustment%');
                                })
                                ->sum('amount');
                            $curLoanBal = max(0, $loan->amount - $curPrincipalPayments);
                            
                            $reduction = min($toReduce, $curLoanBal);
                            if ($reduction > 0) {
                                LoanPayment::create([
                                    'loan_id' => $loan->id,
                                    'amount' => $reduction,
                                    'payment_date' => $adjustmentDate,
                                    'due_date' => $adjustmentDate,
                                    'notes' => "Principal Repayment (Manual Adjustment: {$reason})",
                                    'status' => 'paid',
                                    'group_id' => $transactionGroup->id,
                                ]);
                                
                                $toReduce -= $reduction;
                                
                                // Sync remaining_balance column and close if 0
                                $remaining = max(0, $curLoanBal - $reduction);
                                $loan->update([
                                    'remaining_balance' => $remaining,
                                    'status' => $remaining <= 0 ? 'completed' : $loan->status,
                                    'completed_at' => $remaining <= 0 ? $adjustmentDate : $loan->completed_at
                                ]);
                            }
                        }
                        
                        // Only add an overflow record if there's still a reduction needed but loans were exhausted
                        if ($toReduce > 0) {
                             $latest = $activeLoans->last();
                             LoanPayment::create([
                                'loan_id' => $latest->id,
                                'amount' => $toReduce,
                                'payment_date' => $adjustmentDate,
                                'due_date' => $adjustmentDate,
                                'notes' => "Principal Repayment (Manual Adjustment Overflow: {$reason})",
                                'status' => 'paid',
                                'group_id' => $transactionGroup->id,
                            ]);
                            $latest->update(['remaining_balance' => 0, 'status' => 'completed', 'completed_at' => $adjustmentDate]);
                        }
                    } elseif ($difference < 0) {
                        // INCREASE balance: Adjust the latest active loan upwards
                        // Using the common $activeLoan reference defined above
                        $activeLoan->increment('amount', abs($difference));
                        $activeLoan->update(['remaining_balance' => $activeLoan->remaining_balance + abs($difference)]);
                    }
                } elseif ($newLoan > 0) {
                    // Create new loan if none exists
                    Loan::create([
                        'user_id' => $user->id,
                        'loan_number' => 'MANUAL-' . time(),
                        'amount' => $newLoan,
                        'remaining_balance' => $newLoan,
                        'interest_rate' => 10,
                        'term_months' => 0,
                        'monthly_payment' => 0,
                        'total_payment' => $newLoan * 1.1,
                        'status' => 'active',
                        'approved_at' => $adjustmentDate,
                        'disbursed_at' => $adjustmentDate,
                        'purpose' => "Manual adjustment by admin: {$reason}",
                    ]);
                }
            }

            // Handle Interest Balance Adjustment
            $currentInterest = FinancialCalculationService::calculateLoanInterest($user);
            $newInterest = (float) ($request->loan_interest_total ?? $currentInterest);

            if ($currentInterest != $newInterest) {
                $diffInterest = $newInterest - $currentInterest;
                
                if ($diffInterest > 0) {
                    // Add debt
                    Transaction::create([
                        'user_id' => $user->id,
                        'type' => 'loan_interest',
                        'amount' => abs($diffInterest),
                        'description' => "Interest Balance Adjustment (Manual Adjustment: {$reason})",
                        'reference' => 'INT-ADJ-' . time(),
                        'status' => 'completed',
                        'transaction_date' => $adjustmentDate,
                        'group_id' => $transactionGroup->id,
                    ]);
                } else {
                    // Record payment to reduce balance
                    // LoanPayment REQUIRES a loan_id, so we find the most recent one if no active one
                    $targetLoanId = $activeLoan ? $activeLoan->id : Loan::where('user_id', $user->id)->latest()->value('id');
                    
                    if ($targetLoanId) {
                        LoanPayment::create([
                            'user_id' => $user->id,
                            'loan_id' => $targetLoanId,
                            'amount' => abs($diffInterest),
                            'payment_date' => $adjustmentDate,
                            'due_date' => $adjustmentDate,
                            'notes' => "Interest Payment (Manual Adjustment: {$reason})",
                            'status' => 'paid',
                            'group_id' => $transactionGroup->id,
                        ]);
                    } else {
                        // If no loan ever existed, we create a negative interest transaction to reduce balance
                        Transaction::create([
                            'user_id' => $user->id,
                            'type' => 'loan_interest',
                            'amount' => -abs($diffInterest),
                            'description' => "Interest Balance Reduction (Manual Adjustment: {$reason})",
                            'reference' => 'INT-ADJ-NEG-' . time(),
                            'status' => 'completed',
                            'transaction_date' => $adjustmentDate,
                            'group_id' => $transactionGroup->id,
                        ]);
                    }
                }
            }

            // Update Essential Commodity - Use transaction-based current balance
            $essentialCommodity = UserCommodity::where('user_id', $user->id)
                ->where('commodity_name', 'essential')
                ->first();
            $currentEssential = $essentialCommodity ? $essentialCommodity->balance : 0;
            $newEssential = $request->essential_balance;

            if ($currentEssential != $newEssential) {
                $difference = $newEssential - $currentEssential;
                $type = $difference > 0 ? 'credit' : 'debit';

                CommodityTransaction::create([
                    'user_id' => $user->id,
                    'commodity_type' => 'essential',
                    'amount' => abs($difference),
                    'type' => $type,
                    'description' => "Manual adjustment by admin: {$reason}",
                    'transaction_date' => $adjustmentDate,
                    'group_id' => $transactionGroup->id,
                ]);

                // Also log a general transaction so it appears in the user's recent transactions
                \App\Models\Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'commodity_adjustment',
                    'amount' => abs($difference),
                    'description' => "Essential commodity adjustment by admin: {$reason}",
                    'reference' => 'ADJ-ESS-' . date('Ymd') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT) . '-' . mt_rand(100, 999),
                    'status' => 'completed',
                    'group_id' => $transactionGroup->id,
                ]);

                UserCommodity::updateOrCreate([
                    'user_id' => $user->id,
                    'commodity_name' => 'essential'
                ], [
                    'balance' => $newEssential
                ]);
            }

            // Update Non-essential Commodity - Use transaction-based current balance
            $nonEssentialCommodity = UserCommodity::where('user_id', $user->id)
                ->where('commodity_name', 'non_essential')
                ->first();
            $currentNonEssential = $nonEssentialCommodity ? $nonEssentialCommodity->balance : 0;
            $newNonEssential = $request->non_essential_balance;

            if ($currentNonEssential != $newNonEssential) {
                $difference = $newNonEssential - $currentNonEssential;
                $type = $difference > 0 ? 'credit' : 'debit';

                CommodityTransaction::create([
                    'user_id' => $user->id,
                    'commodity_type' => 'non_essential',
                    'amount' => abs($difference),
                    'type' => $type,
                    'description' => "Manual adjustment by admin: {$reason}",
                    'transaction_date' => $adjustmentDate,
                    'group_id' => $transactionGroup->id,
                ]);

                // Also log a general transaction so it appears in the user's recent transactions
                \App\Models\Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'commodity_adjustment',
                    'amount' => abs($difference),
                    'description' => "Non-essential commodity adjustment by admin: {$reason}",
                    'reference' => 'ADJ-NON-' . date('Ymd') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT) . '-' . mt_rand(100, 999),
                    'status' => 'completed',
                    'group_id' => $transactionGroup->id,
                ]);

                UserCommodity::updateOrCreate([
                    'user_id' => $user->id,
                    'commodity_name' => 'non_essential'
                ], [
                    'balance' => $newNonEssential
                ]);
            }

            // Update Electronics Balance - Use transaction-based current balance
            $electronicsBalance = FinancialCalculationService::calculateElectronicsBalance($user);
            $newElectronics = (float) $request->electronics_balance;

            if ($electronicsBalance != $newElectronics) {
                $difference = $newElectronics - $electronicsBalance;
                
                // If new > current, create a purchase record (increase debt)
                // If new < current, create a repayment record (decrease debt)
                \App\Models\Electronics::create([
                    'user_id' => $user->id,
                    'amount' => abs($difference),
                    'transaction_type' => $difference > 0 ? 'purchase' : 'repayment',
                    'description' => "Electronics Balance Adjustment (Manual: {$reason})",
                    'processed_by' => Auth::id(),
                    // Note: If you have a group_id or transaction_date col in electronics table, you should add it here.
                    // Checking existing columns in Electronics model view: user_id, amount, transaction_type, payment_method, reference_number, description, processed_by
                ]);

                // Also log a general transaction so it appears in the user's recent transactions
                \App\Models\Transaction::create([
                    'user_id' => $user->id,
                    'type' => $difference > 0 ? 'electronics' : 'electronics_repayment',
                    'amount' => abs($difference),
                    'description' => "Electronics balance adjustment by admin: {$reason}",
                    'reference' => 'ADJ-ELX-' . time(),
                    'status' => 'completed',
                    'group_id' => $transactionGroup->id,
                ]);
            }

            // Complete the transaction group
            $totalAmount = 0;
            $totalRecords = 0;

            // Calculate totals from all transactions in this group
            $savingTransactions = SavingTransaction::where('group_id', $transactionGroup->id)->get();
            $shareTransactions = ShareTransaction::where('group_id', $transactionGroup->id)->get();
            $commodityTransactions = CommodityTransaction::where('group_id', $transactionGroup->id)->get();
            $loanPayments = LoanPayment::where('group_id', $transactionGroup->id)->get();

            $totalAmount += $savingTransactions->sum('amount');
            $totalAmount += $shareTransactions->sum('amount');
            $totalAmount += $commodityTransactions->sum('amount');
            $totalAmount += $loanPayments->sum('amount');

            $totalRecords = $savingTransactions->count() + $shareTransactions->count() +
                           $commodityTransactions->count() + $loanPayments->count();

            // Update transaction group with totals and mark as completed
            $transactionGroup->update([
                'total_amount' => $totalAmount,
                'total_records' => $totalRecords,
                'status' => 'completed',
                'processed_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.users.edit_finances', $user->id)
                ->with('success', "Financial records updated successfully for {$user->name}. All changes have been logged for audit purposes.");

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to update financial records: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $departments = Department::orderBy('title')->get();

        return view('admin.users.edit_user', [
            'user' => $user,
            'departments' => $departments,
        ]);
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'member_number' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'department_id' => 'required|exists:departments,id',
            'role' => 'required|in:admin,member',
            'password' => 'nullable|string|min:8|confirmed',
            'electronics_amount' => 'nullable|numeric|min:0',
            'commodity_amount' => 'nullable|numeric|min:0',
        ]);

        // Update user data
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->member_number = $validated['member_number'];
        $user->department_id = $validated['department_id'];
        $user->role = $validated['role'];

        // Update password if provided
        if (isset($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Handle electronics amount update/creation
        if (isset($validated['electronics_amount'])) {
            $electronicsAmount = $validated['electronics_amount'];

            // Find existing electronics record
            $electronics = $user->electronics;

            if ($electronics) {
                // Update existing record
                $electronics->amount = $electronicsAmount;
                $electronics->save();

                Log::info('Electronics updated for user', [
                    'user_id' => $user->id,
                    'electronics_id' => $electronics->id,
                    'electronics_amount' => $electronicsAmount
                ]);
            } elseif ($electronicsAmount > 0) {
                // Create new record if amount is positive and no record exists
                $newElectronics = new \App\Models\Electronics([
                    'amount' => $electronicsAmount,
                    'description' => 'Initial electronics balance (updated)',
                    'processed_by' => Auth::id(),
                ]);
                $user->electronics()->save($newElectronics);

                Log::info('New Electronics record created for user during update', [
                    'user_id' => $user->id,
                    'electronics_id' => $newElectronics->id,
                    'electronics_amount' => $electronicsAmount
                ]);
            }
        }

        // Handle commodity amount update/creation
        if (isset($validated['commodity_amount'])) {
            $commodityAmount = $validated['commodity_amount'];

            // Find existing commodity record
            $commodity = $user->userCommodities;

            if ($commodity) {
                // Update existing record
                $commodity->balance = $commodityAmount;
                $commodity->save();

                Log::info('Commodity updated for user', [
                    'user_id' => $user->id,
                    'commodity_id' => $commodity->id,
                    'commodity_amount' => $commodityAmount
                ]);
            } elseif ($commodityAmount > 0) {
                // Create new record if amount is positive and no record exists
                $newCommodity = new \App\Models\UserCommodity([
                    'commodity_name' => 'General Commodity',
                    'balance' => $commodityAmount,
                ]);
                $user->userCommodities()->save($newCommodity);

                Log::info('New Commodity record created for user during update', [
                    'user_id' => $user->id,
                    'commodity_id' => $newCommodity->id,
                    'commodity_amount' => $commodityAmount
                ]);
            }
        }

        return redirect()->route('admin.users.all')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent the admin from deleting themselves
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account.');
        }

        // Prevent deleting the super admin
        if ($user->role === 'admin' && $user->id === 1) {
            return redirect()->back()
                ->with('error', 'You cannot delete the super admin account.');
        }

        // Delete the user
        Log::info('Deleting user', [
            'deleted_user_id' => $user->id,
            'deleted_user_email' => $user->email,
        ]);

        // Log the user deletion
        Log::info('User deleted by admin', [
            'admin_id' => Auth::id(),
            'deleted_user_id' => $user->id,
            'deleted_user_email' => $user->email,
        ]);

        $user->delete();

        return redirect()->route('admin.users.all')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Show the bulk upload form.
     *
     * @return \Illuminate\View\View
     */
    public function showBulkUpload()
    {
        $departments = Department::all();
        return view('admin.users.bulk_upload', compact('departments'));
    }

    /**
     * Process bulk upload of users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkUpload(Request $request)
    {
        // Log that the method was called
        Log::info('Bulk upload method called', [
            'user_id' => Auth::id(),
            'request_method' => $request->method(),
            'has_file' => $request->hasFile('excel_file'),
            'all_files' => $request->allFiles(),
            'all_input' => $request->all()
        ]);

        // Increase execution time and memory limit for bulk operations
        set_time_limit(300); // 5 minutes
        ini_set('memory_limit', '512M');

        // Optimize database connection for large operations
        DB::statement('SET SESSION wait_timeout = 600');
        DB::statement('SET SESSION interactive_timeout = 600');

        // Validate the uploaded file
        try {
            $request->validate([
                'excel_file' => 'required|file|max:10240', // 10MB max, more permissive MIME type validation
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Bulk upload validation failed', [
                'user_id' => Auth::id(),
                'errors' => $e->errors(),
                'request_data' => $request->all(),
                'file_info' => $request->hasFile('excel_file') ? [
                    'original_name' => $request->file('excel_file')->getClientOriginalName(),
                    'mime_type' => $request->file('excel_file')->getMimeType(),
                    'size' => $request->file('excel_file')->getSize(),
                    'extension' => $request->file('excel_file')->getClientOriginalExtension(),
                ] : null
            ]);
            return redirect()->route('admin.users.bulk_upload')
                ->withErrors($e->validator)
                ->withInput();
        }

        // Get the uploaded file
        $file = $request->file('excel_file');
        $fileName = $file->getClientOriginalName();
        $path = $file->store('temp');
        $fullPath = Storage::path($path);

        try {
            // Read file efficiently (supports both CSV and Excel)
            Log::info('Attempting to read uploaded file', [
                'user_id' => Auth::id(),
                'file_path' => $fullPath,
                'extension' => $file->getClientOriginalExtension(),
                'file_exists' => file_exists($fullPath),
                'file_size' => filesize($fullPath)
            ]);

            $csvData = $this->readUploadedFile($fullPath, $file->getClientOriginalExtension());

            Log::info('File reading completed', [
                'user_id' => Auth::id(),
                'data_count' => count($csvData),
                'first_row' => !empty($csvData) ? $csvData[0] : null
            ]);

            if (empty($csvData)) {
                return redirect()->route('admin.users.bulk_upload')
                    ->with('error', 'No valid data found in the uploaded file. Please check your CSV/Excel file format.');
            }

            // Create UserBulkUpload record to track this upload
            $bulkUpload = \App\Models\UserBulkUpload::create([
                'file_name' => $fileName,
                'file_path' => $path,
                'total_records' => count($csvData),
                'processed_records' => 0,
                'failed_records' => 0,
                'description' => 'User bulk upload from CSV file',
                'status' => 'processing',
                'uploaded_by' => Auth::id(),
                'upload_started_at' => now(),
            ]);

            // Pre-hash the default password once (major performance improvement)
            $defaultPassword = 'Password123';
            $hashedPassword = Hash::make($defaultPassword);

            // Initialize counters
            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            // Enhanced validation following Monthly MAB pattern
            $validationResults = $this->validateBulkUploadData($csvData, $fileName);

            // If there are validation issues, update bulk upload record and show detailed error page
            if ($validationResults['hasIssues']) {
                // Update bulk upload record with validation results
                $bulkUpload->update([
                    'status' => 'validation_failed',
                    'failed_records' => $validationResults['totalIssues'],
                    'processing_summary' => [
                        'validation_issues' => $validationResults['validationIssues'],
                        'total_records' => $validationResults['totalRecords'],
                        'valid_records' => $validationResults['validRecords'],
                        'success_rate' => $validationResults['successRate'],
                    ],
                    'upload_completed_at' => now(),
                ]);

                // Store minimal validation data to prevent MySQL session timeout
                $sessionId = uniqid('bulk_upload_');

                // Store only file path and essential validation results (no large CSV data)
                Session::put('bulk_upload_' . $sessionId, [
                    'file_path' => $path,
                    'bulk_upload_id' => $bulkUpload->id,
                    'total_csv_count' => count($csvData),
                    'validation_summary' => [
                        'totalRecords' => $validationResults['totalRecords'],
                        'validRecords' => $validationResults['validRecords'],
                        'totalIssues' => $validationResults['totalIssues'],
                        'successRate' => $validationResults['successRate'],
                    ],
                    'validation_issues' => $validationResults['validationIssues'],
                    'uploaded_at' => now(),
                ]);

                return redirect()->route('admin.users.bulk_upload_validation_errors', ['session_id' => $sessionId]);
            }

            // Create transaction group for this bulk upload
            $transactionGroupService = new TransactionGroupService();
            $transactionGroup = $transactionGroupService->createGroup(
                'user_bulk_upload',
                'User Bulk Upload - ' . $fileName,
                'User bulk upload from ' . $fileName . ' with financial transactions',
                [
                    'file_name' => $fileName,
                    'total_records' => count($csvData),
                    'bulk_upload_id' => $bulkUpload->id
                ],
                Auth::id()
            );

            // Process in chunks to avoid memory issues
            $chunkSize = 50;
            $chunks = array_chunk($csvData, $chunkSize);

            Log::info('Starting bulk user upload', [
                'admin_id' => Auth::id(),
                'total_rows' => count($csvData),
                'chunks' => count($chunks),
                'transaction_group_id' => $transactionGroup->id
            ]);

            foreach ($chunks as $chunkIndex => $chunk) {
                try {
                    DB::beginTransaction();

                    $chunkSuccess = 0;
                    $chunkErrors = [];

                    foreach ($chunk as $index => $row) {
                        $globalIndex = ($chunkIndex * $chunkSize) + $index;

                        if (empty($row[0])) continue;

                        try {
                            // Map and validate data
                            $userData = $this->mapCsvRowToUserData($row);
                            $this->validateUserData($userData);
                            $this->checkForDuplicates($userData);

                            // Create user
                            $userCreateData = [
                                'name' => $userData['name'],
                                'member_number' => $userData['member_number'],
                                'department_id' => $userData['department_id'],
                                'role' => 'member',
                                'password' => $hashedPassword, // Use pre-hashed password
                                'status' => 'active',
                                'password_change_required' => true,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];

                            if (!empty($userData['email'])) {
                                $userCreateData['email'] = $userData['email'];
                                $userCreateData['email_verified_at'] = now();
                            }

                            $user = User::create($userCreateData);

                            // Create member record with entrance fee status
                            $user->member()->create([
                                'member_number' => $userData['member_number'],
                                'status' => 'active',
                                'joined_at' => $userData['joined_at'],
                                'entrance_fee_paid' => $userData['entrance_fee_paid'], // Fix: Include entrance fee status
                                'total_electronics_amount' => 0,
                            ]);

                            // Create financial transactions using the new transaction-based system
                            $this->createUserTransactions($user, $userData, $defaultPassword, $transactionGroup->id);

                            $chunkSuccess++;

                        } catch (\Exception $e) {
                            $chunkErrors[] = 'Error on row ' . ($globalIndex + 2) . ': ' . $e->getMessage();
                        }
                    }

                    DB::commit();

                    $successCount += $chunkSuccess;
                    $errorCount += count($chunkErrors);
                    $errors = array_merge($errors, $chunkErrors);

                    Log::info("Processed chunk " . ($chunkIndex + 1) . "/" . count($chunks), [
                        'chunk_success' => $chunkSuccess,
                        'chunk_errors' => count($chunkErrors)
                    ]);

                } catch (\Exception $e) {
                    DB::rollBack();
                    $chunkErrorMessage = 'Error processing chunk ' . ($chunkIndex + 1) . ': ' . $e->getMessage();
                    $errors[] = $chunkErrorMessage;
                    $errorCount += count($chunk);
                }
            }

            // Complete the transaction group and calculate total amount
            $totalAmount = 0;

            // Get all transactions in this group to calculate total
            $savingTransactions = SavingTransaction::where('group_id', $transactionGroup->id)->get();
            $shareTransactions = ShareTransaction::where('group_id', $transactionGroup->id)->get();

            $totalAmount += $savingTransactions->sum('amount');
            $totalAmount += $shareTransactions->sum('amount');

            // Update transaction group with totals and mark as completed
            $transactionGroup->update([
                'total_amount' => $totalAmount,
                'total_records' => $successCount,
                'status' => 'completed',
                'processed_at' => now(),
            ]);

            // Update bulk upload record with results
            $bulkUpload->update([
                'processed_records' => $successCount,
                'failed_records' => $errorCount,
                'status' => $errorCount > 0 ? 'completed' : 'completed',
                'upload_completed_at' => now(),
                'processing_summary' => [
                    'total_records' => count($csvData),
                    'processed_records' => $successCount,
                    'failed_records' => $errorCount,
                    'processing_time' => now()->diffInSeconds($bulkUpload->upload_started_at),
                    'errors' => array_slice($errors, 0, 10), // Store first 10 errors for reference
                ],
            ]);

            // Delete temporary file
            Storage::delete($path);

            // Log completion
            Log::info('Bulk user upload completed', [
                'admin_id' => Auth::id(),
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'bulk_upload_id' => $bulkUpload->id
            ]);

            // Return results
            if ($errorCount > 0) {
                return redirect()->route('admin.users.bulk_upload_details', $bulkUpload->id)
                    ->with('success', "Bulk upload completed: {$successCount} users created, {$errorCount} errors occurred.");
            }

            return redirect()->route('admin.users.bulk_upload_details', $bulkUpload->id)
                ->with('success', "Bulk upload completed successfully! {$successCount} users created.");

        } catch (\Exception $e) {
            // Log the exception details
            Log::error('Exception during bulk user upload', [
                'admin_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'bulk_upload_id' => isset($bulkUpload) ? $bulkUpload->id : null
            ]);

            // Mark bulk upload as failed if it was created
            if (isset($bulkUpload)) {
                $bulkUpload->markAsFailed($e->getMessage());
            }

            if (isset($path)) {
                Storage::delete($path);
            }

            return redirect()->route('admin.users.bulk_upload')
                ->with('error', 'Error during bulk user upload: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Read uploaded file efficiently (supports CSV and Excel)
     */
    private function readUploadedFile($fullPath, $extension)
    {
        $data = [];

        if (in_array(strtolower($extension), ['xlsx', 'xls'])) {
            // Read Excel file
            try {
                $spreadsheet = IOFactory::load($fullPath);
                $worksheet = $spreadsheet->getActiveSheet();
                $data = $worksheet->toArray();

                // Remove empty rows
                $data = array_filter($data, function ($row) {
                    return !empty(array_filter($row, function ($cell) {
                        return !empty(trim($cell));
                    }));
                });

                // Reset array keys
                $data = array_values($data);

                // Remove the header row if it exists
                if (count($data) > 0) {
                    array_shift($data);
                }

            } catch (\Exception $e) {
                throw new \Exception('Error reading Excel file: ' . $e->getMessage());
            }
        } else {
            // Read CSV file
            $data = array_map('str_getcsv', file($fullPath));

            // Remove empty rows
            $data = array_filter($data, function ($row) {
                return !empty(array_filter($row, function ($cell) {
                    return !empty(trim($cell));
                }));
            });

            // Reset array keys
            $data = array_values($data);

            // Remove the header row if it exists
            if (count($data) > 0) {
                array_shift($data);
            }
        }

        return $data;
    }

    /**
     * Map CSV row to user data
     */
    private function mapCsvRowToUserData($row)
    {
        return [
            'name' => $row[0] ?? null,
            'email' => $row[1] ?? null,
            'member_number' => $row[2] ?? null,
            'department_id' => $this->getDepartmentId($row[3] ?? ''),
            'joined_at' => $row[4] ? date('Y-m-d', strtotime($row[4])) : date('Y-m-d'),
            'entrance_fee_paid' => strtolower($row[5] ?? '') === 'yes',
            'loan_amount' => $row[6] ? $this->parseAmount($row[6]) : 0,
            'loan_interest_amount' => $row[7] ? $this->parseAmount($row[7]) : 0,
            'share_amount' => $row[8] ? $this->parseAmount($row[8]) : 0,
            'saving_amount' => $row[9] ? $this->parseAmount($row[9]) : 0,
            'essential_commodity_amount' => $row[10] ? $this->parseAmount($row[10]) : 0,
            'non_essential_commodity_amount' => $row[11] ? $this->parseAmount($row[11]) : 0,
            'electronics_amount' => $row[12] ? $this->parseAmount($row[12]) : 0,
        ];
    }

    /**
     * Validate user data
     */
    private function validateUserData($userData)
    {
        $missingFields = [];
        if (empty(trim($userData['name']))) $missingFields[] = 'Name';
        if (empty(trim($userData['member_number']))) $missingFields[] = 'Member Number';

        if (!empty($missingFields)) {
            throw new \Exception('Missing required fields: ' . implode(', ', $missingFields));
        }

        if (!empty($userData['email']) && !filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Invalid email format: ' . $userData['email']);
        }
    }

    /**
     * Check for duplicate users
     */
    private function checkForDuplicates($userData)
    {
        if (!empty($userData['email']) && User::where('email', $userData['email'])->exists()) {
            throw new \Exception('Email already exists: ' . $userData['email']);
        }

        if (User::where('member_number', $userData['member_number'])->exists()) {
            throw new \Exception('Member number already exists: ' . $userData['member_number']);
        }
    }

    /**
     * Create transactions for a user using the new transaction-based system
     */
    private function createUserTransactions($user, $userData, $defaultPassword, $transactionGroupId = null)
    {
        // Create saving transaction using the new transaction-based system
        if ($userData['saving_amount'] > 0) {
            SavingTransaction::create([
                'user_id' => $user->id,
                'type' => 'credit',
                'amount' => $userData['saving_amount'],
                'description' => 'Initial savings from bulk upload',
                'group_id' => $transactionGroupId,
            ]);
        }

        // Create share transaction using the new transaction-based system
        if ($userData['share_amount'] > 0) {
            ShareTransaction::create([
                'user_id' => $user->id,
                'type' => 'credit',
                'amount' => $userData['share_amount'],
                'description' => 'Initial shares from bulk upload',
                'group_id' => $transactionGroupId,
            ]);
        }

        // Create loan record with current balance (no automatic interest calculation)
        if ($userData['loan_amount'] > 0) {
            $currentBalance = $userData['loan_amount']; // This is the actual outstanding balance

            $user->loans()->create([
                'loan_number' => \App\Models\Loan::generateLoanNumber(),
                'amount' => $currentBalance, // Store the current outstanding balance
                'interest_rate' => 0.10, // 10% interest rate for future calculations
                'term_months' => 0, // No fixed term
                'monthly_payment' => 0, // No fixed monthly payment
                'total_payment' => $currentBalance, // Current balance (no automatic interest added)
                'remaining_balance' => $currentBalance, // Current outstanding balance
                'status' => 'active',
                'purpose' => 'Initial loan balance from bulk upload',
                'repayment_method' => 'bursary_deduction',
                'submitted_at' => now(),
                'approved_at' => now(),
                'approved_by' => Auth::id() ?? 8, // Use current admin or fallback to admin ID 8
                'disbursed_at' => now(),
                'disbursed_by' => Auth::id() ?? 8, // Use current admin or fallback to admin ID 8
            ]);
        }

        // Create essential commodity balance
        if ($userData['essential_commodity_amount'] > 0) {
            $commodity = new UserCommodity([
                'commodity_name' => 'essential',
                'balance' => $userData['essential_commodity_amount'],
                'commodity_type' => 'essential',
            ]);
            $user->userCommodities()->save($commodity);

            // Record commodity transaction for essential
            \App\Models\CommodityTransaction::create([
                'user_id' => $user->id,
                'commodity_type' => 'essential',
                'amount' => $userData['essential_commodity_amount'],
                'type' => 'credit',
                'description' => 'Initial essential commodity from bulk upload',
                'transaction_date' => now(),
                'group_id' => $transactionGroupId,
            ]);
        }

        // Create non-essential commodity balance
        if ($userData['non_essential_commodity_amount'] > 0) {
            $commodity = new UserCommodity([
                'commodity_name' => 'non_essential',
                'balance' => $userData['non_essential_commodity_amount'],
                'commodity_type' => 'non_essential',
            ]);
            $user->userCommodities()->save($commodity);

            // Record commodity transaction for non-essential
            \App\Models\CommodityTransaction::create([
                'user_id' => $user->id,
                'commodity_type' => 'non_essential',
                'amount' => $userData['non_essential_commodity_amount'],
                'type' => 'credit',
                'description' => 'Initial non-essential commodity from bulk upload',
                'transaction_date' => now(),
                'group_id' => $transactionGroupId,
            ]);
        }

        // Create loan interest payment if specified
        if ($userData['loan_interest_amount'] > 0) {
            // Create single loan interest transaction (no duplicates)
            \App\Models\Transaction::create([
                'user_id' => $user->id,
                'type' => 'loan_interest',
                'amount' => $userData['loan_interest_amount'],
                'description' => 'Initial loan interest payment from bulk upload',
                'reference' => 'USR-INT-' . date('Ymd') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT) . '-' . mt_rand(100, 999),
                'status' => 'completed',
                'group_id' => $transactionGroupId,
            ]);
        }

        // Create electronics balance and corresponding transactions for visibility
        if ($userData['electronics_amount'] > 0) {
            // Persist electronics item/balance
            $user->electronics()->create([
                'amount' => $userData['electronics_amount'],
                'description' => 'Initial electronics from bulk upload',
                'transaction_type' => 'purchase',
                'transaction_date' => now(),
            ]);

            // Record a commodity transaction for electronics (credit = liability created)
            \App\Models\CommodityTransaction::create([
                'user_id' => $user->id,
                'commodity_type' => 'electronics',
                'amount' => $userData['electronics_amount'],
                'type' => 'credit',
                'description' => 'Initial electronics from bulk upload',
                'transaction_date' => now(),
                'group_id' => $transactionGroupId,
            ]);

            // Also record a general transaction entry for dashboard visibility
            \App\Models\Transaction::create([
                'user_id' => $user->id,
                'type' => 'electronics',
                'amount' => $userData['electronics_amount'],
                'description' => 'Initial electronics from bulk upload',
                'reference' => 'USR-ELX-' . date('Ymd') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'status' => 'completed',
                'group_id' => $transactionGroupId,
            ]);
        }

        // Log user creation
        Log::info('User created via bulk upload', [
            'user_id' => $user->id,
            'member_number' => $user->member_number,
            'default_password' => $defaultPassword
        ]);
    }

    /**
     * Get department ID from department name or code (optional - returns null if not found or empty)
     * Matches against both title (partial) and code (exact, case-insensitive).
     * Does NOT filter by is_active so all departments in the system are always findable.
     */
    private function getDepartmentId($departmentName)
    {
        // Return null for empty/null department names - this is acceptable
        if (empty($departmentName) || trim($departmentName) === '') {
            return null;
        }

        $search = trim($departmentName);

        // Search by code (exact, case-insensitive) first, then by title (partial match)
        $department = Department::where(function ($q) use ($search) {
                $q->whereRaw('LOWER(code) = ?', [strtolower($search)])
                  ->orWhere('title', 'LIKE', '%' . $search . '%');
            })
            ->first();

        // Return null if department not found - will be handled gracefully
        return $department ? $department->id : null;
    }

    /**
     * Parse amount from string
     */
    private function parseAmount($value)
    {
        if (empty($value)) {
            return 0;
        }

        // Remove currency symbols and commas
        $value = preg_replace('/[^\d.]/', '', $value);

        // Convert to float
        return (float)$value;
    }

    /**
     * Validate bulk upload data following Monthly MAB pattern
     */
    private function validateBulkUploadData($csvData, $fileName)
    {
        $validationIssues = [
            'missing_required' => [],
            'duplicates' => [],
            'invalid_data' => [],
            'not_found' => []
        ];

        $validRecords = 0;
        $validRecordsPreview = [];
        $seenMemberNumbers = [];
        $seenEmails = [];

        foreach ($csvData as $index => $row) {
            $rowNumber = $index + 2; // Excel row number (accounting for header)
            $hasIssues = false;

            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // Parse user data
            $userData = [
                'name' => !empty($row[0]) ? trim($row[0]) : '',
                'email' => !empty($row[1]) ? trim($row[1]) : '',
                'member_number' => !empty($row[2]) ? trim($row[2]) : '',
                'department' => !empty($row[3]) ? trim($row[3]) : '',
                'joined_date' => !empty($row[4]) ? trim($row[4]) : '',
                'entrance_fee_paid' => !empty($row[5]) ? strtolower(trim($row[5])) === 'yes' : false,
                'loan_amount' => !empty($row[6]) ? (float) str_replace(',', '', trim($row[6])) : 0,
                'loan_interest_amount' => !empty($row[7]) ? (float) str_replace(',', '', trim($row[7])) : 0,
                'share_amount' => !empty($row[8]) ? (float) str_replace(',', '', trim($row[8])) : 0,
                'saving_amount' => !empty($row[9]) ? (float) str_replace(',', '', trim($row[9])) : 0,
                'essential_commodity_amount' => !empty($row[10]) ? (float) str_replace(',', '', trim($row[10])) : 0,
                'non_essential_commodity_amount' => !empty($row[11]) ? (float) str_replace(',', '', trim($row[11])) : 0,
                'electronics_amount' => !empty($row[12]) ? (float) str_replace(',', '', trim($row[12])) : 0,
            ];

            // Check for missing required fields
            if (empty($userData['name']) || empty($userData['member_number'])) {
                $validationIssues['missing_required'][] = [
                    'row' => $rowNumber,
                    'issue' => 'Missing required fields: ' .
                              (empty($userData['name']) ? 'Name ' : '') .
                              (empty($userData['member_number']) ? 'Member Number' : ''),
                    'data' => $row
                ];
                $hasIssues = true;
            }

            // FIXED: Do not generate fake emails - allow null emails for users who don't have one
            // Users can login using either email OR member number
            if (empty($userData['email'])) {
                $userData['email'] = null; // Allow null emails instead of generating fake ones
                $counter = 1;
                while (isset($seenEmails[$userData['email']])) {
                    $userData['email'] = strtolower(str_replace(' ', '.', $userData['name'])) . $counter . '@asfepilcics.store';
                    $counter++;
                }
            }

            // Check for duplicates within the file only (not database - that's expected for bulk upload)
            if (!empty($userData['member_number'])) {
                if (isset($seenMemberNumbers[$userData['member_number']])) {
                    $validationIssues['duplicates'][] = [
                        'member_number' => $userData['member_number'],
                        'first_row' => $seenMemberNumbers[$userData['member_number']],
                        'duplicate_row' => $rowNumber,
                        'data' => $row
                    ];
                    $hasIssues = true;
                } else {
                    $seenMemberNumbers[$userData['member_number']] = $rowNumber;
                }
            }

            if (!empty($userData['email'])) {
                if (isset($seenEmails[$userData['email']])) {
                    $validationIssues['duplicates'][] = [
                        'member_number' => $userData['email'],
                        'first_row' => $seenEmails[$userData['email']],
                        'duplicate_row' => $rowNumber,
                        'data' => $row
                    ];
                    $hasIssues = true;
                } else {
                    $seenEmails[$userData['email']] = $rowNumber;
                }
            }

            // Only check database duplicates if this is a new upload (not a re-upload scenario)
            // For bulk uploads, we typically expect to create new users, so database duplicates are less relevant
            // unless specifically checking for existing member numbers that shouldn't be duplicated

            // Validate data formats
            if (!empty($userData['joined_date'])) {
                try {
                    \Carbon\Carbon::parse($userData['joined_date']);
                } catch (\Exception $e) {
                    $validationIssues['invalid_data'][] = [
                        'row' => $rowNumber,
                        'field' => 'Joined Date',
                        'issue' => 'Invalid date format',
                        'value' => $userData['joined_date']
                    ];
                    $hasIssues = true;
                }
            }

            // Department validation - OPTIONAL: Only validate if department is explicitly provided
            if (!empty($userData['department']) && trim($userData['department']) !== '') {
                $search = trim($userData['department']);

                // Match by code (exact, case-insensitive) OR title (partial match)
                // Do NOT filter by is_active — all system departments should be findable
                $department = Department::where(function ($q) use ($search) {
                        $q->whereRaw('LOWER(code) = ?', [strtolower($search)])
                          ->orWhere('title', 'LIKE', '%' . $search . '%');
                    })
                    ->first();

                // Only flag as error if department is provided but doesn't match any existing department
                if (!$department) {
                    $validationIssues['not_found'][] = [
                        'row' => $rowNumber,
                        'name' => $userData['name'],
                        'department' => $search,
                        'member_number' => $userData['member_number']
                    ];
                    $hasIssues = true;
                }
            }
            // Note: Empty/null department fields are perfectly acceptable and don't count as errors

            // If no issues, add to valid records
            if (!$hasIssues) {
                $validRecords++;
                if (count($validRecordsPreview) < 20) { // Preview first 20 valid records
                    $validRecordsPreview[] = $userData;
                }
            }
        }

        $totalRecords = count($csvData);
        $totalIssues = count($validationIssues['missing_required']) +
                      count($validationIssues['duplicates']) +
                      count($validationIssues['invalid_data']) +
                      count($validationIssues['not_found']);

        $successRate = $totalRecords > 0 ? round(($validRecords / $totalRecords) * 100, 1) : 0;

        return [
            'hasIssues' => $totalIssues > 0,
            'totalRecords' => $totalRecords,
            'validRecords' => $validRecords,
            'totalIssues' => $totalIssues,
            'successRate' => $successRate,
            'validationIssues' => $validationIssues,
            'validRecordsPreview' => $validRecordsPreview
        ];
    }

    /**
     * Show validation errors page for bulk upload (following Monthly MAB pattern)
     */
    public function showValidationErrors($sessionId)
    {
        $sessionData = Session::get('bulk_upload_' . $sessionId);

        if (!$sessionData) {
            return redirect()->route('admin.users.bulk_upload')
                ->with('error', 'Session expired. Please upload the file again.');
        }

        // Re-read and validate the CSV file
        $filePath = $sessionData['file_path'];
        $fullPath = Storage::path($filePath);

        if (!file_exists($fullPath)) {
            return redirect()->route('admin.users.bulk_upload')
                ->with('error', 'Upload file not found. Please upload again.');
        }

        // Determine file extension from file path
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $csvData = $this->readUploadedFile($fullPath, $extension);
        $validationResults = $this->validateBulkUploadData($csvData, basename($filePath));

        return view('admin.users.bulk_upload_validation_errors', [
            'fileName' => basename($filePath),
            'totalRecords' => $validationResults['totalRecords'],
            'validRecords' => $validationResults['validRecords'],
            'totalIssues' => $validationResults['totalIssues'],
            'successRate' => $validationResults['successRate'],
            'validationIssues' => $validationResults['validationIssues'],
            'validRecordsPreview' => array_slice($validationResults['validRecordsPreview'], 0, 100),
            'sessionId' => $sessionId
        ]);
    }

    /**
     * Process bulk upload with valid records only (following Monthly MAB pattern)
     */
    public function processBulkUpload(Request $request)
    {
        $sessionId = $request->session_id;
        $sessionData = Session::get('bulk_upload_' . $sessionId);
        $processValidOnly = $request->boolean('process_valid_only', false);

        if (!$sessionData) {
            return redirect()->route('admin.users.bulk_upload')
                ->with('error', 'Session expired. Please upload the file again.');
        }

        // Re-read the CSV file to process data (avoid large session data)
        $filePath = $sessionData['file_path'];
        $fullPath = Storage::path($filePath);

        if (!file_exists($fullPath)) {
            return redirect()->route('admin.users.bulk_upload')
                ->with('error', 'Upload file not found. Please upload again.');
        }

        try {
            // Re-read data from file (supports CSV and Excel)
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $csvData = $this->readUploadedFile($fullPath, $extension);

            // Re-validate to get current validation results
            $validationResults = $this->validateBulkUploadData($csvData, 'processing');

            // Increase execution time and memory limit for bulk operations
            set_time_limit(300); // 5 minutes
            ini_set('memory_limit', '512M');

            // Pre-hash the default password once
            $defaultPassword = 'Password123';
            $hashedPassword = Hash::make($defaultPassword);

            // Initialize counters
            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            // Create transaction group for this bulk upload processing
            $transactionGroupService = new TransactionGroupService();
            $transactionGroup = $transactionGroupService->createGroup(
                'user_bulk_upload',
                'User Bulk Upload - Processing Valid Records',
                'Processing valid records from user bulk upload validation',
                [
                    'session_id' => $sessionId,
                    'process_valid_only' => $processValidOnly,
                    'bulk_upload_id' => $sessionData['bulk_upload_id'] ?? null
                ],
                Auth::id()
            );

            // Process only valid records if requested
            $recordsToProcess = [];
            if ($processValidOnly) {
                // Filter only valid records based on validation results
                foreach ($csvData as $index => $row) {
                    $rowNumber = $index + 2;
                    $hasIssues = false;

                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }

                    // Check if this row has validation issues
                    foreach ($validationResults['validationIssues'] as $issueType => $issues) {
                        foreach ($issues as $issue) {
                            if (isset($issue['row']) && $issue['row'] == $rowNumber) {
                                $hasIssues = true;
                                break 2;
                            }
                            if (isset($issue['duplicate_row']) && $issue['duplicate_row'] == $rowNumber) {
                                $hasIssues = true;
                                break 2;
                            }
                        }
                    }

                    if (!$hasIssues) {
                        $recordsToProcess[] = $row;
                    }
                }
            } else {
                $recordsToProcess = $csvData;
            }

            // Process records in chunks
            $chunkSize = 50;
            $chunks = array_chunk($recordsToProcess, $chunkSize);

            Log::info('Starting bulk user upload processing', [
                'admin_id' => Auth::id(),
                'total_rows' => count($recordsToProcess),
                'chunks' => count($chunks),
                'process_valid_only' => $processValidOnly
            ]);

            foreach ($chunks as $chunkIndex => $chunk) {
                try {
                    DB::beginTransaction();

                    $chunkSuccess = 0;
                    $chunkErrors = [];

                    foreach ($chunk as $localIndex => $row) {
                        $globalIndex = ($chunkIndex * $chunkSize) + $localIndex;

                        try {
                            // Parse user data
                            $userData = [
                                'name' => trim($row[0]),
                                'email' => !empty($row[1]) ? trim($row[1]) : null,
                                'member_number' => trim($row[2]),
                                'department' => !empty($row[3]) ? trim($row[3]) : null,
                                'joined_date' => !empty($row[4]) ? trim($row[4]) : null,
                                'entrance_fee_paid' => !empty($row[5]) ? strtolower(trim($row[5])) === 'yes' : false,
                                'loan_amount' => !empty($row[6]) ? (float) str_replace(',', '', trim($row[6])) : 0,
                                'loan_interest_amount' => !empty($row[7]) ? (float) str_replace(',', '', trim($row[7])) : 0,
                                'share_amount' => !empty($row[8]) ? (float) str_replace(',', '', trim($row[8])) : 0,
                                'saving_amount' => !empty($row[9]) ? (float) str_replace(',', '', trim($row[9])) : 0,
                                'essential_commodity_amount' => !empty($row[10]) ? (float) str_replace(',', '', trim($row[10])) : 0,
                                'non_essential_commodity_amount' => !empty($row[11]) ? (float) str_replace(',', '', trim($row[11])) : 0,
                                'electronics_amount' => !empty($row[12]) ? (float) str_replace(',', '', trim($row[12])) : 0,
                            ];

                            // FIXED: Do not generate fake emails - allow null emails
                            // Users can login using either email OR member number
                            if (empty($userData['email'])) {
                                $userData['email'] = null; // Allow null emails instead of generating fake ones
                            }

                            // Get department ID
                            $userData['department_id'] = $this->getDepartmentId($userData['department']);

                            // Parse joined date
                            $userData['joined_at'] = !empty($userData['joined_date']) ?
                                \Carbon\Carbon::parse($userData['joined_date']) : now();

                            // Create user
                            $userCreateData = [
                                'name' => $userData['name'],
                                'member_number' => $userData['member_number'],
                                'department_id' => $userData['department_id'],
                                'role' => 'member',
                                'password' => $hashedPassword,
                                'status' => 'active',
                                'password_change_required' => true,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];

                            if (!empty($userData['email'])) {
                                $userCreateData['email'] = $userData['email'];
                                $userCreateData['email_verified_at'] = now();
                            }

                            $user = User::create($userCreateData);

                            // Create member record with entrance fee status
                            $user->member()->create([
                                'member_number' => $userData['member_number'],
                                'status' => 'active',
                                'joined_at' => $userData['joined_at'],
                                'entrance_fee_paid' => $userData['entrance_fee_paid'],
                            ]);

                            // Create financial transactions
                            $this->createUserTransactions($user, $userData, $defaultPassword, $transactionGroup->id);

                            $chunkSuccess++;

                        } catch (\Exception $e) {
                            $chunkErrors[] = 'Error on row ' . ($globalIndex + 2) . ': ' . $e->getMessage();
                        }
                    }

                    DB::commit();

                    $successCount += $chunkSuccess;
                    $errorCount += count($chunkErrors);
                    $errors = array_merge($errors, $chunkErrors);

                } catch (\Exception $e) {
                    DB::rollBack();
                    $chunkErrorMessage = 'Error processing chunk ' . ($chunkIndex + 1) . ': ' . $e->getMessage();
                    $errors[] = $chunkErrorMessage;
                    $errorCount += count($chunk);
                }
            }

            // Complete the transaction group and calculate total amount
            $totalAmount = 0;

            // Get all transactions in this group to calculate total
            $savingTransactions = SavingTransaction::where('group_id', $transactionGroup->id)->get();
            $shareTransactions = ShareTransaction::where('group_id', $transactionGroup->id)->get();

            $totalAmount += $savingTransactions->sum('amount');
            $totalAmount += $shareTransactions->sum('amount');

            // Update transaction group with totals and mark as completed
            $transactionGroup->update([
                'total_amount' => $totalAmount,
                'total_records' => $successCount,
                'status' => 'completed',
                'processed_at' => now(),
            ]);

            // Update the original bulk upload record with success status
            if (isset($sessionData['bulk_upload_id'])) {
                $originalBulkUpload = \App\Models\UserBulkUpload::find($sessionData['bulk_upload_id']);
                if ($originalBulkUpload) {
                    $originalBulkUpload->update([
                        'processed_records' => $successCount,
                        'failed_records' => $errorCount,
                        'status' => $errorCount > 0 ? 'completed_with_errors' : 'completed',
                        'upload_completed_at' => now(),
                        'processing_summary' => [
                            'success_count' => $successCount,
                            'error_count' => $errorCount,
                            'errors' => $errors,
                            'process_valid_only' => $processValidOnly,
                            'transaction_group_id' => $transactionGroup->id
                        ]
                    ]);
                }
            }

            // Clean up session and file
            Session::forget('bulk_upload_' . $sessionId);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Log completion
            Log::info('Bulk user upload processing completed', [
                'admin_id' => Auth::id(),
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'process_valid_only' => $processValidOnly
            ]);

            // Return results
            if ($errorCount > 0) {
                return redirect()->route('admin.users.all')
                    ->with('success', "Bulk upload completed: {$successCount} users created.")
                    ->with('warning', "{$errorCount} records had errors and were skipped.");
            }

            return redirect()->route('admin.users.all')
                ->with('success', "Bulk upload completed successfully! {$successCount} users created.");

        } catch (\Exception $e) {
            // Clean up on error
            Session::forget('bulk_upload_' . $sessionId);
            if (isset($filePath) && file_exists($filePath)) {
                unlink($filePath);
            }

            Log::error('Error during bulk user upload processing', [
                'admin_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->route('admin.users.add')
                ->with('error', 'Error during bulk user upload processing: ' . $e->getMessage());
        }
    }

    /**
     * Download CSV template for bulk user upload
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="bulk_user_template.csv"',
        ];

        $columns = [
            'Name',
            'Email',
            'Member Number',
            'Department',
            'Joined Date',
            'Entrance Fee Paid',
            'Loan Amount',
            'Loan Interest Amount',
            'Share Amount',
            'Saving Amount',
            'Essential Commodity Amount',
            'Non-essential Commodity Amount',
            'Electronics Amount'
        ];

        $sampleData = [
            [
                'John Doe',
                'john.doe@example.com',
                'P/SS/001',
                '',
                '1/15/2024',
                'Yes',
                '50000',
                '5000',
                '10000',
                '25000',
                '15000',
                '8000',
                '5000'
            ],
            [
                'Jane Smith',
                'jane.smith@example.com',
                'P/SS/002',
                '',
                '2/20/2024',
                'No',
                '75000',
                '7500',
                '10000',
                '15000',
                '8000',
                '5000',
                '3000'
            ],
            [
                'Mike Johnson',
                'mike.johnson@example.com',
                'P/SS/003',
                '',
                '3/10/2024',
                'Yes',
                '100000',
                '10000',
                '10000',
                '30000',
                '20000',
                '12000',
                '7500'
            ],
            [
                'Sarah Wilson',
                'sarah.wilson@example.com',
                'P/SS/004',
                '',
                '4/5/2024',
                'Yes',
                '60000',
                '6000',
                '5000',
                '20000',
                '12000',
                '6000',
                '4000'
            ],
            [
                'David Brown',
                'david.brown@example.com',
                'P/SS/005',
                '',
                '5/12/2024',
                'No',
                '80000',
                '8000',
                '5000',
                '28000',
                '18000',
                '9000',
                '6000'
            ],
            [
                'Lisa Davis',
                'lisa.davis@example.com',
                'P/SS/006',
                '',
                '6/18/2024',
                'Yes',
                '45000',
                '4500',
                '10000',
                '18000',
                '10000',
                '5000',
                '3500'
            ],
            [
                'Robert Miller',
                'robert.miller@example.com',
                'P/SS/007',
                '',
                '7/22/2024',
                'Yes',
                '55000',
                '5500',
                '10000',
                '22000',
                '14000',
                '7000',
                '4500'
            ],
            [
                'Emily Garcia',
                'emily.garcia@example.com',
                'P/SS/008',
                '',
                '8/30/2024',
                'No',
                '30000',
                '3000',
                '10000',
                '12000',
                '8000',
                '4000',
                '2500'
            ],
            [
                'James Rodriguez',
                'james.rodriguez@example.com',
                'P/SS/009',
                '',
                '9/14/2024',
                'Yes',
                '70000',
                '7000',
                '10000',
                '26000',
                '16000',
                '8000',
                '5500'
            ],
            [
                'Maria Lopez',
                'maria.lopez@example.com',
                'P/SS/010',
                '',
                '10/8/2024',
                'Yes',
                '65000',
                '6500',
                '10000',
                '24000',
                '17000',
                '8500',
                '5000'
            ]
        ];

        $callback = function() use ($columns, $sampleData) {
            $file = fopen('php://output', 'w');

            // Add header row
            fputcsv($file, $columns);

            // Add sample data rows
            foreach ($sampleData as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * View detailed report of a specific user bulk upload
     *
     * @param int $id The upload ID to view details for
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function viewBulkUploadDetails($id)
    {
        try {
            $upload = \App\Models\UserBulkUpload::with('uploader')->findOrFail($id);

            // Get processing summary with detailed records
            $processingSummary = $upload->processing_summary ?? [];

            // Prepare detailed records for display
            $validationIssues = $processingSummary['validation_issues'] ?? [];
            $successfulRecords = [];
            $failedRecords = [];

            // If we have validation issues, organize them by type
            if (!empty($validationIssues)) {
                foreach ($validationIssues as $issueType => $issues) {
                    foreach ($issues as $issue) {
                        $failedRecords[] = [
                            'row' => $issue['row'] ?? 'Unknown',
                            'field' => $issue['field'] ?? 'Unknown',
                            'value' => $issue['value'] ?? 'N/A',
                            'issue_type' => ucfirst(str_replace('_', ' ', $issueType)),
                            'message' => $issue['message'] ?? 'Validation failed',
                        ];
                    }
                }
            }

            // Get users created around the upload time (approximate tracking)
            $uploadTimeStart = $upload->created_at->subMinutes(5);
            $uploadTimeEnd = $upload->upload_completed_at ?? $upload->created_at->addHours(1);

            $recentUsers = \App\Models\User::whereBetween('created_at', [$uploadTimeStart, $uploadTimeEnd])
                ->with('department')
                ->orderBy('created_at', 'desc')
                ->limit($upload->processed_records ?? 50)
                ->get();

            foreach ($recentUsers as $user) {
                // Determine transaction type based on user's department or default to 'general'
                $transactionType = 'general';
                if ($user->department) {
                    // Map department to transaction type
                    $departmentName = strtolower($user->department->name);
                    if (str_contains($departmentName, 'saving')) {
                        $transactionType = 'saving';
                    } elseif (str_contains($departmentName, 'share')) {
                        $transactionType = 'share';
                    } elseif (str_contains($departmentName, 'loan')) {
                        $transactionType = 'loan';
                    } elseif (str_contains($departmentName, 'commodity')) {
                        $transactionType = 'commodity';
                    }
                }

                $successfulRecords[] = [
                    'name' => $user->name,
                    'email' => $user->email ?? 'N/A',
                    'member_number' => $user->member_number ?? 'N/A',
                    'department' => $user->department->name ?? 'N/A',
                    'transaction_type' => $transactionType,
                    'created_at' => $user->created_at,
                    'status' => $user->status ?? 'active',
                ];
            }

            $uploadSummary = [
                'file_name' => $upload->file_name,
                'total_records' => $upload->total_records,
                'processed_records' => $upload->processed_records,
                'failed_records' => $upload->failed_records,
                'status' => $upload->status,
                'uploaded_by' => $upload->uploader->name ?? 'Unknown',
                'upload_date' => $upload->created_at,
                'completed_date' => $upload->upload_completed_at,
                'processing_summary' => $processingSummary,
                'success_rate' => $upload->success_rate ?? 0,
            ];

            return view('admin.users.bulk_upload_details', [
                'upload' => $upload,
                'uploadSummary' => $uploadSummary,
                'successfulRecords' => $successfulRecords,
                'failedRecords' => $failedRecords,
                'validationIssues' => $validationIssues,
            ]);

        } catch (\Exception $e) {
            return redirect()->route('admin.users.bulk_upload')
                ->with('error', 'Error loading bulk upload details: ' . $e->getMessage());
        }
    }

    /**
     * Export all users with their financial data to Excel
     */
    public function exportFinancialData()
    {
        try {
            // Get all users with member data
            $users = User::with(['member', 'member.department'])
                ->where('role', 'member')
                ->orderBy('name')
                ->get();

            // Prepare data for export
            $exportData = [];
            $exportData[] = [
                'Name',
                'Email',
                'Member Number',
                'Department',
                'Joined Date',
                'Entrance Fee Paid',
                'Loan Amount',
                'Loan Interest Amount',
                'Share Amount',
                'Saving Amount',
                'Essential Commodity Amount',
                'Non-essential Commodity Amount',
                'Electronics Amount'
            ];

            foreach ($users as $user) {
                // Calculate financial balances using FinancialCalculationService
                $savingsBalance = FinancialCalculationService::calculateSavingsBalance($user);
                $sharesBalance = FinancialCalculationService::calculateSharesBalance($user);
                $loanInterest = FinancialCalculationService::calculateLoanInterest($user);

                // Calculate loan amounts
                $loanAmount = $user->loans()->where('status', 'active')->sum('remaining_balance');
                $loanInterestAmount = $loanInterest;

                // Calculate commodity balances
                $essentialCommodityBalance = $user->userCommodities()
                    ->where('commodity_name', 'essential')
                    ->sum('balance');

                $nonEssentialCommodityBalance = $user->userCommodities()
                    ->where('commodity_name', 'non_essential')
                    ->sum('balance');

                // Calculate electronics balance
                $electronicsBalance = $user->electronics()->sum('remaining_balance');

                $exportData[] = [
                    $user->name,
                    $user->email,
                    $user->member->member_number ?? 'N/A',
                    $user->member->department->name ?? 'N/A',
                    $user->created_at ? $user->created_at->format('Y-m-d') : 'N/A',
                    $user->member->entrance_fee_paid ? 'Yes' : 'No',
                    number_format($loanAmount, 2),
                    number_format($loanInterestAmount, 2),
                    number_format($sharesBalance, 2),
                    number_format($savingsBalance, 2),
                    number_format($essentialCommodityBalance, 2),
                    number_format($nonEssentialCommodityBalance, 2),
                    number_format($electronicsBalance, 2)
                ];
            }

            // Create CSV content
            $csvContent = '';
            foreach ($exportData as $row) {
                $csvContent .= implode(',', array_map(function($field) {
                    // Escape fields containing commas or quotes
                    if (strpos($field, ',') !== false || strpos($field, '"') !== false) {
                        return '"' . str_replace('"', '""', $field) . '"';
                    }
                    return $field;
                }, $row)) . "\n";
            }

            // Return CSV file
            return response($csvContent, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="user_financial_data_' . date('Y-m-d_H-i-s') . '.csv"',
            ]);

        } catch (\Exception $e) {
            Log::error('Financial data export failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            Log::info('Redirecting back due to exception: ' . $e->getMessage());

            return redirect()->route('admin.users.all')
                ->with('error', 'Failed to export financial data: ' . $e->getMessage());
        }
    }

    /**
     * Export all members data with specific columns to CSV
     */
    public function exportMembersData()
    {
        try {
            // Get all users with member data - simplified query
            $users = User::where('role', 'member')
                ->orderBy('name')
                ->get();

            // Prepare data for export
            $exportData = [];
            $exportData[] = [
                'COOPNO',
                'Name',
                'Entrance Fee',
                'Share',
                'Saving',
                'Loan',
                'Interest',
                'Electronics',
                'Essential',
                'Non-Essential'
            ];

            foreach ($users as $user) {
                // Safely calculate financial balances with fallbacks
                $savingsBalance = 0;
                $sharesBalance = 0;
                $loanInterest = 0;
                $loanAmount = 0;
                $essentialCommodityBalance = 0;
                $nonEssentialCommodityBalance = 0;
                $electronicsBalance = 0;

                try { $savingsBalance = FinancialCalculationService::calculateSavingsBalance($user); } catch (\Exception $e) { /* ignore */ }
                try { $sharesBalance = FinancialCalculationService::calculateSharesBalance($user); } catch (\Exception $e) { /* ignore */ }
                try { $loanInterest = FinancialCalculationService::calculateLoanInterest($user); } catch (\Exception $e) { /* ignore */ }
                try { $loanAmount = FinancialCalculationService::calculateLoanBalance($user); } catch (\Exception $e) { /* ignore */ }
                try { $essentialCommodityBalance = FinancialCalculationService::calculateEssentialCommodityBalance($user); } catch (\Exception $e) { /* ignore */ }
                try { $nonEssentialCommodityBalance = FinancialCalculationService::calculateNonEssentialCommodityBalance($user); } catch (\Exception $e) { /* ignore */ }
                try { $electronicsBalance = FinancialCalculationService::calculateElectronicsBalance($user); } catch (\Exception $e) { /* ignore */ }

                // Get entrance fee status
                $entranceFee = 'No';
                if ($user->member && $user->member->entrance_fee_paid) {
                    $entranceFee = 'Yes';
                }

                $exportData[] = [
                    $user->member ? $user->member->member_number : 'N/A',
                    $user->name,
                    $entranceFee,
                    number_format($sharesBalance, 2),
                    number_format($savingsBalance, 2),
                    number_format($loanAmount, 2),
                    number_format($loanInterest, 2),
                    number_format($electronicsBalance, 2),
                    number_format($essentialCommodityBalance, 2),
                    number_format($nonEssentialCommodityBalance, 2)
                ];
            }

            // Create CSV content
            $csvContent = '';
            foreach ($exportData as $row) {
                $csvContent .= implode(',', array_map(function($field) {
                    // Escape fields containing commas or quotes
                    if (strpos($field, ',') !== false || strpos($field, '"') !== false) {
                        return '"' . str_replace('"', '""', $field) . '"';
                    }
                    return $field;
                }, $row)) . "\n";
            }

            // Return CSV file
            return response($csvContent, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="members_data_' . date('Y-m-d_H-i-s') . '.csv"',
            ]);

        } catch (\Exception $e) {
            // Return basic CSV if everything fails
            $csvContent = "COOPNO,Name,Entrance Fee,Share,Saving,Loan,Interest,Electronics,Essential,Non-Essential\n";
            $csvContent .= "ERROR,Error occurred,Please contact admin,,,,,,,,,\n";

            return response($csvContent, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="members_data_' . date('Y-m-d_H-i-s') . '.csv"',
            ]);
        }
    }
}




