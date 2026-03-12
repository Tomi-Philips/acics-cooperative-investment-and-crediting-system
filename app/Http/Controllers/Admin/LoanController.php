<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LoanController extends Controller
{
    /**
     * Display a listing of all loans.
     */
    public function index(Request $request)
    {
        $query = Loan::with(['user', 'user.department'])->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search by loan number or member name
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('loan_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $loans = $query->paginate(10);

        return view('admin.loans.index', compact('loans'));
    }

    /**
     * Display a listing of pending loan applications.
     */
    public function pendingApprovals(Request $request)
    {
        $query = Loan::with(['user', 'user.department'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc');

        // Search by loan number or member name
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('loan_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $loans = $query->paginate(10);

        return view('admin.loans.pending_approvals', compact('loans'));
    }

    /**
     * Show the form for creating a new loan.
     */
    public function create()
    {
        $users = User::where('role', 'member')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        // Get eligible members with their max loan amounts
        $eligibleMembers = [];
        $ineligibleMembers = [];

        foreach ($users as $user) {
            if ($user->member) {
                $eligibilityCheck = $user->member->isEligibleForLoan(true);

                if ($eligibilityCheck['eligible']) {
                    // Get the financial data for the member
                    $savings = $user->member->total_savings;
                    $shares = $user->member->total_shares;
                    $commodities = $user->member->total_commodity_balance;
                    $loans = $user->member->total_loan_balance;

                    $eligibleMembers[] = [
                        'id' => $user->id,
                        'name' => $user->name,
                        'member_number' => $user->member->member_number,
                        'max_loan_amount' => $user->member->max_loan_amount,
                        'savings' => $savings,
                        'shares' => $shares,
                        'commodities' => $commodities,
                        'loans' => $loans,
                    ];
                } else {
                    $ineligibleMembers[] = [
                        'id' => $user->id,
                        'name' => $user->name,
                        'member_number' => $user->member->member_number,
                        'reason' => $eligibilityCheck['reason'],
                        'joined_at' => $eligibilityCheck['joined_at'] ?? null,
                        'months_remaining' => $eligibilityCheck['months_remaining'] ?? null,
                    ];
                }
            }
        }

        // If no eligible members, show a message
        if (empty($eligibleMembers)) {
            return view('admin.loans.create', [
                'users' => $users,
                'eligibleMembers' => [],
                'ineligibleMembers' => $ineligibleMembers,
                'noEligibleMembers' => true
            ]);
        }

        return view('admin.loans.create', [
            'users' => $users,
            'eligibleMembers' => $eligibleMembers,
            'ineligibleMembers' => $ineligibleMembers
        ]);
    }

    /**
     * Store a newly created loan in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1000',
            'purpose' => 'nullable|string|max:500',
            'repayment_method' => 'required|string|in:bursary_deduction',
        ]);

        // Set fixed interest rate (10%)
        $interestRate = 10.0;

        // Calculate total payment (principal + 10% interest)
        $principal = $validated['amount'];
        $interestAmount = $principal * 0.10; // 10% interest
        $totalPayment = $principal + $interestAmount;

        // Check if user is eligible for loan
        $user = User::findOrFail($validated['user_id']);
        $member = $user->member;

        if (!$member) {
            return back()->with('error', 'User is not a member.')->withInput();
        }

        if (!$member->isEligibleForLoan()) {
            $errors = [];

            // Check membership duration
            $minMonths = config('business_rules.loan_eligibility.minimum_membership_months', 6);
            if ($member->membership_duration_months < $minMonths) {
                $errors[] = "Member must have at least {$minMonths} months of membership.";
            }

            // Check entrance fee
            if (!$member->entrance_fee_paid) {
                $errors[] = "Member must have paid the entrance fee.";
            }

            return back()->with('error', 'User is not eligible for a loan: ' . implode(' ', $errors))->withInput();
        }

        // Check if loan amount is within eligible limit
        $maxLoanAmount = $member->max_loan_amount;
        if ($principal > $maxLoanAmount) {
            return back()->with('error', "Loan amount exceeds the maximum eligible amount of ₦" . number_format($maxLoanAmount, 2))->withInput();
        }

        try {
            DB::beginTransaction();

            $loan = new Loan();
            $loan->user_id = $validated['user_id'];
            $loan->loan_number = Loan::generateLoanNumber();
            $loan->amount = $principal;
            $loan->interest_rate = $interestRate;
            $loan->term_months = 0; // No fixed term
            $loan->monthly_payment = 0; // No fixed monthly payment
            $loan->total_payment = round($totalPayment, 2);
            $loan->remaining_balance = $principal; // Set initial remaining balance to principal only
            $loan->purpose = $validated['purpose'] ?? null;
            $loan->repayment_method = $validated['repayment_method'];
            $loan->status = 'pending';
            $loan->submitted_at = now();
            $loan->save();

            // Auto-approve and disburse the loan if created by admin
            if (Auth::user()->role === 'admin') {
                $loan->status = 'active';
                $loan->approved_at = now();
                $loan->approved_by = Auth::id();
                $loan->disbursed_at = now(); // Auto-disburse upon approval
                $loan->save();

                // Create transaction group for loan disbursement
                $transactionGroupService = new \App\Services\TransactionGroupService();
                $groupTitle = "Loan Disbursement - {$loan->user->name} ({$loan->loan_number})";
                $transactionGroup = $transactionGroupService->createGroup(
                    'loan_disbursement',
                    $groupTitle,
                    "Loan of ₦" . number_format($loan->amount, 2) . " disbursed to {$loan->user->name}",
                    [
                        'loan_id' => $loan->id,
                        'loan_number' => $loan->loan_number,
                        'user_id' => $loan->user_id,
                        'amount' => $loan->amount
                    ],
                    Auth::id()
                );

                // Create transaction record for loan disbursement
                \App\Models\Transaction::create([
                    'user_id' => $loan->user_id,
                    'type' => 'loan_disbursement',
                    'amount' => $loan->amount,
                    'description' => "Loan disbursement - {$loan->loan_number}",
                    'reference' => 'LOAN-DISB-' . date('Ymd') . '-' . str_pad($loan->user_id, 4, '0', STR_PAD_LEFT) . '-' . $loan->id,
                    'status' => 'completed',
                    'transaction_date' => now(),
                    'group_id' => $transactionGroup->id,
                ]);

                // Create transaction record for loan interest (10%)
                \App\Models\Transaction::create([
                    'user_id' => $loan->user_id,
                    'type' => 'loan_interest',
                    'amount' => $interestAmount,
                    'description' => "Loan Interest (10%) - {$loan->loan_number}",
                    'reference' => 'LOAN-INT-' . date('Ymd') . '-' . str_pad($loan->user_id, 4, '0', STR_PAD_LEFT) . '-' . $loan->id,
                    'status' => 'completed',
                    'transaction_date' => now(),
                    'group_id' => $transactionGroup->id,
                ]);

                // Complete the transaction group
                $transactionGroup->update([
                    'total_amount' => $loan->amount,
                    'total_records' => 1,
                    'status' => 'completed'
                ]);

                // No repayment schedule needed - flexible repayment within 24 months

                // Update the member's total loan amount
                if ($member) {
                    $member->updateTotalLoanAmount($loan->amount);
                }
            }

            DB::commit();

            return redirect()->route('admin.loans.index')
                ->with('success', 'Loan created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create loan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified loan.
     */
    public function show(Loan $loan)
    {
        $loan->load(['user', 'user.department', 'approver', 'rejecter', 'repayments']);
        return view('admin.loans.show', compact('loan'));
    }

    /**
     * Approve the specified loan.
     */
    public function approve(Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return back()->with('error', 'This loan cannot be approved because it is not pending.');
        }

        try {
            DB::beginTransaction();

            $loan->status = 'active';
            $loan->approved_at = now();
            $loan->approved_by = Auth::id();
            $loan->disbursed_at = now(); // Auto-disburse upon approval
            $loan->save();

            // Create transaction group for loan disbursement
            $transactionGroupService = new \App\Services\TransactionGroupService();
            $groupTitle = "Loan Disbursement - {$loan->user->name} ({$loan->loan_number})";
            $transactionGroup = $transactionGroupService->createGroup(
                'loan_disbursement',
                $groupTitle,
                "Loan of ₦" . number_format($loan->amount, 2) . " disbursed to {$loan->user->name}",
                [
                    'loan_id' => $loan->id,
                    'loan_number' => $loan->loan_number,
                    'user_id' => $loan->user_id,
                    'amount' => $loan->amount
                ],
                Auth::id()
            );

            // Create transaction record for loan disbursement
            \App\Models\Transaction::create([
                'user_id' => $loan->user_id,
                'type' => 'loan_disbursement',
                'amount' => $loan->amount,
                'description' => "Loan disbursement - {$loan->loan_number}",
                'reference' => 'LOAN-DISB-' . date('Ymd') . '-' . str_pad($loan->user_id, 4, '0', STR_PAD_LEFT) . '-' . $loan->id,
                'status' => 'completed',
                'transaction_date' => now(),
                'group_id' => $transactionGroup->id,
            ]);

            // Create transaction record for loan interest (10%)
            $interestRate = $loan->interest_rate > 1 ? $loan->interest_rate / 100 : $loan->interest_rate;
            $interestAmount = $loan->amount * $interestRate;

            \App\Models\Transaction::create([
                'user_id' => $loan->user_id,
                'type' => 'loan_interest',
                'amount' => $interestAmount,
                'description' => "Loan Interest (10%) - {$loan->loan_number}",
                'reference' => 'LOAN-INT-' . date('Ymd') . '-' . str_pad($loan->user_id, 4, '0', STR_PAD_LEFT) . '-' . $loan->id,
                'status' => 'completed',
                'transaction_date' => now(),
                'group_id' => $transactionGroup->id,
            ]);

            // Complete the transaction group
            $transactionGroup->update([
                'total_amount' => $loan->amount,
                'total_records' => 1,
                'status' => 'completed'
            ]);

            // No repayment schedule needed - flexible repayment within 24 months

            // Update the member's total loan amount
            $member = $loan->user->member;
            if ($member) {
                $member->updateTotalLoanAmount($loan->amount);
            }

            // Create notification for the loan applicant
            NotificationController::createNotification(
                $loan->user_id,
                'Loan Approved',
                'Your loan application for ₦' . number_format($loan->amount, 2) . ' has been approved.',
                'loan_approval',
                route('user.loan_board'),
                [
                    'loan_id' => $loan->id,
                    'loan_number' => $loan->loan_number,
                    'amount' => $loan->amount,
                ]
            );

            DB::commit();

            return redirect()->route('admin.loans.pending-approvals')
                ->with('success', 'Loan approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve loan: ' . $e->getMessage());
        }
    }

    /**
     * Reject the specified loan.
     */
    public function reject(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if ($loan->status !== 'pending') {
            return back()->with('error', 'This loan cannot be rejected because it is not pending.');
        }

        $loan->status = 'rejected';
        $loan->rejected_at = now();
        $loan->rejected_by = Auth::id();
        $loan->rejection_reason = $validated['rejection_reason'];
        $loan->save();

        // Create notification for the loan applicant
        NotificationController::createNotification(
            $loan->user_id,
            'Loan Application Rejected',
            'Your loan application for ₦' . number_format($loan->amount, 2) . ' has been rejected.',
            'loan_rejection',
            route('user.loan_board'),
            [
                'loan_id' => $loan->id,
                'loan_number' => $loan->loan_number,
                'amount' => $loan->amount,
                'rejection_reason' => $validated['rejection_reason'],
            ]
        );

        return redirect()->route('admin.loans.pending-approvals')
            ->with('success', 'Loan rejected successfully.');
    }




}