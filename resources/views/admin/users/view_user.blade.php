@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Enhanced Header Section -->
    <div class="p-6 mb-8 border border-gray-200 shadow-lg bg-gradient-to-r from-green-50 to-blue-50 rounded-xl">
        <div class="flex flex-col items-center justify-between lg:flex-row">
            <div class="mb-6 lg:mb-0">
                <h1 class="flex items-center text-2xl font-bold text-gray-800">
                    <div class="p-2 mr-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    User Profile
                </h1>
                <p class="mt-2 text-sm text-gray-600">View and manage user information and financial records</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.users.all') }}"
                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to All Users
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-1">
            <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
                <!-- Card Header -->
                <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                    <div class="flex items-center">
                        <div class="p-2 mr-4 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">User Information</h2>
                            <p class="mt-1 text-sm text-gray-600">Member profile details</p>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-8">
                    <div class="flex flex-col items-center mb-6">
                        <div class="flex items-center justify-center w-24 h-24 mb-4 text-3xl font-semibold text-green-800 bg-green-100 rounded-full">
                            {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(strpos($user->name, ' ') !== false ? substr($user->name, strpos($user->name, ' ') + 1, 1) : '', 0, 1)) }}
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h4>
                        <p class="text-sm text-gray-500">Member since {{ $user->created_at->format('F d, Y') }}</p>
                        <div class="mt-2">
                            @if($user->status === 'active')
                            <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full"> Active </span>
                            @elseif($user->status === 'pending')
                            <span class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full"> Pending </span>
                            @elseif($user->status === 'verified')
                            <span class="px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full"> Verified </span>
                            @elseif($user->status === 'rejected')
                            <span class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full"> Rejected </span>
                            @else
                            <span class="px-2 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full"> {{ ucfirst($user->status) }} </span>
                            @endif
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <h4 class="mb-1 text-sm font-medium text-gray-500">Member ID</h4>
                            <p class="text-sm font-medium text-gray-900">{{ $user->member_number ?? 'Not assigned' }}</p>
                        </div>
                        <div>
                            <h4 class="mb-1 text-sm font-medium text-gray-500">Email</h4>
                            <p class="text-sm font-medium text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div>
                            <h4 class="mb-1 text-sm font-medium text-gray-500">Email Verified</h4>
                            <p class="text-sm font-medium text-gray-900">
                                @if($user->email_verified_at)
                                <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full"> Yes </span>
                                @else
                                <span class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full"> No </span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <h4 class="mb-1 text-sm font-medium text-gray-500">Department</h4>
                            <p class="text-sm font-medium text-gray-900">{{ $user->department->title ?? 'Not assigned' }}</p>
                        </div>
                        <div>
                            <h4 class="mb-1 text-sm font-medium text-gray-500">Role</h4>
                            <p class="text-sm font-medium text-gray-900">
                                @if($user->role === 'admin')
                                <span class="px-2 py-1 text-xs font-medium text-purple-800 bg-purple-100 rounded-full"> Admin </span>
                                @else
                                <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full"> Member </span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="pt-6 mt-6 border-t border-gray-200">
                        <div class="flex flex-col space-y-3">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Edit Profile
                            </a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete User
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            @if($user->role !== 'admin')
            <div class="mb-6 overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
                <!-- Card Header -->
                <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                    <div class="flex items-center">
                        <div class="p-2 mr-4 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Financial Summary</h2>
                            <p class="mt-1 text-sm text-gray-600">Current account balances</p>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-8">
                        <!-- Entrance Fee Status -->
                        <div class="mb-4">
                            @if(isset($entrancePaid) && $entrancePaid)
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-800 bg-green-100 border border-green-200 rounded-full">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Entrance fee: Paid
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-amber-800 bg-amber-100 border border-amber-200 rounded-full">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z"/></svg>
                                    Entrance fee: Not paid
                                </span>
                            @endif
                        </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-medium text-gray-500">Savings Balance</h4>
                                <svg class="w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-gray-900">₦{{ number_format($savingsBalance, 2) }}</div>
                        </div>
                        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-medium text-gray-500">Shares Balance</h4>
                                <svg class="w-5 h-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-gray-900">₦{{ number_format($sharesBalance, 2) }}</div>
                        </div>
                        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-medium text-gray-500">Loan Balance</h4>
                                <svg class="w-5 h-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="text-2xl font-bold {{ $loanBalance > 0 ? 'text-red-600' : 'text-gray-900' }}">₦{{ number_format($loanBalance, 2) }}</div>
                        </div>
                        <!-- Loan Interest Paid -->
                        <div class="p-4 bg-white border border-orange-200 rounded-lg shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-medium text-gray-500">Loan Interest Paid</h4>
                                <svg class="w-5 h-5 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-orange-600">₦{{ number_format($loanInterestPaid ?? 0, 2) }}</div>
                        </div>
                        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-medium text-gray-500">Commodity Balances</h4>
                                <svg class="w-5 h-5 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-500">Essential</span>
                                    <span class="text-lg font-bold {{ ($essentialBalance ?? 0) > 0 ? 'text-red-600' : 'text-gray-900' }}">₦{{ number_format($essentialBalance ?? 0, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-500">Non-essential</span>
                                    <span class="text-lg font-bold {{ ($nonEssentialBalance ?? 0) > 0 ? 'text-red-600' : 'text-gray-900' }}">₦{{ number_format($nonEssentialBalance ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-medium text-gray-500">Electronics Balance</h4>
                                <svg class="w-5 h-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1l-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="text-2xl font-bold {{ $electronicsBalance > 0 ? 'text-red-600' : 'text-gray-900' }}">₦{{ number_format($electronicsBalance, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
                <!-- Card Header -->
                <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-purple-100">
                    <div class="flex items-center">
                        <div class="p-2 mr-4 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Transaction History</h2>
                            <p class="mt-1 text-sm text-gray-600">Recent account activity</p>
                        </div>
                    </div>
                </div>

                <!-- Tab Navigation -->
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button class="px-6 py-3 text-sm font-medium text-green-600 border-b-2 border-green-500 tab-button active" data-tab="savings"> Savings </button>
                        <button class="px-6 py-3 text-sm font-medium text-gray-500 border-b-2 border-transparent tab-button hover:text-gray-700 hover:border-gray-300" data-tab="shares"> Shares </button>
                        <button class="px-6 py-3 text-sm font-medium text-gray-500 border-b-2 border-transparent tab-button hover:text-gray-700 hover:border-gray-300" data-tab="loans"> Loans </button>
                        <button class="px-6 py-3 text-sm font-medium text-gray-500 border-b-2 border-transparent tab-button hover:text-gray-700 hover:border-gray-300" data-tab="commodities"> Commodities </button>
                    </nav>
                </div>
                <div class="p-6">
                    <!-- Savings Tab -->
                    <div id="savings-tab" class="tab-content">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-medium text-gray-900">Savings Transactions</h4>
                        </div>
                        @if(count($savingsTransactions) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Date </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Amount </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Type </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Description </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($savingsTransactions as $transaction)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap"> {{ $transaction->date }} </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ $transaction->amount_class }} font-medium"> {{ $transaction->amount_formatted }} </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->type_class }}"> {{ $transaction->type }} </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap"> {{ $transaction->description }} </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="p-4 text-center rounded-lg bg-gray-50">
                            <p class="text-gray-500">No savings transactions found.</p>
                        </div>
                        @endif
                    </div>
                    <!-- Shares Tab -->
                    <div id="shares-tab" class="tab-content" style="display: none;">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-medium text-gray-900">Shares Transactions</h4>
                        </div>
                        @if(count($sharesTransactions) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Date </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Amount </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Type </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Description </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($sharesTransactions as $transaction)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap"> {{ $transaction->date }} </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ $transaction->amount_class }} font-medium"> {{ $transaction->amount_formatted }} </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->type_class }}"> {{ $transaction->type }} </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap"> {{ $transaction->description }} </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="p-4 text-center rounded-lg bg-gray-50">
                            <p class="text-gray-500">No shares transactions found.</p>
                        </div>
                        @endif
                    </div>

                    <!-- Loans Tab -->
                    <div id="loans-tab" class="tab-content" style="display: none;">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-medium text-gray-900">Loan Transactions</h4>
                        </div>
                        @if(count($loansTransactions) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Date </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Amount </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Type </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Description </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($loansTransactions as $transaction)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap"> {{ $transaction->date }} </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ $transaction->amount_class }} font-medium"> {{ $transaction->amount_formatted }} </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->type_class }}"> {{ $transaction->type }} </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap"> {{ $transaction->description }} </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="p-4 text-center rounded-lg bg-gray-50">
                            <p class="text-gray-500">No loan transactions found.</p>
                        </div>
                        @endif
                    </div>

                    <!-- Commodities Tab -->
                    <div id="commodities-tab" class="tab-content" style="display: none;">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-medium text-gray-900">Commodity Transactions</h4>
                        </div>
                        @if(count($commoditiesTransactions) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Date </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Amount </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Type </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Description </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($commoditiesTransactions as $transaction)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap"> {{ $transaction->date }} </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ $transaction->amount_class }} font-medium"> {{ $transaction->amount_formatted }} </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->type_class }}"> {{ $transaction->type }} </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap"> {{ $transaction->description }} </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="p-4 text-center rounded-lg bg-gray-50">
                            <p class="text-gray-500">No commodity transactions found.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @else
            <div class="overflow-hidden bg-white rounded-lg shadow-md">
                <div class="p-6 text-center">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <h3 class="mb-2 text-lg font-medium text-gray-900">Admin User</h3>
                    <p class="text-gray-500">Admin users do not have financial records as they are not members of the cooperative.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Tab functionality
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                tabButtons.forEach(btn => {
                    btn.classList.remove('active');
                    btn.classList.remove('border-green-500');
                    btn.classList.remove('text-green-600');
                    btn.classList.add('border-transparent');
                    btn.classList.add('text-gray-500');
                });

                // Add active class to clicked button
                button.classList.add('active');
                button.classList.add('border-green-500');
                button.classList.add('text-green-600');
                button.classList.remove('border-transparent');
                button.classList.remove('text-gray-500');

                // Hide all tab contents
                tabContents.forEach(content => {
                    content.style.display = 'none';
                });

                // Show the selected tab content
                const tabName = button.getAttribute('data-tab');
                document.getElementById(`${tabName}-tab`).style.display = 'block';
            });
        });
    });
</script>
@endsection