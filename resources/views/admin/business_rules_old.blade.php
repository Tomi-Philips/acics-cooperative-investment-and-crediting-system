@extends('layouts.admin')

@section('content')
    <div class="overflow-hidden bg-white rounded-lg shadow-md">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Business Rules Settings</h1>
                <button type="submit" form="business-rules-form"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Changes
                </button>
            </div>

            @if (session('success'))
                <div class="p-4 mb-4 border-l-4 border-green-400 bg-green-50">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 mb-4 border-l-4 border-red-400 bg-red-50">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form id="business-rules-form" action="{{ route('admin.business_rules.store') }}" method="POST"
                class="space-y-8">
                @csrf

                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-4 py-3 border-b border-gray-200 rounded-t-lg bg-gray-50">
                        <h2 class="text-lg font-medium text-gray-900">Membership Rules</h2>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label for="minimum_initial_deposit" class="block mb-1 text-sm font-medium text-gray-700">Minimum Initial Deposit (₦)</label>
                                <div class="relative mt-1 rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">₦</span>
                                    </div>
                                    <input type="number" id="minimum_initial_deposit" name="minimum_initial_deposit"
                                        value="{{ $rules['minimum_initial_deposit'] ?? 20000 }}"
                                        class="block w-full pl-8 pr-12 border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Current value: ₦{{ number_format($rules['minimum_initial_deposit'] ?? 20000) }}</p>
                            </div>
                            <div>
                                <label for="entrance_fee" class="block mb-1 text-sm font-medium text-gray-700">Entrance Fee (₦)</label>
                                <div class="relative mt-1 rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">₦</span>
                                    </div>
                                    <input type="number" id="entrance_fee" name="entrance_fee"
                                        value="{{ $rules['entrance_fee'] ?? 1000 }}"
                                        class="block w-full pl-8 pr-12 border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">One-time fee deducted from initial deposit</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-4 py-3 border-b border-gray-200 rounded-t-lg bg-gray-50">
                        <h2 class="text-lg font-medium text-gray-900">Share Rules</h2>
                    </div>
                    <div class="p-4 space-y-4">
                        <div>
                            <label for="maximum_share_contribution" class="block mb-1 text-sm font-medium text-gray-700">Maximum Share Contribution (₦)</label>
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₦</span>
                                </div>
                                <input type="number" id="maximum_share_contribution" name="maximum_share_contribution"
                                    value="{{ $rules['maximum_share_contribution'] ?? 10000 }}"
                                    class="block w-full pl-8 pr-12 border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 sm:text-sm">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Current value: ₦{{ number_format($rules['maximum_share_contribution'] ?? 10000) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-4 py-3 border-b border-gray-200 rounded-t-lg bg-gray-50">
                        <h2 class="text-lg font-medium text-gray-900">Loan Eligibility Rules</h2>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label for="minimum_membership_months" class="block mb-1 text-sm font-medium text-gray-700">Minimum Membership Duration (months)</label>
                                <input type="number" id="minimum_membership_months" name="minimum_membership_months"
                                    value="{{ $rules['minimum_membership_months'] ?? 6 }}"
                                    class="block w-full border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                <p class="mt-1 text-xs text-gray-500">Current value: {{ $rules['minimum_membership_months'] ?? 6 }} months</p>
                            </div>
                            <div>
                                <label for="loan_multiplier" class="block mb-1 text-sm font-medium text-gray-700">Loan Amount Multiplier</label>
                                <input type="number" id="loan_multiplier" name="loan_multiplier"
                                    value="{{ $rules['loan_multiplier'] ?? 2 }}" step="0.1"
                                    class="block w-full border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                <p class="mt-1 text-xs text-gray-500">Multiplier for (Savings + Shares) to determine maximum loan amount</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-4 py-3 border-b border-gray-200 rounded-t-lg bg-gray-50">
                        <h2 class="text-lg font-medium text-gray-900">Loan Terms</h2>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label for="interest_rate" class="block mb-1 text-sm font-medium text-gray-700">Interest Rate (%)</label>
                                <div class="relative mt-1 rounded-md shadow-sm">
                                    <input type="number" id="interest_rate" name="interest_rate"
                                        value="{{ $rules['interest_rate'] ?? 10 }}" step="0.1"
                                        class="block w-full pr-12 border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">%</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Current value: {{ $rules['interest_rate'] ?? 10 }}%</p>
                            </div>
                            <div>
                                <label for="repayment_period" class="block mb-1 text-sm font-medium text-gray-700">Repayment Period (months)</label>
                                <input type="number" id="repayment_period" name="repayment_period"
                                    value="{{ $rules['repayment_period'] ?? 24 }}"
                                    class="block w-full border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                <p class="mt-1 text-xs text-gray-500">Current value: {{ $rules['repayment_period'] ?? 24 }} months</p>
                            </div>
                        </div>
                        <div>
                            <label for="repayment_method" class="block mb-1 text-sm font-medium text-gray-700">Repayment Method</label>
                            <select id="repayment_method" name="repayment_method"
                                class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                <option value="bursary_deduction"
                                    {{ ($rules['repayment_method'] ?? '') == 'bursary_deduction' ? 'selected' : '' }}>
                                    Bursary Deduction</option>
                                <option value="bank_transfer"
                                    {{ ($rules['repayment_method'] ?? '') == 'bank_transfer' ? 'selected' : '' }}>Bank
                                    Transfer</option>
                                <option value="cash_payment"
                                    {{ ($rules['repayment_method'] ?? '') == 'cash_payment' ? 'selected' : '' }}>Cash
                                    Payment</option>
                                <option value="check_payment"
                                    {{ ($rules['repayment_method'] ?? '') == 'check_payment' ? 'selected' : '' }}>Check
                                    Payment</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-4 py-3 border-b border-gray-200 rounded-t-lg bg-gray-50">
                        <h2 class="text-lg font-medium text-gray-900">Application Process</h2>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="online_notification" name="online_notification" type="checkbox" value="true"
                                    {{ ($rules['online_notification'] ?? 'true') == 'true' ? 'checked' : '' }}
                                    class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="online_notification" class="font-medium text-gray-700">Online application serves as notification</label>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="physical_form_required" name="physical_form_required" type="checkbox"
                                    value="true"
                                    {{ ($rules['physical_form_required'] ?? 'true') == 'true' ? 'checked' : '' }}
                                    class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="physical_form_required" class="font-medium text-gray-700">Physical form with signatures required</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-8">
                    <button type="reset"
                        class="inline-flex items-center px-4 py-2 mr-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Reset to Defaults
                    </button>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection