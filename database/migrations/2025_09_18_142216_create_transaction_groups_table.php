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
        Schema::create('transaction_groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_type'); // mab_bulk_upload, manual_transaction, admin_approval, etc.
            $table->string('group_reference')->unique(); // Unique reference for the group
            $table->string('title'); // Display title for the group
            $table->text('description')->nullable(); // Description of the group
            $table->decimal('total_amount', 15, 2)->default(0); // Total amount in the group
            $table->integer('total_records')->default(0); // Number of transactions in the group
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            $table->json('metadata')->nullable(); // Additional data like upload info, etc.
            $table->timestamps();

            $table->index(['group_type', 'status']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_groups');
    }
};
