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
        Schema::table('available_commodities', function (Blueprint $table) {
            $table->string('commodity_type')->after('description')->nullable(); // Add commodity_type column
            $table->string('status')->after('commodity_type')->default('active'); // Add status column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('available_commodities', function (Blueprint $table) {
            $table->dropColumn('commodity_type');
            $table->dropColumn('status');
        });
    }
};
