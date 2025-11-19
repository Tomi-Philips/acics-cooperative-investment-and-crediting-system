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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    Loan Management
                </h1>
                <p class="mt-2 text-sm text-gray-600">Manage and monitor all loan applications and active loans</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.loans.create') }}"
                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white transition-all duration-200 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 border border-transparent rounded-lg shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Loan
                </a>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="mt-4">
            <nav class="flex p-1 space-x-8 bg-gray-100 rounded-lg shadow-inner" role="group">
                <a href="{{ route('admin.loans.index') }}"
                    class="flex-1 px-4 py-2.5 text-sm font-medium text-center rounded-md transition-all duration-200 bg-green-600 text-white shadow-sm">
                    <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    All Loans
                    <span class="ml-2 bg-green-100 text-green-800 py-0.5 px-2.5 rounded-full text-xs font-medium">{{ count($loans) }}</span>
                </a>
                <a href="{{ route('admin.loans.approval') }}"
                    class="flex-1 px-4 py-2.5 text-sm font-medium text-center rounded-md transition-all duration-200 text-gray-700 hover:bg-white hover:text-green-700">
                    <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Pending Approvals
                    <span class="ml-2 bg-gray-100 text-gray-800 py-0.5 px-2.5 rounded-full text-xs font-medium">{{ $loans->where('status', 'pending')->count() }}</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="p-4 mb-6 text-green-700 bg-green-100 border-l-4 border-green-500 rounded-lg" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="p-4 mb-6 text-red-700 bg-red-100 border-l-4 border-red-500 rounded-lg" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <!-- Enhanced Stats Cards -->
    <div class="grid gap-6 mb-8 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        <!-- Total Loans Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-blue-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-blue-50 to-blue-100 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-blue-100 rounded-full opacity-20"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="p-4 shadow-lg bg-gradient-to-br from-blue-400 to-blue-500 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                                </svg>
                            </div>
                            <div class="absolute w-4 h-4 bg-blue-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Total Loans</p>
                            <p class="mt-1 text-3xl font-bold text-gray-800">{{ $loans->total() }}</p>
                            <p class="mt-1 text-xs font-medium text-blue-600">All applications</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Loans Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-green-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-green-50 to-green-100 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-green-100 rounded-full opacity-20"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="p-4 shadow-lg bg-gradient-to-br from-green-400 to-green-500 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="absolute w-4 h-4 bg-green-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Active Loans</p>
                            <p class="mt-1 text-3xl font-bold text-gray-800">{{ $loans->where('status', 'active')->count() }}</p>
                            <p class="mt-1 text-xs font-medium text-green-600">Currently disbursed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                            <p class="mt-1 text-3xl font-bold text-gray-800">{{ $loans->where('status', 'pending')->count() }}</p>
                            <p class="mt-1 text-xs font-medium text-orange-600">Awaiting review</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Amount Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-purple-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-purple-50 to-purple-100 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-purple-100 rounded-full opacity-20"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="p-4 shadow-lg bg-gradient-to-br from-purple-400 to-purple-500 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="absolute w-4 h-4 bg-purple-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Total Amount</p>
                            <p class="mt-1 text-3xl font-bold text-gray-800">₦{{ number_format($loans->sum('amount'), 2) }}</p>
                            <p class="mt-1 text-xs font-medium text-purple-600">All loans value</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loans Table Card -->
    <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-2xl">
        <!-- Card Header with Search and Filters -->
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-green-50 to-blue-50">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center">
                    <div class="p-2 mr-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">All Loans</h2>
                        <p class="text-sm text-gray-600">{{ count($loans) }} total loans</p>
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <!-- Status Filter -->
                    <div class="relative">
                        <select id="status-filter" name="status" class="pl-10 pr-8 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white cursor-pointer transition-all duration-200" onchange="filterByStatus(this.value)">
                            <option value="all" @if(request('status') == 'all') selected @endif>All Statuses</option>
                            <option value="pending" @if(request('status') == 'pending') selected @endif>Pending</option>
                            <option value="approved" @if(request('status') == 'approved') selected @endif>Approved</option>
                            <option value="active" @if(request('status') == 'active') selected @endif>Active</option>
                            <option value="paid" @if(request('status') == 'paid') selected @endif>Paid</option>
                            <option value="rejected" @if(request('status') == 'rejected') selected @endif>Rejected</option>
                            <option value="defaulted" @if(request('status') == 'defaulted') selected @endif>Defaulted</option>
                        </select>
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Search -->
                    <form action="{{ route('admin.loans.index') }}" method="GET" class="flex">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" placeholder="Search loans..." value="{{ request('search') }}"
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm transition duration-150 ease-in-out">
                        </div>
                        <button type="submit" class="ml-2 px-4 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200">
                            Search
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">Loan Number</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">Member</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">Amount</th>
                        <th scope="col" class="hidden px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase md:table-cell">Term</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">Status</th>
                        <th scope="col" class="hidden px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase sm:table-cell">Date</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($loans as $loan)
                        <tr class="transition-all duration-200 border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center w-10 h-10 text-sm font-bold text-white rounded-full bg-gradient-to-r from-blue-500 to-purple-600">
                                        {{ substr($loan->loan_number, -2) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-semibold text-gray-900">{{ $loan->loan_number }}</div>
                                        <div class="text-xs text-gray-500">Loan ID</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center w-10 h-10 text-sm font-bold text-white rounded-full bg-gradient-to-r from-teal-500 to-green-600">
                                        {{ strtoupper(substr($loan->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-semibold text-gray-900">{{ $loan->user->name ?? 'Unknown User' }}</div>
                                        <div class="text-xs text-gray-500">{{ $loan->user->department->title ?? 'No Department' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">₦{{ number_format($loan->amount, 2) }}</div>
                                <div class="text-xs text-gray-500">Loan Amount</div>
                            </td>
                            <td class="hidden px-6 py-5 md:table-cell whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $loan->term_months }} months</div>
                                <div class="text-xs text-gray-500">Term</div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                @if($loan->status == 'pending')
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-orange-800 bg-orange-100 rounded-full">
                                        <div class="w-2 h-2 mr-2 bg-orange-400 rounded-full animate-pulse"></div>
                                        Pending
                                    </span>
                                @elseif($loan->status == 'approved')
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                        <div class="w-2 h-2 mr-2 bg-green-400 rounded-full"></div>
                                        Approved
                                    </span>
                                @elseif($loan->status == 'rejected')
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                        <div class="w-2 h-2 mr-2 bg-red-400 rounded-full"></div>
                                        Rejected
                                    </span>
                                @elseif($loan->status == 'active')
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">
                                        <div class="w-2 h-2 mr-2 bg-blue-400 rounded-full"></div>
                                        Active
                                    </span>
                                @elseif($loan->status == 'paid')
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-purple-800 bg-purple-100 rounded-full">
                                        <div class="w-2 h-2 mr-2 bg-purple-400 rounded-full"></div>
                                        Paid
                                    </span>
                                @elseif($loan->status == 'defaulted')
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full">
                                        <div class="w-2 h-2 mr-2 bg-gray-400 rounded-full"></div>
                                        Defaulted
                                    </span>
                                @endif
                            </td>
                            <td class="hidden px-6 py-5 sm:table-cell whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $loan->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $loan->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-5 text-sm font-medium whitespace-nowrap">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.loans.show', $loan) }}" class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white transition-all duration-200 rounded-lg shadow-sm bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 hover:shadow-md">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>
                                    @if($loan->status == 'pending')
                                        <form action="{{ route('admin.loans.approve', $loan) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to approve this loan?');">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white transition-all duration-200 rounded-lg shadow-sm bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 hover:shadow-md">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Approve
                                            </button>
                                        </form>
                                    @endif
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="px-6 py-16 text-center">
                <div class="flex flex-col items-center justify-center">
                    <div class="p-4 mb-4 bg-gray-100 rounded-full">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="mb-2 text-lg font-semibold text-gray-900">No loans found</h3>
                    <p class="mb-4 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
                    <a href="{{ route('admin.loans.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-colors duration-200 bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add New Loan
                    </a>
                </div>
            </td>
        </tr>
    @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($loans->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $loans->links() }}
    @endif
</div>
</div>

@push('scripts')
<script>
    function filterByStatus(status) {
        const urlParams = new URLSearchParams(window.location.search);
        if (status === 'all') {
            urlParams.delete('status');
        } else {
            urlParams.set('status', status);
        }
        window.location.href = "{{ route('admin.loans.index') }}?" + urlParams.toString();
    }

    // Set the selected status in the dropdown based on the URL parameter
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        if (status) {
            document.getElementById('status-filter').value = status;
        }
    });
</script>
@endpush
@endsection
