@extends('layouts.admin')

@section('content')
    <div class="max-w-2xl px-4 py-8 mx-auto sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white border border-gray-100 shadow-xl rounded-xl">
            <div class="px-6 py-5 bg-gradient-to-r from-green-600 to-green-500">
                <div class="flex items-center justify-between">
                    <h2 class="flex items-center text-2xl font-bold text-white">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Loan Calculator
                    </h2>
                    <span class="px-3 py-1 text-xs font-semibold text-green-100 bg-green-700 bg-opacity-50 rounded-full">Beta</span>
                </div>
                <p class="mt-1 text-sm text-green-100">Calculate your loan repayment details.</p>
            </div>

            <div class="px-6 py-5">
                <form class="space-y-5">
                    <div class="space-y-2">
                        <label for="loan_amount" class="flex items-center text-sm font-medium text-gray-700">
                            <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Loan Amount
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₦</span>
                            </div>
                            <input type="number" id="loan_amount"
                                   class="block w-full py-3 pl-8 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="0.00" min="1000" step="1000">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <span class="text-sm text-gray-500">NGN</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="term_months" class="flex items-center text-sm font-medium text-gray-700">
                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Term (Months)
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <input type="number" id="term_months"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="12" min="1" max="60">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="text-sm text-gray-500">months</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="interest_rate" class="flex items-center text-sm font-medium text-gray-700">
                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.933 12.8a1 1 0 000-1.6L6.6 7.2A1 1 0 005 8v8a1 1 0 001.6.8l5.333-4zM19.933 12.8a1 1 0 000-1.6l-5.333-4A1 1 0 0013 8v8a1 1 0 001.6.8l5.333-4z"></path>
                                </svg>
                                Interest Rate
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <input type="number" id="interest_rate"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="5.5" min="0" max="100" step="0.1">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="text-sm text-gray-500">%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="button" id="calculate_button"
                                class="relative flex justify-center w-full px-4 py-3 font-medium text-white transition-all duration-200 bg-green-600 border border-transparent rounded-lg shadow-md group hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 hover:shadow-lg">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-5 h-5 text-green-300 transition-colors duration-200 group-hover:text-green-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            Calculate Repayment
                        </button>
                    </div>
                </form>

                <div class="pt-8 mt-8 border-t border-gray-200">
                    <h3 class="flex items-center mb-4 text-lg font-semibold text-gray-900">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Repayment Details
                    </h3>
                    <div class="p-5 space-y-4 bg-gray-50 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Monthly Payment</h4>
                                <p class="text-xs text-gray-400">Fixed amount per month</p>
                            </div>
                            <div class="text-xl font-bold text-green-600">₦ <span id="monthly_payment">0.00</span></div>
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Total Repayment</h4>
                                <p class="text-xs text-gray-400">Principal + Interest</p>
                            </div>
                            <div class="text-lg font-semibold text-gray-800">₦ <span id="total_repayment">0.00</span></div>
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Total Interest</h4>
                                <p class="text-xs text-gray-400">Cost of borrowing</p>
                            </div>
                            <div class="text-lg font-semibold text-gray-800">₦ <span id="total_interest">0.00</span></div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <button id="amortization_button"
                                class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-green-600 border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500">
                            View Amortization Schedule
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const loanAmountInput = document.getElementById('loan_amount');
                const termMonthsInput = document.getElementById('term_months');
                const interestRateInput = document.getElementById('interest_rate');
                const calculateButton = document.getElementById('calculate_button');
                const monthlyPaymentSpan = document.getElementById('monthly_payment');
                const totalRepaymentSpan = document.getElementById('total_repayment');
                const totalInterestSpan = document.getElementById('total_interest');
                const amortizationButton = document.getElementById('amortization_button');

                // Function to format numbers as Nigerian Naira
                function formatNaira(amount) {
                    return new Intl.NumberFormat('en-NG', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(amount);
                }

                function calculateLoan() {
                    const loanAmount = parseFloat(loanAmountInput.value);
                    const termMonths = parseInt(termMonthsInput.value);
                    const annualInterestRate = parseFloat(interestRateInput.value);

                    if (isNaN(loanAmount) || isNaN(termMonths) || isNaN(annualInterestRate) ||
                        loanAmount <= 0 || termMonths <= 0 || annualInterestRate < 0) {
                        monthlyPaymentSpan.textContent = '0.00';
                        totalRepaymentSpan.textContent = '0.00';
                        totalInterestSpan.textContent = '0.00';
                        amortizationButton.style.display = 'none'; // Hide amortization button if inputs are invalid
                        return;
                    }

                    amortizationButton.style.display = 'flex'; // Show amortization button if inputs are valid

                    const monthlyInterestRate = (annualInterestRate / 100) / 12;

                    let monthlyPayment;
                    if (monthlyInterestRate === 0) {
                        // Simple interest calculation for 0% interest rate
                        monthlyPayment = loanAmount / termMonths;
                    } else {
                        // Compound interest (PMT formula)
                        monthlyPayment = loanAmount * (monthlyInterestRate * Math.pow(1 + monthlyInterestRate, termMonths)) /
                                        (Math.pow(1 + monthlyInterestRate, termMonths) - 1);
                    }


                    const totalRepayment = monthlyPayment * termMonths;
                    const totalInterest = totalRepayment - loanAmount;

                    monthlyPaymentSpan.textContent = formatNaira(monthlyPayment);
                    totalRepaymentSpan.textContent = formatNaira(totalRepayment);
                    totalInterestSpan.textContent = formatNaira(totalInterest);
                }

                // Attach event listeners
                calculateButton.addEventListener('click', calculateLoan);
                loanAmountInput.addEventListener('input', calculateLoan);
                termMonthsInput.addEventListener('input', calculateLoan);
                interestRateInput.addEventListener('input', calculateLoan);

                // Initial calculation when the page loads
                calculateLoan();

                // Amortization schedule logic (conceptual - you'd implement the actual display here)
                amortizationButton.addEventListener('click', function() {
                    alert('Amortization schedule would be displayed here!');
                    // In a real application, you'd likely fetch/calculate and display a detailed table
                    // based on the current loan parameters.
                });
            });
        </script>
    @endpush
@endsection