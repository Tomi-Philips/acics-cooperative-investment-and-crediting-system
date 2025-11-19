<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClearLoansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to allow truncating tables with foreign key constraints
        Schema::disableForeignKeyConstraints();
        
        // Truncate the loans table
        DB::table('loans')->truncate();
        
        // Also truncate related tables if they exist
        if (Schema::hasTable('loan_repayments')) {
            DB::table('loan_repayments')->truncate();
        }
        
        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
        
        $this->command->info('All loan records have been cleared successfully.');
    }
}
