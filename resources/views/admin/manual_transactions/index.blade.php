@extends('layouts.admin')

@section('title', 'Manual Transaction Management')

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
                    Manual Transaction Management
                </h1>
                <p class="mt-2 text-sm text-gray-600">Process in-person member transactions and manage financial operations</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.manual_transactions.create') }}"
                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white transition-all duration-200 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 border border-transparent rounded-lg shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Individual Transaction
                </a>
                {{-- <a href="{{ route('admin.manual_transactions.bulk') }}"
                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white transition-all duration-200 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 border border-transparent rounded-lg shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                    Bulk Upload
                </a> --}}
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="px-4 py-3 mb-6 text-green-700 bg-green-100 border border-green-400 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="px-4 py-3 mb-6 text-red-700 bg-red-100 border border-red-400 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Transaction Type Cards -->
    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
        <!-- Entrance Fee -->
        <div class="p-6 bg-white border-l-4 border-purple-500 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 bg-purple-100 rounded-full">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Entrance Fee</h3>
                    <p class="text-sm text-gray-600">One-time membership payment</p>
                    <p class="text-xs font-medium text-purple-600">Addition Only</p>
                </div>
            </div>
        </div>

        <!-- Shares -->
        <div class="p-6 bg-white border-l-4 border-green-500 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Shares</h3>
                    <p class="text-sm text-gray-600">Max: ₦10,000 per member</p>
                    <p class="text-xs font-medium text-green-600">Addition Only</p>
                </div>
            </div>
        </div>

        <!-- Savings -->
        <div class="p-6 bg-white border-l-4 border-blue-500 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Savings</h3>
                    <p class="text-sm text-gray-600">Deposits & withdrawals</p>
                    <p class="text-xs font-medium text-blue-600">Addition + Subtraction</p>
                </div>
            </div>
        </div>

        <!-- Loans -->
        <div class="p-6 bg-white border-l-4 border-yellow-500 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 bg-yellow-100 rounded-full">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Loans</h3>
                    <p class="text-sm text-gray-600">Disbursement & repayment</p>
                    <p class="text-xs font-medium text-yellow-600">Addition + Subtraction</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions Card -->
    <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
        <!-- Card Header -->
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="p-2 mr-4 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Recent Manual Transactions</h2>
                        <p class="mt-1 text-sm text-gray-600">Search, filter and manage manual transactions</p>
                    </div>
                </div>
                <div class="text-sm text-gray-500">
                    {{ $recentTransactions->total() }} transactions
                </div>
            </div>
        </div>

        <!-- Search and Filter Form -->
        <div class="px-8 py-4 border-b border-gray-200 bg-gray-50">
            <form method="GET" action="{{ route('admin.manual_transactions.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6">
                <!-- Search -->
                <div>
                    <label for="search" class="block mb-1 text-xs font-medium text-gray-700">Search</label>
                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Name, member #, description..."
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>

                <!-- Type Filter -->
                <div>
                    <label for="type" class="block mb-1 text-xs font-medium text-gray-700">Type</label>
                    <select id="type" name="type" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        <option value="">All Types</option>
                        <option value="entrance" {{ request('type') === 'entrance' ? 'selected' : '' }}>Entrance</option>
                        <option value="shares" {{ request('type') === 'shares' ? 'selected' : '' }}>Shares</option>
                        <option value="savings" {{ request('type') === 'savings' ? 'selected' : '' }}>Savings</option>
                        <option value="loan" {{ request('type') === 'loan' ? 'selected' : '' }}>Loan</option>
                        <option value="commodity" {{ request('type') === 'commodity' ? 'selected' : '' }}>Commodity</option>
                        <option value="electronics" {{ request('type') === 'electronics' ? 'selected' : '' }}>Electronics</option>
                    </select>
                </div>

                <!-- Operation Filter -->
                <div>
                    <label for="operation" class="block mb-1 text-xs font-medium text-gray-700">Operation</label>
                    <select id="operation" name="operation" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        <option value="">All Operations</option>
                        <option value="Addition" {{ request('operation') === 'Addition' ? 'selected' : '' }}>Addition</option>
                        <option value="Subtraction" {{ request('operation') === 'Subtraction' ? 'selected' : '' }}>Subtraction</option>
                        <option value="Interest Payment" {{ request('operation') === 'Interest Payment' ? 'selected' : '' }}>Interest Payment</option>
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label for="date_from" class="block mb-1 text-xs font-medium text-gray-700">Date From</label>
                    <input type="date"
                           id="date_from"
                           name="date_from"
                           value="{{ request('date_from') }}"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>

                <!-- Date To -->
                <div>
                    <label for="date_to" class="block mb-1 text-xs font-medium text-gray-700">Date To</label>
                    <input type="date"
                           id="date_to"
                           name="date_to"
                           value="{{ request('date_to') }}"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>

                <!-- Amount Range -->
                <div class="flex space-x-2">
                    <div class="flex-1">
                        <label for="amount_min" class="block mb-1 text-xs font-medium text-gray-700">Min Amount</label>
                        <input type="number"
                               id="amount_min"
                               name="amount_min"
                               value="{{ request('amount_min') }}"
                               step="0.01"
                               min="0"
                               placeholder="0.00"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div class="flex-1">
                        <label for="amount_max" class="block mb-1 text-xs font-medium text-gray-700">Max Amount</label>
                        <input type="number"
                               id="amount_max"
                               name="amount_max"
                               value="{{ request('amount_max') }}"
                               step="0.01"
                               min="0"
                               placeholder="0.00"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-end space-x-2 xl:col-span-6 lg:col-span-4 md:col-span-2">
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search
                    </button>
                    <a href="{{ route('admin.manual_transactions.index') }}" class="px-4 py-2 text-sm font-medium text-white bg-gray-600 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Content -->
        <div class="p-8">
            @if($recentTransactions->count() > 0)
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden border-b border-gray-200 shadow-sm sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Member</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Type</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Operation</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Amount</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Description</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentTransactions as $transaction)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $transaction->user_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $transaction->member_number }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($transaction->type === 'entrance') bg-purple-100 text-purple-800
                                                    @elseif($transaction->type === 'shares') bg-green-100 text-green-800
                                                    @elseif($transaction->type === 'savings') bg-blue-100 text-blue-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($transaction->type) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($transaction->operation === 'Addition') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ $transaction->operation }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                                ₦{{ number_format($transaction->amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ Str::limit($transaction->description, 50) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                {{ $transaction->created_at->format('M j, Y g:i A') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="py-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No manual transactions</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by processing your first manual transaction.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.manual_transactions.create') }}"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-all duration-200 border border-transparent rounded-md shadow-sm bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 hover:shadow-md">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Process Transaction
                        </a>
                    </div>
                </div>
            @endif

            <!-- Pagination -->
            @if($recentTransactions->hasPages())
                <div class="px-8 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing {{ $recentTransactions->firstItem() }} to {{ $recentTransactions->lastItem() }} of {{ $recentTransactions->total() }} results
                        </div>
                        <div class="flex space-x-1">
                            {{ $recentTransactions->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
