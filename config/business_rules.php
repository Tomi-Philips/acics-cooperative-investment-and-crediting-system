<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Membership Rules
    |--------------------------------------------------------------------------
    */
    'membership' => [
        'minimum_initial_deposit' => 20000, // ₦20,000
        'entrance_fee' => 1000, // Amount to be deducted once
    ],

    /*
    |--------------------------------------------------------------------------
    | Share Rules
    |--------------------------------------------------------------------------
    */
    'shares' => [
        'maximum_contribution' => 10000, // ₦10,000 maximum share contribution
    ],

    /*
    |--------------------------------------------------------------------------
    | Loan Eligibility Rules
    |--------------------------------------------------------------------------
    */
    'loan_eligibility' => [
        'minimum_membership_months' => 6, // 6+ months of membership
        'entrance_fee_required' => true, // Must have paid entrance fee
        'multiplier' => 2, // Loan amount = 2 × (Savings + Shares - Loan - Commodity - Electronics)
    ],

    /*
    |--------------------------------------------------------------------------
    | Loan Terms
    |--------------------------------------------------------------------------
    */
    'loan_terms' => [
        'interest_rate' => 10, // 10% interest rate
        'max_repayment_period_months' => 24, // Maximum 24-month repayment period (flexible)
        'repayment_method' => 'bursary_deduction', // Only bursary deduction is allowed
        'available_repayment_methods' => ['bursary_deduction'], // Only one repayment method available
        'fixed_term' => false, // No fixed terms - flexible repayment
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Process
    |--------------------------------------------------------------------------
    */
    'application_process' => [
        'online_notification' => true, // Online application serves as notification
        'physical_form_required' => true, // Physical form with signatures required
    ],
];
