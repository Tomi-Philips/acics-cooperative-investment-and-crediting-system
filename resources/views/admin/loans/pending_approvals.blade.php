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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    Pending Loan Approvals
                </h1>
                <p class="mt-2 text-sm text-gray-600">Review and manage loan applications awaiting approval</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.loans.index') }}" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to All Loans
                </a>
            </div>
        </div>
    </div>

        <!-- Stats Cards -->
        <div class="grid gap-6 mb-8 md:grid-cols-2 lg:grid-cols-3">
            <!-- Pending Approvals Card -->
            <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-orange-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
                <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-orange-50 to-orange-100 group-hover:opacity-100"></div>
                <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-orange-100 rounded-full opacity-20"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="relative">
                                <div class="p-4 shadow-lg bg-gradient-to-br from-orange-400 to-orange-500 rounded-2xl">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="absolute w-4 h-4 bg-orange-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Pending Approvals</p>
                                <p class="mt-1 text-3xl font-bold text-gray-800">{{ count($loans) }}</p>
                                <p class="mt-1 text-xs font-medium text-orange-600">Awaiting review</p>
                            </div>
                        </div>
                        <div class="text-orange-200">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average Loan Amount Card -->
            <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-green-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
                <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-green-50 to-emerald-100 group-hover:opacity-100"></div>
                <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-green-100 rounded-full opacity-20"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="relative">
                                <div class="p-4 shadow-lg bg-gradient-to-br from-green-400 to-emerald-500 rounded-2xl">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                                <div class="absolute w-4 h-4 bg-green-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Average Amount</p>
                                <p class="mt-1 text-2xl font-bold text-gray-800">₦{{ number_format($loans->avg('amount') ?? 0, 2) }}</p>
                                <p class="mt-1 text-xs font-medium text-green-600">Per application</p>
                            </div>
                        </div>
                        <div class="text-green-200">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average Term Card -->
            <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-blue-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
                <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-blue-50 to-indigo-100 group-hover:opacity-100"></div>
                <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-blue-100 rounded-full opacity-20"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="relative">
                                <div class="p-4 shadow-lg bg-gradient-to-br from-blue-400 to-indigo-500 rounded-2xl">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="absolute w-4 h-4 bg-blue-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Average Term</p>
                                <p class="mt-1 text-2xl font-bold text-gray-800">{{ round($loans->avg('term_months') ?? 0) }} <span class="text-lg font-normal text-gray-600">months</span></p>
                                <p class="mt-1 text-xs font-medium text-blue-600">Repayment period</p>
                            </div>
                        </div>
                        <div class="text-blue-200">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Loans List Card -->
    <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
        <!-- Card Header with Search and Filters -->
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
            <div class="flex flex-col space-y-4 lg:flex-row lg:items-center lg:justify-between lg:space-y-0">
                <div class="flex items-center">
                    <div class="p-2 mr-4 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Pending Loan Applications</h2>
                        <p class="mt-1 text-sm text-gray-600">Review and process loan applications</p>
                    </div>
                </div>

                <!-- Search and Filter Controls -->
                <div class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
                    <div class="relative flex-1 sm:max-w-xs">
                        <form action="{{ route('admin.loans.pending-approvals') }}" method="GET" class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" placeholder="Search loans..." class="block w-full py-2 pr-3 text-sm transition duration-150 ease-in-out border border-gray-300 rounded-lg pl-9 focus:outline-none focus:ring-green-500 focus:border-green-500" value="{{ request('search') }}">
                        </form>
                    </div>
                    <div class="relative">
                        <select id="status-filter" class="px-3 py-2 text-sm transition-all duration-200 bg-white border border-gray-300 rounded-lg cursor-pointer focus:ring-2 focus:ring-green-500 focus:border-green-500" onchange="filterByStatus(this.value)">
                            <option value="all">All Statuses</option>
                            <option value="pending" selected>Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="p-8">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden border-b border-gray-200 shadow-sm sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Loan Number
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Member
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Amount
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Term
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Date Submitted
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($loans as $loan)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                            {{ $loan->loan_number }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <div class="flex items-center">
                                                <div class="flex items-center justify-center w-8 h-8 mr-3 font-semibold text-white bg-green-500 rounded-full">
                                                    {{ strtoupper(substr($loan->user->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-900">{{ $loan->user->name ?? 'Unknown User' }}</p>
                                                    <p class="text-xs text-gray-500">{{ $loan->user->department->title ?? 'No Department' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                            ₦{{ number_format($loan->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ $loan->term_months }} months
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ $loan->submitted_at->format('M d, Y') }}
                                            <span class="block text-xs text-gray-400">{{ $loan->submitted_at->diffForHumans() }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('admin.loans.show', $loan) }}" class="text-blue-600 hover:text-blue-900" title="View Details">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                                <button onclick="openApprovalModal({{ $loan->id }})" class="text-green-600 hover:text-green-900" title="Approve Loan">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                                <button onclick="openRejectModal({{ $loan->id }})" class="text-red-600 hover:text-red-900" title="Reject Loan">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center py-8">
                                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <p class="mt-4 text-lg font-semibold">No pending loan applications found.</p>
                                                <p class="text-gray-500">All loan applications have been processed.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if ($loans->hasPages())
                <div class="flex items-center justify-center px-4 py-3 bg-white border-t border-gray-200 sm:px-6 sm:py-4 sm:justify-between">
                    <div class="hidden sm:block">
                        <p class="text-sm text-gray-700">
                            @if ($loans->total() > 0)
                                Showing
                                <span class="font-medium">{{ $loans->firstItem() }}</span>
                                to
                                <span class="font-medium">{{ $loans->lastItem() }}</span>
                                of
                                <span class="font-medium">{{ $loans->total() }}</span>
                                loan applications
                            @else
                                No loan applications found
                            @endif
                        </p>
                    </div>
                    <div class="flex justify-center w-full sm:w-auto">
                        {{ $loans->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div id="approval-modal" class="fixed inset-0 z-50 hidden overflow-y-auto animate-fade-in">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop with blur effect -->
        <div class="fixed inset-0 transition-all duration-300" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="relative z-30 inline-block overflow-hidden text-left align-bottom transition-all transform bg-white shadow-2xl rounded-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modal-slide-up">
            <!-- Header with gradient -->
            <div class="relative px-6 py-4 bg-gradient-to-r from-emerald-500 to-green-600 sm:px-6">
                <div class="flex items-center">
                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <svg class="text-white w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-bold text-white">
                            Approve Loan Application
                        </h3>
                        <p class="text-sm text-emerald-100">Confirm approval action</p>
                    </div>
                </div>
                <!-- Decorative elements -->
                <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-white rounded-full bg-opacity-10"></div>
                <div class="absolute bottom-0 left-0 w-16 h-16 -mb-8 -ml-8 bg-white rounded-full bg-opacity-5"></div>
            </div>

            <div class="px-6 py-6 bg-white">
                <div class="text-center sm:text-left">
                    <div class="mb-4">
                        <p class="text-base leading-relaxed text-gray-700">
                            You are about to approve this loan application. This action will:
                        </p>
                        <ul class="mt-3 space-y-2 text-sm text-gray-600">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Activate the loan for the member
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Send approval notification
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Update loan status permanently
                            </li>
                        </ul>
                    </div>
                    <div class="p-4 border border-yellow-200 rounded-lg bg-yellow-50">
                        <div class="flex">
                            <svg class="w-5 h-5 text-yellow-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-yellow-800">Important</p>
                                <p class="mt-1 text-sm text-yellow-700">This action cannot be undone. Please review the application details carefully.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 sm:flex sm:flex-row-reverse sm:px-6">
                <form id="approval-form" method="POST" action="" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="inline-flex justify-center w-full px-6 py-3 text-base font-semibold text-white bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Approve Application
                    </button>
                </form>
                <button type="button" onclick="closeApprovalModal()" class="inline-flex justify-center w-full px-6 py-3 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejection-modal" class="fixed inset-0 z-30 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
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
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            Reject Loan Application
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Please provide a reason for rejecting this loan application.
                            </p>
                            <form id="rejection-form" method="POST" action="">
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
    function openApprovalModal(id) {
        document.getElementById('approval-form').action = "{{ url('admin/loans') }}/" + id + "/approve";
        document.getElementById('approval-modal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeApprovalModal() {
        document.getElementById('approval-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function openRejectModal(id) {
        document.getElementById('rejection-form').action = "{{ url('admin/loans') }}/" + id + "/reject";
        document.getElementById('rejection-modal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeRejectionModal() {
        document.getElementById('rejection-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function filterByStatus(status) {
        if (status === 'all') {
            window.location.href = "{{ route('admin.loans.index') }}";
        } else if (status === 'pending') {
            window.location.href = "{{ route('admin.loans.pending-approvals') }}";
        } else {
            window.location.href = "{{ route('admin.loans.index') }}?status=" + status;
        }
    }

    // Close modals when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        const approvalModal = document.getElementById('approval-modal');
        const rejectionModal = document.getElementById('rejection-modal');

        window.addEventListener('click', function(event) {
            if (event.target === approvalModal) {
                closeApprovalModal();
            }
            if (event.target === rejectionModal) {
                closeRejectionModal();
            }
        });
    });
</script>
@endpush
@endsection
