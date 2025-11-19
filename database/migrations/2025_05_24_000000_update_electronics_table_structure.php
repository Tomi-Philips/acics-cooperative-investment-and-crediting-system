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
        // First drop the electronics_payments table that references electronics
        Schema::dropIfExists('electronics_payments');
        
        // Then modify the electronics table
        Schema::table('electronics', function (Blueprint $table) {
            // Drop existing columns
            $table->dropColumn([
                'electronics_name',
                'category',
                'price',
                'quantity',
                'status',
                'image_path',
                'total_amount',
                'remaining_balance',
                'total_paid'
            ]);
            
            // Add new columns
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('transaction_type')->default('initial');
            $table->string('payment_method')->nullable();
            $table->string('reference_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the original structure
        Schema::table('electronics', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn([
                'amount',
                'transaction_type',
                'payment_method',
                'reference_number'
            ]);
            
            // Add back original columns
            $table->string('electronics_name');
            $table->string('category');
            $table->decimal('price', 10, 2);
            $table->integer('quantity')->default(1);
            $table->string('status')->default('active');
            $table->string('image_path')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('remaining_balance', 10, 2);
            $table->decimal('total_paid', 10, 2)->default(0);
        });
        
        // Recreate electronics_payments table
        Schema::create('electronics_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('electronics_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->string('transaction_id')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('payment_date')->nullable();
            $table->timestamps();
        });
    }
};