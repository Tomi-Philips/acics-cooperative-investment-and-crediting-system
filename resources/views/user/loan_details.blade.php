@extends('layouts.user')

@section('content')
<div class="mx-auto max-w-7xl">
    <!-- Enhanced Header Section -->
    <div class="p-6 mb-6 border border-gray-200 shadow-lg bg-gradient-to-r from-green-50 to-blue-50 rounded-xl">
        <div class="flex flex-col items-center justify-between lg:flex-row">
            <div class="mb-6 lg:mb-0">
                <h1 class="flex items-center text-2xl font-bold text-gray-800">
                    <div class="p-2 mr-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    Loan Details
                </h1>
                <p class="mt-2 text-sm text-gray-600">Loan #{{ $loan->loan_number }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('user.loan_board') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-200 bg-gray-100 rounded-lg shadow-sm hover:bg-gray-200 hover:shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Loan Board
                </a>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Notification Banner -->
        @if(session('success'))
        <div class="px-4 py-3 text-white bg-green-600 rounded-lg shadow-sm">
            <p class="text-sm font-medium"> {{ session('success') }} </p>
        </div>
        @elseif(session('error'))
        <div class="px-4 py-3 text-white bg-red-600 rounded-lg shadow-sm">
            <p class="text-sm font-medium"> {{ session('error') }} </p>
        </div>
        @endif

        <!-- Loan Summary Card -->
        <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-blue-50">
                <h2 class="flex items-center text-lg font-semibold text-gray-800">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Loan Summary
                </h2>
            </div>
            <div class="p-6">
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Loan Amount Card -->
                    <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-blue-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
                        <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-blue-50 to-blue-100 group-hover:opacity-100"></div>
                        <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-blue-100 rounded-full opacity-20"></div>
                        <div class="relative p-6">
                            <div class="flex items-center">
                                <div class="p-4 mr-4 shadow-lg bg-gradient-to-br from-blue-400 to-blue-500 rounded-xl">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Loan Amount</p>
                                    <p class="text-2xl font-bold text-gray-800">₦{{ number_format($loan->amount, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Interest Rate Card -->
                    <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-purple-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
                        <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-purple-50 to-purple-100 group-hover:opacity-100"></div>
                        <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-purple-100 rounded-full opacity-20"></div>
                        <div class="relative p-6">
                            <div class="flex items-center">
                                <div class="p-4 mr-4 shadow-lg bg-gradient-to-br from-purple-400 to-purple-500 rounded-xl">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Interest Rate</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $loan->interest_rate * 100 }}%</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Payment Card -->
                    <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-green-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
                        <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-green-50 to-green-100 group-hover:opacity-100"></div>
                        <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-green-100 rounded-full opacity-20"></div>
                        <div class="relative p-6">
                            <div class="flex items-center">
                                <div class="p-4 mr-4 shadow-lg bg-gradient-to-br from-green-400 to-green-500 rounded-xl">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Total Payment</p>
                                    <p class="text-2xl font-bold text-gray-800">₦{{ number_format($loan->total_payment, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Payment Card -->
                    <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-orange-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
                        <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-orange-50 to-orange-100 group-hover:opacity-100"></div>
                        <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-orange-100 rounded-full opacity-20"></div>
                        <div class="relative p-6">
                            <div class="flex items-center">
                                <div class="p-4 mr-4 shadow-lg bg-gradient-to-br from-orange-400 to-orange-500 rounded-xl">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Monthly Payment</p>
                                    <p class="text-2xl font-bold text-gray-800">₦{{ number_format($loan->monthly_payment, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Term Card -->
                    <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-indigo-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
                        <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-indigo-50 to-indigo-100 group-hover:opacity-100"></div>
                        <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-indigo-100 rounded-full opacity-20"></div>
                        <div class="relative p-6">
                            <div class="flex items-center">
                                <div class="p-4 mr-4 shadow-lg bg-gradient-to-br from-indigo-400 to-indigo-500 rounded-xl">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Term</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $loan->term_months }} {{ Str::plural('Month', $loan->term_months) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Card -->
                    <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-gray-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
                        <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-gray-50 to-gray-100 group-hover:opacity-100"></div>
                        <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-gray-100 rounded-full opacity-20"></div>
                        <div class="relative p-6">
                            <div class="flex items-center">
                                <div class="p-4 mr-4 shadow-lg bg-gradient-to-br from-gray-400 to-gray-500 rounded-xl">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Status</p>
                                    <p class="text-2xl font-bold">
                                        @if($loan->status === 'active')
                                        <span class="text-green-600">Active</span>
                                        @elseif($loan->status === 'pending')
                                        <span class="text-yellow-600">Pending</span>
                                        @elseif($loan->status === 'approved')
                                        <span class="text-blue-600">Approved</span>
                                        @elseif($loan->status === 'rejected')
                                        <span class="text-red-600">Rejected</span>
                                        @elseif($loan->status === 'completed')
                                        <span class="text-gray-600">Completed</span>
                                        @else
                                        <span class="text-gray-600">{{ ucfirst($loan->status) }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 mt-6 md:grid-cols-2">
                    <!-- Loan Details -->
                    <div class="p-6 rounded-lg bg-gray-50">
                        <h3 class="mb-4 text-lg font-semibold text-gray-800">Loan Details</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Purpose:</span>
                                <span class="text-sm font-medium text-gray-700">{{ $loan->purpose }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Application Date:</span>
                                <span class="text-sm font-medium text-gray-700">{{ $loan->submitted_at ? $loan->submitted_at->format('M d, Y') : $loan->created_at->format('M d, Y') }}</span>
                            </div>
                            @if($loan->status !== 'pending')
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Approval Date:</span>
                                <span class="text-sm font-medium text-gray-700">{{ $loan->approved_at ? $loan->approved_at->format('M d, Y') : ($loan->status === 'approved' || $loan->status === 'active' ? $loan->updated_at->format('M d, Y') : 'N/A') }}</span>
                            </div>
                            @endif
                            @if($loan->status === 'active' || $loan->status === 'completed')
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Disbursement Date:</span>
                                <span class="text-sm font-medium text-gray-700">{{ $loan->disbursed_at ? $loan->disbursed_at->format('M d, Y') : $loan->updated_at->format('M d, Y') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="p-6 rounded-lg bg-gray-50">
                        <h3 class="mb-4 text-lg font-semibold text-gray-800">Payment Summary</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Total Paid:</span>
                                <span class="text-sm font-medium text-green-600">₦{{ number_format($loan->total_payment - $loan->remaining_balance, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Remaining Balance:</span>
                                <span class="text-sm font-medium text-red-600">₦{{ number_format($loan->remaining_balance, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Payments Made:</span>
                                <span class="text-sm font-medium text-gray-700">{{ $payments->where('status', 'paid')->count() }} of {{ $payments->count() }}</span>
                            </div>
                            @if($loan->status === 'active')
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Next Payment:</span>
                                <span class="text-sm font-medium text-blue-600">
                                    @php $nextPayment = $payments->where('status', 'pending')->first(); @endphp
                                    @if($nextPayment)
                                    {{ $nextPayment->due_date->format('M d, Y') }} (₦{{ number_format($nextPayment->amount, 2) }})
                                    @else
                                    No payments due
                                    @endif
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Schedule Card -->
        <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-blue-50">
                <h2 class="flex items-center text-lg font-semibold text-gray-800">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Payment Schedule
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Payment #</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Due Date</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Amount</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Payment Date</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(count($payments) > 0)
                        @foreach($payments as $index => $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap"> {{ $index + 1 }} </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap"> {{ $payment->due_date->format('M d, Y') }} </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap"> ₦{{ number_format($payment->amount, 2) }} </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payment->status === 'pending')
                                @if($payment->due_date->isPast())
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Overdue</span>
                                @elseif($payment->due_date->isToday())
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Due Today</span>
                                @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Upcoming</span>
                                @endif
                                @elseif($payment->status === 'paid')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                                @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($payment->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap"> {{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : '-' }} </td>
                            <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                @if($payment->status === 'pending' && $loan->status === 'active')
                                <button class="text-green-600 hover:text-green-900">Pay Now</button>
                                @elseif($payment->status === 'paid')
                                <a href="#" class="text-blue-600 hover:text-blue-900">Receipt</a>
                                @else
                                -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <h4 class="mt-2 text-sm font-medium text-gray-900">No payment schedule</h4>
                                <p class="mt-1 text-sm text-gray-500">This loan does not have a payment schedule yet.</p>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection