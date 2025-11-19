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
        Schema::dropIfExists('business_rules');

        Schema::create('business_rules', function (Blueprint $table) {
            $table->id();
            $table->string('rule_key')->unique();
            $table->string('rule_value');
            $table->string('rule_type')->default('text'); // text, number, boolean, etc.
            $table->string('category');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default business rules
        $this->seedDefaultRules();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_rules');

        Schema::create('business_rules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('category');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Seed default business rules
     */
    private function seedDefaultRules()
    {
        $rules = [
            // Membership Rules
            [
                'rule_key' => 'minimum_initial_deposit',
                'rule_value' => '20000',
                'rule_type' => 'number',
                'category' => 'membership',
                'description' => 'Minimum initial deposit required for membership',
            ],
            [
                'rule_key' => 'entrance_fee',
                'rule_value' => '2000',
                'rule_type' => 'number',
                'category' => 'membership',
                'description' => 'One-time entrance fee deducted from initial deposit',
            ],

            // Share Rules
            [
                'rule_key' => 'maximum_share_contribution',
                'rule_value' => '10000',
                'rule_type' => 'number',
                'category' => 'shares',
                'description' => 'Maximum share contribution allowed',
            ],

            // Loan Eligibility Rules
            [
                'rule_key' => 'minimum_membership_months',
                'rule_value' => '6',
                'rule_type' => 'number',
                'category' => 'loan_eligibility',
                'description' => 'Minimum membership duration in months before loan eligibility',
            ],
            [
                'rule_key' => 'loan_multiplier',
                'rule_value' => '2',
                'rule_type' => 'number',
                'category' => 'loan_eligibility',
                'description' => 'Multiplier for (Savings + Shares) to determine maximum loan amount',
            ],

            // Loan Terms
            [
                'rule_key' => 'interest_rate',
                'rule_value' => '10',
                'rule_type' => 'number',
                'category' => 'loan_terms',
                'description' => 'Interest rate percentage for loans',
            ],
            [
                'rule_key' => 'repayment_period',
                'rule_value' => '24',
                'rule_type' => 'number',
                'category' => 'loan_terms',
                'description' => 'Maximum repayment period in months',
            ],
            [
                'rule_key' => 'repayment_method',
                'rule_value' => 'bursary_deduction',
                'rule_type' => 'select',
                'category' => 'loan_terms',
                'description' => 'Default repayment method',
            ],

            // Application Process
            [
                'rule_key' => 'online_notification',
                'rule_value' => 'true',
                'rule_type' => 'boolean',
                'category' => 'application_process',
                'description' => 'Online application serves as notification',
            ],
            [
                'rule_key' => 'physical_form_required',
                'rule_value' => 'true',
                'rule_type' => 'boolean',
                'category' => 'application_process',
                'description' => 'Physical form with signatures required',
            ],
        ];

        foreach ($rules as $rule) {
            DB::table('business_rules')->insert($rule);
        }
    }
};
