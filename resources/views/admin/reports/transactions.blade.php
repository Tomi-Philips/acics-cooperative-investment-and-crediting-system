@extends('layouts.admin')

@section('content')
    <div class="container px-4 py-6 mx-auto">
        <div class="flex flex-col items-start justify-between mb-6 md:flex-row md:items-center">
            <h1 class="mb-4 text-2xl font-bold text-gray-900 md:mb-0">Transaction Report</h1>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.reports') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Reports
                </a>
                <a href="{{ route('admin.reports.export', ['type' => 'transactions']) }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export CSV
                </a>
            </div>
        </div>

        <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
            <form action="{{ route('admin.reports.transactions') }}" method="GET" class="flex flex-col gap-4 md:flex-row">
                <div>
                    <label for="type" class="block mb-1 text-sm font-medium text-gray-700">Transaction Type</label>
                    <select id="type" name="type"
                        class="block w-full border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 sm:text-sm">
                        <option value="">All Types</option>
                        <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>Deposit</option>
                        <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
                        <option value="loan_payment" {{ request('type') == 'loan_payment' ? 'selected' : '' }}>Loan Payment
                        </option>
                        <option value="loan_disbursement" {{ request('type') == 'loan_disbursement' ? 'selected' : '' }}>Loan
                            Disbursement</option>
                        <option value="share_purchase" {{ request('type') == 'share_purchase' ? 'selected' : '' }}>Share
                            Purchase</option>
                        <option value="commodity_purchase" {{ request('type') == 'commodity_purchase' ? 'selected' : '' }}>
                            Commodity Purchase</option>
                    </select>
                </div>
                <div>
                    <label for="start_date" class="block mb-1 text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
                        class="block w-full border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 sm:text-sm">
                </div>
                <div>
                    <label for="end_date" class="block mb-1 text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
                        class="block w-full border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 sm:text-sm">
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-hidden bg-white rounded-lg shadow-md">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ID</th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">User</th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Type</th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Amount</th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Reference
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $transaction->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex items-center justify-center flex-shrink-0 w-10 h-10 bg-gray-100 rounded-full">
                                            <span class="text-sm font-medium text-gray-800">{{ strtoupper(substr($transaction->user->name ?? 'U', 0, 1)) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $transaction->user->name ?? 'Unknown User' }}</div>
                                            <div class="text-xs text-gray-500">{{ $transaction->user->email ?? 'No email' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if ($transaction->type == 'deposit') bg-green-100 text-green-800 @elseif($transaction->type == 'withdrawal') bg-red-100 text-red-800 @elseif($transaction->type == 'loan_payment') bg-blue-100 text-blue-800 @elseif($transaction->type == 'loan_disbursement') bg-purple-100 text-purple-800 @elseif($transaction->type == 'share_purchase') bg-yellow-100 text-yellow-800 @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucwords(str_replace('_', ' ', $transaction->type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">₦{{ number_format($transaction->amount) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $transaction->reference }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    {{ $transaction->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No transactions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
@endsection