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
        Schema::table('monthly_uploads', function (Blueprint $table) {
            $table->dropUnique('unique_monthly_upload');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_uploads', function (Blueprint $table) {
            $table->unique(['year', 'month', 'upload_type'], 'unique_monthly_upload');
        });
    }
};
