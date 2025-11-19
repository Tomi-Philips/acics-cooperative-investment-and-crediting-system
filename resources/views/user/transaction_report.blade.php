@extends('layouts.user')

@section('title', 'Transaction Report')

@section('content')
<div class="mx-auto max-w-7xl">
    <!-- Enhanced Header Section -->
    <div class="p-6 mb-6 border border-gray-200 shadow-lg bg-gradient-to-r from-green-50 to-blue-50 rounded-xl">
        <div class="flex flex-col items-center justify-between lg:flex-row">
            <div class="mb-6 lg:mb-0">
                <h1 class="flex items-center text-2xl font-bold text-gray-800">
                    <div class="p-2 mr-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    Transaction Report
                </h1>
                <p class="mt-2 text-sm text-gray-600">View and manage all your financial transactions</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    {{ $transactions->total() }} transactions
                </span>
            </div>
        </div>
    </div>

    <div class="space-y-6">

        <!-- Filter Section -->
        <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-blue-50">
                <h2 class="flex items-center text-lg font-semibold text-gray-800">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filter Transactions
                </h2>
            </div>
            <div class="p-6">
                <form class="grid grid-cols-1 gap-4 md:grid-cols-4" method="GET"
                    action="{{ route('user.transaction_report') }}">
                    <div>
                        <label for="type" class="block mb-1 text-sm font-medium text-gray-700">Transaction Type</label>
                        <select id="type" name="type"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:border-transparent focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">All Types</option>
                            @foreach ($transactionTypes as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="start_date" class="block mb-1 text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:border-transparent focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label for="end_date" class="block mb-1 text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:border-transparent focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label for="status" class="block mb-1 text-sm font-medium text-gray-700">Status</label>
                        <select id="status" name="status"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:border-transparent focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">All Statuses</option>
                            @foreach ($transactionStatuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end space-x-2 md:col-span-4">
                        <a href="{{ route('user.transaction_report') }}"
                            class="px-4 py-2 text-gray-700 transition-colors rounded-md bg-gradient-to-r from-gray-200 to-gray-300 hover:from-gray-300 hover:to-gray-400">
                            Reset
                        </a>
                        <button type="submit"
                            class="px-4 py-2 text-white transition-colors rounded-md bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Export Options -->
        <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-blue-50">
                <h2 class="flex items-center text-lg font-semibold text-gray-800">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export Options
                </h2>
            </div>
            <div class="p-6">
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('user.transaction_report.pdf', request()->query()) }}"
                        class="inline-flex items-center px-4 py-2 text-white transition-all duration-200 rounded-lg shadow-md bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Export as PDF
                    </a>
                    <a href="{{ route('user.transaction_report.excel', request()->query()) }}"
                        class="inline-flex items-center px-4 py-2 text-white transition-all duration-200 rounded-lg shadow-md bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export as Excel
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid gap-6 mb-6 md:grid-cols-2">
            <!-- Total Deposits Card -->
            <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-green-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
                <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-green-50 to-green-100 group-hover:opacity-100"></div>
                <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-green-100 rounded-full opacity-20"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-4 mr-4 shadow-lg bg-gradient-to-br from-green-400 to-green-500 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Total Deposits</p>
                                <p class="text-2xl font-bold text-gray-800">₦{{ number_format($totalDeposits, 2) }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="inline-flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Withdrawals Card -->
            <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-red-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
                <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-red-50 to-red-100 group-hover:opacity-100"></div>
                <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-red-100 rounded-full opacity-20"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-4 mr-4 shadow-lg bg-gradient-to-br from-red-400 to-red-500 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Total Withdrawals</p>
                                <p class="text-2xl font-bold text-gray-800">₦{{ number_format($totalWithdrawals, 2) }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="inline-flex items-center justify-center w-8 h-8 bg-red-100 rounded-full">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Table -->
        <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-blue-50">
                <h2 class="flex items-center text-lg font-semibold text-gray-800">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Transaction History
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase whitespace-nowrap">
                            Date & Time
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase whitespace-nowrap">
                            Type
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase whitespace-nowrap">
                            Description
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase whitespace-nowrap">
                            Reference
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase whitespace-nowrap">
                            Amount
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase whitespace-nowrap">
                            Charges
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase whitespace-nowrap">
                            Net Amount
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase whitespace-nowrap">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if (count($transactions) > 0)
                        @foreach ($transactions as $transaction)
                            <tr class="transition-colors hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $transaction->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $transaction->created_at->format('H:i:s') }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-5 h-5 mr-2">
                                            @if (in_array($transaction->type, ['deposit', 'loan_disbursement', 'saving_credit', 'share_credit']))
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="{{ $transaction->icon_class }}">
                                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                                                </svg>
                                            @elseif(in_array($transaction->type, ['withdrawal', 'loan_payment', 'saving_debit', 'share_debit', 'commodity_essential', 'commodity_non_essential', 'electronics']))
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="{{ $transaction->icon_class }}">
                                                    <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="{{ $transaction->icon_class }}">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </div>
                                        {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                                    {{ $transaction->description ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                                    {{ $transaction->reference ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">
                                    ₦{{ number_format($transaction->amount, 2) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-red-600 whitespace-nowrap">
                                    -₦{{ number_format($transaction->charges, 2) }}
                                </td>
                                <td class="px-4 py-3 text-sm font-medium whitespace-nowrap {{ $transaction->net_amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->net_amount >= 0 ? '+' : '' }}₦{{ number_format($transaction->net_amount, 2) }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span
                                        class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $transaction->status_badge_class }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-sm text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions found</h3>
                                <p class="mt-1 text-sm text-gray-500">No transactions match your current filter criteria.
                                </p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    @if ($transactions->hasPages())
            <div class="flex items-center justify-between px-4 py-3 mt-4 bg-white border-t border-gray-200 sm:px-6">
                <div class="flex justify-between flex-1 sm:hidden">
                    @if ($transactions->onFirstPage())
                        <span
                            class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 border border-gray-300 rounded-md cursor-not-allowed bg-gray-50">
                            Previous
                        </span>
                    @else
                        <a href="{{ $transactions->previousPageUrl() }}"
                            class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Previous
                        </a>
                    @endif
                    @if ($transactions->hasMorePages())
                        <a href="{{ $transactions->nextPageUrl() }}"
                            class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Next
                        </a>
                    @else
                        <span
                            class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 border border-gray-300 rounded-md cursor-not-allowed bg-gray-50">
                            Next
                        </span>
                    @endif
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium">{{ $transactions->firstItem() ?? 0 }}</span> to
                            <span class="font-medium">{{ $transactions->lastItem() ?? 0 }}</span> of
                            <span class="font-medium">{{ $transactions->total() }}</span> results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            {{-- Previous Page Link --}}
                            @if ($transactions->onFirstPage())
                                <span
                                    class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 border border-gray-300 cursor-not-allowed rounded-l-md bg-gray-50">
                                    <span class="sr-only">Previous</span>
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $transactions->previousPageUrl() }}"
                                    class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">
                                    <span class="sr-only">Previous</span>
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @endif
                            {{-- Pagination Elements --}}
                            @foreach ($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                                @if ($page == $transactions->currentPage())
                                    <span aria-current="page"
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-green-600 border border-green-500 bg-green-50">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                            {{-- Next Page Link --}}
                            @if ($transactions->hasMorePages())
                                <a href="{{ $transactions->nextPageUrl() }}"
                                    class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">
                                    <span class="sr-only">Next</span>
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @else
                                <span
                                    class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 border border-gray-300 cursor-not-allowed rounded-r-md bg-gray-50">
                                    <span class="sr-only">Next</span>
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection