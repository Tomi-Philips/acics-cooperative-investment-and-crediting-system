@extends('layouts.user')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/loan-application.css') }}">
@endsection

@section('scripts')
<script src="{{ asset('js/loan-application.js') }}"></script>
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Enhanced Header Section -->
    <div class="p-6 mb-6 border border-gray-200 shadow-lg bg-gradient-to-r from-green-50 to-blue-50 rounded-xl">
        <div class="flex flex-col items-center justify-between lg:flex-row">
            <div class="mb-6 lg:mb-0">
                <h1 class="flex items-center text-2xl font-bold text-gray-800">
                    <div class="p-2 mr-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    Apply for New Loan
                </h1>
                <p class="mt-2 text-sm text-gray-600">Submit your loan application and get approved funds quickly</p>
            </div>
        </div>
    </div>

    <!-- Loan Eligibility Info Box -->
    <div class="p-4 mb-6 border border-blue-200 rounded-lg bg-blue-50">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Loan Eligibility Information</h3>
                <div class="mt-2 space-y-1 text-sm text-blue-700">
                    <p>• You must be a member for at least 6 months to be eligible</p>
                    <p>• <strong>Available for new loan: ₦{{ number_format($eligibleAmount, 2) }}</strong></p>
                    @if($totalActiveLoanBalance > 0)
                        <p>• Current active loan balance: ₦{{ number_format($totalActiveLoanBalance, 2) }}</p>
                    @endif
                    <p>• Fixed interest rate: 10%</p>
                    <p>• No fixed term; repayments continue via bursary deductions until balance is cleared</p>
                    <p>• Repayment method: Bursary Deduction</p>
                    <p>• <strong>Multiple loans allowed</strong> - each loan is calculated based on your current financial position</p>
                </div>
                @if(!$eligibility['eligible'])
                <div class="p-2 mt-3 text-sm text-red-800 bg-red-100 border border-red-200 rounded">
                    <p class="font-semibold">You are not eligible for a loan at this time:</p>
                    <ul class="mt-1 list-disc list-inside">
                        <li>{{ $eligibility['reason'] }}</li>
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Active Loans Section -->
    @if($activeLoans->count() > 0)
    <div class="p-6 mb-6 overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
        <h3 class="flex items-center mb-4 text-xl font-semibold text-gray-800">
            <svg class="w-6 h-6 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
            Your Active Loans ({{ $activeLoans->count() }})
        </h3>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach($activeLoans as $loan)
            <div class="p-4 border border-orange-100 bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-orange-800">{{ $loan->loan_number }}</span>
                    <span class="px-2 py-1 text-xs font-semibold text-orange-800 bg-orange-200 rounded-full">Active</span>
                </div>
                <div class="space-y-1 text-sm text-orange-700">
                    <div class="flex justify-between">
                        <span>Original Amount:</span>
                        <span class="font-semibold">₦{{ number_format($loan->amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Remaining Balance:</span>
                        <span class="font-semibold">₦{{ number_format($loan->remaining_balance, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Interest (10%):</span>
                        <span class="font-semibold">₦{{ number_format($loan->amount * 0.10, 2) }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="p-3 mt-4 bg-orange-50 border border-orange-200 rounded-lg">
            <p class="text-sm text-orange-800">
                <strong>Total Active Loan Balance:</strong> ₦{{ number_format($totalActiveLoanBalance, 2) }}
            </p>
        </div>
    </div>
    @endif

    <!-- Member Financial Stats -->
    <div class="p-6 mb-6 overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
        <h3 class="flex items-center mb-6 text-xl font-semibold text-gray-800">
            <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Your Financial Summary
        </h3>

        <div class="grid gap-4 mb-6 md:grid-cols-2 lg:grid-cols-3">
            <!-- Savings Balance Card -->
            <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-green-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
                <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-green-50 to-green-100 group-hover:opacity-100"></div>
                <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-green-100 rounded-full opacity-20"></div>
                <div class="relative p-4">
                    <div class="flex items-center">
                        <div class="p-3 mr-4 shadow-lg bg-gradient-to-br from-green-400 to-green-500 rounded-xl">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Savings</p>
                            <p class="text-lg font-bold text-gray-800">₦{{ number_format($savingsBalance, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shares Balance Card -->
            <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-purple-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
                <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-purple-50 to-purple-100 group-hover:opacity-100"></div>
                <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-purple-100 rounded-full opacity-20"></div>
                <div class="relative p-4">
                    <div class="flex items-center">
                        <div class="p-3 mr-4 shadow-lg bg-gradient-to-br from-purple-400 to-purple-500 rounded-xl">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Shares</p>
                            <p class="text-lg font-bold text-gray-800">₦{{ number_format($sharesBalance, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan Balance Card -->
            <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-red-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
                <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-red-50 to-red-100 group-hover:opacity-100"></div>
                <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-red-100 rounded-full opacity-20"></div>
                <div class="relative p-4">
                    <div class="flex items-center">
                        <div class="p-3 mr-4 shadow-lg bg-gradient-to-br from-red-400 to-red-500 rounded-xl">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Loan Balance</p>
                            <p class="text-lg font-bold text-gray-800">₦{{ number_format($loanBalance, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Essential Commodity Balance Card -->
            <div class="relative overflow-hidden transition-all duration-300 transform bg-white border shadow-lg border-amber-100 group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
                <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-amber-50 to-amber-100 group-hover:opacity-100"></div>
                <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 rounded-full bg-amber-100 opacity-20"></div>
                <div class="relative p-4">
                    <div class="flex items-center">
                        <div class="p-3 mr-4 shadow-lg bg-gradient-to-br from-amber-400 to-amber-500 rounded-xl">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Commodity (Essential)</p>
                            <p class="text-lg font-bold text-gray-800">₦{{ number_format($essentialBalance ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Electronics Balance Card -->
            <div class="relative overflow-hidden transition-all duration-300 transform bg-white border shadow-lg border-cyan-100 group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
                <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-cyan-50 to-cyan-100 group-hover:opacity-100"></div>
                <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 rounded-full bg-cyan-100 opacity-20"></div>
                <div class="relative p-4">
                    <div class="flex items-center">
                        <div class="p-3 mr-4 shadow-lg bg-gradient-to-br from-cyan-400 to-cyan-500 rounded-xl">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Electronics</p>
                            <p class="text-lg font-bold text-gray-800">₦{{ number_format($electronicsBalance ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Non-Essential Commodity Card -->
            <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-orange-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
                <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-orange-50 to-orange-100 group-hover:opacity-100"></div>
                <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-orange-100 rounded-full opacity-20"></div>
                <div class="relative p-4">
                    <div class="flex items-center">
                        <div class="p-3 mr-4 shadow-lg bg-gradient-to-br from-orange-400 to-orange-500 rounded-xl">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Non-Essential</p>
                            <p class="text-lg font-bold text-gray-800">₦{{ number_format($nonEssentialBalance ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan Interest Paid Card -->
            <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-indigo-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
                <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-indigo-50 to-indigo-100 group-hover:opacity-100"></div>
                <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-indigo-100 rounded-full opacity-20"></div>
                <div class="relative p-4">
                    <div class="flex items-center">
                        <div class="p-3 mr-4 shadow-lg bg-gradient-to-br from-indigo-400 to-indigo-500 rounded-xl">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Loan Interest</p>
                            <p class="text-lg font-bold text-gray-800">₦{{ number_format($loanInterestTotal ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Entrance Fee Status Card -->
            <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-teal-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
                <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-teal-50 to-teal-100 group-hover:opacity-100"></div>
                <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-teal-100 rounded-full opacity-20"></div>
                <div class="relative p-4">
                    <div class="flex items-center">
                        <div class="p-3 mr-4 shadow-lg bg-gradient-to-br from-teal-400 to-teal-500 rounded-xl">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Entrance Fee</p>
                            <p class="text-lg font-bold {{ $entrancePaid ? 'text-green-600' : 'text-red-600' }}">
                                {{ $entrancePaid ? 'Paid' : 'Not Paid' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Eligible Loan Amount Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-green-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-green-50 to-green-100 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-green-100 rounded-full opacity-20"></div>
            <div class="relative p-4">
                <div class="flex items-center">
                    <div class="p-3 mr-4 shadow-lg bg-gradient-to-br from-green-400 to-green-500 rounded-xl">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Eligible Amount</p>
                        <p class="text-lg font-bold text-gray-800">₦{{ number_format($maxLoanAmount, 2) }}</p>
                        <p class="mt-1 text-xs text-gray-500">2×(Savings+Shares) - (Loan+Commodity+Non essential+Electronics)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loan Application Form -->
    <div class="p-6 overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
        <h3 class="flex items-center mb-6 text-xl font-semibold text-gray-800">
            <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Loan Application Form
        </h3>
        <form class="space-y-6" action="{{ route('user.loan_application.post') }}" method="POST">
        @csrf
        @if(session('success'))
        <div class="p-4 text-green-800 bg-green-100 border border-green-200 rounded-lg">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="p-4 text-red-800 bg-red-100 border border-red-200 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        <!-- Loan Amount -->
        <div class="space-y-1">
            <label for="loan_amount" class="block text-sm font-medium text-gray-700">Loan Amount (₦)</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <span class="text-gray-500">₦</span>
                </div>
                <input type="number" id="loan_amount" name="amount" placeholder="50000" min="1000" max="{{ $eligibleAmount }}" value="{{ old('amount') }}" class="block w-full pl-8 border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('amount') border-red-500 @enderror" required {{ !$eligibility['eligible'] || $eligibleAmount <= 0 ? 'disabled' : '' }} />
            </div>
            @error('amount')
            <p class="text-xs text-red-600">{{ $message }}</p>
            @else
                @if($eligibleAmount <= 0)
                    <p class="text-xs text-red-600">No loan amount available based on your current financial position.</p>
                @else
                    <p class="text-xs text-gray-500">Enter your desired loan amount (min: ₦1,000, max: ₦{{ number_format($eligibleAmount) }})</p>
                @endif
            @enderror
            <!-- Loan calculation preview -->
            <div id="calculation_preview"></div>
        </div>

        <!-- Loan Purpose -->
        <div class="space-y-1">
            <label for="loan_purpose" class="block text-sm font-medium text-gray-700">Purpose of Loan</label>
            <textarea id="loan_purpose" name="purpose" rows="3" placeholder="Briefly describe what you need the loan for..." class="block w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('purpose') border-red-500 @enderror" required {{ !$eligibility['eligible'] ? 'disabled' : '' }}>{{ old('purpose') }}</textarea>
            @error('purpose')
            <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>        <!-- Additional Information -->
        <div class="space-y-1">
            <label for="additional_info" class="block text-sm font-medium text-gray-700">Additional Information (Optional)</label>
            <textarea id="additional_info" name="additional_info" rows="2" placeholder="Any additional information you'd like to provide..." class="block w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('additional_info') border-red-500 @enderror" {{ !$eligibility['eligible'] ? 'disabled' : '' }}>{{ old('additional_info') }}</textarea>
            @error('additional_info')
            <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Application Process Note -->
        <div class="p-4 border rounded-lg bg-amber-50 border-amber-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-amber-800">Important Note</h3>
                    <div class="mt-2 text-sm text-amber-700">
                        <p>This online application serves as notification of your intent. You will need to complete a physical form with signatures to finalize your application. All loans have a fixed 10% interest rate and are repaid via bursary deduction.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Terms Agreement -->
        <div class="flex items-start">
            <div class="flex items-center h-5">
                <input id="terms" name="terms" type="checkbox" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" required {{ !$eligibility['eligible'] ? 'disabled' : '' }} />
            </div>
            <div class="ml-3 text-sm">
                <label for="terms" class="font-medium text-gray-700">I agree to the <a href="{{ route('terms') }}" target="_blank" class="text-green-600 hover:underline">terms and conditions</a></label>
                <p class="text-gray-500">By submitting, you acknowledge our lending policies</p>
            </div>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 {{ !$eligibility['eligible'] ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$eligibility['eligible'] ? 'disabled' : '' }}>
                Submit Loan Application
                <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        </form>
    </div>
</div>
@endsection