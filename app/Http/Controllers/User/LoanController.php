<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Services\FinancialCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class LoanController extends Controller
{
    /**
     * Display the loan board with user's loan information.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user()->load('member');

        // Get active and pending loans
        $activeLoans = $user->loans()
            ->whereIn('status', ['active', 'pending', 'approved'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get loan payment history (exclude interest payments, only show principal repayments)
        $loanPayments = LoanPayment::whereIn('loan_id', $user->loans()->pluck('id'))
            ->where(function($query) {
                $query->where('notes', 'not like', '%Interest Payment%')
                      ->where('notes', 'not like', '%interest payment%');
            })
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get loan eligibility information
        $eligibility = $user->member->isEligibleForLoan(true);

        // Get maximum loan amount
        $maxLoanAmount = $user->member->getMaxLoanAmountAttribute();

        return view('user.loan_board', [
            'user' => $user,
            'activeLoans' => $activeLoans,
            'loanPayments' => $loanPayments,
            'eligibility' => $eligibility,
            'maxLoanAmount' => $maxLoanAmount
        ]);
    }

    /**
     * Display the loan application form.
     *
     * @return \Illuminate\View\View
     */
    public function showApplicationForm()
    {
        $user = Auth::user()->load('member');

        // Check if the user has a member relationship loaded
        if (!$user->member) {
            return redirect()->route('user.loan_board')
                ->with('error', 'You must be a member to apply for a loan.');
        }

        // Get eligibility information
        $eligibility = $user->member->isEligibleForLoan(true);

        // Get financial stats using transaction-based calculations
        $savingsBalance = FinancialCalculationService::calculateSavingsBalance($user);
        $sharesBalance = FinancialCalculationService::calculateSharesBalance($user);
        $loanBalance = FinancialCalculationService::calculateLoanBalance($user);
        $commodityBalance = FinancialCalculationService::calculateCommodityBalance($user);
        $electronicsBalance = FinancialCalculationService::calculateElectronicsBalance($user);

        // Get separate commodity balances
        $essentialBalance = $user->userCommodities()->where('commodity_name', 'essential')->sum('balance') ?? 0;
        $nonEssentialBalance = $user->userCommodities()->where('commodity_name', 'non_essential')->sum('balance') ?? 0;

        // Get total loan interest (10% of all active/approved loans)
        $loanInterestTotal = FinancialCalculationService::calculateLoanInterest($user);

        // Get entrance fee status
        $entrancePaid = $user->member ? $user->member->entrance_fee_paid : false;

        // Get maximum loan amount using transaction-based calculations
        // This amount already represents the available amount for new loans
        $maxLoanAmount = FinancialCalculationService::calculateMaxLoanAmount($user);

        // Get current loan exposure for display
        $totalActiveLoanBalance = $user->loans()->where('status', 'active')->sum('remaining_balance');

        // Get active loans for display
        $activeLoans = $user->loans()->where('status', 'active')->get();

        return view('user.loan_application', [
            'user' => $user,
            'maxLoanAmount' => $maxLoanAmount,
            'eligibleAmount' => $maxLoanAmount, // This is the available amount for new loan
            'totalActiveLoanBalance' => $totalActiveLoanBalance,
            'activeLoans' => $activeLoans,
            'eligibility' => $eligibility,
            'savingsBalance' => $savingsBalance,
            'sharesBalance' => $sharesBalance,
            'loanBalance' => $loanBalance,
            'commodityBalance' => $commodityBalance,
            'electronicsBalance' => $electronicsBalance,
            'essentialBalance' => $essentialBalance,
            'nonEssentialBalance' => $nonEssentialBalance,
            'loanInterestTotal' => $loanInterestTotal,
            'entrancePaid' => $entrancePaid
        ]);
    }

    /**
     * Process the loan application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processApplication(Request $request)
    {
        $user = Auth::user()->load('member');

        // Get eligibility information
        $eligibility = $user->member->isEligibleForLoan(true);

        // Check if user is eligible for a loan
        if (!$eligibility['eligible']) {
            return redirect()->route('user.loan_board')
                ->with('error', 'You are not eligible for a loan at this time: ' . $eligibility['reason']);
        }

        // Get maximum loan amount (this is the available amount for new loan)
        $maxLoanAmount = $user->member->getMaxLoanAmountAttribute();

        // Validate the request (no loan term in the system)
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1000|max:' . $maxLoanAmount,
            'purpose' => 'required|string|max:255',
            'additional_info' => 'nullable|string|max:1000',
        ]);

        // Calculate interest and total amount (fixed 10% of principal)
        $interestRate = 0.10; // 10%
        $interestAmount = $validated['amount'] * $interestRate;
        $totalPayment = $validated['amount'] + $interestAmount;
        $monthlyPayment = 0; // No term; monthly schedule not used

        // Generate a unique loan number
        $loanNumber = Loan::generateLoanNumber();

        // Create the loan application (no term)
        $loan = Loan::create([
            'user_id' => $user->id,
            'loan_number' => $loanNumber,
            'amount' => $validated['amount'],
            'interest_rate' => $interestRate,
            'term_months' => 0,
            'monthly_payment' => 0,
            'total_payment' => $totalPayment,
            'remaining_balance' => $validated['amount'], // Store only principal in remaining balance
            'purpose' => $validated['purpose'],
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        // Notify all admin users about the new loan application
        \App\Http\Controllers\NotificationController::notifyAdmins(
            'New Loan Application Submitted',
            "User {$user->name} has submitted a loan application for ₦" . number_format($validated['amount'], 2) . " (no fixed term; 10% interest)",
            'loan_application',
            route('admin.loans.approval'),
            [
                'loan_id' => $loan->id,
                'user_id' => $user->id,
                'amount' => $validated['amount'],
                'interest_rate' => $interestRate
            ]
        );

        return redirect()->route('user.loan_board')
            ->with('success', 'Your loan application has been submitted successfully and is pending approval.');
    }

    /**
     * Display the loan details.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = Auth::user();
        $loan = Loan::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $payments = $loan->payments()->orderBy('due_date')->get();

        return view('user.loan_details', [
            'user' => $user,
            'loan' => $loan,
            'payments' => $payments
        ]);
    }
}
