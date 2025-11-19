@extends('layouts.admin')

@section('content')
<div class="min-h-screen py-8 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col space-y-4 lg:flex-row lg:items-center lg:justify-between lg:space-y-0">
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <div class="flex-shrink-0 p-2 shadow-lg sm:p-3 bg-gradient-to-r from-green-500 to-green-600 rounded-xl">
                        <svg class="w-6 h-6 text-white sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h1 class="text-xl font-bold text-gray-900 truncate sm:text-2xl lg:text-3xl">Loan Details: {{ $loan->loan_number }}</h1>
                        <p class="mt-1 text-sm text-gray-600 sm:text-base lg:text-lg">Complete loan information and repayment schedule</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-2 lg:space-x-3">
                    @if($loan->status === 'pending')
                        <button onclick="openApprovalModal()" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white transition-colors duration-150 bg-green-600 rounded-lg shadow-sm sm:px-4 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-1 sm:w-5 sm:h-5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="hidden sm:inline">Approve Loan</span>
                            <span class="sm:hidden">Approve</span>
                        </button>
                        <button onclick="openRejectModal()" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white transition-colors duration-150 bg-red-600 rounded-lg shadow-sm sm:px-4 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-4 h-4 mr-1 sm:w-5 sm:h-5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span class="hidden sm:inline">Reject Loan</span>
                            <span class="sm:hidden">Reject</span>
                        </button>
                    @endif
                    <a href="{{ route('admin.loans.index') }}" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-lg shadow-sm sm:px-4 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <svg class="w-4 h-4 mr-1 sm:w-5 sm:h-5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span class="hidden sm:inline">Back to Loans</span>
                        <span class="sm:hidden">Back</span>
                    </a>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="mt-4">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                    @if($loan->status == 'pending') bg-yellow-100 text-yellow-800
                    @elseif($loan->status == 'approved') bg-green-100 text-green-800
                    @elseif($loan->status == 'rejected') bg-red-100 text-red-800
                    @elseif($loan->status == 'active') bg-blue-100 text-blue-800
                    @elseif($loan->status == 'paid') bg-purple-100 text-purple-800
                    @elseif($loan->status == 'defaulted') bg-gray-100 text-gray-800
                    @endif">
                    <div class="w-2 h-2 rounded-full mr-2
                        @if($loan->status == 'pending') bg-yellow-400
                        @elseif($loan->status == 'approved') bg-green-400
                        @elseif($loan->status == 'rejected') bg-red-400
                        @elseif($loan->status == 'active') bg-blue-400
                        @elseif($loan->status == 'paid') bg-purple-400
                        @elseif($loan->status == 'defaulted') bg-gray-400
                        @endif"></div>
                    {{ ucfirst($loan->status) }}
                </span>
            </div>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="p-4 mb-6 text-green-700 bg-green-100 border border-green-200 rounded-lg" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div class="p-4 mb-6 text-red-700 bg-red-100 border border-red-200 rounded-lg" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 md:gap-8 md:grid-cols-2 xl:grid-cols-3">
            <!-- Main Content -->
            <div class="space-y-6 md:col-span-2 xl:col-span-2">
                <!-- Loan Information Card -->
                <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
                    <div class="px-4 py-3 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100 sm:px-6 sm:py-4">
                        <div class="flex items-center">
                            <div class="p-2 mr-3 bg-blue-100 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">Loan Information</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-1">
                                <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Member</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $loan->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $loan->user->department->title ?? 'No Department' }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Loan Number</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $loan->loan_number }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Date Submitted</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $loan->submitted_at->format('M d, Y') }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Loan Amount</label>
                                <p class="text-2xl font-bold text-green-600">₦{{ number_format($loan->amount, 2) }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Interest Rate</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $loan->interest_rate }}%</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Term</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $loan->term_months }} months</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Monthly Payment</label>
                                <p class="text-lg font-semibold text-gray-900">₦{{ number_format($loan->monthly_payment, 2) }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Total Payment</label>
                                <p class="text-lg font-semibold text-gray-900">₦{{ number_format($loan->total_payment, 2) }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Total Interest</label>
                                <p class="text-lg font-semibold text-gray-900">₦{{ number_format($loan->total_payment - $loan->amount, 2) }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Repayment Method</label>
                                <p class="text-lg font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $loan->repayment_method)) }}</p>
                            </div>
                        </div>

                        @if($loan->purpose)
                            <div class="pt-6 mt-6 border-t border-gray-200">
                                <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Purpose</label>
                                <p class="mt-2 leading-relaxed text-gray-700">{{ $loan->purpose }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Loan Status Card -->
                <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
                    <div class="px-4 py-3 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100 sm:px-6 sm:py-4">
                        <div class="flex items-center">
                            <div class="p-2 mr-3 bg-green-100 rounded-lg">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">Loan Status</h2>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            @if($loan->status === 'approved' || $loan->status === 'active' || $loan->status === 'paid')
                                <div class="space-y-1">
                                    <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Approved By</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $loan->approver->name ?? 'N/A' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Approved Date</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $loan->approved_at ? $loan->approved_at->format('M d, Y') : 'N/A' }}</p>
                                </div>
                            @endif
                            @if($loan->status === 'rejected')
                                <div class="space-y-1">
                                    <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Rejected By</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $loan->rejecter->name ?? 'N/A' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Rejected Date</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $loan->rejected_at ? $loan->rejected_at->format('M d, Y') : 'N/A' }}</p>
                                </div>
                                <div class="col-span-2 space-y-1">
                                    <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Rejection Reason</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $loan->rejection_reason ?? 'No reason provided' }}</p>
                                </div>
                            @endif
                            @if($loan->status === 'active' || $loan->status === 'paid')
                                <div class="space-y-1">
                                    <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Disbursed By</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $loan->disburser->name ?? 'N/A' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Disbursed Date</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $loan->disbursed_at ? $loan->disbursed_at->format('M d, Y') : 'N/A' }}</p>
                                </div>
                            @endif
                            @if($loan->status === 'active' || $loan->status === 'paid' || $loan->status === 'defaulted')
                                <div class="space-y-1">
                                    <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Paid Amount</label>
                                    <p class="text-lg font-semibold text-green-600">₦{{ number_format($loan->paid_amount, 2) }}</p>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Remaining Balance</label>
                                    <p class="text-lg font-semibold text-red-600">₦{{ number_format($loan->remaining_balance, 2) }}</p>
                                </div>
                                <div class="col-span-2 space-y-1">
                                    <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Progress</label>
                                    <div class="w-full h-3 mt-2 bg-gray-200 rounded-full">
                                        <div class="h-3 transition-all duration-300 bg-green-600 rounded-full" style="width: {{ $loan->progress_percentage }}%"></div>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-600">{{ number_format($loan->progress_percentage, 1) }}% complete</p>
                                </div>
                            @endif
                            @if($loan->status === 'paid')
                                <div class="space-y-1">
                                    <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Completed Date</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $loan->completed_at ? $loan->completed_at->format('M d, Y') : 'N/A' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Repayment Schedule Card -->
            @if($loan->status !== 'pending' && $loan->status !== 'rejected')
                <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
                    <div class="px-4 py-3 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-purple-100 sm:px-6 sm:py-4">
                        <div class="flex items-center">
                            <div class="p-2 mr-3 bg-purple-100 rounded-lg">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">Repayment Schedule</h2>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:px-6 sm:py-3">Payment #</th>
                                        <th class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:px-6 sm:py-3">Due Date</th>
                                        <th class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:px-6 sm:py-3">Payment Amount</th>
                                        <th class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:px-6 sm:py-3">Principal</th>
                                        <th class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:px-6 sm:py-3">Interest</th>
                                        <th class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:px-6 sm:py-3">Status</th>
                                        <th class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:px-6 sm:py-3">Paid Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($loan->repayments as $repayment)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-3 py-3 whitespace-nowrap sm:px-6 sm:py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $repayment->payment_number }}</div>
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap sm:px-6 sm:py-4">
                                                <div class="text-sm text-gray-900">{{ $repayment->due_date->format('M d, Y') }}</div>
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap sm:px-6 sm:py-4">
                                                <div class="text-sm font-medium text-gray-900">₦{{ number_format($repayment->amount, 2) }}</div>
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap sm:px-6 sm:py-4">
                                                <div class="text-sm text-gray-500">₦{{ number_format($repayment->principal_amount, 2) }}</div>
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap sm:px-6 sm:py-4">
                                                <div class="text-sm text-gray-500">₦{{ number_format($repayment->interest_amount, 2) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($repayment->status == 'pending')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <svg class="w-2 h-2 mr-1.5 text-yellow-400" fill="currentColor" viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3" />
                                                        </svg>
                                                        Pending
                                                    </span>
                                                @elseif($repayment->status == 'paid')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <svg class="w-2 h-2 mr-1.5 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3" />
                                                        </svg>
                                                        Paid
                                                    </span>
                                                @elseif($repayment->status == 'late')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                        <svg class="w-2 h-2 mr-1.5 text-orange-400" fill="currentColor" viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3" />
                                                        </svg>
                                                        Late
                                                    </span>
                                                @elseif($repayment->status == 'missed')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <svg class="w-2 h-2 mr-1.5 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3" />
                                                        </svg>
                                                        Missed
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap sm:px-6 sm:py-4">
                                                <div class="text-sm text-gray-500">{{ $repayment->paid_at ? $repayment->paid_at->format('M d, Y') : '-' }}</div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-3 py-3 text-sm text-center text-gray-500 sm:px-6 sm:py-4">
                                                No repayment schedule available.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Approval Modal --}}
    <div id="approval-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 z-40 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative z-50 inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-green-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                Approve Loan Application
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to approve this loan application? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form id="approval-form" method="POST" action="{{ route('admin.loans.approve', $loan) }}">
                        @csrf
                        <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Approve
                        </button>
                    </form>
                    <button type="button" onclick="closeApprovalModal()" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Rejection Modal --}}
    <div id="rejection-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="modal-title-reject">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 z-40 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative z-50 inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title-reject">
                                Reject Loan Application
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Please provide a reason for rejecting this loan application.
                                </p>
                                <form id="rejection-form" method="POST" action="{{ route('admin.loans.reject', $loan) }}">
                                    @csrf
                                    <div class="mt-4">
                                        <textarea name="rejection_reason" rows="3" class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Rejection reason" required></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" form="rejection-form" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Reject
                    </button>
                    <button type="button" onclick="closeRejectionModal()" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openApprovalModal() {
                document.getElementById('approval-modal').classList.remove('hidden');
            }

            function closeApprovalModal() {
                document.getElementById('approval-modal').classList.add('hidden');
            }

            function openRejectModal() {
                document.getElementById('rejection-modal').classList.remove('hidden');
            }

            function closeRejectionModal() {
                document.getElementById('rejection-modal').classList.add('hidden');
            }
        </script>
    @endpush
@endsection