@extends('layouts.admin')

@section('content')
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Transaction Details - {{ $monthlyUpload->formatted_date }}</h1>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}"
                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-green-600">
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <a href="{{ route('admin.bulk_updates') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-green-600 md:ml-2">Bulk Updates</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Transactions</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        {{-- Upload Summary --}}
        <div class="p-6 mb-6 bg-white rounded-lg shadow-md">
            <h2 class="mb-4 text-lg font-semibold text-gray-800">Upload Summary</h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-blue-600">{{ $monthlyUpload->total_records }}</p>
                    <p class="text-sm text-gray-600">Total Records</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $monthlyUpload->processed_records }}</p>
                    <p class="text-sm text-gray-600">Processed</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-red-600">{{ $monthlyUpload->failed_records }}</p>
                    <p class="text-sm text-gray-600">Failed</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-purple-600">{{ $monthlyUpload->success_rate }}%</p>
                    <p class="text-sm text-gray-600">Success Rate</p>
                </div>
            </div>
        </div>

        {{-- Transaction Tabs --}}
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <!-- Mobile-responsive tab navigation with horizontal scroll -->
                <div class="overflow-x-auto scrollbar-hide">
                    <nav class="-mb-px flex space-x-4 min-w-max px-4 sm:px-0" aria-label="Tabs">
                        <button onclick="showTab('entrance')" id="entrance-tab" class="tab-button active border-purple-500 text-purple-600 whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm flex-shrink-0">
                            Entrance ({{ $entranceTransactions->count() }})
                        </button>
                        <button onclick="showTab('shares')" id="shares-tab" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm flex-shrink-0">
                            Shares ({{ $shareTransactions->count() }})
                        </button>
                        <button onclick="showTab('savings')" id="savings-tab" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm flex-shrink-0">
                            Savings ({{ $savingTransactions->count() }})
                        </button>
                        <button onclick="showTab('loans')" id="loans-tab" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm flex-shrink-0">
                            Loan Repayments ({{ $loanPayments->count() }})
                        </button>
                        <button onclick="showTab('commodities')" id="commodities-tab" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm flex-shrink-0">
                            Commodities ({{ $commodityTransactions->count() }})
                        </button>
                        <button onclick="showTab('electronics')" id="electronics-tab" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm flex-shrink-0">
                            Electronics ({{ $electronicsTransactions->count() }})
                        </button>
                        <button onclick="showTab('loaninterest')" id="loaninterest-tab" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm flex-shrink-0">
                            Loan Interest ({{ $loanInterestTransactions->count() }})
                        </button>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Entrance Transactions --}}
        <div id="entrance-content" class="tab-content active">
            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Entrance Fee Payments</h3>
                    @if($entranceTransactions->count() > 0)
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <input type="text" id="entrance-search" placeholder="Search entrance fees..."
                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <button onclick="clearSearch('entrance')" class="px-3 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50">
                                Clear
                            </button>
                        </div>
                    @endif
                </div>

                @if($entranceTransactions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="entrance-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($entranceTransactions as $transaction)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap" data-label="member">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $transaction->user->name ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $transaction->user->member->member_number ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" data-label="amount" data-amount="{{ $transaction->amount }}">
                                            ₦{{ number_format($transaction->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" data-label="reference">
                                            {{ $transaction->reference ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap" data-label="status">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" data-label="date" data-date="{{ $transaction->created_at->format('Y-m-d') }}">
                                            {{ $transaction->created_at->format('M j, Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No entrance fee payments</h3>
                        <p class="mt-1 text-sm text-gray-500">No entrance fee payments were processed for this month.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Share Transactions --}}
        <div id="shares-content" class="tab-content hidden">
            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Shares</h3>
                    @if($shareTransactions->count() > 0)
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <input type="text" id="shares-search" placeholder="Search shares..."
                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <button onclick="clearSearch('shares')" class="px-3 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50">
                                Clear
                            </button>
                        </div>
                    @endif
                </div>
                @if($shareTransactions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="shares-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member No.</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="shares-tbody">
                                @foreach($shareTransactions as $transaction)
                                    <tr class="shares-row">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" data-label="member">
                                            {{ $transaction->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" data-label="member_number">
                                            {{ $transaction->user->member->member_number ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" data-label="amount" data-amount="{{ $transaction->amount }}">
                                            ₦{{ number_format($transaction->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap" data-label="type">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $transaction->type === 'credit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($transaction->type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500" data-label="description">{{ $transaction->description }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" data-label="date" data-date="{{ $transaction->transaction_date->format('Y-m-d') }}">
                                            {{ $transaction->transaction_date->format('M j, Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="shares-no-results" class="hidden text-center py-8">
                        <div class="text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.562M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <p class="text-lg font-medium">No results found</p>
                            <p class="text-sm">Try adjusting your search terms</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500">No shares found for this upload.</p>
                @endif
            </div>
        </div>

        {{-- Saving Transactions --}}
        <div id="savings-content" class="tab-content hidden">
            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Savings</h3>
                    @if($savingTransactions->count() > 0)
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <input type="text" id="savings-search" placeholder="Search savings..."
                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <button onclick="clearSearch('savings')" class="px-3 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50">
                                Clear
                            </button>
                        </div>
                    @endif
                </div>
                @if($savingTransactions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="savings-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member No.</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="savings-tbody">
                                @foreach($savingTransactions as $transaction)
                                    <tr class="savings-row">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" data-label="member">
                                            {{ $transaction->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" data-label="member_number">
                                            {{ $transaction->user->member->member_number ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" data-label="amount" data-amount="{{ $transaction->amount }}">
                                            ₦{{ number_format($transaction->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap" data-label="type">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $transaction->type === 'credit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($transaction->type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500" data-label="description">{{ $transaction->description }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" data-label="date" data-date="{{ $transaction->transaction_date->format('Y-m-d') }}">
                                            {{ $transaction->transaction_date->format('M j, Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="savings-no-results" class="hidden text-center py-8">
                        <div class="text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.562M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <p class="text-lg font-medium">No results found</p>
                            <p class="text-sm">Try adjusting your search terms</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500">No savings found for this upload.</p>
                @endif
            </div>
        </div>

        {{-- Loan Payments --}}
        <div id="loans-content" class="tab-content hidden">
            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Loan Repayments</h3>
                    @if($loanPayments->count() > 0)
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <input type="text" id="loans-search" placeholder="Search loan repayments..."
                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <button onclick="clearSearch('loans')" class="px-3 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50">
                                Clear
                            </button>
                        </div>
                    @endif
                </div>
                @if($loanPayments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="loans-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member No.</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Repayment Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="loans-tbody">
                                @foreach($loanPayments as $payment)
                                    <tr class="loans-row">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" data-label="member">
                                            {{ $payment->loan->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" data-label="member_number">
                                            {{ $payment->loan->user->member->member_number ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" data-label="loan_id" data-loan="{{ $payment->loan->id }}">
                                            #{{ $payment->loan->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" data-label="amount" data-amount="{{ $payment->amount }}">
                                            ₦{{ number_format($payment->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" data-label="repayment_type">
                                            Principal Repayment
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" data-label="date" data-date="{{ $payment->payment_date->format('Y-m-d') }}">
                                            {{ $payment->payment_date->format('M j, Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="loans-no-results" class="hidden text-center py-8">
                        <div class="text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.562M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <p class="text-lg font-medium">No results found</p>
                            <p class="text-sm">Try adjusting your search terms</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500">No loan repayments found for this upload.</p>
                @endif
            </div>
        </div>

        {{-- Commodity Transactions --}}
        <div id="commodities-content" class="tab-content hidden">
            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Commodities</h3>
                    @if($commodityTransactions->count() > 0)
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <input type="text" id="commodities-search" placeholder="Search commodities..."
                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <button onclick="clearSearch('commodities')" class="px-3 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50">
                                Clear
                            </button>
                        </div>
                    @endif
                </div>
                @if($commodityTransactions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="commodities-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member No.</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commodity Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="commodities-tbody">
                                @foreach($commodityTransactions as $transaction)
                                    <tr class="commodities-row">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" data-label="member">
                                            {{ $transaction->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" data-label="member_number">
                                            {{ $transaction->user->member->member_number ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" data-label="commodity_type" data-commodity="{{ $transaction->commodity_type }}">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $transaction->commodity_type === 'essential' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ ucfirst(str_replace('_', ' ', $transaction->commodity_type)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" data-label="amount" data-amount="{{ $transaction->amount }}">
                                            ₦{{ number_format($transaction->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap" data-label="type">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $transaction->type === 'credit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($transaction->type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500" data-label="description">{{ $transaction->description }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" data-label="date" data-date="{{ $transaction->transaction_date->format('Y-m-d') }}">
                                            {{ $transaction->transaction_date->format('M j, Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="commodities-no-results" class="hidden text-center py-8">
                        <div class="text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.562M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <p class="text-lg font-medium">No results found</p>
                            <p class="text-sm">Try adjusting your search terms</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500">No commodities found for this upload.</p>
                @endif
            </div>
        </div>

        {{-- Electronics Transactions --}}
        <div id="electronics-content" class="tab-content hidden">
            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Electronics</h3>
                    @if($electronicsTransactions->count() > 0)
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <input type="text" id="electronics-search" placeholder="Search electronics..."
                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <button onclick="clearSearch('electronics')" class="px-3 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50">
                                Clear
                            </button>
                        </div>
                    @endif
                </div>

                @if($electronicsTransactions->count() > 0)
                    <div class="overflow-x-auto">
                        <div id="electronics-table">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($electronicsTransactions as $transaction)
                                        <tr class="electronics-row hover:bg-gray-50"
                                            data-member-name="{{ $transaction->user->name ?? 'N/A' }}"
                                            data-member-coopno="{{ $transaction->user->member->member_number ?? 'N/A' }}"
                                            data-amount="{{ $transaction->amount }}"
                                            data-type="{{ $transaction->transaction_type }}"
                                            data-method="{{ $transaction->payment_method }}"
                                            data-reference="{{ $transaction->reference_number }}">
                                            <td class="px-6 py-4 whitespace-nowrap" data-label="member">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $transaction->user->name ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $transaction->user->member->member_number ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" data-label="amount" data-amount="{{ $transaction->amount }}">
                                                ₦{{ number_format($transaction->amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" data-label="type">
                                                {{ ucfirst($transaction->transaction_type) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" data-label="payment_method">
                                                {{ $transaction->payment_method ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" data-label="reference">
                                                {{ $transaction->reference_number ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" data-label="date" data-date="{{ $transaction->created_at }}">
                                                {{ $transaction->created_at->format('M d, Y H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div id="electronics-no-results" class="hidden text-center py-8">
                            <p class="text-gray-500">No electronics transactions match your search.</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500">No electronics found for this upload.</p>
                @endif
            </div>
        </div>

        {{-- Loan Interest Transactions --}}
        <div id="loaninterest-content" class="tab-content hidden">
            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Loan Interest Payments</h3>
                    @if($loanInterestTransactions->count() > 0)
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <input type="text" id="loaninterest-search" placeholder="Search loan interest..."
                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <button onclick="clearSearch('loaninterest')" class="px-3 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50">
                                Clear
                            </button>
                        </div>
                    @endif
                </div>

                @if($loanInterestTransactions->count() > 0)
                    <div class="overflow-x-auto">
                        <div id="loaninterest-table">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan Number</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($loanInterestTransactions as $transaction)
                                        <tr class="loaninterest-row hover:bg-gray-50"
                                            data-member-name="{{ $transaction->loan->user->name ?? 'N/A' }}"
                                            data-member-coopno="{{ $transaction->loan->user->member->member_number ?? 'N/A' }}"
                                            data-loan-number="{{ $transaction->loan->loan_number ?? 'N/A' }}"
                                            data-amount="{{ $transaction->amount }}"
                                            data-status="{{ $transaction->status }}">
                                            <td class="px-6 py-4 whitespace-nowrap" data-label="member">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $transaction->loan->user->name ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $transaction->loan->user->member->member_number ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" data-label="loan_number">
                                                {{ $transaction->loan->loan_number ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" data-label="amount" data-amount="{{ $transaction->amount }}">
                                                ₦{{ number_format($transaction->amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" data-label="date" data-date="{{ $transaction->due_date }}">
                                                {{ $transaction->due_date ? \Carbon\Carbon::parse($transaction->due_date)->format('M d, Y') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" data-label="date" data-date="{{ $transaction->payment_date }}">
                                                {{ $transaction->payment_date ? \Carbon\Carbon::parse($transaction->payment_date)->format('M d, Y') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap" data-label="status">
                                                @if($transaction->status == 'pending')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Pending
                                                    </span>
                                                @elseif($transaction->status == 'paid')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Paid
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        {{ ucfirst($transaction->status) }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div id="loaninterest-no-results" class="hidden text-center py-8">
                            <p class="text-gray-500">No loan interest transactions match your search.</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500">No loan interest found for this upload.</p>
                @endif
            </div>
        </div>

        {{-- Back Button --}}
        <div class="mt-6">
            <a href="{{ route('admin.bulk_updates') }}" 
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                ← Back to Bulk Updates
            </a>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
                content.classList.remove('active');
            });

            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-green-500', 'text-green-600', 'border-purple-500', 'text-purple-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected tab content
            const tabContent = document.getElementById(tabName + '-content');
            tabContent.classList.remove('hidden');
            tabContent.classList.add('active');

            // Add active class to selected tab with appropriate color
            const activeTab = document.getElementById(tabName + '-tab');
            activeTab.classList.add('active');
            activeTab.classList.remove('border-transparent', 'text-gray-500');

            // Set tab-specific colors
            if (tabName === 'entrance') {
                activeTab.classList.add('border-purple-500', 'text-purple-600');
            } else {
                activeTab.classList.add('border-green-500', 'text-green-600');
            }
        }

        // Search functionality
        function performSearch(tabName) {
            const searchInput = document.getElementById(tabName + '-search');
            const searchTerm = searchInput.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.' + tabName + '-row');
            const noResultsDiv = document.getElementById(tabName + '-no-results');
            const tableDiv = document.getElementById(tabName + '-table').parentElement;
            let visibleRows = 0;

            rows.forEach(row => {
                let shouldShow = false;

                if (searchTerm === '') {
                    shouldShow = true;
                } else {
                    // Get all data attributes and text content for searching
                    const cells = row.querySelectorAll('td[data-label]');

                    // Build a comprehensive search string for the entire row
                    let rowSearchText = '';

                    cells.forEach(cell => {
                        const label = cell.getAttribute('data-label');
                        let searchableText = '';

                        // Handle different data types
                        switch(label) {
                            case 'member':
                            case 'member_number':
                            case 'description':
                            case 'payment_method':
                            case 'loan_number':
                            case 'status':
                            case 'reference':
                                searchableText = cell.textContent.toLowerCase().trim();
                                break;
                            case 'amount':
                                const amount = cell.getAttribute('data-amount');
                                searchableText = (amount || '') + ' ' + cell.textContent.toLowerCase().trim();
                                break;
                            case 'date':
                                const date = cell.getAttribute('data-date');
                                searchableText = (date || '') + ' ' + cell.textContent.toLowerCase().trim();
                                break;
                            case 'loan_id':
                                const loanId = cell.getAttribute('data-loan');
                                searchableText = (loanId || '') + ' ' + cell.textContent.toLowerCase().trim();
                                break;
                            case 'commodity_type':
                                const commodityType = cell.getAttribute('data-commodity');
                                searchableText = (commodityType || '') + ' ' + cell.textContent.toLowerCase().trim();
                                break;
                            case 'type':
                                searchableText = cell.textContent.toLowerCase().trim();
                                break;
                        }

                        // Add to row search text with space separator
                        if (searchableText) {
                            rowSearchText += ' ' + searchableText;
                        }
                    });

                    // Clean up the row search text
                    rowSearchText = rowSearchText.trim().replace(/\s+/g, ' ');

                    // Check if search term exists in the row
                    shouldShow = rowSearchText.includes(searchTerm);
                }

                if (shouldShow) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Show/hide no results message
            if (visibleRows === 0 && searchTerm !== '') {
                tableDiv.style.display = 'none';
                noResultsDiv.classList.remove('hidden');
            } else {
                tableDiv.style.display = '';
                noResultsDiv.classList.add('hidden');
            }
        }

        function clearSearch(tabName) {
            const searchInput = document.getElementById(tabName + '-search');
            searchInput.value = '';
            performSearch(tabName);
        }

        // Initialize search functionality when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners for search inputs
            const searchInputs = ['entrance', 'shares', 'savings', 'loans', 'commodities', 'electronics', 'loaninterest'];

            searchInputs.forEach(tabName => {
                const searchInput = document.getElementById(tabName + '-search');
                if (searchInput) {
                    // Real-time search as user types
                    searchInput.addEventListener('input', function() {
                        performSearch(tabName);
                    });

                    // Handle Enter key
                    searchInput.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            performSearch(tabName);
                        }
                    });
                }
            });
        });
    </script>

    <style>
        /* Hide scrollbar for mobile tab navigation while keeping functionality */
        .scrollbar-hide {
            -ms-overflow-style: none;  /* Internet Explorer 10+ */
            scrollbar-width: none;  /* Firefox */
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;  /* Safari and Chrome */
        }

        /* Ensure tabs are properly spaced on mobile */
        @media (max-width: 640px) {
            .tab-button {
                min-width: 80px;
                text-align: center;
            }
        }

        /* Add smooth scrolling for better UX */
        .overflow-x-auto {
            scroll-behavior: smooth;
        }

        /* Ensure tab content is responsive */
        .tab-content .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
        }

        /* Mobile table improvements */
        @media (max-width: 768px) {
            .tab-content table {
                font-size: 0.875rem;
            }

            .tab-content th,
            .tab-content td {
                padding: 0.5rem 0.75rem;
            }
        }
    </style>
@endsection
