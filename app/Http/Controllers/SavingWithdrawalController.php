<?php

namespace App\Http\Controllers;

use App\Models\SavingWithdrawal;
use App\Services\FinancialCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavingWithdrawalController extends Controller
{
    public function create()
     {
        $user = Auth::user()->load('member');

        // Get financial data using transaction-based calculations
        $loanBalance = FinancialCalculationService::calculateLoanBalance($user);
        $savingsBalance = FinancialCalculationService::calculateSavingsBalance($user);
        $sharesBalance = FinancialCalculationService::calculateSharesBalance($user);
        $commodityBalance = FinancialCalculationService::calculateCommodityBalance($user);
        $electronicsBalance = FinancialCalculationService::calculateElectronicsBalance($user);

        // Get separate commodity balances for display
        $essentialBalance = $user->userCommodities()->where('commodity_name', 'essential')->sum('balance') ?? 0;
        $nonEssentialBalance = $user->userCommodities()->where('commodity_name', 'non_essential')->sum('balance') ?? 0;

        // Get user's withdrawal history
        $withdrawalHistory = SavingWithdrawal::where('user_id', $user->id)
                            ->orderBy('created_at', 'desc')
                            ->get();

        // Pass the financial data directly to the view
        return view('user.saving_withdrawals.create', [
            'loanBalance' => $loanBalance,
            'savingsBalance' => $savingsBalance,
            'sharesBalance' => $sharesBalance,
            'commodityBalance' => $commodityBalance,
            'electronicsBalance' => $electronicsBalance,
            'essentialBalance' => $essentialBalance,
            'nonEssentialBalance' => $nonEssentialBalance,
            'withdrawalHistory' => $withdrawalHistory,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $savings = FinancialCalculationService::calculateSavingsBalance($user);

        if ($savings <= 0) {
            return back()->with('error', 'You cannot request a withdrawal because you have no savings.');
        }

        // Check if the user has been a member for at least 6 months
        $membershipStartDate = $user->member->membership_date;
        if ($membershipStartDate && $membershipStartDate->diff(now())->days < 180) {
            return back()->with('error', 'You must be a member for at least 6 months to request a withdrawal.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $savings,
            'notes' => 'nullable|string|max:500',
        ]);

        $withdrawal = SavingWithdrawal::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        // Notify all admin users about the new withdrawal request
        \App\Http\Controllers\NotificationController::notifyAdmins(
            'New Savings Withdrawal Request',
            "User {$user->name} has submitted a savings withdrawal request for ₦" . number_format($request->amount, 2),
            'withdrawal_request',
            route('admin.saving_withdrawals.index'),
            [
                'withdrawal_id' => $withdrawal->id,
                'user_id' => $user->id,
                'amount' => $request->amount
            ]
        );

        return redirect()->route('user.dashboard')->with('success', 'Withdrawal request submitted successfully.');
    }

    public function index(Request $request)
    {
        // Get withdrawals with user relationship, but only include those with valid users
        $query = SavingWithdrawal::with('user')
            ->whereHas('user'); // Only include withdrawals where user exists

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%')
                             ->orWhere('email', 'like', '%' . $search . '%');
                })
                ->orWhere('amount', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        // Apply status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->latest()->paginate(10)->withQueryString();

        return view('admin.saving_withdrawals.index', compact('withdrawals'));
    }

    public function update(Request $request, SavingWithdrawal $savingWithdrawal)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $oldStatus = $savingWithdrawal->status;
        $savingWithdrawal->status = $request->status;
        $savingWithdrawal->processed_at = now();
        $savingWithdrawal->processed_by = Auth::id();
        $savingWithdrawal->save();

        // Process savings deduction if approved
        if ($request->status === 'approved' && $oldStatus === 'pending') {
            // Create a debit transaction to reduce savings balance
            \App\Models\SavingTransaction::create([
                'user_id' => $savingWithdrawal->user_id,
                'amount' => $savingWithdrawal->amount,
                'type' => 'debit',
                'description' => "Savings withdrawal approved - Request #{$savingWithdrawal->id}",
                'transaction_date' => now(),
                'processed_by' => Auth::id(),
            ]);

            $message = "Withdrawal request approved and ₦" . number_format($savingWithdrawal->amount, 2) . " has been deducted from user's savings.";
        } else {
            $message = 'Withdrawal request updated successfully.';
        }

        return redirect()->route('admin.saving_withdrawals.index')->with('success', $message);
    }
}



