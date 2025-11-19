@extends('layouts.user')

@section('content')
<!-- Notification Banner -->
@if(session('success'))
<div class="flex items-start p-4 mb-6 text-sm text-white bg-green-600 rounded-lg">
    <svg class="flex-shrink-0 w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
    </svg>
    <div>
        <p>{{ session('success') }}</p>
    </div>
</div>
@endif

@if(session('error'))
<div class="flex items-start p-4 mb-6 text-sm text-white bg-red-600 rounded-lg">
    <svg class="flex-shrink-0 w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
    </svg>
    <div>
        <p>{{ session('error') }}</p>
    </div>
</div>
@endif

<!-- Enhanced Header Section -->
<div class="p-6 mb-6 border border-gray-200 shadow-lg bg-gradient-to-r from-green-50 to-blue-50 rounded-xl">
    <div class="flex flex-col items-center justify-between lg:flex-row">
        <div class="mb-6 lg:mb-0">
            <h1 class="flex items-center text-2xl font-bold text-gray-800">
                <div class="p-2 mr-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                User Dashboard
            </h1>
            <p class="mt-2 text-sm text-gray-600">Welcome back, {{ $user->name }}! Here's an overview of your account</p>
            <div class="flex flex-wrap items-center mt-3 space-x-6 text-sm text-gray-600">
                <span class="flex items-center">
                    <div class="p-1 mr-2 bg-blue-100 rounded-full">
                        <svg class="w-3 h-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="font-semibold">Member ID:</span> {{ $user->member_number }}
                </span>
                @if($user->member && $user->member->joined_at)
                <span class="flex items-center">
                    <div class="p-1 mr-2 bg-green-100 rounded-full">
                        <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="font-semibold">Joined:</span> {{ $user->member->joined_at->format('M d, Y') }}
                </span>
                @endif
            </div>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('user.profile') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-all duration-200 border border-green-600 rounded-lg shadow-sm bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                View Profile
            </a>
        </div>
    </div>
</div>

<!-- Entrance Fee Status -->
<div class="mb-4">
    @if(isset($entrancePaid) && $entrancePaid)
        <div class="flex items-center p-3 text-green-800 border border-green-200 rounded-lg bg-green-50">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="text-sm font-medium">Entrance fee status: Paid</span>
        </div>
    @else
        <div class="flex items-center p-3 border rounded-lg text-amber-800 bg-amber-50 border-amber-200">
            <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">Entrance fee status: Not paid</span>
        </div>
    @endif
</div>

<!-- Stats Cards -->
<div class="grid gap-4 mb-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
    <!-- Savings Balance Card -->
    <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-green-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
        <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-green-50 to-green-100 group-hover:opacity-100"></div>
        <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-green-100 rounded-full opacity-20"></div>
        <div class="relative p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center">
                    <div class="relative flex-shrink-0">
                        <div class="p-3 shadow-lg sm:p-4 bg-gradient-to-br from-green-400 to-green-500 rounded-2xl">
                            <svg class="w-5 h-5 text-white sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                        </div>
                        <div class="absolute w-3 h-3 bg-green-500 rounded-full sm:w-4 sm:h-4 -top-1 -right-1 animate-pulse"></div>
                    </div>
                    <div class="mt-2 ml-3 sm:ml-4 sm:mt-0">
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase sm:text-sm">Savings Balance</p>
                        <p class="mt-1 text-2xl font-bold text-gray-800 sm:text-3xl">₦{{ number_format($savingsBalance, 2) }}</p>
                        <p class="mt-1 text-xs font-medium text-green-600">Available funds</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Shares Balance Card -->
    <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-purple-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
        <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-purple-50 to-purple-100 group-hover:opacity-100"></div>
        <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-purple-100 rounded-full opacity-20"></div>
        <div class="relative p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center">
                    <div class="relative flex-shrink-0">
                        <div class="p-3 shadow-lg sm:p-4 bg-gradient-to-br from-purple-400 to-purple-500 rounded-2xl">
                            <svg class="w-5 h-5 text-white sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div class="absolute w-3 h-3 bg-purple-500 rounded-full sm:w-4 sm:h-4 -top-1 -right-1 animate-pulse"></div>
                    </div>
                    <div class="mt-2 ml-3 sm:ml-4 sm:mt-0">
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase sm:text-sm">Shares Balance</p>
                        <p class="mt-1 text-2xl font-bold text-gray-800 sm:text-3xl">₦{{ number_format($sharesBalance, 2) }}</p>
                        <p class="mt-1 text-xs font-medium text-purple-600">Investment value</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loan Balance Card -->
    <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-red-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
        <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-red-50 to-red-100 group-hover:opacity-100"></div>
        <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-red-100 rounded-full opacity-20"></div>
        <div class="relative p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center">
                    <div class="relative flex-shrink-0">
                        <div class="p-3 shadow-lg sm:p-4 bg-gradient-to-br from-red-400 to-red-500 rounded-2xl">
                            <svg class="w-5 h-5 text-white sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="absolute w-3 h-3 bg-red-500 rounded-full sm:w-4 sm:h-4 -top-1 -right-1 animate-pulse"></div>
                    </div>
                    <div class="mt-2 ml-3 sm:ml-4 sm:mt-0">
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase sm:text-sm">Loan Balance</p>
                        <p class="mt-1 text-2xl font-bold text-gray-800 sm:text-3xl">₦{{ number_format($loanBalance, 2) }}</p>
                        <p class="mt-1 text-xs font-medium text-red-600">Outstanding amount</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loan Interest Paid Card -->
    <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-orange-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
        <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-orange-50 to-orange-100 group-hover:opacity-100"></div>
        <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-orange-100 rounded-full opacity-20"></div>
        <div class="relative p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center">
                    <div class="relative flex-shrink-0">
                        <div class="p-3 shadow-lg sm:p-4 bg-gradient-to-br from-orange-400 to-orange-500 rounded-2xl">
                            <svg class="w-5 h-5 text-white sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="absolute w-3 h-3 bg-orange-500 rounded-full sm:w-4 sm:h-4 -top-1 -right-1 animate-pulse"></div>
                    </div>
                    <div class="mt-2 ml-3 sm:ml-4 sm:mt-0">
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase sm:text-sm">Loan Interest</p>
                        <p class="mt-1 text-2xl font-bold text-gray-800 sm:text-3xl">₦{{ number_format(\App\Services\FinancialCalculationService::calculateLoanInterest($user), 2) }}</p>
                        <p class="mt-1 text-xs font-medium text-orange-600">Total interest owed</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Essential Commodity Balance Card -->
    <div class="relative overflow-hidden transition-all duration-300 transform bg-white border shadow-lg border-amber-100 group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
        <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-amber-50 to-amber-100 group-hover:opacity-100"></div>
        <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 rounded-full bg-amber-100 opacity-20"></div>
        <div class="relative p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center">
                    <div class="relative flex-shrink-0">
                        <div class="p-3 shadow-lg sm:p-4 bg-gradient-to-br from-amber-400 to-amber-500 rounded-2xl">
                            <svg class="w-5 h-5 text-white sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div class="absolute w-3 h-3 rounded-full sm:w-4 sm:h-4 bg-amber-500 -top-1 -right-1 animate-pulse"></div>
                    </div>
                    <div class="mt-2 ml-3 sm:ml-4 sm:mt-0">
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase sm:text-sm">Essential Commodity</p>
                        <p class="mt-1 text-2xl font-bold text-gray-800 sm:text-3xl">₦{{ number_format($essentialBalance, 2) }}</p>
                        <p class="mt-1 text-xs font-medium text-amber-600">Purchase credit</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Non-Essential Commodity Balance Card -->
    <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-orange-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
        <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-orange-50 to-orange-100 group-hover:opacity-100"></div>
        <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-orange-100 rounded-full opacity-20"></div>
        <div class="relative p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center">
                    <div class="relative flex-shrink-0">
                        <div class="p-3 shadow-lg sm:p-4 bg-gradient-to-br from-orange-400 to-orange-500 rounded-2xl">
                            <svg class="w-5 h-5 text-white sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div class="absolute w-3 h-3 bg-orange-500 rounded-full sm:w-4 sm:h-4 -top-1 -right-1 animate-pulse"></div>
                    </div>
                    <div class="mt-2 ml-3 sm:ml-4 sm:mt-0">
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase sm:text-sm">Non-Essential</p>
                        <p class="mt-1 text-2xl font-bold text-gray-800 sm:text-3xl">₦{{ number_format($nonEssentialBalance, 2) }}</p>
                        <p class="mt-1 text-xs font-medium text-orange-600">Purchase credit</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Electronics Balance Card -->
    <div class="relative overflow-hidden transition-all duration-300 transform bg-white border shadow-lg border-cyan-100 group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
        <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-cyan-50 to-cyan-100 group-hover:opacity-100"></div>
        <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 rounded-full bg-cyan-100 opacity-20"></div>
        <div class="relative p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center">
                    <div class="relative flex-shrink-0">
                        <div class="p-3 shadow-lg sm:p-4 bg-gradient-to-br from-cyan-400 to-cyan-500 rounded-2xl">
                            <svg class="w-5 h-5 text-white sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="absolute w-3 h-3 rounded-full sm:w-4 sm:h-4 bg-cyan-500 -top-1 -right-1 animate-pulse"></div>
                    </div>
                    <div class="mt-2 ml-3 sm:ml-4 sm:mt-0">
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase sm:text-sm">Electronics</p>
                        <p class="mt-1 text-2xl font-bold text-gray-800 sm:text-3xl">₦{{ number_format($electronicsBalance, 2) }}</p>
                        <p class="mt-1 text-xs font-medium text-cyan-600">Purchase credit</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Stats Cards -->
<div class="grid gap-6 mb-8 md:grid-cols-2 lg:grid-cols-3">
    <!-- Active Loans Card -->
    <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-blue-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
        <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-blue-50 to-blue-100 group-hover:opacity-100"></div>
        <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-blue-100 rounded-full opacity-20"></div>
        <div class="relative p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center">
                    <div class="relative flex-shrink-0">
                        <div class="p-3 shadow-lg sm:p-4 bg-gradient-to-br from-blue-400 to-blue-500 rounded-2xl">
                            <svg class="w-5 h-5 text-white sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="absolute w-3 h-3 bg-blue-500 rounded-full sm:w-4 sm:h-4 -top-1 -right-1 animate-pulse"></div>
                    </div>
                    <div class="mt-2 ml-3 sm:ml-4 sm:mt-0">
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase sm:text-sm">Active Loans</p>
                        <p class="mt-1 text-2xl font-bold text-gray-800 sm:text-3xl">{{ number_format($activeLoansCount) }}</p>
                        <p class="mt-1 text-xs font-medium text-blue-600">Currently active</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Commodity Items Card -->
    <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-green-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
        <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-green-50 to-green-100 group-hover:opacity-100"></div>
        <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-green-100 rounded-full opacity-20"></div>
        <div class="relative p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center">
                    <div class="relative flex-shrink-0">
                        <div class="p-3 shadow-lg sm:p-4 bg-gradient-to-br from-green-400 to-green-500 rounded-2xl">
                            <svg class="w-5 h-5 text-white sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div class="absolute w-3 h-3 bg-green-500 rounded-full sm:w-4 sm:h-4 -top-1 -right-1 animate-pulse"></div>
                    </div>
                    <div class="mt-2 ml-3 sm:ml-4 sm:mt-0">
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase sm:text-sm">Commodity Items</p>
                        <p class="mt-1 text-2xl font-bold text-gray-800 sm:text-3xl">{{ number_format($commodityItemsCount) }}</p>
                        <p class="mt-1 text-xs font-medium text-green-600">Available for purchase</p>
                    </div>
                </div>
                <div class="mt-3 sm:mt-0 sm:ml-4">
                    <a href="{{ route('user.commodity.marketplace') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium text-white rounded-lg bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700">
                        Browse
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Feedback Card -->
    <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-orange-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
        <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-orange-50 to-orange-100 group-hover:opacity-100"></div>
        <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-orange-100 rounded-full opacity-20"></div>
        <div class="relative p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center">
                    <div class="relative flex-shrink-0">
                        <div class="p-3 shadow-lg sm:p-4 bg-gradient-to-br from-orange-400 to-orange-500 rounded-2xl">
                            <svg class="w-5 h-5 text-white sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <div class="absolute w-3 h-3 bg-orange-500 rounded-full sm:w-4 sm:h-4 -top-1 -right-1 animate-pulse"></div>
                    </div>
                    <div class="mt-2 ml-3 sm:ml-4 sm:mt-0">
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase sm:text-sm">Support Tickets</p>
                        <p class="mt-1 text-2xl font-bold text-gray-800 sm:text-3xl">{{ number_format($pendingFeedbackCount) }}</p>
                        <p class="mt-1 text-xs font-medium text-orange-600">Pending responses</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Recent Transactions Card -->
<div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
    <!-- Card Header -->
    <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Recent Transactions</h2>
                <p class="mt-1 text-sm text-gray-600">Your latest financial activities and transactions</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('user.transaction_report') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-all duration-200 border border-green-600 rounded-lg shadow-sm bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    View All
                </a>
            </div>
        </div>
    </div>
    <!-- Table Content -->
    <div class="p-2">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden border-b border-gray-200 shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date & Time</th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Type</th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Amount</th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Charges</th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Net Amount</th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @if(count($recentTransactions) > 0)
                    @foreach($recentTransactions as $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $transaction->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $transaction->created_at->format('H:i:s') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $typeColors = [
                                    'shares' => 'purple',
                                    'savings' => 'green',
                                    'loan' => 'blue',
                                    'commodity' => 'amber',
                                    'withdrawal' => 'red',
                                    'deposit' => 'green',
                                    'loan_disbursement' => 'blue',
                                    'loan_payment' => 'blue',
                                    'commodity_essential' => 'amber',
                                    'commodity_non_essential' => 'amber',
                                    'electronics' => 'orange',
                                    'entrance_fee' => 'indigo',
                                    'share_credit' => 'purple',
                                    'share_debit' => 'purple',
                                    'saving_credit' => 'green',
                                    'saving_debit' => 'green',
                                ];
                                $type = strtolower($transaction->type);
                                $color = $typeColors[$type] ?? 'gray';
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800 rounded-full">
                                {{ ucfirst($transaction->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                            ₦{{ number_format($transaction->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-red-600 whitespace-nowrap">
                            -₦{{ number_format($transaction->charges, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $transaction->net_amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $transaction->net_amount >= 0 ? '+' : '' }}₦{{ number_format($transaction->net_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($transaction->status === 'completed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Completed
                            </span>
                            @elseif($transaction->status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                            @elseif($transaction->status === 'failed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Failed
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ ucfirst($transaction->status) }}
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <h4 class="mt-2 text-sm font-medium text-gray-900">No transactions found</h4>
                            <p class="mt-1 text-sm text-gray-500">You don't have any recent transactions.</p>
                        </td>
                    </tr>
                @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @if ($recentTransactions->hasPages())
        <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="text-sm text-gray-700">
                Showing {{ $recentTransactions->firstItem() }} to {{ $recentTransactions->lastItem() }} of {{ $recentTransactions->total() }} transactions
            </div>
            <div class="flex space-x-1">
                {{-- Previous Page Link --}}
                @if ($recentTransactions->onFirstPage())
                    <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-not-allowed rounded-l-md">
                        Previous
                    </span>
                @else
                    <a href="{{ $recentTransactions->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">
                        Previous
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($recentTransactions->getUrlRange(1, $recentTransactions->lastPage()) as $page => $url)
                    @if ($page == $recentTransactions->currentPage())
                        <span class="px-3 py-2 text-sm font-medium text-blue-600 border border-blue-500 cursor-default bg-blue-50">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 hover:bg-gray-50">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($recentTransactions->hasMorePages())
                    <a href="{{ $recentTransactions->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">
                        Next
                    </a>
                @else
                    <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-not-allowed rounded-r-md">
                        Next
                    </span>
                @endif
            </div>
        </div>
    @endif

    <div class="px-6 py-3 text-right border-t border-gray-200 bg-gray-50">
        <a href="{{ route('user.transaction_report') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
            View all transactions →
        </a>
    </div>
</div>
@endsection