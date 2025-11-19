<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ACICS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    @stack('head')
</head>
<body class="min-h-screen bg-gray-200">
    <header>
        <nav class="fixed z-50 w-full bg-white shadow-md">
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex flex-row-reverse items-center gap-3">
                        <div class="flex items-center flex-shrink-0 ml-2 sm:ml-0">
                            <a href="#" class="font-semibold text-green-900">
                                <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="w-auto h-8" />
                            </a>
                        </div>
                        <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center justify-center p-2 text-gray-500 rounded-md hover:text-green-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500 sm:hidden">
                            <span class="sr-only">Open sidebar</span>
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex items-center space-x-2">
                        <div class="relative">
                            <button id="adminNotificationDropdownButton" class="text-gray-600 hover:text-green-600 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span id="adminNotificationBadge" class="absolute top-0 right-0 hidden w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>

                            <div id="adminNotificationDropdown" class="absolute right-0 z-50 hidden mt-2 transition duration-200 ease-out origin-top-right transform scale-95 bg-white rounded-md shadow-lg opacity-0 w-80 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                <div class="flex items-center justify-between px-4 py-2 border-b border-gray-100">
                                    <h3 class="text-sm font-semibold text-gray-900">Admin Notifications</h3>
                                    <button id="adminMarkAllAsReadBtn" class="text-xs text-green-600 hover:text-green-800">
                                        Mark all as read
                                    </button>
                                </div>
                                <div class="overflow-y-auto max-h-60" id="adminNotificationList">
                                    <div class="py-8 text-sm text-center text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p>No notifications yet</p>
                                    </div>
                                </div>
                                <div class="px-4 py-2 text-center border-t border-gray-100">
                                    <a href="#" class="text-xs text-gray-500 hover:text-gray-700">
                                        View all notifications
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <button id="dropdownToggle" class="flex items-center focus:outline-none">
                                <div class="flex items-center justify-center w-8 h-8 text-green-800 bg-green-100 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div id="dropdownMenu" class="absolute right-0 z-50 hidden w-48 py-1 mt-2 transition-all duration-200 ease-out origin-top-right transform scale-95 bg-white rounded-md shadow-lg opacity-0 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your Profile</a>
                                <form action="{{ route('logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="block w-full px-4 py-2 text-sm text-left text-gray-700 border-t border-gray-100 hover:bg-gray-100">Sign out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <aside id="default-sidebar" class="fixed custom-scroll text-xs top-0 left-0 z-40 mt-[3.8rem] sm:mt-[3rem] md:mt-[3.5rem] w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
            <div class="h-full px-3 py-4 overflow-y-auto bg-white shadow-md shadow-slate-300">
                <div class="px-2 mb-4">
                    <div class="p-3 border border-gray-100 rounded-lg shadow-sm bg-gradient-to-r from-green-50 to-blue-50">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-10 h-10 font-bold text-green-600 bg-green-100 rounded-full">
                                    AH
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">Asfepilcics Admin</p>
                                <p class="text-xs text-gray-500 truncate">Super Administrator</p>
                            </div>
                        </div>
                        <div class="flex justify-center mt-2">
                            <span class="inline-flex text-center items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                ADMIN NAVIGATION
                            </span>
                        </div>
                    </div>
                </div>

                <ul class="pb-12 space-y-1 font-medium">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 text-gray-900 transition-colors rounded-lg hover:bg-gray-100 group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span class="ms-3">Dashboard</span>
                        </a>
                    </li>

                    <li class="dropdown">
                        <button class="flex items-center w-full p-2 text-gray-900 transition-colors rounded-lg dropdown-toggle hover:bg-gray-100 group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span class="flex-1 text-left ms-3 whitespace-nowrap">Users</span>
                            <svg class="w-3 h-3 dropdown-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>
                        <ul class="hidden py-2 space-y-1 dropdown-menu pl-11">
                            <li>
                                <a href="{{ route('admin.users.add') }}" class="flex items-center gap-2 p-2 text-sm text-gray-600 transition-colors rounded-lg hover:bg-gray-100 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Add New User</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.all') }}" class="flex items-center gap-2 p-2 text-sm text-gray-600 transition-colors rounded-lg hover:bg-gray-100 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75a2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                    </svg>
                                    <span>All Users</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.pending-memberships') }}" class="flex items-center gap-2 p-2 text-sm text-gray-600 transition-colors rounded-lg hover:bg-gray-100 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                    <span>Pending Memberships</span>
                                    @if(isset($pendingMembershipsCount) && $pendingMembershipsCount > 0)
                                        <span class="inline-flex items-center justify-center w-5 h-5 ml-2 text-xs font-semibold text-white bg-red-500 rounded-full">{{ $pendingMembershipsCount }}</span>
                                    @endif
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <button class="flex items-center w-full p-2 text-gray-900 transition-colors rounded-lg dropdown-toggle hover:bg-gray-100 group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="flex-1 text-left ms-3 whitespace-nowrap">Departments</span>
                            <svg class="w-3 h-3 dropdown-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>
                        <ul class="hidden py-2 space-y-1 dropdown-menu pl-11">
                            <li>
                                <a href="{{ route('admin.departments.add') }}" class="flex items-center gap-2 p-2 text-sm text-gray-600 transition-colors rounded-lg hover:bg-gray-100 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Add Department</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.departments.all') }}" class="flex items-center gap-2 p-2 text-sm text-gray-600 transition-colors rounded-lg hover:bg-gray-100 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75a2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                    </svg>
                                    <span>All Departments</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <button class="flex items-center w-full p-2 text-gray-900 transition-colors rounded-lg dropdown-toggle hover:bg-gray-100 group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="flex-1 text-left ms-3 whitespace-nowrap">Loan Management</span>
                            <svg class="w-3 h-3 dropdown-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>
                        <ul class="hidden py-2 space-y-1 dropdown-menu pl-11">
                            <li>
                                <a href="{{ route('admin.loans.approval') }}" class="flex items-center p-2 text-sm text-gray-600 transition-colors rounded-lg hover:bg-gray-100 group">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Loan Approval</span>
                                    @if(isset($pendingLoansCount) && $pendingLoansCount > 0)
                                        <span class="inline-flex items-center justify-center w-5 h-5 ml-2 text-xs font-semibold text-white bg-red-500 rounded-full">{{ $pendingLoansCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.loans.index') }}" class="flex items-center p-2 text-sm text-gray-600 transition-colors rounded-lg hover:bg-gray-100 group">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span>All Loans</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.loans.calculator') }}" class="flex items-center gap-2 p-2 text-sm text-gray-600 transition-colors rounded-lg hover:bg-gray-100 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V13.5Zm0 2.25h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V18Zm2.498-6.75h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V13.5Zm0 2.25h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V18Zm2.504-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5Zm0 2.25h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V18Zm2.498-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5ZM8.25 6h7.5v2.25h-7.5V6ZM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 0 0 2.25 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0 0 12 2.25Z" />
                                    </svg>
                                    <span>Loan Calculator</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.manual_transactions.create') }}" class="flex items-center p-2 text-sm text-gray-600 transition-colors rounded-lg hover:bg-gray-100 group">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <span>Create Manual Transaction</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <button class="flex items-center w-full p-2 text-gray-900 transition-colors rounded-lg dropdown-toggle hover:bg-gray-100 group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span class="flex-1 text-left ms-3 whitespace-nowrap">Commodity Management</span>
                            <svg class="w-3 h-3 dropdown-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>
                        <ul class="hidden py-2 space-y-1 dropdown-menu pl-11">
                            <li>
                                <a href="{{ route('admin.commodities.create') }}" class="flex items-center p-2 text-sm text-gray-600 transition-colors rounded-lg hover:bg-gray-100 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="mr-2 size-4">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />
                                    </svg>
                                    Add New Commodity
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.commodities.index') }}" class="flex items-center p-2 text-sm text-gray-600 transition-colors rounded-lg hover:bg-gray-100 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75a2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                    </svg>
                                    View Commodities
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('admin.transactions') }}" class="flex items-center p-2 text-gray-900 transition-colors rounded-lg hover:bg-gray-100 group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <span class="ms-3">Transactions</span>
                        </a>
                    </li>

                    <li class="dropdown">
                        <button class="flex items-center w-full p-2 text-gray-900 transition-colors rounded-lg dropdown-toggle hover:bg-gray-100 group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                            <span class="flex-1 text-left ms-3 whitespace-nowrap">Manual Transactions</span>
                            <span class="inline-flex items-center justify-center ml-2 px-2 py-0.5 text-xs font-medium text-white bg-green-600 rounded">New</span>
                            <svg class="w-3 h-3 dropdown-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>
                        <ul class="hidden py-2 space-y-1 dropdown-menu pl-11">
                            <li>
                                <a href="{{ route('admin.manual_transactions.index') }}" class="flex items-center p-2 text-sm text-gray-600 transition-colors rounded-lg hover:bg-gray-100 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="mr-2 size-4">
                                        <path fill-rule="evenodd" d="M2.25 13.5a8.25 8.25 0 018.25-8.25.75.75 0 01.75.75v6.75H18a.75.75 0 01.75.75 8.25 8.25 0 01-16.5 0z" clip-rule="evenodd" />
                                        <path fill-rule="evenodd" d="M12.75 3a.75.75 0 01.75-.75 8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75h-7.5V3z" clip-rule="evenodd" />
                                    </svg>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.manual_transactions.create') }}" class="flex items-center p-2 text-sm text-gray-600 transition-colors rounded-lg hover:bg-gray-100 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="mr-2 size-4">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />
                                    </svg>
                                    Individual Transaction
                                </a>
                            </li>
                            {{-- <li>
                                <a href="{{ route('admin.manual_transactions.bulk') }}" class="flex items-center p-2 text-sm text-gray-600 transition-colors rounded-lg hover:bg-gray-100 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                    </svg>
                                    Bulk Upload
                                </a>
                            </li> --}}
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('admin.saving_withdrawals.index') }}" class="flex items-center p-2 text-gray-900 transition-colors rounded-lg hover:bg-gray-100 group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="ms-3">Saving Withdrawal Requests</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.bulk_updates') }}" class="flex items-center p-2 text-gray-900 transition-colors rounded-lg hover:bg-gray-100 group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            <span class="ms-3">MAB Bulk Updates</span>
                            <span class="inline-flex items-center justify-center ml-2 px-2 py-0.5 text-xs font-medium text-white bg-green-600 rounded">New</span>
                        </a>
                    </li>

                    <li class="dropdown">
                        <button class="flex items-center w-full p-2 text-gray-900 transition-colors rounded-lg dropdown-toggle hover:bg-gray-100 group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                            <span class="flex-1 text-left ms-3 whitespace-nowrap">Support Tickets</span>
                            <svg class="w-3 h-3 dropdown-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>
                        <ul class="hidden py-2 space-y-1 dropdown-menu pl-11">
                            <li>
                                <a href="{{ route('admin.tickets.open') }}" class="flex items-center gap-2 p-2 text-sm text-gray-600 transition-colors rounded-lg hover:bg-gray-100 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                                    </svg>
                                    <span>Open Tickets</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.tickets.closed') }}" class="flex items-center gap-2 p-2 text-sm text-gray-600 transition-colors rounded-lg hover:bg-gray-100 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
                                    </svg>
                                    <span>Closed Tickets</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('admin.reports') }}" class="flex items-center p-2 text-gray-900 transition-colors rounded-lg hover:bg-gray-100 group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="ms-3">Reports</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.system_users') }}" class="flex items-center p-2 text-gray-900 transition-colors rounded-lg hover:bg-gray-100 group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="ms-3">System Users</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.administration') }}" class="flex items-center p-2 text-gray-900 transition-colors rounded-lg hover:bg-gray-100 group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="ms-3">Administration</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.business_rules') }}" class="flex items-center p-2 text-gray-900 transition-colors rounded-lg hover:bg-gray-100 group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            <span class="ms-3">Business Rules</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.faqs.index') }}" class="flex items-center p-2 text-gray-900 transition-colors rounded-lg hover:bg-gray-100 group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="ms-3">Manage FAQs</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.events.index') }}" class="flex items-center p-2 text-gray-900 transition-colors rounded-lg hover:bg-gray-100 group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            <span class="ms-3">Manage Events</span>
                        </a>
                    </li>

                    <div class="pt-4 mt-4 border-t border-gray-200">
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center w-full p-2 text-left text-red-600 transition-colors rounded-lg hover:bg-red-50 group">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span class="ml-3 font-medium">Logout</span>
                            </button>
                        </form>
                    </div>
                </ul>
            </div>
        </aside>
    </header>

    <main class="min-h-screen bg-gray-200">
        <div class="min-h-screen pt-16 bg-gray-200 sm:ml-64 sm:pt-16">
            <div class="p-4 bg-gray-200">
                @yield('content')
            </div>
        </div>
    </main>

    <section class="hidden">
        @include('components.logout_modal');
    </section>

    @stack('scripts')
</body>
</html>