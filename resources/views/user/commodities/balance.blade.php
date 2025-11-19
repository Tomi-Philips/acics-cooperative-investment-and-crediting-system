@extends('layouts.user')

@section('content')
<div class="mx-auto max-w-7xl">
    <div class="flex flex-col items-start justify-between gap-4 mb-8 md:flex-row md:items-center">
        <div>
            <h1 class="flex items-center gap-2 text-2xl font-bold text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="text-amber-600 h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                My Commodity Balance
            </h1>
            <p class="mt-1 text-sm text-gray-500">View your commodity balance and transaction history</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('user.commodity.marketplace') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-colors duration-150 bg-amber-600 border border-transparent rounded-md shadow-sm hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                Browse Marketplace
            </a>
        </div>
    </div>

    <!-- Balance Summary -->
    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">
        <div class="p-6 bg-white shadow-sm rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Commodity Balance</p>
                    <p class="mt-2 text-3xl font-bold {{ $totalCommodityBalance > 0 ? 'text-red-600' : 'text-amber-600' }}">₦{{ number_format($totalCommodityBalance, 2) }}</p>
                </div>
                <div class="p-3 rounded-full bg-amber-100">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white shadow-sm rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Commodity Types</p>
                    <p class="mt-2 text-3xl font-bold text-blue-600">{{ $userCommodities->count() }}</p>
                </div>
                <div class="p-3 rounded-full bg-blue-100">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white shadow-sm rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Recent Transactions</p>
                    <p class="mt-2 text-3xl font-bold text-green-600">{{ $commodityTransactions->count() }}</p>
                </div>
                <div class="p-3 rounded-full bg-green-100">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <!-- Commodity Balances by Type -->
        <div class="p-6 bg-white shadow-sm rounded-xl">
            <h2 class="mb-6 text-lg font-semibold text-gray-800">Commodity Balances by Type</h2>
            
            @if($userCommodities->count() > 0)
                <div class="space-y-4">
                    @foreach($userCommodities as $commodity)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 rounded-lg {{ $commodity->commodity_name === 'essential' ? 'bg-green-100' : 'bg-blue-100' }}">
                                    <svg class="w-5 h-5 {{ $commodity->commodity_name === 'essential' ? 'text-green-600' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $commodity->commodity_name)) }}</p>
                                    <p class="text-xs text-gray-500">Last updated: {{ $commodity->updated_at->format('M j, Y') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-semibold {{ $commodity->balance > 0 ? 'text-red-600' : 'text-gray-900' }}">₦{{ number_format($commodity->balance, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <p class="mt-4 text-sm text-gray-500">No commodity balance found</p>
                    <p class="text-xs text-gray-400">Your commodity balance will appear here after MAB uploads</p>
                </div>
            @endif
        </div>

        <!-- Recent Transactions -->
        <div class="p-6 bg-white shadow-sm rounded-xl">
            <h2 class="mb-6 text-lg font-semibold text-gray-800">Recent Commodity Transactions</h2>
            
            @if($commodityTransactions->count() > 0)
                <div class="space-y-4">
                    @foreach($commodityTransactions as $transaction)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 rounded-lg {{ $transaction->type === 'credit' ? 'bg-green-100' : 'bg-red-100' }}">
                                    <svg class="w-5 h-5 {{ $transaction->type === 'credit' ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($transaction->type === 'credit')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6" />
                                        @endif
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $transaction->commodity_type)) }}</p>
                                    <p class="text-xs text-gray-500">{{ $transaction->transaction_date->format('M j, Y H:i') }}</p>
                                    @if($transaction->description)
                                        <p class="text-xs text-gray-400">{{ $transaction->description }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold {{ $transaction->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->type === 'credit' ? '+' : '-' }}₦{{ number_format($transaction->amount, 2) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-6 text-center">
                    <a href="{{ route('user.transaction_report') }}" class="text-sm text-amber-600 hover:text-amber-800">
                        View all transactions →
                    </a>
                </div>
            @else
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4" />
                    </svg>
                    <p class="mt-4 text-sm text-gray-500">No transactions found</p>
                    <p class="text-xs text-gray-400">Your commodity transactions will appear here</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
