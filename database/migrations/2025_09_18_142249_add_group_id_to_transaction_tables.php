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
        // Add group_id to transactions table
        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->foreignId('group_id')->nullable()->after('id')->constrained('transaction_groups')->onDelete('set null');
                $table->index(['group_id']);
            });
        }

        // Add group_id to share_transactions table
        if (Schema::hasTable('share_transactions')) {
            Schema::table('share_transactions', function (Blueprint $table) {
                $table->foreignId('group_id')->nullable()->after('id')->constrained('transaction_groups')->onDelete('set null');
                $table->index(['group_id']);
            });
        }

        // Add group_id to saving_transactions table
        if (Schema::hasTable('saving_transactions')) {
            Schema::table('saving_transactions', function (Blueprint $table) {
                $table->foreignId('group_id')->nullable()->after('id')->constrained('transaction_groups')->onDelete('set null');
                $table->index(['group_id']);
            });
        }

        // Add group_id to commodity_transactions table
        if (Schema::hasTable('commodity_transactions')) {
            Schema::table('commodity_transactions', function (Blueprint $table) {
                $table->foreignId('group_id')->nullable()->after('id')->constrained('transaction_groups')->onDelete('set null');
                $table->index(['group_id']);
            });
        }

        // Add group_id to loan_payments table
        if (Schema::hasTable('loan_payments')) {
            Schema::table('loan_payments', function (Blueprint $table) {
                $table->foreignId('group_id')->nullable()->after('id')->constrained('transaction_groups')->onDelete('set null');
                $table->index(['group_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove group_id from all tables
        $tables = ['transactions', 'share_transactions', 'saving_transactions', 'commodity_transactions', 'loan_payments'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->dropForeign(['group_id']);
                    $blueprint->dropColumn('group_id');
                });
            }
        }
    }
};
