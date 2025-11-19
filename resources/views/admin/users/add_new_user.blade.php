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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    User Management
                </h1>
                <p class="mt-2 text-sm text-gray-600">Create new users, manage existing member finances, or bulk upload multiple users</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.users.all') }}" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white transition-all duration-200 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 border border-transparent rounded-lg shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    View All Users
                </a>

                
            </div>
        </div>
        <!-- Enhanced Tab Navigation -->
        <div class="inline-flex gap-2 p-1 mt-2 bg-gray-100 rounded-lg shadow-inner" role="group">
            <a href="{{ route('admin.users.add') }}" class="px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 border-0 rounded-md user-type-btn focus:z-10 focus:ring-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="inline w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                New User
            </a>
            <a href="{{ route('admin.users.add_finances') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-transparent border-0 rounded-md user-type-btn hover:bg-white hover:text-green-700 focus:z-10 focus:ring-2 focus:ring-green-500 transition-all duration-200">
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



    <!-- New User Form - Basic User Creation Only -->
    <div id="newUserForm" class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
        <!-- Form Header -->
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
            <div class="flex items-center">
                <div class="p-2 mr-4 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Create New User</h2>
                    <p class="mt-1 text-sm text-gray-600">Add a new member to the cooperative system with basic account information</p>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <div class="p-8">
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="user_type" id="user_type" value="{{ old('user_type', 'new') }}">

                @if ($errors->any())
                <div class="p-4 mb-6 text-red-700 border border-red-200 rounded-lg bg-red-50" role="alert">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="font-semibold">Please fix the following errors:</p>
                    </div>
                    <ul class="list-disc ml-7">
                        @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Bulk Upload Errors -->
                @if (session('bulk_errors'))
                <div class="p-4 mb-6 text-red-700 border border-red-200 rounded-lg bg-red-50" role="alert">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="font-semibold">Bulk Upload Errors:</p>
                    </div>
                    <ul class="list-disc ml-7">
                        @foreach (session('bulk_errors') as $error)
                        <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Basic Information Section -->
                <div class="space-y-6">
                    <div class="pb-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Basic Information</h3>
                        <p class="mt-1 text-sm text-gray-600">Essential details for the new user account</p>
                    </div>

                    <!-- Name Field -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="name" class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" id="name" name="name" value="{{ old('name') }}" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200 @error('name') border-red-500 bg-red-50 @enderror" placeholder="Enter full name" required>
                                @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Member Number Field -->
                        <div class="space-y-2">
                            <label for="member_number" class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                                Member Number <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" id="member_number" name="member_number" value="{{ old('member_number') }}" pattern="[Pp]/[Ss]{2}/[0-9]{3}" title="Format should be P/SS/759 (case insensitive)" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200 font-mono @error('member_number') border-red-500 bg-red-50 @enderror" placeholder="P/SS/759" required>
                                @error('member_number')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <p class="text-xs text-gray-500">Format: P/SS/123 (case insensitive)</p>
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="flex items-center text-sm font-semibold text-gray-700">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Email Address <span class="text-xs text-gray-500">(Optional)</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200 @error('email') border-red-500 bg-red-50 @enderror" placeholder="user@example.com (optional)">
                        @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500">If not provided, a default email will be generated</p>
                    </div>

                    <!-- Department and Role Fields -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Department Field -->
                        <div class="space-y-2">
                            <label for="department" class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Department <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="department" name="department_id" class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm bg-white cursor-pointer transition-all duration-200 @error('department_id') border-red-500 bg-red-50 @enderror" required>
                                    <option value="">Select a department</option>
                                    @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->title }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                                @error('department_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Role Field -->
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
                        <!-- Join Date Field -->
                        <div class="space-y-2">
                            <label for="joined_at" class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Join Date <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="date" id="joined_at" name="joined_at" value="{{ old('joined_at', date('Y-m-d')) }}" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200 @error('joined_at') border-red-500 bg-red-50 @enderror" required>
                                @error('joined_at')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <p class="text-xs text-gray-500">Date when the member joined the cooperative</p>
                        </div>

                        <!-- Entrance Fee Status -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Entrance Fee Status
                            </label>
                            <div class="flex items-center p-3 border border-gray-300 rounded-lg bg-gray-50">
                                <input type="checkbox" id="entrance_fee_paid" name="entrance_fee_paid" value="1" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" {{ old('entrance_fee_paid', true) ? 'checked' : '' }}>
                                <label for="entrance_fee_paid" class="ml-3 text-sm font-medium text-gray-700">Entrance Fee Paid</label>
                            </div>
                            <p class="text-xs text-gray-500">Required for loan eligibility (6+ months membership + entrance fee)</p>
                        </div>
                    </div>
                </div>

                <!-- Information Note -->
                <div class="p-4 border border-blue-200 rounded-lg bg-blue-50">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mr-3 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-800">New User Creation</h4>
                            <p class="mt-1 text-sm text-blue-700">This form creates a basic user account with no financial records. To add financial information (loans, savings, shares, etc.) to existing users, use the "Add Finances" tab above.</p>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Submit Button -->
                <div class="flex items-center justify-between pt-8 mt-8 border-t border-gray-200">
                    <a href="{{ route('admin.users.all') }}" class="inline-flex items-center px-6 py-3 text-sm font-medium text-white transition-all duration-200 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 border border-transparent rounded-lg shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-8 py-3 text-sm font-semibold text-white transition-all duration-200 transform border border-transparent rounded-lg shadow-lg bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Create New User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

