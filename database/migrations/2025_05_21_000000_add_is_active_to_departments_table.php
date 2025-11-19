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
        // Check if is_active column doesn't exist on departments table
        if (!Schema::hasColumn('departments', 'is_active')) {
            Schema::table('departments', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('description');
            });
        }

        // Update existing departments to be active
        DB::table('departments')->update(['is_active' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('departments', 'is_active')) {
            Schema::table('departments', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }
};