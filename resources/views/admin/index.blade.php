@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Enhanced Header Section -->
    <div class="p-6 mb-6 border border-gray-200 shadow-lg bg-gradient-to-r from-green-50 to-blue-50 rounded-xl">
        <div class="flex flex-col items-center justify-between lg:flex-row">
            <div class="mb-6 lg:mb-0">
                <h1 class="flex items-center text-2xl font-bold text-gray-800">
                    <div class="p-2 mr-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            Total Amount                        
                        </svg>
                    </div>
                    Admin Dashboard
                </h1>
                <p class="mt-2 text-sm text-gray-600">Overview of key metrics and recent activities</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid gap-6 mb-8 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        <!-- Total Users Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-blue-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-blue-50 to-blue-100 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-blue-100 rounded-full opacity-20"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="p-4 shadow-lg bg-gradient-to-br from-blue-400 to-blue-500 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="absolute w-4 h-4 bg-blue-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Total Users</p>
                            <p class="mt-1 text-3xl font-bold text-gray-800">{{ number_format($totalUsers) }}</p>
                            <p class="mt-1 text-xs font-medium text-blue-600">Registered members</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Loans Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-green-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-green-50 to-green-100 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-green-100 rounded-full opacity-20"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="p-4 shadow-lg bg-gradient-to-br from-green-400 to-green-500 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="absolute w-4 h-4 bg-green-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Active Loans</p>
                            <p class="mt-1 text-3xl font-bold text-gray-800">{{ number_format($activeLoans) }}</p>
                            <p class="mt-1 text-xs font-medium text-green-600">Currently disbursed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-teal-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-teal-50 to-teal-100 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-teal-100 rounded-full opacity-20"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="p-4 shadow-lg bg-gradient-to-br from-teal-400 to-teal-500 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="absolute w-4 h-4 bg-teal-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Total Revenue</p>
                            <p class="mt-1 text-3xl font-bold text-gray-800">₦{{ number_format($totalRevenue) }}</p>
                            <p class="mt-1 text-xs font-medium text-teal-600">All time earnings</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Loans Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-orange-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-orange-50 to-orange-100 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-orange-100 rounded-full opacity-20"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="p-4 shadow-lg bg-gradient-to-br from-orange-400 to-orange-500 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="absolute w-4 h-4 bg-orange-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Pending Loans</p>
                            <p class="mt-1 text-3xl font-bold text-gray-800">{{ number_format($pendingLoans) }}</p>
                            <p class="mt-1 text-xs font-medium text-orange-600">Awaiting approval</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Cards -->
    <div class="grid gap-6 mb-8 md:grid-cols-2 lg:grid-cols-3">
        <!-- Inactive Users Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-red-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-red-50 to-red-100 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-red-100 rounded-full opacity-20"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="p-4 shadow-lg bg-gradient-to-br from-red-400 to-red-500 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="absolute w-4 h-4 bg-red-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Inactive Users</p>
                            <p class="mt-1 text-3xl font-bold text-gray-800">{{ number_format($inactiveUsers) }}</p>
                            <p class="mt-1 text-xs font-medium text-red-600">Need attention</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Feedback Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-green-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-green-50 to-green-100 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-green-100 rounded-full opacity-20"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="p-4 shadow-lg bg-gradient-to-br from-green-400 to-green-500 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <div class="absolute w-4 h-4 bg-green-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Pending Feedback</p>
                            <p class="mt-1 text-3xl font-bold text-gray-800">{{ number_format($pendingFeedback) }}</p>
                            <p class="mt-1 text-xs font-medium text-green-600">Support tickets</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loan Requests Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-blue-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-blue-50 to-blue-100 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-blue-100 rounded-full opacity-20"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="p-4 shadow-lg bg-gradient-to-br from-blue-400 to-blue-500 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                            <div class="absolute w-4 h-4 bg-blue-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Loan Requests</p>
                            <p class="mt-1 text-3xl font-bold text-gray-800">{{ number_format($pendingLoans) }}</p>
                            <p class="mt-1 text-xs font-medium text-blue-600">Pending applications</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
        <div class="overflow-hidden transition-shadow bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Loan Trend</h3>
                    <div class="flex space-x-2">
                        <select class="text-xs border-gray-300 rounded-md sm:text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option selected>Last 7 days</option>
                            <option>Last 30 days</option>
                            <option>Last 90 days</option>
                        </select>
                    </div>
                </div>
                <div class="relative">
                    <canvas id="loanChart" class="w-full h-48 sm:h-56"></canvas>
                </div>
            </div>
        </div>

        <div class="overflow-hidden transition-shadow bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">New User Signups</h3>
                    <div class="flex space-x-2">
                        <select class="text-xs border-gray-300 rounded-md sm:text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option selected>Last 7 days</option>
                            <option>Last 30 days</option>
                            <option>Last 90 days</option>
                        </select>
                    </div>
                </div>
                <div class="relative">
                    <canvas id="userChart" class="w-full h-48 sm:h-56"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions Card -->
    <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
        <!-- Card Header -->
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Recent Transactions</h2>
                    <p class="mt-1 text-sm text-gray-600">Grouped and individual transactions with smart organization</p>
                </div>
                <div class="flex space-x-3">
                    <button id="refreshTransactions" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-all duration-200 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 border border-transparent rounded-lg shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh
                    </button>
                    <a href="{{ route('admin.transactions') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-all duration-200 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 border border-transparent rounded-lg shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        View All
                    </a>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="p-2">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden border-b border-gray-200 shadow-sm sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Date/Time
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Transaction Type
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Description
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Amount
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Records
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Method
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentTransactions as $group)
                                    <tr class="bg-white hover:bg-green-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $group->created_at->format('M d, Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $group->created_at->format('H:i:s') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $groupTypeColors = [
                                                    'mab_bulk_upload' => 'purple',
                                                    'user_bulk_upload' => 'blue',
                                                    'manual_transaction' => 'green',
                                                    'admin_approval' => 'yellow',
                                                    'system_transaction' => 'gray',
                                                    'bulk_operation' => 'indigo'
                                                ];
                                                $color = $groupTypeColors[$group->group_type] ?? 'gray';

                                                // Get the primary transaction type from the group for icon selection
                                                $primaryTransaction = $group->transactions->first();
                                                $transactionType = $primaryTransaction ? $primaryTransaction->type : 'unknown';

                                                $transactionIcons = [
                                                    'loan_disbursement' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                                                    'loan_repayment' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>',
                                                    'loan_interest' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                                                    'entrance_fee' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>',
                                                    'commodity' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
                                                    'electronics' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
                                                    'saving_credit' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>',
                                                    'saving_debit' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>',
                                                    'share_credit' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                                                    'share_debit' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                                                    'default' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>'
                                                ];
                                                $iconPath = $transactionIcons[$transactionType] ?? $transactionIcons['default'];
                                            @endphp
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-{{ $color }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    {!! $iconPath !!}
                                                </svg>
                                                <span class="px-2 py-1 text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800 rounded-full">
                                                    {{ $group->group_type_display }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900" title="{{ $group->title }}">
                                                {{ Str::limit($group->title, 35, '...') }}
                                            </div>
                                            {{-- <div class="text-xs text-gray-500">Ref: {{ Str::limit($group->group_reference, 15, '...') }}</div> --}}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                            ₦{{ number_format($group->total_amount ?? 0, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">
                                                {{ $group->total_records ?? 0 }} records
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            Grouped
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusColors = [
                                                    'completed' => 'green',
                                                    'pending' => 'yellow',
                                                    'failed' => 'red',
                                                    'processing' => 'blue'
                                                ];
                                                $statusColor = $statusColors[$group->status] ?? 'gray';
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-{{ $statusColor }}-400" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                {{ ucfirst($group->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                            <a href="{{ route('admin.transactions.show', 'GROUP-' . $group->id) }}" class="mr-3 text-blue-600 hover:text-blue-900" title="View Group Details">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                </svg>
                                                <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions found</h3>
                                                <p class="mt-1 text-sm text-gray-500">Get started by processing some transactions.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            @if($recentTransactions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing {{ $recentTransactions->firstItem() }} to {{ $recentTransactions->lastItem() }} of {{ $recentTransactions->total() }} transactions
                        </div>
                        <div class="flex space-x-1">
                            {{-- Previous Page Link --}}
                            @if ($recentTransactions->onFirstPage())
                                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-not-allowed rounded-l-md">
                                    Previous
                                </span>
                            @else
                                <a href="{{ $recentTransactions->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-white bg-gradient-to-r from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600 border border-transparent rounded-l-md">
                                    Previous
                                </a>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($recentTransactions->getUrlRange(1, $recentTransactions->lastPage()) as $page => $url)
                                @if ($page == $recentTransactions->currentPage())
                                    <span class="px-3 py-2 text-sm font-medium text-blue-600 border border-blue-500 cursor-default bg-blue-50">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="px-3 py-2 text-sm font-medium text-white bg-gradient-to-r from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600 border border-transparent">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($recentTransactions->hasMorePages())
                                <a href="{{ $recentTransactions->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-white bg-gradient-to-r from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600 border border-transparent rounded-r-md">
                                    Next
                                </a>
                            @else
                                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-not-allowed rounded-r-md">
                                    Next
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart data from controller
    const dateLabels = {!! $dateLabels !!};
    const userRegistrationData = {!! $userRegistrationData !!};
    const loanCountData = {!! $loanCountData !!};
    const loanAmountData = {!! $loanAmountData !!};

    // Loan Trend Chart
    const loanCtx = document.getElementById('loanChart').getContext('2d');
    const loanChart = new Chart(loanCtx, {
        type: 'line',
        data: {
            labels: dateLabels,
            datasets: [{
                label: 'Loan Applications',
                data: loanCountData,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        title: function(context) {
                            return new Date(context[0].label).toLocaleDateString('en-US', {
                                weekday: 'short',
                                month: 'short',
                                day: 'numeric'
                            });
                        },
                        label: function(context) {
                            return context.parsed.y + ' loan applications';
                        }
                    }
                }
            },
            scales: {
                x: {
                    display: true,
                    grid: {
                        display: false
                    },
                    ticks: {
                        callback: function(value, index, values) {
                            const date = new Date(this.getLabelForValue(value));
                            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                        },
                        color: '#6B7280',
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    display: true,
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        color: '#6B7280',
                        font: {
                            size: 12
                        },
                        stepSize: 1
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    // User Signups Chart
    const userCtx = document.getElementById('userChart').getContext('2d');
    const userChart = new Chart(userCtx, {
        type: 'bar',
        data: {
            labels: dateLabels,
            datasets: [{
                label: 'New Users',
                data: userRegistrationData,
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                borderColor: 'rgb(16, 185, 129)',
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        title: function(context) {
                            return new Date(context[0].label).toLocaleDateString('en-US', {
                                weekday: 'short',
                                month: 'short',
                                day: 'numeric'
                            });
                        },
                        label: function(context) {
                            return context.parsed.y + ' new users';
                        }
                    }
                }
            },
            scales: {
                x: {
                    display: true,
                    grid: {
                        display: false
                    },
                    ticks: {
                        callback: function(value, index, values) {
                            const date = new Date(this.getLabelForValue(value));
                            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                        },
                        color: '#6B7280',
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    display: true,
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        color: '#6B7280',
                        font: {
                            size: 12
                        },
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush