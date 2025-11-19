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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    Reports Dashboard
                </h1>
                <p class="mt-2 text-sm text-gray-600">Comprehensive analytics and insights for your cooperative</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.reports.users') }}"
                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white transition-all duration-200 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 border border-transparent rounded-lg shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    User Report
                </a>
                <a href="{{ route('admin.reports.loans') }}"
                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white transition-all duration-200 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 border border-transparent rounded-lg shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Loan Report
                </a>
                <a href="{{ route('admin.reports.transactions') }}"
                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white transition-all duration-200 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 border border-transparent rounded-lg shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Transaction Report
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
        <div class="p-6 bg-white border-l-4 border-blue-500 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 mr-4 bg-blue-100 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Members</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_users']) }}</p>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white border-l-4 border-purple-500 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 mr-4 bg-purple-100 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Loans</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_loans']) }}</p>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white border-l-4 border-yellow-500 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 mr-4 bg-yellow-100 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Loan Amount</p>
                    <p class="text-2xl font-bold text-gray-800">₦{{ number_format($stats['total_loan_amount']) }}</p>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white border-l-4 border-green-500 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 mr-4 bg-green-100 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Transactions</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_transactions']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-2">
        <!-- Recent Transactions Card -->
        <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
            <!-- Card Header -->
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                <div class="flex items-center">
                    <div class="p-2 mr-4 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Recent Transactions</h2>
                        <p class="mt-1 text-sm text-gray-600">Latest financial activities</p>
                    </div>
                </div>
            </div>

            <!-- Table Content -->
            <div class="p-8">
                <div class="divide-y divide-gray-200">
                    @forelse($recentTransactions as $transaction)
                        <div class="flex items-center justify-between py-4">
                            <div class="flex items-center">
                                <div
                                    class="flex items-center justify-center flex-shrink-0 w-10 h-10 bg-gray-100 rounded-full">
                                    <span class="text-sm font-medium text-gray-800">{{ strtoupper(substr($transaction->user->name ?? 'U', 0, 1)) }}</span>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $transaction->user->name ?? 'Unknown User' }}</p>
                                    <p class="text-sm text-gray-500">{{ ucwords(str_replace('_', ' ', $transaction->type)) }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">₦{{ number_format($transaction->amount) }}</p>
                                <p class="text-xs text-gray-500">{{ $transaction->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-4 text-center text-gray-500">
                            No recent transactions found.
                        </div>
                    @endforelse
                </div>
                <div class="pt-4 border-t border-gray-200 bg-gray-50">
                    <a href="{{ route('admin.reports.transactions') }}"
                        class="text-sm font-medium text-green-600 hover:text-green-500">View all transactions →</a>
                </div>
            </div>
        </div>

        <!-- Distribution Overview Card -->
        <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
            <!-- Card Header -->
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                <div class="flex items-center">
                    <div class="p-2 mr-4 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Distribution Overview</h2>
                        <p class="mt-1 text-sm text-gray-600">Analytics and breakdowns</p>
                    </div>
                </div>
            </div>

            <!-- Charts Content -->
            <div class="p-8">
                <div class="mb-6">
                    <h3 class="mb-2 text-sm font-medium text-gray-700">Loans by Status</h3>
                    <div class="space-y-2">
                        @foreach($loansByStatus as $status => $count)
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-medium text-gray-700">{{ ucfirst($status) }}</span>
                                    <span class="text-xs font-medium text-gray-700">{{ $count }}</span>
                                </div>
                                <div class="w-full h-2 bg-gray-200 rounded-full">
                                    <div class="h-2 rounded-full @if($status == 'approved') bg-green-500 @elseif($status == 'pending') bg-yellow-500 @elseif($status == 'rejected') bg-red-500 @else bg-blue-500 @endif"
                                        style="width: {{ ($count / max(1, array_sum($loansByStatus))) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="mb-2 text-sm font-medium text-gray-700">Transactions by Type</h3>
                    <div class="space-y-2">
                        @foreach($transactionsByType as $type => $count)
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-medium text-gray-700">{{ ucwords(str_replace('_', ' ', $type)) }}</span>
                                    <span class="text-xs font-medium text-gray-700">{{ $count }}</span>
                                </div>
                                <div class="w-full h-2 bg-gray-200 rounded-full">
                                    <div class="h-2 rounded-full @if($type == 'deposit') bg-green-500 @elseif($type == 'withdrawal') bg-red-500 @elseif($type == 'loan_payment') bg-blue-500 @elseif($type == 'loan_disbursement') bg-purple-500 @elseif($type == 'share_purchase') bg-yellow-500 @else bg-gray-500 @endif"
                                        style="width: {{ ($count / max(1, array_sum($transactionsByType))) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection