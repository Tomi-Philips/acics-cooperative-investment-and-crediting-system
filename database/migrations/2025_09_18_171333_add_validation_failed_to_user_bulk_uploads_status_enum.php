<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'validation_failed' to the enum values for the status column
        DB::statement("ALTER TABLE user_bulk_uploads MODIFY COLUMN status ENUM('pending', 'processing', 'completed', 'failed', 'validation_failed') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'validation_failed' from the enum values for the status column
        DB::statement("ALTER TABLE user_bulk_uploads MODIFY COLUMN status ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending'");
    }
};
