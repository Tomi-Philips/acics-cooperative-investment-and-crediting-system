<?php

namespace Database\Seeders;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all member users
        $members = User::where('role', 'member')->get();
        
        if ($members->isEmpty()) {
            $this->command->info('No member users found. Please run UserSeeder first.');
            return;
        }
        
        // Create sample loans
        foreach ($members as $index => $member) {
            // Create a pending loan
            $this->createLoan($member, 'pending');
            
            // Create an approved loan for some members
            if ($index % 2 === 0) {
                $this->createLoan($member, 'approved');
            }
            
            // Create a rejected loan for some members
            if ($index % 3 === 0) {
                $this->createLoan($member, 'rejected');
            }
        }
        
        $this->command->info('Sample loans created successfully.');
    }
    
    /**
     * Create a loan with the given status for a member.
     */
    private function createLoan(User $member, string $status): Loan
    {
        $amount = rand(10000, 100000);
        $interestRate = 10.0;
        $termMonths = rand(3, 24);
        
        // Calculate monthly payment and total payment
        $principal = $amount;
        $rate = $interestRate / 100 / 12; // Monthly interest rate
        
        // Monthly payment formula: P * r * (1 + r)^n / ((1 + r)^n - 1)
        $monthlyPayment = $principal * $rate * pow(1 + $rate, $termMonths) / (pow(1 + $rate, $termMonths) - 1);
        $totalPayment = $monthlyPayment * $termMonths;
        
        $loan = new Loan();
        $loan->user_id = $member->id;
        $loan->loan_number = Loan::generateLoanNumber();
        $loan->amount = $amount;
        $loan->interest_rate = $interestRate;
        $loan->term_months = $termMonths;
        $loan->monthly_payment = round($monthlyPayment, 2);
        $loan->total_payment = round($totalPayment, 2);
        $loan->purpose = 'Sample loan for testing';
        $loan->repayment_method = 'bursary_deduction';
        $loan->status = $status;
        $loan->submitted_at = now()->subDays(rand(1, 30));
        
        // Set approval/rejection details if applicable
        if ($status === 'approved') {
            $admin = User::where('role', 'admin')->first();
            $loan->approved_at = now()->subDays(rand(1, 15));
            $loan->approved_by = $admin ? $admin->id : null;
        } elseif ($status === 'rejected') {
            $admin = User::where('role', 'admin')->first();
            $loan->rejected_at = now()->subDays(rand(1, 15));
            $loan->rejected_by = $admin ? $admin->id : null;
            $loan->rejection_reason = 'Sample rejection reason for testing';
        }
        
        $loan->save();
        
        return $loan;
    }
}
