<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change enum `type` to VARCHAR(50) to allow flexible transaction types
        DB::statement("ALTER TABLE `transactions` MODIFY `type` VARCHAR(50) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to the original ENUM definition
        // NOTE: This may fail if rows contain values outside the enum set.
        DB::statement("ALTER TABLE `transactions` MODIFY `type` ENUM('deposit','withdrawal','loan_payment','loan_disbursement','share_purchase','commodity_purchase') NOT NULL");
    }
};

