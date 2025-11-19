@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Enhanced Header Section -->
    <div class="p-6 mb-8 border border-gray-200 shadow-lg bg-gradient-to-r from-green-50 to-blue-50 rounded-xl">
        <div class="flex flex-col items-center justify-between lg:flex-row">
            <div class="mb-6 lg:mb-0">
                <h1 class="flex items-center text-2xl font-bold text-gray-800">
                    <div class="p-2 mr-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    Loan Calculator
                </h1>
                <p class="mt-2 text-sm text-gray-600">Calculate loan interest with 10% simple interest rate</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.loans.index') }}" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to All Loans
                </a>
            </div>
        </div>
    </div>

    <!-- Calculator Grid -->
    <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-2">
        <!-- Loan Parameters Card -->
        <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
            <!-- Card Header -->
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                <div class="flex items-center">
                    <div class="p-2 mr-4 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Loan Parameters</h2>
                        <p class="mt-1 text-sm text-gray-600">Enter loan amount to calculate 10% interest</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-8">
                <form id="calculator-form" class="space-y-6">
                    <div>
                        <label for="amount" class="flex items-center text-sm font-semibold text-gray-700">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Loan Amount (₦)
                        </label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-gray-500">₦</span>
                            </div>
                            <input type="number" id="amount" name="amount" min="1000" step="1000" value="10000" class="pl-8 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5" required>
                        </div>
                        <div class="mt-2">
                            <input type="range" id="amount_slider" min="1000" max="500000" step="1000" value="10000" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" oninput="updateAmountValue(this.value)">
                            <div class="flex justify-between mt-1 text-xs text-gray-500">
                                <span>₦1,000</span>
                                <span>₦500,000</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="interest_rate" class="flex items-center text-sm font-semibold text-gray-700">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Interest Rate (%)
                        </label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-gray-500">%</span>
                            </div>
                            <input type="number" id="interest_rate" name="interest_rate" value="10" disabled class="pl-8 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Fixed at 10% simple interest for all loans</p>
                    </div>

                    <div>
                        <label class="flex items-center text-sm font-semibold text-gray-700">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Repayment Terms
                        </label>
                        <div class="p-3 mt-1 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-800 font-medium">Flexible Repayment</p>
                            <p class="text-xs text-blue-600 mt-1">Members can repay anytime within 24 months via bursary deduction</p>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="button" id="calculate-btn" class="w-full px-5 py-3 text-sm font-medium text-center text-white transition-all duration-200 ease-in-out transform bg-purple-700 rounded-lg hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 hover:scale-105">
                            <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Calculate Loan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Loan Summary Card -->
        <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
            <!-- Card Header -->
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                <div class="flex items-center">
                    <div class="p-2 mr-4 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Loan Summary</h2>
                        <p class="mt-1 text-sm text-gray-600">Simple interest calculation breakdown</p>
                    </div>
                </div>
            </div>

            <!-- Summary Content -->
            <div class="p-8">
                <div id="loan-summary" class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 rounded-lg bg-gray-50">
                            <p class="text-xs font-medium text-gray-600 sm:text-sm">Principal Amount</p>
                            <p id="summary-principal" class="mt-1 text-base font-bold text-purple-600 break-words sm:text-lg md:text-xl">
                                ₦10,000.00
                            </p>
                        </div>
                        <div class="p-4 rounded-lg bg-gray-50">
                            <p class="text-xs font-medium text-gray-600 sm:text-sm">Interest Rate</p>
                            <p id="summary-interest-rate" class="mt-1 text-base font-bold text-purple-600 sm:text-lg md:text-xl">
                                10.0%
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 rounded-lg bg-gray-50">
                            <p class="text-xs font-medium text-gray-600 sm:text-sm">Interest Amount</p>
                            <p id="summary-interest-amount" class="mt-1 text-base font-bold text-orange-600 sm:text-lg md:text-xl">
                                <span class="animate-pulse">Calculating...</span>
                            </p>
                        </div>
                        <div class="p-4 rounded-lg bg-gray-50">
                            <p class="text-xs font-medium text-gray-600 sm:text-sm">Repayment Terms</p>
                            <p class="mt-1 text-base font-bold text-blue-600 break-words sm:text-lg md:text-xl">
                                Flexible (24 months max)
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 rounded-lg bg-gray-50">
                            <p class="text-xs font-medium text-gray-600 sm:text-sm">Total Payment</p>
                            <p id="summary-total-payment" class="mt-1 text-base font-bold text-green-600 break-words sm:text-lg md:text-xl">
                                <span class="animate-pulse">Calculating...</span>
                            </p>
                        </div>
                        <div class="p-4 rounded-lg bg-gray-50">
                            <p class="text-xs font-medium text-gray-600 sm:text-sm">Interest Rate</p>
                            <p class="mt-1 text-base font-bold text-purple-600 break-words sm:text-lg md:text-xl">
                                10% Simple Interest
                            </p>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('admin.loans.create') }}" class="block w-full px-5 py-3 text-sm font-medium text-center text-white transition-all duration-200 ease-in-out bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                            <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create New Loan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loan Information Card -->
    <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
        <!-- Card Header -->
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
            <div class="flex items-center">
                <div class="p-2 mr-4 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Loan Information</h2>
                    <p class="mt-1 text-sm text-gray-600">Important details about our loan system</p>
                </div>
            </div>
        </div>

        <!-- Information Content -->
        <div class="p-8">
            <div class="space-y-6">
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h3 class="flex items-center text-lg font-semibold text-blue-800">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Flexible Repayment System
                    </h3>
                    <p class="mt-2 text-sm text-blue-700">
                        Our cooperative uses a flexible repayment system. Members can repay their loans anytime within 24 months through bursary deduction.
                    </p>
                </div>

                <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                    <h3 class="flex items-center text-lg font-semibold text-green-800">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Simple Interest Calculation
                    </h3>
                    <p class="mt-2 text-sm text-green-700">
                        We use a simple 10% interest rate. For example: ₦100,000 loan = ₦10,000 interest = ₦110,000 total repayment.
                    </p>
                </div>

                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <h3 class="flex items-center text-lg font-semibold text-yellow-800">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        Repayment Method
                    </h3>
                    <p class="mt-2 text-sm text-yellow-700">
                        All loan repayments are processed through bursary deduction. Members can make partial or full payments anytime.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Update amount value from slider function
    function updateAmountValue(value) {
        const amountInput = document.getElementById('amount');
        if (amountInput) {
            amountInput.value = value;
        }
        // Auto-calculate when amount changes
        calculateLoan();
    }

    // Format number as currency function
    function formatCurrency(value) {
        return '₦' + parseFloat(value).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    // Calculate loan function with simple interest
    function calculateLoan() {
        const principal = parseFloat(document.getElementById('amount').value);
        const interestRate = 10.0; // Fixed at 10%

        if (isNaN(principal) || principal <= 0) {
            alert('Please enter a valid loan amount.');
            return;
        }

        // Show loading state
        document.getElementById('summary-interest-amount').innerHTML = '<span class="animate-pulse">Calculating...</span>';
        document.getElementById('summary-total-payment').innerHTML = '<span class="animate-pulse">Calculating...</span>';

        // Calculate simple interest: Interest = Principal × Rate
        const interestAmount = principal * (interestRate / 100);
        const totalPayment = principal + interestAmount;

        // Update summary with simple interest calculation
        document.getElementById('summary-principal').textContent = formatCurrency(principal);
        document.getElementById('summary-interest-rate').textContent = interestRate + '%';
        document.getElementById('summary-interest-amount').textContent = formatCurrency(interestAmount);
        document.getElementById('summary-total-payment').textContent = formatCurrency(totalPayment);
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listener to calculate button
        document.getElementById('calculate-btn').addEventListener('click', calculateLoan);

        // Add event listener to amount input
        document.getElementById('amount').addEventListener('input', function() {
            // Sync the slider with the amount input
            const amountSlider = document.getElementById('amount_slider');
            if (amountSlider) {
                amountSlider.value = this.value;
            }
        });

        // Add event listener to amount input for blur event
        document.getElementById('amount').addEventListener('blur', calculateLoan);

        // Calculate loan on page load
        calculateLoan();
    });
</script>
@endpush
@endsection