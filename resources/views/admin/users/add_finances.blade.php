@extends('layouts.admin')

@section('title', 'Add Finances - User Management')

@section('content')
<div class="container max-w-6xl mx-auto">
    <!-- Header Section -->
    <div class="p-6 mb-8 border border-gray-200 shadow-lg bg-gradient-to-r from-green-50 to-blue-50 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center mb-4">
                    <div class="p-3 mr-4 bg-green-100 rounded-lg">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
                        <p class="mt-2 text-gray-600">Create new users, manage existing member finances, or bulk upload multiple users</p>
                    </div>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.users.all') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    View All Users
                </a>
            </div>
        </div>
        <!-- Enhanced Tab Navigation -->
        <div class="inline-flex gap-2 p-1 mt-2 bg-gray-100 rounded-lg shadow-inner" role="group">
            <a href="{{ route('admin.users.add') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-transparent border-0 rounded-md user-type-btn hover:bg-white hover:text-green-700 focus:z-10 focus:ring-2 focus:ring-green-500 transition-all duration-200">
                <svg class="inline w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                New User
            </a>
            <a href="{{ route('admin.users.add_finances') }}" class="px-4 py-2.5 text-sm font-medium text-white bg-green-600 border-0 rounded-md user-type-btn hover:bg-green-700 focus:z-10 focus:ring-2 focus:ring-green-500 transition-all duration-200 shadow-sm">
                <svg class="inline w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
                Add Finances
            </a>
            <a href="{{ route('admin.users.bulk_upload') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-transparent border-0 rounded-md user-type-btn hover:bg-white hover:text-green-700 focus:z-10 focus:ring-2 focus:ring-green-500 transition-all duration-200">
                <svg class="inline w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                </svg>
                Bulk Upload
            </a>
        </div>
    </div>

    <!-- Add Finances Form -->
    <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
        <!-- Form Header -->
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-green-100">
            <div class="flex items-center">
                <div class="p-2 mr-4 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Create New User with Financial Records</h2>
                    <p class="mt-1 text-sm text-gray-600">Create a new cooperative member with their initial financial information</p>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <div class="p-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="font-medium">Please fix the following errors:</p>
                            <ul class="mt-1 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.users.store_finances') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Basic Information Section -->
                <div class="space-y-6">
                    <div class="pb-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Basic Information</h3>
                        <p class="mt-1 text-sm text-gray-600">Essential details for the new user account</p>
                    </div>

                    <!-- Name and Member Number -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200 @error('name') border-red-500 bg-red-50 @enderror" placeholder="Enter full name" required>
                            @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="member_number" class="block text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a1.994 1.994 0 01-1.414.586H7a4 4 0 01-4-4V7a4 4 0 014-4z"></path>
                                </svg>
                                Member Number
                            </label>
                            <input type="text" id="member_number" name="member_number" value="{{ old('member_number') }}" pattern="[Pp]/[Ss]{2}/[0-9]{3}" title="Format should be P/SS/759 (case insensitive)" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200 font-mono @error('member_number') border-red-500 bg-red-50 @enderror" placeholder="P/SS/759">
                            @error('member_number')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500">Format: P/SS/123 (case insensitive) - Optional if email is provided</p>
                        </div>
                    </div>

                    <!-- Email and Department -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                                Email Address
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200 @error('email') border-red-500 bg-red-50 @enderror" placeholder="user@example.com (optional)">
                            @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500">Optional if member number is provided - User must have either email or member number</p>
                        </div>

                        <div class="space-y-2">
                            <label for="department_id" class="block text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Department <span class="text-red-500">*</span>
                            </label>
                            <select id="department_id" name="department_id" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm @error('department_id') border-red-500 bg-red-50 @enderror" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="role" class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                User Role <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="role" name="role" class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm bg-white cursor-pointer transition-all duration-200 @error('role') border-red-500 bg-red-50 @enderror" required>
                                    <option value="">Select a role</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="member" {{ old('role', 'member') == 'member' ? 'selected' : '' }}>Member</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('role')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Account Settings Section -->
                <div class="space-y-6">
                    <div class="pb-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Account Settings</h3>
                        <p class="mt-1 text-sm text-gray-600">Security and membership details</p>
                    </div>

                    <!-- Password Fields -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Password Field -->
                        <div class="space-y-2">
                            <label for="password" class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="password" name="password" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200 @error('password') border-red-500 bg-red-50 @enderror" placeholder="Enter password" required>
                                @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <p class="text-xs text-gray-500">Minimum 8 characters with numbers and symbols</p>
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="space-y-2">
                            <label for="confirm_password" class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="confirm_password" name="password_confirmation" class="block w-full px-4 py-3 placeholder-gray-400 transition-all duration-200 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Confirm password" required>
                        </div>
                    </div>

                    <!-- Membership Details -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="joined_at" class="block text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Join Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="joined_at" name="joined_at" value="{{ old('joined_at', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm @error('joined_at') border-red-500 bg-red-50 @enderror" required>
                            @error('joined_at')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                Entrance Fee Status <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center space-x-6">
                                <label class="flex items-center">
                                    <input type="radio" name="entrance_fee_paid" value="1" {{ old('entrance_fee_paid', '1') == '1' ? 'checked' : '' }} class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                                    <span class="ml-2 text-sm text-gray-700">Paid</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="entrance_fee_paid" value="0" {{ old('entrance_fee_paid') == '0' ? 'checked' : '' }} class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                                    <span class="ml-2 text-sm text-gray-700">Not Paid</span>
                                </label>
                            </div>
                            @error('entrance_fee_paid')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Financial Information Section -->
                <div class="space-y-6">
                    <div class="pb-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Financial Information</h3>
                        <p class="mt-1 text-sm text-gray-600">Add initial financial records for this user</p>
                    </div>

                    <!-- Financial Fields Row 1 -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div class="space-y-2">
                            <label for="shares" class="block text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Shares Amount
                            </label>
                            <input type="number" id="shares" name="shares" value="{{ old('shares') }}" step="0.01" min="0" max="10000" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200 @error('shares') border-red-500 bg-red-50 @enderror" placeholder="0.00">
                            <p class="mt-1 text-xs text-gray-500">Maximum share contribution: ₦10,000</p>
                            @error('shares')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="savings" class="block text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Savings Amount
                            </label>
                            <input type="number" id="savings" name="savings" value="{{ old('savings') }}" step="0.01" min="0" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200 @error('savings') border-red-500 bg-red-50 @enderror" placeholder="0.00">
                            @error('savings')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="loan_amount" class="block text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                Loan Amount
                            </label>
                            <input type="number" id="loan_amount" name="loan_amount" value="{{ old('loan_amount') }}" step="0.01" min="0" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200 @error('loan_amount') border-red-500 bg-red-50 @enderror" placeholder="0.00">
                            @error('loan_amount')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Financial Fields Row 2 -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div class="space-y-2">
                            <label for="essential_commodity_amount" class="block text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Essential Commodity
                            </label>
                            <input type="number" id="essential_commodity_amount" name="essential_commodity_amount" value="{{ old('essential_commodity_amount') }}" step="0.01" min="0" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200 @error('essential_commodity_amount') border-red-500 bg-red-50 @enderror" placeholder="0.00">
                            @error('essential_commodity_amount')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="non_essential_commodity_amount" class="block text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Non-Essential Commodity
                            </label>
                            <input type="number" id="non_essential_commodity_amount" name="non_essential_commodity_amount" value="{{ old('non_essential_commodity_amount') }}" step="0.01" min="0" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200 @error('non_essential_commodity_amount') border-red-500 bg-red-50 @enderror" placeholder="0.00">
                            @error('non_essential_commodity_amount')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="electronics_amount" class="block text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Electronics Amount
                            </label>
                            <input type="number" id="electronics_amount" name="electronics_amount" value="{{ old('electronics_amount') }}" step="0.01" min="0" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200 @error('electronics_amount') border-red-500 bg-red-50 @enderror" placeholder="0.00">
                            @error('electronics_amount')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Financial Fields Row 3 -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="loan_interest_amount" class="block text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                Loan Interest Amount
                            </label>
                            <input type="number" id="loan_interest_amount" name="loan_interest_amount" value="{{ old('loan_interest_amount') }}" step="0.01" min="0" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200 @error('loan_interest_amount') border-red-500 bg-red-50 @enderror" placeholder="0.00">
                            <p class="mt-1 text-xs text-gray-500">Interest payments made separately from loan principal</p>
                            @error('loan_interest_amount')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-6 border-t border-gray-200">
                    <button type="submit" class="px-8 py-3 text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium text-sm transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Financial Records
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


