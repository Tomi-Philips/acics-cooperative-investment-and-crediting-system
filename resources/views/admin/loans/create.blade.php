@extends('layouts.admin')

@section('content')
    <div class="container grid px-6 mx-auto">
        <div class="flex flex-col items-start justify-between my-6 md:flex-row md:items-center">
            <div>
                <h2 class="flex items-center text-2xl font-semibold text-gray-700">
                    <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New Loan
                </h2>
                <p class="mt-1 text-sm text-gray-500">Create a new loan for an eligible cooperative member</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.loans.index') }}" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                    <svg class="inline w-4 h-4 mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to All Loans
                </a>
            </div>
        </div>

        ---

        <div class="flex mb-4 text-sm text-gray-600">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                Dashboard
            </a>
            <span class="mx-2">/</span>
            <a href="{{ route('admin.loans.index') }}" class="inline-flex items-center">
                Loans
            </a>
            <span class="mx-2">/</span>
            <span>Add New Loan</span>
        </div>

        ---

        @if(session('error'))
            <div class="p-4 mb-4 text-red-700 bg-red-100 border-l-4 border-red-500" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 mb-4 text-red-700 bg-red-100 border-l-4 border-red-500" role="alert">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="font-bold">Please fix the following errors:</p>
                </div>
                <ul class="ml-8 list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        ---

        <div class="px-4 py-3 mb-8 overflow-x-auto bg-white rounded-lg shadow-md">
            <h4 class="flex items-center mb-4 font-semibold text-gray-800">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Loan Details
            </h4>
            <form action="{{ route('admin.loans.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
                    <div>
                        <label for="user_id" class="block mb-2 text-sm font-medium text-gray-900">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Member
                        </label>
                        @if(isset($noEligibleMembers) && $noEligibleMembers)
                            <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <span class="font-medium">No eligible members found!</span>
                                </div>
                                <p class="mt-2">Members must have at least 6 months of membership and have paid their entrance fee to be eligible for loans.</p>
                                @if(count($ineligibleMembers) > 0)
                                    <div class="mt-4">
                                        <h4 class="mb-2 font-medium">Ineligible Members:</h4>
                                        <ul class="space-y-2">
                                            @foreach($ineligibleMembers as $member)
                                                <li class="pl-4 border-l-2 border-yellow-400">
                                                    <span class="font-medium">{{ $member['name'] }}</span> ({{ $member['member_number'] }})
                                                    <p class="mt-1 text-xs">{{ $member['reason'] }}</p>
                                                    @if(isset($member['joined_at']) && isset($member['months_remaining']))
                                                        <p class="text-xs">Joined: {{ $member['joined_at'] }} ({{ $member['months_remaining'] }} more months until eligible)</p>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="mt-4">
                                    <a href="{{ route('admin.users.add') }}" class="text-blue-600 hover:underline">
                                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Add a new member
                                    </a>
                                </div>
                            </div>
                            <select id="user_id" name="user_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" disabled>
                                <option value="">No eligible members available</option>
                            </select>
                        @else
                            <select id="user_id" name="user_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                                <option value="">Select a member</option>
                                @foreach($eligibleMembers as $member)
                                    <option value="{{ $member['id'] }}" {{ old('user_id') == $member['id'] ? 'selected' : '' }} data-max-loan="{{ $member['max_loan_amount'] }}" data-savings="{{ $member['savings'] }}" data-shares="{{ $member['shares'] }}" data-commodities="{{ $member['commodities'] }}" data-loans="{{ $member['loans'] }}">
                                        {{ $member['name'] }} ({{ $member['member_number'] }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Only eligible members are shown (6+ months membership and entrance fee paid)</p>
                        @endif
                    </div>

                    <div id="member_stats" class="hidden">
                        <label class="block mb-2 text-sm font-medium text-gray-900">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Member Financial Stats
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="p-2 rounded-lg bg-gray-50">
                                <p class="text-xs font-medium text-gray-600">Savings Balance</p>
                                <p id="savings_balance" class="text-sm font-bold text-blue-600">₦0.00</p>
                            </div>
                            <div class="p-2 rounded-lg bg-gray-50">
                                <p class="text-xs font-medium text-gray-600">Shares Balance</p>
                                <p id="shares_balance" class="text-sm font-bold text-purple-600">₦0.00</p>
                            </div>
                            <div class="p-2 rounded-lg bg-gray-50">
                                <p class="text-xs font-medium text-gray-600">Commodity Balance</p>
                                <p id="commodity_balance" class="text-sm font-bold text-orange-600">₦0.00</p>
                            </div>
                            <div class="p-2 rounded-lg bg-gray-50">
                                <p class="text-xs font-medium text-gray-600">Loan Balance</p>
                                <p id="loan_balance" class="text-sm font-bold text-red-600">₦0.00</p>
                            </div>
                        </div>
                        <div class="p-2 mt-2 rounded-lg bg-green-50">
                            <p class="text-xs font-medium text-gray-600">Eligible Loan Amount</p>
                            <p id="eligible_loan_amount" class="text-sm font-bold text-green-600">₦0.00</p>
                            <p class="mt-1 text-xs text-gray-500">Calculation: 2 &times; (Savings + Shares - Commodity - Loan)</p>
                        </div>
                    </div>

                    <div>
                        <label for="amount" class="block mb-2 text-sm font-medium text-gray-900">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Loan Amount (₦)
                        </label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-gray-500">₦</span>
                            </div>
                            <input type="number" id="amount" name="amount" min="1000" step="1000" value="{{ old('amount', 10000) }}" class="pl-8 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                        </div>
                        <div class="mt-2">
                            <input type="range" id="amount_slider" min="1000" max="500000" step="1000" value="{{ old('amount', 10000) }}" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            <div class="flex justify-between mt-1 text-xs text-gray-500">
                                <span>₦1,000</span>
                                <span id="max_loan_info">Maximum: <span id="max_loan_amount" class="font-medium text-green-600">₦0.00</span></span>
                            </div>
                        </div>
                    </div>



                    <div>
                        <label for="repayment_method" class="block mb-2 text-sm font-medium text-gray-900">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Repayment Method
                        </label>
                        <select id="repayment_method" name="repayment_method" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                            <option value="bursary_deduction" selected>Bursary Deduction</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Only bursary deduction is allowed as repayment method</p>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="purpose" class="block mb-2 text-sm font-medium text-gray-900">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Loan Purpose (Optional)
                    </label>
                    <textarea id="purpose" name="purpose" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" placeholder="Purpose of the loan">{{ old('purpose') }}</textarea>
                </div>

                <div class="p-4 mb-6 border border-gray-200 rounded-lg bg-gray-50">
                    <h3 class="flex items-center mb-3 text-lg font-medium text-gray-900">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Loan Details Preview
                    </h3>
                    <div class="grid w-full grid-cols-1 gap-4 text-sm sm:grid-cols-2">
                        <div class="p-3 bg-white rounded-lg shadow-sm">
                            <p class="text-xs text-gray-500">Interest Rate:</p>
                            <p class="text-base font-medium text-gray-900">10% (Fixed)</p>
                        </div>
                        <div class="p-3 bg-white rounded-lg shadow-sm">
                            <p class="text-xs text-gray-500">Monthly Payment:</p>
                            <p id="monthly_payment" class="text-base font-medium text-green-600">Calculating...</p>
                        </div>
                        <div class="p-3 bg-white rounded-lg shadow-sm">
                            <p class="text-xs text-gray-500">Total Payment:</p>
                            <p id="total_payment" class="text-base font-medium text-green-600">Calculating...</p>
                        </div>
                        <div class="p-3 bg-white rounded-lg shadow-sm">
                            <p class="text-xs text-gray-500">Total Interest:</p>
                            <p id="total_interest" class="text-base font-medium text-green-600">Calculating...</p>
                        </div>
                    </div>
                    <div class="mt-4 text-center sm:text-left">
                        <a href="{{ route('admin.loans.calculator') }}" target="_blank" class="inline-flex items-center text-sm text-blue-600 hover:underline">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Open Loan Calculator for detailed amortization schedule
                        </a>
                    </div>
                </div>

                <div class="flex flex-col items-center justify-center gap-3 sm:flex-row sm:justify-end">
                    <a href="{{ route('admin.loans.index') }}" class="w-full sm:w-auto text-center text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" class="w-full sm:w-auto text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Loan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/loan-create.js') }}"></script>
@endsection