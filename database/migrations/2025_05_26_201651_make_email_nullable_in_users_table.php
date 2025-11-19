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
        Schema::table('users', function (Blueprint $table) {
            // Make email column nullable and remove unique constraint temporarily
            $table->dropUnique(['email']);
            $table->string('email')->nullable()->change();

            // Add unique constraint back but only for non-null values
            $table->unique('email', 'users_email_unique_not_null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove the custom unique constraint
            $table->dropUnique('users_email_unique_not_null');

            // Make email required again and add back unique constraint
            $table->string('email')->nullable(false)->change();
            $table->unique('email');
        });
    }
};
