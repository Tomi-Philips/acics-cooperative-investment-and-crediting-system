<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->decimal('remaining_balance', 12, 2)->after('total_payment')->default(0);
        });

        // Update existing loans to set the remaining balance
        DB::statement('UPDATE loans SET remaining_balance = total_payment WHERE status IN ("pending", "approved", "active")');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn('remaining_balance');
        });
    }
};
