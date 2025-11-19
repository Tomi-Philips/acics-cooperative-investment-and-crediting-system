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
                    Loan Overview
                </h1>
                <p class="mt-2 text-sm text-gray-600">Manage your active loans and repayment schedule. Multiple loans are allowed based on your capacity.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('user.loan_application') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-all duration-200 bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-md hover:from-green-600 hover:to-green-700 hover:shadow-lg {{ !$eligibility['eligible'] ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$eligibility['eligible'] ? 'disabled' : '' }}>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Apply for New Loan
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
        @elseif(count($activeLoans) > 0 && $activeLoans->first()->payments()->where('status', 'pending')->where('due_date', '>=', now())->first())
        <div class="px-4 py-3 text-white bg-green-600 rounded-lg shadow-sm">
            <p class="text-sm font-medium">
                <span class="font-bold">Loan Outstanding:</span> Your payment of ₦{{ number_format($activeLoans->first()->payments()->where('status', 'pending')->where('due_date', '>=', now())->first()->amount, 2) }} is due on <span class="underline">{{ $activeLoans->first()->payments()->where('status', 'pending')->where('due_date', '>=', now())->first()->due_date->format('F d, Y') }}</span>.
            </p>
        </div>
        @endif

        <!-- Active Loans Card -->
        <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-blue-50">
                <h2 class="flex items-center text-lg font-semibold text-gray-800">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Active Loans
                    <span class="ml-auto text-sm font-normal bg-green-100 text-green-800 px-2.5 py-0.5 rounded-full">{{ count($activeLoans) }} {{ Str::plural('Loan', count($activeLoans)) }}</span>
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Loan ID</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Amount</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Interest Rate</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Next Payment</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(count($activeLoans) > 0)
                        @foreach($activeLoans as $loan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">LOAN-{{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}</div>
                                <div class="text-xs text-gray-500">{{ $loan->purpose }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">₦{{ number_format($loan->amount, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $loan->interest_rate > 1 ? $loan->interest_rate : $loan->interest_rate * 100 }}%</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($loan->status === 'active')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                @elseif($loan->status === 'pending')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending Approval</span>
                                @elseif($loan->status === 'approved')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Approved</span>
                                @elseif($loan->status === 'rejected')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejected</span>
                                @elseif($loan->status === 'completed')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Completed</span>
                                @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($loan->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($loan->status === 'pending')
                                <div class="text-sm text-yellow-600">Awaiting approval</div>
                                <div class="text-xs text-gray-500">Submitted on {{ $loan->submitted_at ? $loan->submitted_at->format('M d, Y') : $loan->created_at->format('M d, Y') }}</div>
                                @elseif($loan->status === 'approved')
                                <div class="text-sm text-blue-600">Disbursement pending</div>
                                <div class="text-xs text-gray-500">Approved on {{ $loan->approved_at ? $loan->approved_at->format('M d, Y') : $loan->updated_at->format('M d, Y') }}</div>
                                @else
                                @php $nextPayment = $loan->payments()->where('status', 'pending')->orderBy('due_date')->first(); @endphp
                                @if($nextPayment)
                                <div class="text-sm text-gray-900">{{ $nextPayment->due_date->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">₦{{ number_format($nextPayment->amount, 2) }}</div>
                                @else
                                <div class="text-sm text-gray-500">No payments due</div>
                                @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                <a href="{{ route('user.loan.show', $loan->id) }}" class="mr-4 text-green-600 hover:text-green-900">View</a>
                                @if($loan->status === 'active' && isset($nextPayment) && $nextPayment)
                                <a href="#" class="text-blue-600 hover:text-blue-900">Pay</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h4 class="mt-2 text-sm font-medium text-gray-900">No active loans</h4>
                                <p class="mt-1 text-sm text-gray-500">You don't have any active loans at the moment.</p>
                                @if($eligibility['eligible'])
                                <div class="mt-3">
                                    <a href="{{ route('user.loan_application') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-white border border-transparent rounded-md bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Apply for a Loan
                                    </a>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Loan History Card -->
        <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-blue-50">
                <h2 class="flex items-center text-lg font-semibold text-gray-800">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Loan History
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Loan ID</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Amount</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Purpose</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Closed On</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php $completedLoans = $user->loans()->where('status', 'completed')->orderBy('updated_at', 'desc')->take(5)->get(); @endphp
                        @if(count($completedLoans) > 0)
                        @foreach($completedLoans as $loan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">LOAN-{{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">₦{{ number_format($loan->amount, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $loan->purpose }}</td>
                            <td class="px-6 py-4 text-sm font-medium whitespace-nowrap text-emerald-600">Completed</td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $loan->updated_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                <a href="{{ route('user.loan.show', $loan->id) }}" class="flex items-center justify-end gap-1 text-green-600 hover:text-green-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Statement
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <h4 class="mt-2 text-sm font-medium text-gray-900">No loan history</h4>
                                <p class="mt-1 text-sm text-gray-500">You don't have any completed loans yet.</p>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @if(count($completedLoans) > 0)
            <div class="px-6 py-3 text-right border-t border-gray-200 bg-gray-50">
                <a href="#" class="text-sm font-medium text-green-600 hover:text-green-800">View all loan history →</a>
            </div>
            @endif
        </div>

        <!-- Repayment Schedule Card -->
        <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-blue-50">
                <h2 class="flex items-center text-lg font-semibold text-gray-800">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Repayment Schedule
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Description</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Amount Due</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php $loanPayments = $loanPayments ?? collect(); @endphp
                        @if(count($loanPayments) > 0)
                        @foreach($loanPayments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $payment->due_date->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">
                                    @if($payment->due_date->isPast() && $payment->status === 'pending')
                                    <span class="text-red-600">Overdue</span>
                                    @elseif($payment->due_date->isToday() && $payment->status === 'pending')
                                    <span class="text-orange-600">Due today</span>
                                    @elseif($payment->status === 'pending')
                                    {{ $payment->due_date->diffForHumans() }}
                                    @else
                                    {{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : 'Not paid' }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                Loan LOAN-{{ str_pad($payment->loan_id, 5, '0', STR_PAD_LEFT) }}
                                @if($payment->notes)
                                <div class="text-xs text-gray-400">{{ $payment->notes }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">₦{{ number_format($payment->amount, 2) }}</td>
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
                            <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                @if($payment->status === 'pending')
                                <button class="text-green-600 hover:text-green-900">Pay Now</button>
                                @else
                                <a href="{{ route('user.loan.show', $payment->loan_id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <h4 class="mt-2 text-sm font-medium text-gray-900">No upcoming payments</h4>
                                <p class="mt-1 text-sm text-gray-500">You don't have any scheduled loan payments.</p>
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