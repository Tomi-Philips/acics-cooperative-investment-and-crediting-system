<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ACICS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    @yield('styles')
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
                            <button id="notificationDropdownButton" class="text-gray-600 hover:text-green-600 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span id="notificationBadge" class="absolute top-0 right-0 hidden w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                            <div id="notificationDropdown" class="absolute right-0 z-50 hidden mt-2 transition duration-200 ease-out origin-top-right transform scale-95 bg-white rounded-md shadow-lg opacity-0 w-80 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                <div class="flex items-center justify-between px-4 py-2 border-b border-gray-100">
                                    <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                    <button id="markAllAsReadBtn" class="text-xs text-green-600 hover:text-green-800">
                                        Mark all as read
                                    </button>
                                </div>
                                <div class="overflow-y-auto max-h-60" id="notificationList">
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
                            <div id="dropdownMenu" class="absolute right-0 z-50 hidden w-48 py-1 mt-2 transition duration-200 ease-out origin-top-right transform bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your Profile</a>
                                <a href="{{ route('user.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
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
            <div class="h-full px-3 py-4 overflow-y-auto bg-white shadow-xl">
                <div class="px-2 mb-4">
                    <div class="p-4 border border-gray-100 shadow-lg rounded-xl bg-gradient-to-r from-green-50 to-blue-50">
                        <div class="flex items-center space-x-1.5">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 font-bold text-green-600 bg-green-100 rounded-full shadow-md">
                                    {{ substr(Auth::user()->name, 0, 1) }}{{ substr(strpos(Auth::user()->name, ' ') !== false ? substr(Auth::user()->name, strpos(Auth::user()->name, ' ') + 1, 1) : '', 0, 1) }}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-600 truncate">Member</p>
                            </div>
                        </div>
                        <div class="flex justify-center mt-3">
                            <span class="inline-flex gap-1.5 text-center items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 shadow-sm">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ Auth::user()->member_number ?? 'N/A' }}</span>
                            </span>
                        </div>
                    </div>
                </div>
                <ul class="pb-10 space-y-1.5 font-medium">
                    <li>
                        <a href="{{ route('user.dashboard') }}" class="flex items-center p-2.5 text-gray-900 transition-all duration-200 rounded-lg hover:bg-gradient-to-r hover:from-green-50 hover:to-green-100 hover:shadow-md group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span class="font-medium ms-3">Dashboard</span>
                        </a>
                    </li>
                    <li class="dropdown">
                        <button class="flex items-center w-full p-3 text-gray-900 transition-all duration-200 rounded-lg dropdown-toggle hover:bg-gradient-to-r hover:from-blue-50 hover:to-blue-100 hover:shadow-md group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="flex-1 font-medium text-left ms-3 whitespace-nowrap">Loans</span>
                            <svg class="w-3 h-3 transition-transform duration-200 dropdown-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>
                        <ul class="hidden py-2 space-y-1 dropdown-menu pl-11">
                            <li>
                                <a href="{{ route('user.loan_board') }}" class="flex items-center gap-2 p-2 text-sm text-gray-600 transition-all duration-200 rounded-lg hover:bg-gradient-to-r hover:from-gray-50 hover:to-gray-100 hover:shadow-sm group">
                                    <svg class="w-4 h-4 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span>Loan Overview</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.loan_application') }}" class="flex items-center gap-2 p-2 text-sm text-gray-600 transition-all duration-200 rounded-lg hover:bg-gradient-to-r hover:from-gray-50 hover:to-gray-100 hover:shadow-sm group">
                                    <svg class="w-4 h-4 text-gray-500 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <span>Loan Application</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('user.commodity') }}" class="flex items-center p-2.5 text-gray-900 transition-all duration-200 rounded-lg hover:bg-gradient-to-r hover:from-amber-50 hover:to-amber-100 hover:shadow-md group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span class="font-medium ms-3">Commodity</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.saving_withdrawals.create') }}" class="flex items-center p-2.5 text-gray-900 transition-all duration-200 rounded-lg hover:bg-gradient-to-r hover:from-teal-50 hover:to-teal-100 hover:shadow-md group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h.01M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3v-4a3 3 0 013-3h2m8 4H9"/>
                            </svg>
                            <span class="font-medium ms-3">Request Saving Withdrawal</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.support') }}" class="flex items-center p-2.5 text-gray-900 transition-all duration-200 rounded-lg hover:bg-gradient-to-r hover:from-purple-50 hover:to-purple-100 hover:shadow-md group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                            <span class="font-medium ms-3">Support Tickets</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.transaction_report') }}" class="flex items-center p-2.5 text-gray-900 transition-all duration-200 rounded-lg hover:bg-gradient-to-r hover:from-indigo-50 hover:to-indigo-100 hover:shadow-md group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="font-medium ms-3">Transaction Report</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.settings') }}" class="flex items-center p-2.5 text-gray-900 transition-all duration-200 rounded-lg hover:bg-gradient-to-r hover:from-gray-50 hover:to-gray-100 hover:shadow-md group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="font-medium ms-3">Settings</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.profile') }}" class="flex items-center p-2.5 text-gray-900 transition-all duration-200 rounded-lg hover:bg-gradient-to-r hover:from-cyan-50 hover:to-cyan-100 hover:shadow-md group">
                            <svg class="w-5 h-5 text-gray-500 transition-colors group-hover:text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="font-medium ms-3">My Profile</span>
                        </a>
                    </li>
                    <div class="pt-4 mt-4 border-t border-gray-200">
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center w-full p-3 text-left text-red-600 transition-all duration-200 rounded-lg hover:bg-gradient-to-r hover:from-red-50 hover:to-red-100 hover:shadow-md group">
                                <svg class="w-5 h-5 text-red-500 transition-colors group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span class="ml-3 font-bold">Logout</span>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    @yield('scripts')
</body>
</html>