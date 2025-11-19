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
            if (!Schema::hasColumn('transactions', 'transaction_number')) {
                $table->string('transaction_number')->nullable()->after('user_id');
            }
            
            if (!Schema::hasColumn('transactions', 'category')) {
                $table->string('category')->nullable()->after('type');
            }
            
            if (!Schema::hasColumn('transactions', 'charges')) {
                $table->decimal('charges', 10, 2)->default(0)->after('amount');
            }
            
            if (!Schema::hasColumn('transactions', 'net_amount')) {
                $table->decimal('net_amount', 10, 2)->default(0)->after('charges');
            }
            
            if (!Schema::hasColumn('transactions', 'method')) {
                $table->string('method')->nullable()->after('net_amount');
            }
            
            if (!Schema::hasColumn('transactions', 'processed_by')) {
                $table->foreignId('processed_by')->nullable()->after('reference')->constrained('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('transactions', 'processed_at')) {
                $table->timestamp('processed_at')->nullable()->after('processed_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'transaction_number',
                'category',
                'charges',
                'net_amount',
                'method',
                'processed_at'
            ]);
            
            if (Schema::hasColumn('transactions', 'processed_by')) {
                $table->dropForeign(['processed_by']);
                $table->dropColumn('processed_by');
            }
        });
    }
};
