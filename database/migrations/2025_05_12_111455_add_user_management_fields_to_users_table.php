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
        Schema::table('users', function (Blueprint $table) {
            // Add member number field
            $table->string('member_number')->nullable()->unique()->after('email');

            // Add status field with default 'active'
            $table->string('status')->default('active')->after('role');

            // Add verification fields
            $table->timestamp('verified_at')->nullable()->after('email_verified_at');
            $table->unsignedBigInteger('verified_by')->nullable()->after('verified_at');

            // Add approval fields
            $table->timestamp('approved_at')->nullable()->after('verified_by');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');

            // Add rejection fields
            $table->timestamp('rejected_at')->nullable()->after('approved_by');
            $table->unsignedBigInteger('rejected_by')->nullable()->after('rejected_at');
            $table->text('rejection_reason')->nullable()->after('rejected_by');

            // Add reference number for membership applications
            $table->string('reference_number')->nullable()->unique()->after('rejection_reason');

            // Add password change required flag
            $table->boolean('password_change_required')->default(false)->after('password');

            // Add foreign key constraints
            $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('rejected_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);

            // Drop columns
            $table->dropColumn('member_number');
            $table->dropColumn('status');
            $table->dropColumn('verified_at');
            $table->dropColumn('verified_by');
            $table->dropColumn('approved_at');
            $table->dropColumn('approved_by');
            $table->dropColumn('rejected_at');
            $table->dropColumn('rejected_by');
            $table->dropColumn('rejection_reason');
            $table->dropColumn('reference_number');
            $table->dropColumn('password_change_required');
        });
    }
};
