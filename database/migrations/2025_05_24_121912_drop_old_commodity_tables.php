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
        // Drop foreign key constraint in transactions table referencing commodities table
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['commodity_id']);
            $table->dropColumn('commodity_id');
        });

        Schema::dropIfExists('commodities');
        Schema::dropIfExists('commodity_requests');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-create the tables that were dropped in the up method
        // This might not perfectly replicate the original tables if there were multiple
        // migrations modifying them, but it provides a basic rollback capability.

        if (!Schema::hasTable('commodities')) {
            Schema::create('commodities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('commodity_name');
                $table->string('category')->default('Other');
                $table->decimal('price', 12, 2);
                $table->integer('quantity');
                $table->string('status')->default('active'); // active, inactive, out_of_stock
                $table->text('description')->nullable();
                $table->string('image_path')->nullable();
                $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('commodity_requests')) {
             Schema::create('commodity_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('commodity_item_id')->constrained('commodity_items')->onDelete('cascade');
                $table->integer('quantity');
                $table->string('status')->default('pending'); // pending, approved, rejected
                $table->text('notes')->nullable();
                $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }
    }
};
