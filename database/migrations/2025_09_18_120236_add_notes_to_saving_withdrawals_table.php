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
        Schema::table('saving_withdrawals', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('status');
            $table->timestamp('processed_at')->nullable()->after('notes');
            $table->foreignId('processed_by')->nullable()->after('processed_at')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saving_withdrawals', function (Blueprint $table) {
            $table->dropForeign(['processed_by']);
            $table->dropColumn(['notes', 'processed_at', 'processed_by']);
        });
    }
};
