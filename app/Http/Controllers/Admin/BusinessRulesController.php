<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessRule;
use Illuminate\Support\Facades\Log;

class BusinessRulesController extends Controller
{
    /**
     * Display the business rules page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all business rules and organize them by category
        $allRules = BusinessRule::all();

        // Initialize rules array with database values or defaults
        $rules = [];

        // Define default business rules
        $defaultRules = [
            // Membership Rules
            'minimum_initial_deposit' => '20000',
            'entrance_fee' => '2000',

            // Share Rules
            'maximum_share_contribution' => '10000',

            // Loan Eligibility Rules
            'minimum_membership_months' => '6',
            'loan_multiplier' => '2',

            // Loan Terms
            'interest_rate' => '10',
            'repayment_period' => '24',
            'repayment_method' => 'bursary_deduction',

            // Application Process
            'online_notification' => 'true',
            'physical_form_required' => 'true',
        ];

        // Start with default rules
        $rules = $defaultRules;

        // Override with values from the database if they exist
        foreach ($allRules as $rule) {
            $key = $this->getTitleAsKey($rule->title);
            if (!empty($key)) {
                $rules[$key] = $this->getValueFromDescription($rule->description);
            }
        }

        // Group rules by category
        $rulesByCategory = $allRules->groupBy('category');

        return view('admin.business_rules', compact('rules', 'rulesByCategory'));
    }

    /**
     * Store a new business rule or update existing rules.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Log the request for debugging
            Log::info('Business Rules Store Request', ['data' => $request->all()]);

            // Process each form field
            foreach ($request->except('_token') as $key => $value) {
                // Map the key to a title
                $title = $this->getKeyAsTitle($key);
                $description = $this->getValueAsDescription($key, $value);
                $category = $this->getCategoryFromKey($key);

                // Find the rule by title or create a new one
                Log::info('Searching for rule with title: ' . $title);
                $rule = BusinessRule::where('title', $title)->firstOrNew();

                // Update the rule
                $rule->description = $description;
                $rule->category = $category;
                $rule->is_active = true;
                $rule->save();
            }

            return redirect()->route('admin.business_rules')->with('success', 'Business rules updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating business rules', ['error' => $e->getMessage()]);
            return redirect()->route('admin.business_rules')->with('error', 'An error occurred while updating business rules: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing business rule.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
        ]);

        $rule = BusinessRule::findOrFail($id);
        $rule->update($request->all());

        return redirect()->route('admin.business_rules')->with('success', 'Business rule updated successfully.');
    }

    /**
     * Delete a business rule.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $rule = BusinessRule::findOrFail($id);
        $rule->delete();

        return redirect()->route('admin.business_rules')->with('success', 'Business rule deleted successfully.');
    }

    /**
     * Convert a title to a rule key.
     *
     * @param  string  $title
     * @return string
     */
    private function getTitleAsKey($title)
    {
        $title = strtolower($title);

        $mappings = [
            'minimum initial deposit' => 'minimum_initial_deposit',
            'entrance fee' => 'entrance_fee',
            'maximum share contribution' => 'maximum_share_contribution',
            'minimum membership duration' => 'minimum_membership_months',
            'loan amount multiplier' => 'loan_multiplier',
            'interest rate' => 'interest_rate',
            'repayment period' => 'repayment_period',
            'repayment method' => 'repayment_method',
            'online notification' => 'online_notification',
            'physical form required' => 'physical_form_required',
        ];

        foreach ($mappings as $titlePattern => $key) {
            if (strpos($title, $titlePattern) !== false) {
                return $key;
            }
        }

        return '';
    }

    /**
     * Convert a rule key to a title.
     *
     * @param  string  $key
     * @return string
     */
    private function getKeyAsTitle($key)
    {
        // Return the original key instead of a formatted title
        return $key;
    }

    /**
     * Extract a value from a description.
     *
     * @param  string  $description
     * @return string
     */
    private function getValueFromDescription($description)
    {
        // Try to extract a numeric value
        if (preg_match('/(\d+(\.\d+)?)/', $description, $matches)) {
            return $matches[1];
        }

        // Check for boolean values
        if (stripos($description, 'yes') !== false || stripos($description, 'true') !== false || stripos($description, 'enabled') !== false) {
            return 'true';
        }

        if (stripos($description, 'no') !== false || stripos($description, 'false') !== false || stripos($description, 'disabled') !== false) {
            return 'false';
        }

        // Check for repayment method
        if (stripos($description, 'bursary') !== false) {
            return 'bursary_deduction';
        }

        if (stripos($description, 'bank') !== false) {
            return 'bank_transfer';
        }

        if (stripos($description, 'cash') !== false) {
            return 'cash_payment';
        }

        if (stripos($description, 'check') !== false || stripos($description, 'cheque') !== false) {
            return 'check_payment';
        }

        // Default
        return '';
    }

    /**
     * Create a description with the value.
     *
     * @param  string  $key
     * @param  string  $value
     * @return string
     */
    private function getValueAsDescription($key, $value)
    {
        $descriptions = [
            'minimum_initial_deposit' => 'Minimum initial deposit required for membership: ₦' . number_format($value),
            'entrance_fee' => 'One-time entrance fee deducted from initial deposit: ₦' . number_format($value),
            'maximum_share_contribution' => 'Maximum share contribution allowed: ₦' . number_format($value),
            'minimum_membership_months' => 'Minimum membership duration before loan eligibility: ' . $value . ' months',
            'loan_multiplier' => 'Multiplier for (Savings + Shares) to determine maximum loan amount: ' . $value . 'x',
            'interest_rate' => 'Interest rate percentage for loans: ' . $value . '%',
            'repayment_period' => 'Maximum repayment period: ' . $value . ' months',
            'repayment_method' => 'Default repayment method: ' . ucwords(str_replace('_', ' ', $value)),
            'online_notification' => 'Online application serves as notification: ' . ($value === 'true' ? 'Yes' : 'No'),
            'physical_form_required' => 'Physical form with signatures required: ' . ($value === 'true' ? 'Yes' : 'No'),
        ];

        return $descriptions[$key] ?? 'Value: ' . $value;
    }

    /**
     * Determine the category based on the rule key.
     *
     * @param  string  $key
     * @return string
     */
    private function getCategoryFromKey($key)
    {
        if (strpos($key, 'minimum_initial_deposit') !== false || strpos($key, 'entrance_fee') !== false) {
            return 'membership';
        } elseif (strpos($key, 'share') !== false) {
            return 'shares';
        } elseif (strpos($key, 'loan_multiplier') !== false || strpos($key, 'minimum_membership') !== false) {
            return 'loan_eligibility';
        } elseif (strpos($key, 'interest_rate') !== false || strpos($key, 'repayment') !== false) {
            return 'loan_terms';
        } elseif (strpos($key, 'notification') !== false || strpos($key, 'form_required') !== false) {
            return 'application_process';
        }

        return 'other';
    }
}
