@extends('layouts.user')

@section('title', 'My Profile')

@section('content')
@if(session('success'))
<div class="max-w-4xl p-4 mx-auto mb-4 text-green-800 bg-green-100 border border-green-200 rounded-lg">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="max-w-4xl p-4 mx-auto mb-4 text-red-800 bg-red-100 border border-red-200 rounded-lg">
    {{ session('error') }}
</div>
@endif

<div class="max-w-6xl mx-auto">
    <!-- Enhanced Header Section -->
    <div class="p-6 mb-6 border border-gray-200 shadow-lg bg-gradient-to-r from-blue-50 to-green-50 rounded-xl">
        <div class="flex flex-col items-center justify-between lg:flex-row">
            <div class="mb-6 lg:mb-0">
                <h1 class="flex items-center text-2xl font-bold text-gray-800">
                    <div class="p-2 mr-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    My Profile (Updated)
                </h1>
                <p class="mt-2 text-sm text-gray-600">Your personal information and membership details</p>
            </div>
        </div>
    </div>

    <div class="p-5 overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">

        <!-- Personal Information Section -->
        <div class="mb-8">
            <h3 class="flex items-center mb-4 text-xl font-semibold text-gray-700">
                <span class="mr-2">📋</span> Personal Information
            </h3>
            <div class="p-5 rounded-lg bg-blue-50">
                <div class="flex flex-col gap-6 md:flex-row">
                    <!-- Profile Photo -->
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center w-32 h-32 overflow-hidden bg-gray-200 border-4 border-white rounded-full shadow-md">
                            @if($user->member && $user->member->profile_photo)
                            <img src="{{ Storage::url($user->member->profile_photo) }}" alt="Profile Photo" class="object-cover w-full h-full">
                            @else
                            <span class="text-5xl text-gray-400">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="mt-2 text-center">
                            <button type="button" data-modal-target="edit-profile-modal" data-modal-toggle="edit-profile-modal" class="text-sm text-blue-600 hover:text-blue-800">Change Photo</button>
                        </div>
                    </div>

                    <!-- User Details -->
                    <div class="grid flex-grow grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <p class="text-sm text-gray-500">Full Name</p>
                            <p class="font-medium">{{ $user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Membership ID</p>
                            <p class="font-medium">{{ $user->member_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email Address</p>
                            <p class="font-medium">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Phone Number</p>
                            <p class="font-medium">{{ $user->member ? $user->member->phone : 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Department</p>
                            <p class="font-medium">{{ $user->department ? $user->department->name : 'Not assigned' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Member Since</p>
                            <p class="font-medium">{{ $user->member && $user->member->joined_at ? $user->member->joined_at->format('F d, Y') : 'Not available' }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="button" data-modal-target="edit-profile-modal" data-modal-toggle="edit-profile-modal" class="px-4 py-2 text-sm text-white transition-colors rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700">
                        Edit Personal Information
                    </button>
                </div>
            </div>
        </div>

        <!-- Membership Details Section -->
        <div class="mb-8">
            <h3 class="flex items-center mb-4 text-xl font-semibold text-gray-700">
                <span class="mr-2">🏛️</span> Membership Details
            </h3>
            <div class="p-5 rounded-lg bg-gray-50">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <!-- Membership Status Card -->
                    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="text-sm text-gray-500">Membership Status</div>
                        <div class="flex items-center mt-1">
                            @if($user->member && $user->member->status === 'active')
                            <span class="w-3 h-3 mr-2 bg-green-500 rounded-full"></span>
                            <span class="font-medium">Active</span>
                            @elseif($user->member && $user->member->status === 'inactive')
                            <span class="w-3 h-3 mr-2 bg-red-500 rounded-full"></span>
                            <span class="font-medium">Inactive</span>
                            @elseif($user->member && $user->member->status === 'suspended')
                            <span class="w-3 h-3 mr-2 bg-yellow-500 rounded-full"></span>
                            <span class="font-medium">Suspended</span>
                            @else
                            <span class="w-3 h-3 mr-2 bg-gray-500 rounded-full"></span>
                            <span class="font-medium">Unknown</span>
                            @endif
                        </div>
                    </div>

                    <!-- Membership Type Card -->
                    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="text-sm text-gray-500">Membership Type</div>
                        <div class="mt-1 font-medium">{{ $user->member && $user->member->type ? ucfirst($user->member->type) : 'Regular Member' }}</div>
                    </div>

                    <!-- Monthly Contribution Card -->
                    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="text-sm text-gray-500">Monthly Contribution</div>
                        <div class="mt-1 font-medium">₦{{ $user->member && $user->member->monthly_contribution ? number_format($user->member->monthly_contribution, 2) : '0.00' }}</div>
                    </div>
                </div>
                <div class="mt-6">
                    <h4 class="mb-2 font-medium">Membership Benefits:</h4>
                    <ul class="grid grid-cols-1 gap-2 text-sm md:grid-cols-2">
                        <li class="flex items-start">
                            <span class="mr-2 text-green-500">✓</span>
                            <span>Access to loans up to 2× your savings</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2 text-green-500">✓</span>
                            <span>Annual dividends on shares</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2 text-green-500">✓</span>
                            <span>Commodity purchase on credit</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2 text-green-500">✓</span>
                            <span>Financial advisory services</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Next of Kin Section -->
        <div class="mb-8">
            <h3 class="flex items-center mb-4 text-xl font-semibold text-gray-700">
                <span class="mr-2">👪</span> Next of Kin Information
            </h3>
            <div class="p-5 rounded-lg bg-yellow-50">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <p class="text-sm text-gray-500">Full Name</p>
                        <p class="font-medium">{{ $user->member && $user->member->next_of_kin_name ? $user->member->next_of_kin_name : 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Relationship</p>
                        <p class="font-medium">{{ $user->member && $user->member->next_of_kin_relationship ? $user->member->next_of_kin_relationship : 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone Number</p>
                        <p class="font-medium">{{ $user->member && $user->member->next_of_kin_phone ? $user->member->next_of_kin_phone : 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Address</p>
                        <p class="font-medium">{{ $user->member && $user->member->next_of_kin_address ? $user->member->next_of_kin_address : 'Not provided' }}</p>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="button" data-modal-target="next-of-kin-modal" data-modal-toggle="next-of-kin-modal" class="px-4 py-2 text-sm text-white transition-colors rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700">
                        Edit Next of Kin
                    </button>
                </div>
            </div>
        </div>

        <!-- Financial Summary Section -->
        <div>
            <h3 class="flex items-center mb-4 text-xl font-semibold text-gray-700">
                <span class="mr-2">📊</span> Financial Summary
            </h3>
            <div class="p-5 rounded-lg bg-green-50">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    <!-- Entrance Fee Status -->
                    <div class="p-4 bg-white border border-green-100 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">Entrance Fee</div>
                            <div class="flex items-center justify-center w-6 h-6 {{ $transactionData['entrance_fee']['paid'] ? 'bg-green-100' : 'bg-red-100' }} rounded-full">
                                @if($transactionData['entrance_fee']['paid'])
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <div class="mt-1 text-lg font-bold {{ $transactionData['entrance_fee']['paid'] ? 'text-green-600' : 'text-red-600' }}">
                            {{ $transactionData['entrance_fee']['status'] }}
                        </div>
                    </div>

                    <!-- Shares Value -->
                    <div class="p-4 bg-white border border-green-100 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">Shares</div>
                            <div class="flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-1 text-lg font-bold text-blue-600">{{ $financialData['shares_formatted'] ?? '₦0.00' }}</div>
                    </div>

                    <!-- Savings Balance -->
                    <div class="p-4 bg-white border border-green-100 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">Savings</div>
                            <div class="flex items-center justify-center w-6 h-6 bg-green-100 rounded-full">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-1 text-lg font-bold text-green-600">{{ $financialData['savings_formatted'] ?? '₦0.00' }}</div>
                    </div>

                    <!-- Outstanding Loans -->
                    <div class="p-4 bg-white border border-green-100 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">Loan Balance</div>
                            <div class="flex items-center justify-center w-6 h-6 bg-red-100 rounded-full">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-1 text-lg font-bold {{ ($financialData['loan_balance'] ?? 0) > 0 ? 'text-red-600' : 'text-gray-800' }}">{{ $financialData['loan_formatted'] ?? '₦0.00' }}</div>
                    </div>

                    <!-- Essential Commodity Balance -->
                    <div class="p-4 bg-white border border-green-100 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">Essential Commodity</div>
                            <div class="flex items-center justify-center w-6 h-6 bg-purple-100 rounded-full">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-1 text-lg font-bold {{ ($financialData['essential_commodity'] ?? 0) > 0 ? 'text-red-600' : 'text-gray-800' }}">{{ $financialData['essential_commodity_formatted'] ?? '₦0.00' }}</div>
                    </div>

                    <!-- Non-essential Commodity Balance -->
                    <div class="p-4 bg-white border border-green-100 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">Non-essential Commodity</div>
                            <div class="flex items-center justify-center w-6 h-6 bg-yellow-100 rounded-full">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-1 text-lg font-bold {{ ($financialData['non_essential_commodity'] ?? 0) > 0 ? 'text-red-600' : 'text-gray-800' }}">{{ $financialData['non_essential_commodity_formatted'] ?? '₦0.00' }}</div>
                    </div>

                    <!-- Electronics Balance -->
                    <div class="p-4 bg-white border border-green-100 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">Electronics</div>
                            <div class="flex items-center justify-center w-6 h-6 bg-indigo-100 rounded-full">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-1 text-lg font-bold {{ ($financialData['electronics_balance'] ?? 0) > 0 ? 'text-red-600' : 'text-gray-800' }}">{{ $financialData['electronics_formatted'] ?? '₦0.00' }}</div>
                    </div>

                    <!-- Loan Interest Paid -->
                    <div class="p-4 bg-white border border-green-100 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">Loan Interest Paid</div>
                            <div class="flex items-center justify-center w-6 h-6 bg-orange-100 rounded-full">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-1 text-lg font-bold text-orange-600">₦{{ number_format($transactionData['loan_interest']['total_paid'], 2) }}</div>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('user.transaction_report') }}" class="flex items-center text-sm text-blue-600 hover:text-blue-800">
                        View Full Transaction History
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Transaction History Section -->
        <div class="mb-8">
            <!-- Card Header -->
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-blue-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Transaction History</h2>
                        <p class="mt-1 text-sm text-gray-600">Your recent financial activities and transactions</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('user.transaction_report') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-all duration-200 border border-blue-600 rounded-lg shadow-sm bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            View All
                        </a>
                    </div>
                </div>
            </div>

            <!-- Table Content -->
            <div class="p-2">
                @if($transactions->count() > 0)
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden border-b border-gray-200 shadow-sm sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date</th>
                                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Description</th>
                                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Amount</th>
                                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($transactions as $transaction)
                                    <tr class="transition-colors hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">
                                            {{ $transaction->created_at->format('Y-m-d') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="mr-2 h-5 w-5 {{ $transaction->icon_class }}">
                                                    @if (in_array($transaction->type, ['deposit', 'loan_disbursement', 'saving_credit', 'share_credit']))
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                        </svg>
                                                    @elseif(in_array($transaction->type, ['withdrawal', 'loan_payment', 'saving_debit', 'share_debit', 'commodity_essential', 'commodity_non_essential', 'electronics']))
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd" />
                                                        </svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                        </svg>
                                                    @endif
                                                </div>
                                                {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                                            </div>
                                            @if ($transaction->description)
                                                <p class="text-xs text-gray-400 ml-7">{{ $transaction->description }}</p>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">
                                            {{ $transaction->formatted_amount }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $transaction->status_badge_class }}">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @if ($transactions->hasPages())
                        <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200 bg-gray-50">
                            <div class="text-sm text-gray-700">
                                Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} transactions
                            </div>
                            <div class="flex space-x-1">
                                {{-- Previous Page Link --}}
                                @if ($transactions->onFirstPage())
                                    <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-not-allowed rounded-l-md">
                                        Previous
                                    </span>
                                @else
                                    <a href="{{ $transactions->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">
                                        Previous
                                    </a>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                                    @if ($page == $transactions->currentPage())
                                        <span class="px-3 py-2 text-sm font-medium text-blue-600 border border-blue-500 cursor-default bg-blue-50">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 hover:bg-gray-50">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($transactions->hasMorePages())
                                    <a href="{{ $transactions->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">
                                        Next
                                    </a>
                                @else
                                    <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-not-allowed rounded-r-md">
                                        Next
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                @else
                    <div class="px-6 py-12 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2 2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions found</h3>
                        <p class="mt-1 text-sm text-gray-500">Your transaction history will appear here.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Account Settings -->
        <div class="flex items-center justify-between mt-8">
            <a href="{{ route('user.settings') }}" class="flex items-center px-6 py-3 text-sm font-medium text-white transition-colors bg-gradient-to-r from-gray-500 to-gray-600 rounded-lg hover:from-gray-600 hover:to-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Account Settings
            </a>
            <a href="{{ route('user.support') }}" class="flex items-center px-6 py-3 text-sm font-medium text-white transition-colors bg-gradient-to-r from-green-500 to-green-600 rounded-lg hover:from-green-600 hover:to-green-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                Contact Support
            </a>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="edit-profile-modal" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 hidden overflow-y-auto overflow-x-hidden">
    <div class="relative w-full h-full max-w-md p-4 md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 border-b rounded-t md:p-5 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Edit Personal Information
                </h3>
                <button type="button" class="inline-flex items-center justify-center w-8 h-8 text-sm text-gray-400 bg-transparent rounded-lg hover:bg-gray-200 hover:text-gray-900 ms-auto dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="edit-profile-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-4 md:p-5">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter your full name" required>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->member ? $user->member->phone : '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter phone number" required>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                        <input type="text" name="address" id="address" value="{{ old('address', $user->member ? $user->member->address : '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter address" required>
                    </div>
                    <div class="col-span-2">
                        <label for="profile_photo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Profile Photo</label>
                        <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <p class="mt-1 text-sm text-gray-500">Optional. Max file size: 2MB. Accepted formats: JPEG, PNG, JPG</p>
                    </div>
                </div>
                <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 112 0z" clip-rule="evenodd"></path>
                    </svg>
                    Update Profile
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Next of Kin Modal -->
<div id="next-of-kin-modal" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 hidden overflow-y-auto overflow-x-hidden">
    <div class="relative w-full h-full max-w-md p-4 md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 border-b rounded-t md:p-5 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Edit Next of Kin Information
                </h3>
                <button type="button" class="inline-flex items-center justify-center w-8 h-8 text-sm text-gray-400 bg-transparent rounded-lg hover:bg-gray-200 hover:text-gray-900 ms-auto dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="next-of-kin-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form action="{{ route('user.profile.update_next_of_kin') }}" method="POST" class="p-4 md:p-5">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="next_of_kin_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Full Name</label>
                        <input type="text" name="next_of_kin_name" id="next_of_kin_name" value="{{ old('next_of_kin_name', $user->member ? $user->member->next_of_kin_name : '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter next of kin full name" required>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="next_of_kin_relationship" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Relationship</label>
                        <input type="text" name="next_of_kin_relationship" id="next_of_kin_relationship" value="{{ old('next_of_kin_relationship', $user->member ? $user->member->next_of_kin_relationship : '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="e.g., Spouse, Parent, Sibling" required>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="next_of_kin_phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone Number</label>
                        <input type="text" name="next_of_kin_phone" id="next_of_kin_phone" value="{{ old('next_of_kin_phone', $user->member ? $user->member->next_of_kin_phone : '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter phone number" required>
                    </div>
                    <div class="col-span-2">
                        <label for="next_of_kin_address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                        <input type="text" name="next_of_kin_address" id="next_of_kin_address" value="{{ old('next_of_kin_address', $user->member ? $user->member->next_of_kin_address : '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter address" required>
                    </div>
                </div>
                <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 112 0z" clip-rule="evenodd"></path>
                    </svg>
                    Update Next of Kin
                </button>
            </form>
        </div>
    </div>
</div>
@endsection