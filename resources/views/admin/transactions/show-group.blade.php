@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Enhanced Header Section -->
    <div class="p-6 mb-4 border border-gray-200 shadow-lg bg-gradient-to-r from-green-50 to-purple-50 rounded-xl">
        <div class="flex flex-col items-center justify-between lg:flex-row">
            <div class="mb-6 lg:mb-0">
                <div class="flex items-center">
                    @php
                        $iconColors = [
                            'mab_bulk_upload' => 'purple',
                            'user_bulk_upload' => 'blue',
                            'manual_transaction' => 'green',
                            'admin_approval' => 'yellow',
                            'system_transaction' => 'gray',
                            'bulk_operation' => 'indigo'
                        ];
                        $iconColor = $iconColors[$transactionGroup->group_type] ?? 'gray';
                    @endphp
                    <div class="p-3 mr-4 bg-{{ $iconColor }}-100 rounded-xl">
                        <svg class="w-8 h-8 text-{{ $iconColor }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($transactionGroup->group_type == 'mab_bulk_upload')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            @elseif($transactionGroup->group_type == 'user_bulk_upload')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            @elseif($transactionGroup->group_type == 'manual_transaction')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            @elseif($transactionGroup->group_type == 'admin_approval')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            @endif
                        </svg>
                    </div>
                    <div>
                        <h1 class="flex items-center text-lg font-bold text-gray-800 md:text-xl">
                            {{ $transactionGroup->title }}
                        </h1>
                        <p class="mt-1 text-sm text-gray-600">{{ $transactionGroup->group_reference }}</p>
                        <p class="mt-1 text-xs text-gray-500">{{ $transactionGroup->description ?? 'Transaction group details' }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.transactions') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Transactions
                </a>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="px-4 py-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Group Summary -->
        <div class="mb-8 overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-purple-50">
                <div class="flex items-center">
                    <div class="p-2 mr-4 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Summary</h2>
                        <p class="text-sm text-gray-600">Transaction batch overview</p>
                    </div>
                </div>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-2 gap-8 md:grid-cols-3 lg;grid-cols-4">
                    <div class="text-center">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-gray-100 rounded-full">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="text-xl font-bold text-gray-900 md:text-2xl">{{ $transactionGroup->total_records ?? 0 }}</div>
                        <div class="text-sm font-medium text-gray-500">Total Records</div>
                    </div>
                    <div class="text-center">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-green-100 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div class="text-xl font-bold text-green-600 md:text-2xl">₦{{ number_format($transactionGroup->total_amount ?? 0, 2) }}</div>
                        <div class="text-sm font-medium text-gray-500">Total Amount</div>
                    </div>
                    <div class="text-center">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-blue-100 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="text-xl font-bold text-blue-600 md:text-2xl">{{ ucfirst($transactionGroup->status) }}</div>
                        <div class="text-sm font-medium text-gray-500">Status</div>
                    </div>
                    <div class="text-center">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-purple-100 rounded-full">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="text-xl font-bold text-purple-600 md:text-2xl">{{ $transactionGroup->created_at->format('M d, Y') }}</div>
                        <div class="text-sm font-medium text-gray-500">Created Date</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Individual Transactions -->
        <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-green-50 to-blue-50">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center">
                        <div class="p-2 mr-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Individual Transactions</h2>
                            <p class="text-sm text-gray-600">Detailed breakdown of all transactions in this group</p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <!-- Transaction Type Filter -->
                        <div class="relative">
                            <select id="type-filter" class="pl-10 pr-8 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white cursor-pointer transition-all duration-200" onchange="filterByType(this.value)">
                                <option value="all">All Types</option>
                                <option value="share">Share Transactions</option>
                                <option value="saving">Saving Transactions</option>
                                <option value="loan">Loan Payments</option>
                                <option value="commodity">Commodity Transactions</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Search -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" id="search-input" placeholder="Search transactions..."
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm transition duration-150 ease-in-out"
                                onkeyup="searchTransactions(this.value)">
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">User</th>
                            <th class="px-6 py-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Description</th>
                            <th class="px-6 py-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $allTransactions = collect();

                            // Merge paginated share transactions
                            if(isset($shareTransactions) && $shareTransactions->count() > 0) {
                                $allTransactions = $allTransactions->merge($shareTransactions->map(function($t) {
                                    return (object)[
                                        'user' => $t->user,
                                        'type' => 'Share ' . ucfirst($t->type),
                                        'amount' => $t->amount,
                                        'description' => $t->description ?? 'Share transaction',
                                        'created_at' => $t->created_at,
                                        'transaction_date' => $t->transaction_date ?? $t->created_at,
                                        'sort_date' => $t->transaction_date ?? $t->created_at
                                    ];
                                }));
                            }

                            // Merge paginated saving transactions
                            if(isset($savingTransactions) && $savingTransactions->count() > 0) {
                                $allTransactions = $allTransactions->merge($savingTransactions->map(function($t) {
                                    return (object)[
                                        'user' => $t->user,
                                        'type' => 'Saving ' . ucfirst($t->type),
                                        'amount' => $t->amount,
                                        'description' => $t->description ?? 'Saving transaction',
                                        'created_at' => $t->created_at,
                                        'transaction_date' => $t->transaction_date ?? $t->created_at,
                                        'sort_date' => $t->transaction_date ?? $t->created_at
                                    ];
                                }));
                            }

                            // Merge paginated regular transactions
                            if(isset($regularTransactions) && $regularTransactions->count() > 0) {
                                $allTransactions = $allTransactions->merge($regularTransactions->map(function($t) {
                                    return (object)[
                                        'user' => $t->user,
                                        'type' => ucfirst(str_replace('_', ' ', $t->type)),
                                        'amount' => $t->amount,
                                        'description' => $t->description ?? 'Transaction',
                                        'created_at' => $t->created_at,
                                        'transaction_date' => $t->transaction_date ?? $t->created_at,
                                        'sort_date' => $t->transaction_date ?? $t->created_at
                                    ];
                                }));
                            }

                            // Merge paginated commodity transactions
                            if(isset($commodityTransactions) && $commodityTransactions->count() > 0) {
                                $allTransactions = $allTransactions->merge($commodityTransactions->map(function($t) {
                                    return (object)[
                                        'user' => $t->user,
                                        'type' => 'Commodity ' . ucfirst($t->type),
                                        'amount' => $t->amount,
                                        'description' => $t->description ?? 'Commodity transaction',
                                        'created_at' => $t->created_at,
                                        'transaction_date' => $t->transaction_date ?? $t->created_at,
                                        'sort_date' => $t->transaction_date ?? $t->created_at
                                    ];
                                }));
                            }

                            // Merge paginated loan payments
                            if(isset($loanPayments) && $loanPayments->count() > 0) {
                                $allTransactions = $allTransactions->merge($loanPayments->map(function($t) {
                                    return (object)[
                                        'user' => $t->user,
                                        'type' => 'Loan Payment',
                                        'amount' => $t->amount,
                                        'description' => $t->description ?? 'Loan payment',
                                        'created_at' => $t->created_at,
                                        'transaction_date' => $t->payment_date ?? $t->created_at,
                                        'sort_date' => $t->payment_date ?? $t->created_at
                                    ];
                                }));
                            }

                            // Sort by transaction date (most recent first)
                            $allTransactions = $allTransactions->sortByDesc('sort_date');
                        @endphp
                        
                        @forelse($allTransactions as $transaction)
                            <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex items-center justify-center w-10 h-10 text-sm font-bold text-white rounded-full bg-gradient-to-r from-teal-500 to-green-600">
                                            {{ strtoupper(substr($transaction->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $transaction->user->name ?? 'Unknown' }}</div>
                                            <div class="text-sm text-gray-500">{{ $transaction->user->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $typeColors = [
                                            'loan_disbursement' => 'blue',
                                            'loan_repayment' => 'green',
                                            'loan_interest' => 'purple',
                                            'entrance_fee' => 'yellow',
                                            'commodity' => 'indigo',
                                            'electronics' => 'pink',
                                            'saving_credit' => 'emerald',
                                            'saving_debit' => 'red',
                                            'share_credit' => 'cyan',
                                            'share_debit' => 'orange'
                                        ];
                                        $color = $typeColors[$transaction->type] ?? 'gray';

                                        $transactionIcons = [
                                            'loan_disbursement' => '<path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                                            'loan_repayment' => '<path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>',
                                            'loan_interest' => '<path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                                            'entrance_fee' => '<path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>',
                                            'commodity' => '<path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
                                            'electronics' => '<path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
                                            'saving_credit' => '<path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>',
                                            'saving_debit' => '<path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>',
                                            'share_credit' => '<path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                                            'share_debit' => '<path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                                            'default' => '<path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.84L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.84l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>'
                                        ];
                                        $iconPath = $transactionIcons[$transaction->type] ?? $transactionIcons['default'];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold bg-{{ $color }}-100 text-{{ $color }}-800 rounded-full">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            {!! $iconPath !!}
                                        </svg>
                                        {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900 whitespace-nowrap">
                                    <span class="text-green-600">₦{{ number_format($transaction->amount, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="max-w-xs truncate" title="{{ $transaction->description }}">
                                        {{ Str::limit($transaction->description, 50, '...') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $transaction->created_at->format('M d, Y H:i') }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="mb-2 text-lg font-medium text-gray-900">No Individual Transactions</h3>
                                        <p class="text-gray-500">This transaction group doesn't contain any individual transactions.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Section -->
            @if(isset($shareTransactions) || isset($savingTransactions) || isset($regularTransactions) || isset($commodityTransactions) || isset($loanPayments))
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                        <div class="text-sm text-gray-700">
                            @php
                                $totalTransactions = 0;
                                $currentPage = 1;
                                $perPage = 10;

                                if(isset($shareTransactions)) {
                                    $totalTransactions += $shareTransactions->total();
                                }
                                if(isset($savingTransactions)) {
                                    $totalTransactions += $savingTransactions->total();
                                }
                                if(isset($regularTransactions)) {
                                    $totalTransactions += $regularTransactions->total();
                                }
                                if(isset($commodityTransactions)) {
                                    $totalTransactions += $commodityTransactions->total();
                                }
                                if(isset($loanPayments)) {
                                    $totalTransactions += $loanPayments->total();
                                }

                                $displayedCount = $allTransactions->count();
                            @endphp

                            <p>
                                Showing <span class="font-medium">{{ $displayedCount }}</span> of
                                <span class="font-medium">{{ $totalTransactions }}</span> transactions
                            </p>
                        </div>

                        <!-- Pagination Links -->
                        <div class="flex space-x-2">
                            @if(isset($shareTransactions) && $shareTransactions->hasPages())
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Share Transactions</h4>
                                    {{ $shareTransactions->appends(request()->query())->links('pagination.custom') }}
                                </div>
                            @endif

                            @if(isset($savingTransactions) && $savingTransactions->hasPages())
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Saving Transactions</h4>
                                    {{ $savingTransactions->appends(request()->query())->links('pagination.custom') }}
                                </div>
                            @endif

                            @if(isset($regularTransactions) && $regularTransactions->hasPages())
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Regular Transactions</h4>
                                    {{ $regularTransactions->appends(request()->query())->links('pagination.custom') }}
                                </div>
                            @endif

                            @if(isset($commodityTransactions) && $commodityTransactions->hasPages())
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Commodity Transactions</h4>
                                    {{ $commodityTransactions->appends(request()->query())->links('pagination.custom') }}
                                </div>
                            @endif

                            @if(isset($loanPayments) && $loanPayments->hasPages())
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Loan Payments</h4>
                                    {{ $loanPayments->appends(request()->query())->links('pagination.custom') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function filterByType(type) {
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            if (row.querySelector('td[colspan]')) return; // Skip empty state row

            const typeCell = row.querySelector('td:nth-child(2) span');
            if (!typeCell) return;

            const transactionType = typeCell.textContent.toLowerCase();

            if (type === 'all') {
                row.style.display = '';
            } else {
                const shouldShow = transactionType.includes(type.toLowerCase());
                row.style.display = shouldShow ? '' : 'none';
            }
        });

        updateEmptyState();
    }

    function searchTransactions(searchTerm) {
        const rows = document.querySelectorAll('tbody tr');
        const term = searchTerm.toLowerCase();

        rows.forEach(row => {
            if (row.querySelector('td[colspan]')) return; // Skip empty state row

            const userCell = row.querySelector('td:first-child');
            const descriptionCell = row.querySelector('td:nth-child(4)');

            if (!userCell || !descriptionCell) return;

            const userName = userCell.textContent.toLowerCase();
            const description = descriptionCell.textContent.toLowerCase();

            const shouldShow = userName.includes(term) || description.includes(term);
            row.style.display = shouldShow ? '' : 'none';
        });

        updateEmptyState();
    }

    function updateEmptyState() {
        const rows = document.querySelectorAll('tbody tr:not([style*="display: none"])');
        const emptyRow = document.querySelector('tbody tr td[colspan]');
        const visibleDataRows = Array.from(rows).filter(row => !row.querySelector('td[colspan]'));

        if (visibleDataRows.length === 0 && !emptyRow) {
            // Create and show empty state
            const tbody = document.querySelector('tbody');
            const emptyStateRow = document.createElement('tr');
            emptyStateRow.innerHTML = `
                <td colspan="5" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center">
                        <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <h3 class="mb-2 text-lg font-medium text-gray-900">No transactions found</h3>
                        <p class="text-gray-500">Try adjusting your search or filter criteria.</p>
                    </div>
                </td>
            `;
            tbody.appendChild(emptyStateRow);
        } else if (visibleDataRows.length > 0 && emptyRow) {
            // Remove empty state if there are visible rows
            emptyRow.parentElement.remove();
        }
    }
</script>
@endpush

@endsection
