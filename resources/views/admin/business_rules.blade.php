@extends('layouts.admin')

@section('content')
    <div class="container grid px-6 mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Business Rules</h1>
                <p class="text-gray-600">Configure system-wide business rules and financial parameters</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center px-4 py-2 bg-blue-50 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-sm font-medium text-blue-800">Active Rules</span>
                </div>
                <button type="submit" form="business-rules-form"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Changes
                </button>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Business Rules Form -->
        <form id="business-rules-form" action="{{ route('admin.business_rules.store') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Membership Rules -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-900">Membership Rules</h2>
                            <p class="text-sm text-gray-600">Configure membership requirements and fees</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <div class="space-y-2">
                            <label for="minimum_initial_deposit" class="block text-sm font-medium text-gray-700">
                                Minimum Initial Deposit
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">₦</span>
                                </div>
                                <input type="number" id="minimum_initial_deposit" name="minimum_initial_deposit"
                                    value="{{ $rules['minimum_initial_deposit'] ?? 20000 }}"
                                    class="block w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="20000">
                            </div>
                            <p class="text-xs text-gray-500">Minimum amount required to become a member</p>
                        </div>

                        <div class="space-y-2">
                            <label for="entrance_fee" class="block text-sm font-medium text-gray-700">
                                Entrance Fee
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">₦</span>
                                </div>
                                <input type="number" id="entrance_fee" name="entrance_fee"
                                    value="{{ $rules['entrance_fee'] ?? 2000 }}"
                                    class="block w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="2000">
                            </div>
                            <p class="text-xs text-gray-500">One-time fee deducted from initial deposit</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Share Rules -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-900">Share Rules</h2>
                            <p class="text-sm text-gray-600">Configure share contribution limits and policies</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-2">
                        <label for="maximum_share_contribution" class="block text-sm font-medium text-gray-700">
                            Maximum Share Contribution
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm">₦</span>
                            </div>
                            <input type="number" id="maximum_share_contribution" name="maximum_share_contribution"
                                value="{{ $rules['maximum_share_contribution'] ?? 10000 }}"
                                class="block w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                placeholder="10000">
                        </div>
                        <p class="text-xs text-gray-500">Maximum amount a member can contribute to shares</p>
                    </div>
                </div>
            </div>

            <!-- Loan Eligibility Rules -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-violet-50">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-900">Loan Eligibility Rules</h2>
                            <p class="text-sm text-gray-600">Define loan eligibility criteria and limits</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <div class="space-y-2">
                            <label for="minimum_membership_months" class="block text-sm font-medium text-gray-700">
                                Minimum Membership Period
                            </label>
                            <div class="relative">
                                <input type="number" id="minimum_membership_months" name="minimum_membership_months"
                                    value="{{ $rules['minimum_membership_months'] ?? 6 }}"
                                    class="block w-full pr-12 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                    placeholder="6">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">months</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500">Minimum membership duration before loan eligibility</p>
                        </div>

                        <div class="space-y-2">
                            <label for="loan_multiplier" class="block text-sm font-medium text-gray-700">
                                Loan Multiplier
                            </label>
                            <div class="relative">
                                <input type="number" id="loan_multiplier" name="loan_multiplier" step="0.1"
                                    value="{{ $rules['loan_multiplier'] ?? 2 }}"
                                    class="block w-full pr-8 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                    placeholder="2">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">×</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500">Maximum loan amount = savings × multiplier</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan Terms -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-amber-50">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-900">Loan Terms</h2>
                            <p class="text-sm text-gray-600">Configure interest rates and repayment terms</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                        <div class="space-y-2">
                            <label for="interest_rate" class="block text-sm font-medium text-gray-700">
                                Interest Rate
                            </label>
                            <div class="relative">
                                <input type="number" id="interest_rate" name="interest_rate" step="0.1"
                                    value="{{ $rules['interest_rate'] ?? 10 }}"
                                    class="block w-full pr-8 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                    placeholder="10">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">%</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500">Annual interest rate percentage</p>
                        </div>

                        <div class="space-y-2">
                            <label for="repayment_period" class="block text-sm font-medium text-gray-700">
                                Repayment Period
                            </label>
                            <div class="relative">
                                <input type="number" id="repayment_period" name="repayment_period"
                                    value="{{ $rules['repayment_period'] ?? 24 }}"
                                    class="block w-full pr-16 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                    placeholder="24">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">months</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500">Maximum loan repayment period</p>
                        </div>

                        <div class="space-y-2">
                            <label for="repayment_method" class="block text-sm font-medium text-gray-700">
                                Repayment Method
                            </label>
                            <select id="repayment_method" name="repayment_method"
                                class="block w-full py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                                <option value="bursary_deduction" {{ ($rules['repayment_method'] ?? 'bursary_deduction') == 'bursary_deduction' ? 'selected' : '' }}>
                                    Bursary Deduction
                                </option>
                                <option value="manual_payment" {{ ($rules['repayment_method'] ?? 'bursary_deduction') == 'manual_payment' ? 'selected' : '' }}>
                                    Manual Payment
                                </option>
                                <option value="bank_transfer" {{ ($rules['repayment_method'] ?? 'bursary_deduction') == 'bank_transfer' ? 'selected' : '' }}>
                                    Bank Transfer
                                </option>
                            </select>
                            <p class="text-xs text-gray-500">Default loan repayment method</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Process -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-teal-50 to-cyan-50">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-900">Application Process</h2>
                            <p class="text-sm text-gray-600">Configure application workflow and requirements</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="online_notification" name="online_notification" value="true"
                                    {{ ($rules['online_notification'] ?? 'true') == 'true' ? 'checked' : '' }}
                                    class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                                <label for="online_notification" class="ml-3 block text-sm font-medium text-gray-700">
                                    Online Notification
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 ml-7">Send email notifications for application updates</p>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="physical_form_required" name="physical_form_required" value="true"
                                    {{ ($rules['physical_form_required'] ?? 'true') == 'true' ? 'checked' : '' }}
                                    class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                                <label for="physical_form_required" class="ml-3 block text-sm font-medium text-gray-700">
                                    Physical Form Required
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 ml-7">Require physical form submission for applications</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save All Changes
                </button>
            </div>
        </form>
    </div>
@endsection
