<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Member;
use App\Models\Loan;
use App\Models\Saving;
use App\Models\Share;
use App\Models\UserCommodity;
use App\Models\CommodityTransaction;
use App\Models\ShareTransaction;
use App\Models\SavingTransaction;
use App\Models\Transaction;
use App\Models\UserBulkUpload;

use App\Models\LoanPayment;
use App\Models\TransactionGroup;
use App\Models\MonthlyUpload;

class ClearMemberData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'members:clear {--force : Force the operation without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all member data and financial records (preserves admin users)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('This will delete ALL member data and financial records. Are you sure?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info('Starting member data cleanup...');

        try {
            DB::beginTransaction();

            // Get admin users to preserve them
            $adminUsers = User::where('role', 'admin')->pluck('id')->toArray();
            $this->info('Preserving ' . count($adminUsers) . ' admin user(s)');

            // Get member users (non-admin users)
            $memberUsers = User::where('role', '!=', 'admin')->pluck('id')->toArray();
            $this->info('Found ' . count($memberUsers) . ' member user(s) to delete');

            if (empty($memberUsers)) {
                $this->info('No member users found to delete.');
                DB::rollBack();
                return 0;
            }

            // Delete financial records first (due to foreign key constraints)
            $this->deleteFinancialRecords($memberUsers);

            // Delete member profiles
            $deletedMembers = Member::whereIn('user_id', $memberUsers)->delete();
            $this->info("Deleted {$deletedMembers} member profiles");

            // Delete bulk upload records (user + monthly)
            $deletedUserUploads = UserBulkUpload::query()->delete();
            $this->info("Deleted {$deletedUserUploads} user bulk upload records");
            try {
                $deletedMonthlyUploads = MonthlyUpload::query()->delete();
                $this->info("Deleted {$deletedMonthlyUploads} monthly upload records");
            } catch (\Throwable $e) {
                $this->warn('Could not delete monthly uploads: ' . $e->getMessage());
            }

            // Finally delete the member users
            $deletedUsers = User::whereIn('id', $memberUsers)->delete();
            $this->info("Deleted {$deletedUsers} member users");

            DB::commit();

            $this->info('✅ Member data cleanup completed successfully!');
            $this->info('You can now test the bulk upload functionality with a clean database.');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Error during cleanup: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Delete all financial records for the given user IDs
     */
    private function deleteFinancialRecords(array $userIds)
    {
        if (empty($userIds)) {
            return;
        }

        $this->info('Deleting financial records...');

        // Delete transactions tied to the users
        $deletedTransactions = Transaction::whereIn('user_id', $userIds)->delete();
        $this->info("- Deleted {$deletedTransactions} general transactions");

        // Delete share transactions
        $deletedShareTransactions = ShareTransaction::whereIn('user_id', $userIds)->delete();
        $this->info("- Deleted {$deletedShareTransactions} share transactions");

        // Delete saving transactions
        $deletedSavingTransactions = SavingTransaction::whereIn('user_id', $userIds)->delete();
        $this->info("- Deleted {$deletedSavingTransactions} saving transactions");

        // Delete commodity transactions
        $deletedCommodityTransactions = CommodityTransaction::whereIn('user_id', $userIds)->delete();
        $this->info("- Deleted {$deletedCommodityTransactions} commodity transactions");

        // Delete loan payments (if any)
        try {
            $deletedLoanPayments = LoanPayment::whereIn('user_id', $userIds)->delete();
            $this->info("- Deleted {$deletedLoanPayments} loan payment records");
        } catch (\Throwable $e) {
            $this->warn('- Could not delete loan payment records: ' . $e->getMessage());
        }

        // Also delete any remaining transactions that belong to transaction groups we will remove
        try {
            $groupIds = TransactionGroup::pluck('id');
            if ($groupIds->isNotEmpty()) {
                $deletedGroupedTransactions = Transaction::whereIn('group_id', $groupIds)->delete();
                $this->info("- Deleted {$deletedGroupedTransactions} grouped transactions");
            }
        } catch (\Throwable $e) {
            $this->warn('- Could not delete grouped transactions: ' . $e->getMessage());
        }

        // Now delete all transaction groups (clears the dashboard table)
        try {
            $deletedGroups = TransactionGroup::query()->delete();
            $this->info("- Deleted {$deletedGroups} transaction groups");
        } catch (\Throwable $e) {
            $this->warn('- Could not delete transaction groups: ' . $e->getMessage());
        }

        // Delete shares
        $deletedShares = Share::whereIn('user_id', $userIds)->delete();
        $this->info("- Deleted {$deletedShares} share records");

        // Delete savings
        $deletedSavings = Saving::whereIn('user_id', $userIds)->delete();
        $this->info("- Deleted {$deletedSavings} saving records");

        // Delete loans
        $deletedLoans = Loan::whereIn('user_id', $userIds)->delete();
        $this->info("- Deleted {$deletedLoans} loan records");

        // Delete user commodities
        $deletedUserCommodities = UserCommodity::whereIn('user_id', $userIds)->delete();
        $this->info("- Deleted {$deletedUserCommodities} user commodity records");

        // Delete electronics (if the model exists)
        try {
            if (class_exists('\App\Models\Electronics')) {
                $deletedElectronics = \App\Models\Electronics::whereIn('user_id', $userIds)->delete();
                $this->info("- Deleted {$deletedElectronics} electronics records");
            }
        } catch (\Exception $e) {
            $this->warn("- Could not delete electronics records: " . $e->getMessage());
        }
    }
}
