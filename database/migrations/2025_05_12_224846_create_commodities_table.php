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
        // Check if the table already exists
        if (!Schema::hasTable('commodities')) {
            Schema::create('commodities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('commodity_name');
                $table->string('category');
                $table->decimal('price', 12, 2);
                $table->integer('quantity');
                $table->string('status')->default('active'); // active, inactive, out_of_stock
                $table->text('description')->nullable();
                $table->string('image_path')->nullable();
                $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        } else {
            // Add image_path column if it doesn't exist
            if (!Schema::hasColumn('commodities', 'image_path')) {
                Schema::table('commodities', function (Blueprint $table) {
                    $table->string('image_path')->nullable()->after('description');
                });
            }

            // Add category column if it doesn't exist
            if (!Schema::hasColumn('commodities', 'category')) {
                Schema::table('commodities', function (Blueprint $table) {
                    $table->string('category')->after('commodity_name')->default('Other');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commodities');
    }
};
