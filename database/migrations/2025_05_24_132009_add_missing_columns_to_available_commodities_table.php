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
            $table->string('category')->nullable()->after('name');
            $table->integer('quantity')->default(0)->after('price');
            $table->string('status')->default('available')->after('quantity');
            // Assuming 'image' column should be renamed to 'image_path' and kept nullable
            $table->renameColumn('image', 'image_path');
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('available_commodities', function (Blueprint $table) {
            $table->dropForeign(['processed_by']);
            $table->dropColumn('processed_by');
            $table->renameColumn('image_path', 'image');
            $table->dropColumn('status');
            $table->dropColumn('quantity');
            $table->dropColumn('category');
        });
    }
};
