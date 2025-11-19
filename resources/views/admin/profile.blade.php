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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    Admin Profile
                </h1>
                <p class="mt-2 text-sm text-gray-600">Manage your account settings and preferences</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white transition-all duration-200 bg-green-600 border border-transparent rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

        <!-- Profile Overview Card -->
        <div class="relative mb-8 overflow-hidden transition-all duration-300 transform bg-white border border-gray-200 shadow-xl group rounded-2xl hover:shadow-2xl hover:-translate-y-1">
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-green-50 to-blue-50 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 w-32 h-32 -mt-16 -mr-16 bg-green-100 rounded-full opacity-20"></div>
            <div class="relative p-8">
                <div class="flex flex-col items-center gap-8 lg:flex-row">
                    <div class="flex-shrink-0">
                        <div class="relative">
                            <div class="flex items-center justify-center w-32 h-32 text-4xl font-bold text-white rounded-full shadow-2xl bg-gradient-to-br from-green-500 to-green-600">
                                {{ strtoupper(substr($admin->name, 0, 2)) }}
                            </div>
                            <div class="absolute w-8 h-8 bg-green-500 rounded-full -bottom-2 -right-2 animate-pulse"></div>
                        </div>
                    </div>
                    <div class="flex-1 text-center lg:text-left">
                        <h2 class="text-3xl font-bold text-gray-900">{{ $admin->name }}</h2>
                        <p class="mt-2 text-lg text-gray-600">{{ $admin->email }}</p>
                        <div class="mt-4">
                            <span class="inline-flex items-center px-4 py-2 text-sm font-semibold text-purple-800 bg-purple-100 rounded-full">
                                <div class="w-2 h-2 mr-2 bg-purple-500 rounded-full"></div>
                                {{ ucfirst($admin->role) }} Administrator
                            </span>
                        </div>
                        <div class="flex flex-wrap justify-center gap-3 mt-6 lg:justify-start">
                            <a href="{{ route('admin.profile.edit') }}"
                               class="inline-flex items-center px-6 py-3 text-sm font-medium text-white transition-all duration-200 transform border border-transparent rounded-lg shadow-lg bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Edit Profile
                            </a>
                            <a href="{{ route('admin.profile.change_password') }}"
                               class="inline-flex items-center px-6 py-3 text-sm font-medium text-gray-700 transition-all duration-200 transform bg-white border border-gray-300 rounded-lg shadow-lg hover:bg-gray-50 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Change Password
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Enhanced Profile Information Cards -->
    <div class="grid gap-6 mb-8 md:grid-cols-2 lg:grid-cols-3">
        <!-- Full Name Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-blue-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-blue-50 to-blue-100 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-blue-100 rounded-full opacity-20"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="p-4 shadow-lg bg-gradient-to-br from-blue-400 to-blue-500 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="absolute w-4 h-4 bg-blue-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Full Name</p>
                            <p class="mt-1 text-2xl font-bold text-gray-800">{{ $admin->name }}</p>
                            <p class="mt-1 text-xs font-medium text-blue-600">Administrator</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Address Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-green-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-green-50 to-green-100 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-green-100 rounded-full opacity-20"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="p-4 shadow-lg bg-gradient-to-br from-green-400 to-green-500 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="absolute w-4 h-4 bg-green-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Email Address</p>
                            <p class="mt-1 text-lg font-bold text-gray-800 break-all">{{ $admin->email }}</p>
                            <p class="mt-1 text-xs font-medium text-green-600">Primary contact</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Phone Number Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-teal-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-teal-50 to-teal-100 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-teal-100 rounded-full opacity-20"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="p-4 shadow-lg bg-gradient-to-br from-teal-400 to-teal-500 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div class="absolute w-4 h-4 bg-teal-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Phone Number</p>
                            <p class="mt-1 text-2xl font-bold text-gray-800">{{ $admin->phone ?? 'Not provided' }}</p>
                            <p class="mt-1 text-xs font-medium text-teal-600">Contact number</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-purple-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-purple-50 to-purple-100 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-purple-100 rounded-full opacity-20"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="p-4 shadow-lg bg-gradient-to-br from-purple-400 to-purple-500 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div class="absolute w-4 h-4 bg-purple-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Role</p>
                            <p class="mt-1 text-2xl font-bold text-gray-800">{{ ucfirst($admin->role) }}</p>
                            <p class="mt-1 text-xs font-medium text-purple-600">Administrator</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Created Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-indigo-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-indigo-50 to-indigo-100 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-indigo-100 rounded-full opacity-20"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="p-4 shadow-lg bg-gradient-to-br from-indigo-400 to-indigo-500 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="absolute w-4 h-4 bg-indigo-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Account Created</p>
                            <p class="mt-1 text-lg font-bold text-gray-800">{{ $admin->created_at->format('M j, Y') }}</p>
                            <p class="mt-1 text-xs font-medium text-indigo-600">{{ $admin->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Last Activity Card -->
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
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Last Activity</p>
                            <p class="mt-1 text-lg font-bold text-gray-800">{{ $admin->updated_at->format('M j, Y') }}</p>
                            <p class="mt-1 text-xs font-medium text-orange-600">{{ $admin->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section - Full Width -->
    <div class="mb-8">
        <!-- Recent Activity Card -->
        <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-2xl">
            <!-- Enhanced Card Header -->
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 via-blue-50 to-purple-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="p-3 shadow-lg bg-gradient-to-br from-green-500 to-green-600 rounded-2xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <div class="absolute w-4 h-4 bg-green-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-2xl font-bold text-gray-800">Recent Activity</h2>
                            <p class="text-sm text-gray-600">Your latest administrative actions and system interactions</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="inline-flex items-center px-4 py-2 text-sm font-semibold text-green-800 bg-green-100 rounded-full">
                            <div class="w-2 h-2 mr-2 bg-green-500 rounded-full animate-pulse"></div>
                            {{ $recentActivities->count() }} {{ $recentActivities->count() === 1 ? 'Action' : 'Actions' }}
                        </span>
                        <a href="{{ route('admin.dashboard') }}"
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 transition-colors duration-200 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            View Dashboard
                        </a>
                    </div>
                </div>
            </div>
                <div class="p-8">
                    @if($recentActivities->count() > 0)
                        <div class="space-y-6">
                            @foreach($recentActivities as $activity)
                                <div class="flex items-start p-4 transition-all duration-200 border border-gray-100 rounded-lg cursor-pointer hover:bg-gray-50 hover:shadow-md group"
                                    onclick="handleActivityClick('{{ $activity['type'] }}', '{{ $activity['description'] }}')">
                                    <div class="flex-shrink-0 mt-1">
                                        @php
                                            $colorClasses = [
                                                'blue' => 'text-blue-600 bg-blue-100 group-hover:bg-blue-200',
                                                'green' => 'text-green-600 bg-green-100 group-hover:bg-green-200',
                                                'purple' => 'text-purple-600 bg-purple-100 group-hover:bg-purple-200',
                                                'orange' => 'text-orange-600 bg-orange-100 group-hover:bg-orange-200',
                                                'red' => 'text-red-600 bg-red-100 group-hover:bg-red-200'
                                            ];
                                            $colorClass = $colorClasses[$activity['color']] ?? 'text-gray-600 bg-gray-100 group-hover:bg-gray-200';
                                        @endphp
                                        <div class="flex items-center justify-center w-12 h-12 {{ $colorClass }} rounded-full transition-colors duration-200">
                                            @if($activity['icon'] == 'upload')
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                </svg>
                                            @elseif($activity['icon'] == 'package')
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                            @elseif($activity['icon'] == 'user-plus')
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                                </svg>
                                            @elseif($activity['icon'] == 'credit-card')
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                </svg>
                                            @elseif($activity['icon'] == 'dollar-sign')
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @elseif($activity['icon'] == 'arrow-down')
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                                </svg>
                                            @elseif($activity['icon'] == 'check')
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @elseif($activity['icon'] == 'exclamation')
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                                </svg>
                                            @else
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-1 ml-6">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-base font-semibold text-gray-900">{{ $activity['description'] }}</p>
                                                <p class="mt-1 text-sm text-gray-500">{{ $activity['time']->diffForHumans() }}</p>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                @if($activity['type'] == 'upload')
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">
                                                        <div class="w-2 h-2 mr-2 bg-blue-400 rounded-full"></div>
                                                        MAB Upload
                                                    </span>
                                                @elseif($activity['type'] == 'commodity')
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                                        <div class="w-2 h-2 mr-2 bg-green-400 rounded-full"></div>
                                                        Commodity
                                                    </span>
                                                @elseif($activity['type'] == 'user')
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-purple-800 bg-purple-100 rounded-full">
                                                        <div class="w-2 h-2 mr-2 bg-purple-400 rounded-full"></div>
                                                        User Registration
                                                    </span>
                                                @elseif($activity['type'] == 'transaction')
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">
                                                        <div class="w-2 h-2 mr-2 bg-blue-400 rounded-full"></div>
                                                        Manual Transaction
                                                    </span>
                                                @elseif($activity['type'] == 'loan')
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                                        <div class="w-2 h-2 mr-2 bg-green-400 rounded-full"></div>
                                                        Loan
                                                    </span>
                                                @elseif($activity['type'] == 'withdrawal')
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-orange-800 bg-orange-100 rounded-full">
                                                        <div class="w-2 h-2 mr-2 bg-orange-400 rounded-full"></div>
                                                        Withdrawal
                                                    </span>
                                                @elseif($activity['type'] == 'system')
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full">
                                                        <div class="w-2 h-2 mr-2 bg-gray-400 rounded-full"></div>
                                                        System
                                                    </span>
                                                @endif
                                                <svg class="w-5 h-5 text-gray-400 transition-colors duration-200 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        @endforeach
                        </div>
                    @else
                        <div class="py-16 text-center">
                            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-6 bg-gray-100 rounded-full">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <h3 class="mb-2 text-lg font-medium text-gray-900">No Recent Activity</h3>
                            <p class="mb-6 text-gray-500">Your administrative actions will appear here once you start using the system.</p>
                            <a href="{{ route('admin.dashboard') }}"
                            class="inline-flex items-center px-6 py-3 text-sm font-medium text-white transition-all duration-200 border border-transparent rounded-lg shadow-lg bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Go to Dashboard
                            </a>
                        </div>
                    @endif

                    @if($recentActivities->count() > 0)
                        <div class="pt-6 mt-8 border-t border-gray-200">
                            <div class="flex justify-center">
                                <a href="{{ route('admin.dashboard') }}"
                                class="inline-flex items-center px-6 py-3 text-sm font-medium text-green-700 transition-all duration-200 border border-green-200 rounded-lg shadow-sm bg-green-50 hover:bg-green-100 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    View Full Dashboard
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-center mt-6">
                        <button onclick="refreshActivity()"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 transition-colors duration-200 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function handleActivityClick(type, description) {
        // Handle different activity types
        switch(type) {
            case 'upload':
                // Navigate to bulk updates page
                window.location.href = '{{ route("admin.bulk_updates") }}';
                break;
            case 'commodity':
                // Navigate to commodities management
                window.location.href = '{{ route("admin.commodities.index") }}';
                break;
            case 'user':
                // Navigate to users page
                window.location.href = '{{ route("admin.users.all") }}';
                break;
            case 'transaction':
                // Navigate to manual transactions page
                window.location.href = '{{ route("admin.manual_transactions.index") }}';
                break;
            case 'loan':
                // Navigate to loans page
                window.location.href = '{{ route("admin.loans.index") }}';
                break;
            case 'withdrawal':
                // Navigate to saving withdrawals page
                window.location.href = '{{ route("admin.saving_withdrawals.index") }}';
                break;
            default:
                // Show general activity details
                showActivityDetails('Activity Details', description, 'Click on the Dashboard button to see more detailed activity information.');
        }
    }

    function showActivityDetails(title, description, message) {
        // Create a simple modal-like alert
        const alertMessage = `${title}\n\n${description}\n\n${message}`;
        alert(alertMessage);
    }

    function refreshActivity() {
        // Refresh the current page to get updated activity
        window.location.reload();
    }

    // Add some visual feedback for interactive elements
    document.addEventListener('DOMContentLoaded', function() {
        // Add hover effects to activity items
        const activityItems = document.querySelectorAll('[onclick^="handleActivityClick"]');
        activityItems.forEach(item => {
            item.style.cursor = 'pointer';

            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(4px)';
                this.style.transition = 'transform 0.2s ease';
            });

            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });
    });
</script>
@endsection
