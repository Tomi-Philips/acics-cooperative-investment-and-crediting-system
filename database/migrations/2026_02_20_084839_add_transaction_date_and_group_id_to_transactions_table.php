<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Add transaction_date column if it doesn't already exist
            if (!Schema::hasColumn('transactions', 'transaction_date')) {
                $table->timestamp('transaction_date')->nullable()->after('status');
            }

            // Add group_id column if it doesn't already exist
            if (!Schema::hasColumn('transactions', 'group_id')) {
                $table->unsignedBigInteger('group_id')->nullable()->after('transaction_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'transaction_date')) {
                $table->dropColumn('transaction_date');
            }
            if (Schema::hasColumn('transactions', 'group_id')) {
                $table->dropColumn('group_id');
            }
        });
    }
};
