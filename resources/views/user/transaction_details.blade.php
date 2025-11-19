@extends('layouts.user')

@section('title', 'Transaction Details')

@section('content')
    <div class="container px-4 py-6 mx-auto space-y-6">
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('user.dashboard') }}" class="hover:text-green-600">Dashboard</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('user.transaction_report') }}" class="hover:text-green-600">Transaction Report</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-gray-700">Transaction #{{ $transaction->id }}</span>
        </div>

        <div class="overflow-hidden bg-white shadow-sm rounded-xl">
            <div class="p-5 border-b border-gray-100">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Transaction Details</h2>
                        <div class="flex items-center gap-3 mt-1 text-sm text-gray-500">
                            <span>{{ $transaction->reference }}</span>
                            <span>•</span>
                            <span>{{ $transaction->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>
                    <div>
                        @if($transaction->status === 'completed')
                            <span class="px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full">Completed</span>
                        @elseif($transaction->status === 'pending')
                            <span class="px-3 py-1 text-sm font-medium text-yellow-800 bg-yellow-100 rounded-full">Pending</span>
                        @elseif($transaction->status === 'failed')
                            <span class="px-3 py-1 text-sm font-medium text-red-800 bg-red-100 rounded-full">Failed</span>
                        @else
                            <span class="px-3 py-1 text-sm font-medium text-gray-800 bg-gray-100 rounded-full">{{ ucfirst($transaction->status) }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <h3 class="mb-3 text-sm font-medium text-gray-500">Transaction Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Transaction ID:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $transaction->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Reference:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $transaction->reference }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Type:</span>
                                <span class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $transaction->type)) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Amount:</span>
                                <span class="text-sm font-medium text-gray-900">₦{{ number_format($transaction->amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Status:</span>
                                <span class="text-sm font-medium @if($transaction->status === 'completed') text-green-600 @elseif($transaction->status === 'pending') text-yellow-600 @elseif($transaction->status === 'failed') text-red-600 @else text-gray-900 @endif">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="mb-3 text-sm font-medium text-gray-500">Additional Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Date:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $transaction->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Updated:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $transaction->updated_at->format('M d, Y h:i A') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Description:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $transaction->description }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="mb-3 text-sm font-medium text-gray-500">Transaction Details</h3>
                    <div class="p-4 rounded-lg bg-gray-50">
                        <p class="text-sm text-gray-700">{{ $transaction->description }}</p>
                    </div>
                </div>

                <div class="flex justify-between mt-8">
                    <a href="{{ route('user.transaction_report') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Transactions
                    </a>
                    <a href="{{ route('user.transaction_report.pdf', ['id' => $transaction->id]) }}" class="inline-flex items-center text-sm text-green-600 hover:text-green-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download Receipt
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection