<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all member users
        $users = User::where('role', 'member')->get();

        if ($users->isEmpty()) {
            $this->command->info('No member users found. Please run UserSeeder first.');
            return;
        }

        foreach ($users as $index => $user) {
            // Skip if member already exists
            if ($user->member) {
                continue;
            }

            // Create member record
            $member = new Member();
            $member->user_id = $user->id;
            $member->member_number = 'MEM' . str_pad($user->id, 5, '0', STR_PAD_LEFT);
            $member->entrance_fee_paid = true; // Set to true for existing users
            $member->joined_at = Carbon::now()->subMonths(rand(6, 24)); // Random join date between 6 and 24 months ago
            $member->save();

            // Create initial financial records
            $this->createInitialFinancialRecords($user);
        }

        $this->command->info('Member data created successfully.');
    }

    /**
     * Create initial financial records for a user.
     */
    private function createInitialFinancialRecords(User $user): void
    {
        // Create initial savings
        $savingsAmount = rand(5000, 50000);
        $user->savings()->create([
            'amount' => $savingsAmount,
            'transaction_type' => 'deposit',
            'payment_method' => 'bank_transfer',
            'reference_number' => 'INIT' . $user->id . 'SAV',
            'description' => 'Initial savings deposit',
            'processed_by' => 1, // Admin user
        ]);

        // Create initial shares
        $sharesAmount = rand(1000, 10000);
        $user->shares()->create([
            'amount' => $sharesAmount,
            'transaction_type' => 'purchase',
            'payment_method' => 'bank_transfer',
            'reference_number' => 'INIT' . $user->id . 'SHR',
            'description' => 'Initial shares purchase',
            'processed_by' => 1, // Admin user
        ]);

        // Randomly create a commodity balance for some users
        if (rand(0, 1) === 1) {
            $commodityAmount = rand(2000, 20000);
            $user->userCommodities()->create([
                'commodity_name' => 'General Commodity',
                'balance' => $commodityAmount,
            ]);
        }
    }
}
