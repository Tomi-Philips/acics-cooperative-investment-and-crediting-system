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
        Schema::table('business_rules', function (Blueprint $table) {
            if (!Schema::hasColumn('business_rules', 'rule_key')) {
                $table->string('rule_key')->nullable()->after('id');
            }

            if (!Schema::hasColumn('business_rules', 'rule_value')) {
                $table->string('rule_value')->nullable()->after('description');
            }

            if (!Schema::hasColumn('business_rules', 'rule_type')) {
                $table->string('rule_type')->default('text')->after('rule_value');
            }
        });

        // Insert default business rules if the table is empty
        if (DB::table('business_rules')->count() === 0) {
            $this->seedDefaultRules();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_rules', function (Blueprint $table) {
            $table->dropColumn(['rule_key', 'rule_value', 'rule_type']);
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
                'title' => 'Minimum Initial Deposit',
                'description' => 'Minimum initial deposit required for membership',
                'rule_key' => 'minimum_initial_deposit',
                'rule_value' => '20000',
                'rule_type' => 'number',
                'category' => 'membership',
                'is_active' => true,
            ],
            [
                'title' => 'Entrance Fee',
                'description' => 'One-time entrance fee deducted from initial deposit',
                'rule_key' => 'entrance_fee',
                'rule_value' => '2000',
                'rule_type' => 'number',
                'category' => 'membership',
                'is_active' => true,
            ],

            // Share Rules
            [
                'title' => 'Maximum Share Contribution',
                'description' => 'Maximum share contribution allowed',
                'rule_key' => 'maximum_share_contribution',
                'rule_value' => '10000',
                'rule_type' => 'number',
                'category' => 'shares',
                'is_active' => true,
            ],

            // Loan Eligibility Rules
            [
                'title' => 'Minimum Membership Duration',
                'description' => 'Minimum membership duration in months before loan eligibility',
                'rule_key' => 'minimum_membership_months',
                'rule_value' => '6',
                'rule_type' => 'number',
                'category' => 'loan_eligibility',
                'is_active' => true,
            ],
            [
                'title' => 'Loan Amount Multiplier',
                'description' => 'Multiplier for (Savings + Shares) to determine maximum loan amount',
                'rule_key' => 'loan_multiplier',
                'rule_value' => '2',
                'rule_type' => 'number',
                'category' => 'loan_eligibility',
                'is_active' => true,
            ],

            // Loan Terms
            [
                'title' => 'Interest Rate',
                'description' => 'Interest rate percentage for loans',
                'rule_key' => 'interest_rate',
                'rule_value' => '10',
                'rule_type' => 'number',
                'category' => 'loan_terms',
                'is_active' => true,
            ],
            [
                'title' => 'Repayment Period',
                'description' => 'Maximum repayment period in months',
                'rule_key' => 'repayment_period',
                'rule_value' => '24',
                'rule_type' => 'number',
                'category' => 'loan_terms',
                'is_active' => true,
            ],
            [
                'title' => 'Repayment Method',
                'description' => 'Default repayment method',
                'rule_key' => 'repayment_method',
                'rule_value' => 'bursary_deduction',
                'rule_type' => 'select',
                'category' => 'loan_terms',
                'is_active' => true,
            ],

            // Application Process
            [
                'title' => 'Online Notification',
                'description' => 'Online application serves as notification',
                'rule_key' => 'online_notification',
                'rule_value' => 'true',
                'rule_type' => 'boolean',
                'category' => 'application_process',
                'is_active' => true,
            ],
            [
                'title' => 'Physical Form Required',
                'description' => 'Physical form with signatures required',
                'rule_key' => 'physical_form_required',
                'rule_value' => 'true',
                'rule_type' => 'boolean',
                'category' => 'application_process',
                'is_active' => true,
            ],
        ];

        foreach ($rules as $rule) {
            DB::table('business_rules')->insert($rule);
        }
    }
};
