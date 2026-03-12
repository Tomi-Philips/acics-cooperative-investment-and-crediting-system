<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\FinancialCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user()->load('member', 'department');

        // Get financial data using transaction-based calculations
        $financialData = FinancialCalculationService::getFormattedFinancialSummary($user);

        // Get comprehensive transaction data for all 8 transaction types
        $transactionData = $this->getComprehensiveTransactionData($user);

        // Get paginated transaction history
        $transactions = $this->getPaginatedTransactionHistory($user);

        return view('user.profile', [
            'user' => $user,
            'financialData' => $financialData,
            'transactionData' => $transactionData,
            'transactions' => $transactions
        ]);
    }

    /**
     * Display the user's settings page.
     *
     * @return \Illuminate\View\View
     */
    public function settings()
    {
        $user = Auth::user()->load('member');

        return view('user.settings', [
            'user' => $user
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old profile photo if exists
            if ($user->member && $user->member->profile_photo) {
                Storage::disk('public')->delete($user->member->profile_photo);
            }

            // Store new profile photo
            $profilePhotoPath = $request->file('profile_photo')->store('profile_photos', 'public');

            // Update member profile photo
            $user->member()->updateOrCreate(
                ['user_id' => $user->id],
                ['profile_photo' => $profilePhotoPath]
            );
        }

        // Update user name
        $user->update([
            'name' => $validated['name']
        ]);

        // Update member information
        $user->member()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $validated['phone'],
                'address' => $validated['address']
            ]
        );

        return redirect()->route('user.profile')
            ->with('success', 'Profile information updated successfully.');
    }

    /**
     * Update the user's next of kin information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateNextOfKin(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $validated = $request->validate([
            'next_of_kin_name' => 'required|string|max:255',
            'next_of_kin_relationship' => 'required|string|max:50',
            'next_of_kin_phone' => 'required|string|max:20',
            'next_of_kin_address' => 'required|string|max:255',
        ]);

        // Update next of kin information
        $user->member()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'next_of_kin_name' => $validated['next_of_kin_name'],
                'next_of_kin_relationship' => $validated['next_of_kin_relationship'],
                'next_of_kin_phone' => $validated['next_of_kin_phone'],
                'next_of_kin_address' => $validated['next_of_kin_address']
            ]
        );

        return redirect()->route('user.profile')
            ->with('success', 'Next of kin information updated successfully.');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->route('user.settings')
                ->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($validated['password']),
            'password_change_required' => false
        ]);

        return redirect()->route('user.settings')
            ->with('success', 'Password updated successfully.');
    }

    /**
     * Update the user's notification preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $validated = $request->validate([
            'email_notifications' => 'nullable|boolean',
            'sms_notifications' => 'nullable|boolean',
        ]);

        // Update notification preferences
        $user->update([
            'email_notifications' => $request->has('email_notifications'),
            'sms_notifications' => $request->has('sms_notifications')
        ]);

        return redirect()->route('user.settings')
            ->with('success', 'Notification preferences updated successfully.');
    }

    /**
     * Get comprehensive transaction data for all 8 transaction types
     */
    private function getComprehensiveTransactionData($user)
    {
        $data = [];

        // 1. Entrance Fee Status
        $entrancePaid = (bool) optional($user->member)->entrance_fee_paid;

        $data['entrance_fee'] = [
            'status' => $entrancePaid ? 'Paid' : 'Not Paid',
            'paid' => $entrancePaid,
            'transactions' => \App\Models\Transaction::where('user_id', $user->id)
                ->where('type', 'entrance_fee')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];

        // 2. Shares Transactions
        $data['shares'] = [
            'total' => \App\Models\ShareTransaction::where('user_id', $user->id)->sum('amount'),
            'transactions' => \App\Models\ShareTransaction::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];

        // 3. Savings Transactions
        $data['savings'] = [
            'balance' => \App\Models\SavingTransaction::where('user_id', $user->id)
                ->selectRaw('SUM(CASE WHEN type = "credit" THEN amount ELSE -amount END) as balance')
                ->value('balance') ?? 0,
            'transactions' => \App\Models\SavingTransaction::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];

        // 4. Loan Transactions
        $activeLoan = \App\Models\Loan::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        $data['loans'] = [
            'active_loan' => $activeLoan,
            'remaining_balance' => $activeLoan ? $activeLoan->remaining_balance : 0,
            'loan_payments' => \App\Models\LoanPayment::whereHas('loan', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'all_loans' => \App\Models\Loan::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get()
        ];

        // 5. Essential Commodity Transactions
        $data['essential_commodity'] = [
            'balance' => \App\Models\CommodityTransaction::where('user_id', $user->id)
                ->where('commodity_type', 'essential')
                ->selectRaw('SUM(CASE WHEN type = "credit" THEN amount ELSE -amount END) as balance')
                ->value('balance') ?? 0,
            'transactions' => \App\Models\CommodityTransaction::where('user_id', $user->id)
                ->where('commodity_type', 'essential')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];

        // 6. Non-essential Commodity Transactions
        $data['non_essential_commodity'] = [
            'balance' => \App\Models\CommodityTransaction::where('user_id', $user->id)
                ->where('commodity_type', 'non_essential')
                ->selectRaw('SUM(CASE WHEN type = "credit" THEN amount ELSE -amount END) as balance')
                ->value('balance') ?? 0,
            'transactions' => \App\Models\CommodityTransaction::where('user_id', $user->id)
                ->where('commodity_type', 'non_essential')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];

        // 7. Electronics Transactions
        $electronicsBalance = \App\Services\FinancialCalculationService::calculateElectronicsBalance($user);

        $data['electronics'] = [
            'balance' => $electronicsBalance,
            'transactions' => \App\Models\Electronics::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];

        // 8. Loan Interest Payments from both LoanPayments and Transactions
        // Query via relationship to be safe if user_id is missing on loan_payments table
        $loanInterestFromPayments = \App\Models\LoanPayment::whereHas('loan', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status', 'paid')
            ->where(function($q) {
                $q->where('notes', 'like', '%interest%')
                  ->orWhere('notes', 'like', '%Interest%');
            })
            ->sum('amount');

        $loanInterestFromTransactions = \App\Models\Transaction::where('user_id', $user->id)
            ->where('type', 'loan_interest')
            ->where('status', 'completed')
            ->sum('amount');

        $loanInterestPaid = $loanInterestFromPayments + $loanInterestFromTransactions;

        // Get combined transactions for display
        $loanPaymentTransactions = \App\Models\LoanPayment::whereHas('loan', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status', 'paid')
            ->where(function($q) {
                $q->where('notes', 'like', '%interest%')
                  ->orWhere('notes', 'like', '%Interest%');
            })
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        $loanInterestTransactions = \App\Models\Transaction::where('user_id', $user->id)
            ->where('type', 'loan_interest')
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        $data['loan_interest'] = [
            'total_paid' => $loanInterestPaid,
            'transactions' => $loanPaymentTransactions->merge($loanInterestTransactions)->sortByDesc('created_at')->take(5),
            'payment_transactions' => $loanPaymentTransactions,
            'interest_transactions' => $loanInterestTransactions
        ];

        return $data;
    }

    /**
     * Get paginated transaction history for the profile page
     */
    private function getPaginatedTransactionHistory($user)
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
                'formatted_amount' => '₦' . number_format($transaction->amount, 2),
                'status_badge_class' => $this->getStatusBadgeClass('completed'),
                'icon_class' => $this->getIconClass($type)
            ];
        });

        // Get loan payments
        $loanPayments = \App\Models\LoanPayment::whereHas('loan', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->get()->map(function($payment) {
            return (object)[
                'id' => $payment->id,
                'type' => 'loan_payment',
                'amount' => $payment->amount,
                'description' => $payment->notes ?? 'Loan Payment',
                'created_at' => $payment->created_at,
                'status' => $payment->status,
                'reference' => 'LOAN-' . $payment->id,
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
                'formatted_amount' => '₦' . number_format($loan->amount, 2),
                'status_badge_class' => $this->getStatusBadgeClass('completed'),
                'icon_class' => $this->getIconClass('loan_disbursement')
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

        // Manual pagination
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

        return $transactions;
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
