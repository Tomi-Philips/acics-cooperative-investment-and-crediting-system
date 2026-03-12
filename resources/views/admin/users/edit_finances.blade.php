@extends('layouts.admin')

@section('content')
<style>
/* Fallback styles in case external CSS doesn't load */
.max-w-4xl { max-width: 56rem; }
.mx-auto { margin-left: auto; margin-right: auto; }
.p-4 { padding: 1rem; }
.p-6 { padding: 1.5rem; }
.p-8 { padding: 2rem; }
.mb-6 { margin-bottom: 1.5rem; }
.bg-white { background-color: #ffffff; }
.border { border-width: 1px; }
.border-gray-100 { border-color: #f3f4f6; }
.rounded-lg { border-radius: 0.5rem; }
.rounded-xl { border-radius: 0.75rem; }
.shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
.shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
.text-xl { font-size: 1.25rem; }
.font-bold { font-weight: 700; }
.text-gray-800 { color: #1f2937; }
.text-gray-700 { color: #374151; }
.text-gray-500 { color: #6b7280; }
.text-sm { font-size: 0.875rem; }
.grid { display: grid; }
.grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
.grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
.gap-4 { gap: 1rem; }
.gap-6 { gap: 1.5rem; }
.block { display: block; }
.w-full { width: 100%; }
.mt-1 { margin-top: 0.25rem; }
.px-4 { padding-left: 1rem; padding-right: 1rem; }
.py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
.border-gray-300 { border-color: #d1d5db; }
.rounded-md { border-radius: 0.375rem; }
.bg-amber-600 { background-color: #d97706; }
.text-white { color: #ffffff; }
.hover\:bg-amber-700:hover { background-color: #b45309; }
.focus\:ring-amber-500:focus { --tw-ring-color: #f59e0b; }
.focus\:border-amber-500:focus { border-color: #f59e0b; }
.bg-blue-50 { background-color: #eff6ff; }
.border-blue-200 { border-color: #bfdbfe; }
.text-blue-800 { color: #1e40af; }
.text-blue-600 { color: #2563eb; }
.bg-yellow-50 { background-color: #fefce8; }
.border-yellow-200 { border-color: #fde047; }
.text-yellow-800 { color: #92400e; }
.text-yellow-600 { color: #ca8a04; }
.flex { display: flex; }
.items-center { align-items: center; }
.justify-between { justify-content: space-between; }
.justify-end { justify-content: flex-end; }
.space-x-2 > * + * { margin-left: 0.5rem; }
.space-x-3 > * + * { margin-left: 0.75rem; }
.space-y-1 > * + * { margin-top: 0.25rem; }
.space-y-4 > * + * { margin-top: 1rem; }
.w-4 { width: 1rem; }
.h-4 { height: 1rem; }
.w-5 { width: 1.25rem; }
.h-5 { height: 1.25rem; }
.w-6 { width: 1.5rem; }
.h-6 { height: 1.5rem; }
.mr-1 { margin-right: 0.25rem; }
.mr-2 { margin-right: 0.5rem; }
.mt-0\.5 { margin-top: 0.125rem; }
.text-center { text-align: center; }
.text-lg { font-size: 1.125rem; }
.font-semibold { font-weight: 600; }
.font-medium { font-weight: 500; }
.list-disc { list-style-type: disc; }
.list-inside { list-style-position: inside; }
.cursor-pointer { cursor: pointer; }
.transition-colors { transition-property: color, background-color, border-color; }
.duration-150 { transition-duration: 150ms; }
.focus\:outline-none:focus { outline: 2px solid transparent; outline-offset: 2px; }
.focus\:ring-2:focus { --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color); --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color); box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000); }
.focus\:ring-offset-2:focus { --tw-ring-offset-width: 2px; }
@media (min-width: 768px) {
  .md\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  .md\:grid-cols-5 { grid-template-columns: repeat(5, minmax(0, 1fr)); }
}
@media (min-width: 640px) {
  .sm\:flex-row { flex-direction: row; }
  .sm\:mb-0 { margin-bottom: 0; }
  .sm\:text-sm { font-size: 0.875rem; }
}
</style>
<div class="min-h-screen bg-gray-50">
    <div class="max-w-6xl p-4 mx-auto">
        <!-- Header Section -->
        <div class="p-6 mb-6 bg-white border border-gray-100 shadow-sm rounded-xl">
            <div class="flex flex-col items-start justify-between md:flex-row md:items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-amber-50">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-gray-800">Edit Financial Records</h2>
                        <p class="text-sm text-gray-500">{{ $user->name }} • {{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.users.all') }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-xs hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Users
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Current Balances Card -->
            <div class="lg:col-span-1">
                <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800">Current Balances</h3>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <div class="flex items-center p-4 rounded-lg bg-blue-50">
                            <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-blue-600">Shares</p>
                                <p class="text-lg font-bold text-blue-800">₦{{ number_format($sharesBalance, 2) }}</p>
                            </div>
                        </div>

                        <div class="flex items-center p-4 rounded-lg bg-green-50">
                            <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-full">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-600">Savings</p>
                                <p class="text-lg font-bold text-green-800">₦{{ number_format($savingsBalance, 2) }}</p>
                            </div>
                        </div>

                        <div class="flex items-center p-4 rounded-lg bg-red-50">
                            <div class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-full">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-600">Loan Balance</p>
                                <p class="text-lg font-bold text-red-800">₦{{ number_format($loanBalance, 2) }}</p>
                            </div>
                        </div>

                        <div class="flex items-center p-4 rounded-lg bg-purple-50">
                            <div class="flex items-center justify-center w-10 h-10 bg-purple-100 rounded-full">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-purple-600">Essential</p>
                                <p class="text-lg font-bold text-purple-800">₦{{ number_format($essentialBalance, 2) }}</p>
                            </div>
                        </div>

                        <div class="flex items-center p-4 rounded-lg bg-yellow-50">
                            <div class="flex items-center justify-center w-10 h-10 bg-yellow-100 rounded-full">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-yellow-600">Non-essential</p>
                                <p class="text-lg font-bold text-yellow-800">₦{{ number_format($nonEssentialBalance, 2) }}</p>
                            </div>
                        </div>

                        <div class="flex items-center p-4 rounded-lg bg-indigo-50">
                            <div class="flex items-center justify-center w-10 h-10 bg-indigo-100 rounded-full">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-indigo-600">Electronics</p>
                                <p class="text-lg font-bold text-indigo-800">₦{{ number_format($electronicsBalance, 2) }}</p>
                            </div>
                        </div>

                        <!-- Entrance Fee Status -->
                        <div class="flex items-center p-4 rounded-lg {{ isset($entrancePaid) && $entrancePaid ? 'bg-emerald-50' : 'bg-gray-50' }}">
                            <div class="flex items-center justify-center w-10 h-10 {{ isset($entrancePaid) && $entrancePaid ? 'bg-emerald-100' : 'bg-gray-100' }} rounded-full">
                                <svg class="w-5 h-5 {{ isset($entrancePaid) && $entrancePaid ? 'text-emerald-600' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium {{ isset($entrancePaid) && $entrancePaid ? 'text-emerald-600' : 'text-gray-600' }}">Entrance Fee</p>
                                <p class="text-lg font-bold {{ isset($entrancePaid) && $entrancePaid ? 'text-emerald-800' : 'text-gray-800' }}">{{ isset($entrancePaid) && $entrancePaid ? 'Paid' : 'Not Paid' }}</p>
                            </div>
                        </div>

                        <!-- Loan Interest Paid -->
                        <div class="flex items-center p-4 rounded-lg bg-orange-50">
                            <div class="flex items-center justify-center w-10 h-10 bg-orange-100 rounded-full">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-orange-600">Loan Interest</p>
                                <p class="text-lg font-bold text-orange-800">₦{{ number_format(\App\Services\FinancialCalculationService::calculateLoanInterest($user), 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Form Section -->
            <div class="lg:col-span-2">
                <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-800">Manual Financial Adjustment</h2>
                        <p class="mt-1 text-sm text-gray-500">Update user's financial balances. All changes will be logged for audit purposes.</p>
                    </div>

                    @if(session('success'))
                        <div class="flex items-start p-4 mb-6 text-green-800 border border-green-200 rounded-lg bg-green-50">
                            <svg class="w-5 h-5 mt-0.5 mr-3 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="p-4 mb-6 text-red-800 border border-red-200 rounded-lg bg-red-50">
                            <div class="flex items-center mb-1">
                                <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-medium">Please correct the following errors:</span>
                            </div>
                            <ul class="mt-1 ml-5 text-sm list-disc">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.users.update_finances', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Entrance Fee Toggle -->
                        <div class="p-4 mb-4 border border-blue-200 rounded-lg bg-blue-50">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="entrance_fee_paid" value="1" class="w-5 h-5 mr-2" {{ optional($user->member)->entrance_fee_paid ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-blue-800">Entrance Fee Paid</span>
                            </label>
                            <p class="mt-1 text-xs text-blue-700">Toggling this will update the member's entrance fee status. A non-financial audit note will be recorded.</p>
                        </div>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <!-- Shares Balance -->
                            <div>
                                <label for="shares_balance" class="block mb-1 text-sm font-medium text-gray-700">
                                    Shares Balance (₦)
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </div>
                                    <input type="number" name="shares_balance" id="shares_balance" value="{{ old('shares_balance', $sharesBalance) }}"
                                           min="0" step="0.01"
                                           class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500 sm:text-sm py-2.5 @error('shares_balance') border-red-300 @enderror">
                                </div>
                                @error('shares_balance')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Savings Balance -->
                            <div>
                                <label for="savings_balance" class="block mb-1 text-sm font-medium text-gray-700">
                                    Savings Balance (₦)
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <input type="number" name="savings_balance" id="savings_balance" value="{{ old('savings_balance', $savingsBalance) }}"
                                           min="0" step="0.01"
                                           class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500 sm:text-sm py-2.5 @error('savings_balance') border-red-300 @enderror">
                                </div>
                                @error('savings_balance')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Loan Balance -->
                            <div>
                                <label for="loan_balance" class="block mb-1 text-sm font-medium text-gray-700">
                                    Loan Balance (₦)
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </div>
                                    <input type="number" name="loan_balance" id="loan_balance" value="{{ old('loan_balance', $loanBalance) }}"
                                           min="0" step="0.01"
                                           class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500 sm:text-sm py-2.5 @error('loan_balance') border-red-300 @enderror">
                                </div>
                                @error('loan_balance')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>


                            <!-- Total Loan Interest (Sync target) -->
                            <div>
                                <label for="loan_interest_total" class="block mb-1 text-sm font-medium text-gray-700">
                                    Total Loan Interest (₦)
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <input type="number" name="loan_interest_total" id="loan_interest_total" value="{{ old('loan_interest_total', \App\Services\FinancialCalculationService::calculateLoanInterest($user)) }}"
                                           min="0" step="0.01"
                                           class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500 sm:text-sm py-2.5 @error('loan_interest_total') border-red-300 @enderror">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Edit this value to change the total interest owed. The system will auto-calculate the adjustment.</p>
                                @error('loan_interest_total')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Commodity Balance -->
                            <div>
                                <label for="essential_balance" class="block mb-1 text-sm font-medium text-gray-700">
                                    Essential Commodity (₦)
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <input type="number" name="essential_balance" id="essential_balance" value="{{ old('essential_balance', $essentialBalance) }}"
                                           min="0" step="0.01"
                                           class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500 sm:text-sm py-2.5 @error('essential_balance') border-red-300 @enderror">
                                </div>
                                @error('essential_balance')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Non-essential Commodity Balance -->
                            <div>
                                <label for="non_essential_balance" class="block mb-1 text-sm font-medium text-gray-700">
                                    Non-essential Commodity (₦)
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <input type="number" name="non_essential_balance" id="non_essential_balance" value="{{ old('non_essential_balance', $nonEssentialBalance ?? 0) }}"
                                           min="0" step="0.01"
                                           class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500 sm:text-sm py-2.5 @error('non_essential_balance') border-red-300 @enderror">
                                </div>
                                @error('non_essential_balance')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Electronics Balance -->
                            <div>
                                <label for="electronics_balance" class="block mb-1 text-sm font-medium text-gray-700">
                                    Electronics Balance (₦)
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <input type="number" name="electronics_balance" id="electronics_balance" value="{{ old('electronics_balance', $electronicsBalance) }}"
                                           min="0" step="0.01"
                                           class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500 sm:text-sm py-2.5 @error('electronics_balance') border-red-300 @enderror">
                                </div>
                                @error('electronics_balance')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Adjustment Reason -->
                        <div class="mt-6">
                            <label for="adjustment_reason" class="block mb-1 text-sm font-medium text-gray-700">
                                Reason for Adjustment (Required for Audit Trail)
                            </label>
                            <div class="relative">
                                <div class="absolute pointer-events-none top-3 left-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <textarea name="adjustment_reason" id="adjustment_reason" rows="3"
                                          class="pl-10 block w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500 sm:text-sm py-2.5 @error('adjustment_reason') border-red-300 @enderror"
                                          placeholder="Explain why these financial records are being adjusted..." required>{{ old('adjustment_reason') }}</textarea>
                            </div>
                            @error('adjustment_reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">This reason will be recorded in all transaction logs for audit purposes.</p>
                        </div>

                        <!-- Warning Notice -->
                        <div class="p-4 mt-6 border rounded-lg bg-amber-50 border-amber-200">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 mt-0.5 mr-3 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <div class="text-sm text-amber-800">
                                    <p class="mb-1 font-medium">Important Notice:</p>
                                    <ul class="space-y-1 list-disc list-inside">
                                        <li>All changes will create corresponding transaction records</li>
                                        <li>Audit trail will show your admin ID and timestamp</li>
                                        <li>Changes cannot be undone - only corrected with new adjustments</li>
                                        <li>User will see these transactions in their transaction history</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-8 space-x-3">
                            <a href="{{ route('admin.users.all') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-xs hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                                Cancel
                            </a>
                            <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-amber-500 to-amber-600 border border-transparent rounded-lg shadow-xs hover:from-amber-600 hover:to-amber-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                Update Financial Records
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection