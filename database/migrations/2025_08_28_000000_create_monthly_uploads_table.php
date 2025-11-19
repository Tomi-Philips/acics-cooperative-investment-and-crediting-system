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
        Schema::create('monthly_uploads', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('month'); // 1-12
            $table->string('upload_type')->default('financial_records'); // financial_records, commodities, etc.
            $table->string('file_name');
            $table->string('file_path')->nullable();
            $table->integer('total_records');
            $table->integer('processed_records');
            $table->integer('failed_records')->default(0);
            $table->json('update_fields'); // Store which fields were updated
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('upload_started_at')->nullable();
            $table->timestamp('upload_completed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->json('processing_summary')->nullable(); // Store summary of what was processed
            $table->timestamps();

            // Ensure only one upload per month per type
            $table->unique(['year', 'month', 'upload_type'], 'unique_monthly_upload');
            
            // Index for quick lookups
            $table->index(['year', 'month']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_uploads');
    }
};
