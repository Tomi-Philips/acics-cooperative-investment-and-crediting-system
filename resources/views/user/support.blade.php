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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    Support Tickets
                </h1>
                <p class="mt-2 text-sm text-gray-600">Submit a support request or view your ticket history</p>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="document.getElementById('new-ticket-form').scrollIntoView({behavior: 'smooth'})"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-all duration-200 bg-green-600 rounded-lg shadow-md hover:bg-green-700 hover:shadow-lg lg:hidden">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    New Ticket
                </button>
            </div>
        </div>
    </div>

    <div class="space-y-6">

        <!-- New Ticket Form -->
        <div id="new-ticket-form" class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-blue-50">
                <h3 class="flex items-center text-lg font-semibold text-gray-800">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Submit a New Ticket
                </h3>
            </div>
            <div class="p-6">

            @if (session('success'))
                <div class="p-4 mb-4 text-green-800 bg-green-100 border border-green-200 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 mb-4 text-red-800 bg-red-100 border border-red-200 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <form class="space-y-4 sm:space-y-5" action="{{ route('user.support.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <div>
                    <label for="subject" class="block mb-1 text-sm font-medium text-gray-600">Subject</label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                        class="w-full border border-gray-300 rounded-md px-3 sm:px-4 py-2 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('subject') border-red-500 @enderror"
                        placeholder="e.g. Login issue, Profile bug..." required />
                    @error('subject')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block mb-1 text-sm font-medium text-gray-600">Category</label>
                    <select id="category" name="category"
                        class="w-full border border-gray-300 rounded-md px-3 sm:px-4 py-2 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('category') border-red-500 @enderror"
                        required>
                        <option value="">Select a category</option>
                        <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>General Inquiry
                        </option>
                        <option value="account" {{ old('category') == 'account' ? 'selected' : '' }}>Account Issue</option>
                        <option value="loan" {{ old('category') == 'loan' ? 'selected' : '' }}>Loan Related</option>
                        <option value="savings" {{ old('category') == 'savings' ? 'selected' : '' }}>Savings Related
                        </option>
                        <option value="shares" {{ old('category') == 'shares' ? 'selected' : '' }}>Shares Related</option>
                        <option value="commodity" {{ old('category') == 'commodity' ? 'selected' : '' }}>Commodity Related
                        </option>
                    </select>
                    @error('category')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message" class="block mb-1 text-sm font-medium text-gray-600">Message</label>
                    <textarea id="message" name="message" rows="4"
                        class="w-full border border-gray-300 rounded-md px-3 sm:px-4 py-2 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('message') border-red-500 @enderror"
                        placeholder="Describe your issue or request..." required>{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="attachment" class="block mb-1 text-sm font-medium text-gray-600">Attachment (Optional)</label>
                    <input type="file" id="attachment" name="attachment"
                        class="w-full border border-gray-300 rounded-md px-3 sm:px-4 py-2 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('attachment') border-red-500 @enderror" />
                    <p class="mt-1 text-xs text-gray-500">Accepted file types: PDF, DOC, DOCX, JPG, JPEG, PNG (max 2MB)</p>
                    @error('attachment')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-green-600 text-white px-5 py-2.5 rounded-md hover:bg-green-700 transition-colors text-sm sm:text-base font-medium flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Send Ticket
                    </button>
                </div>
            </form>
        </div>

        <!-- My Tickets -->
        <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
            <div class="flex flex-col items-center justify-between gap-3 px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-blue-50 md:flex-row">
                <h3 class="flex items-center text-lg font-semibold text-gray-800">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    My Tickets
                </h3>
                <div class="relative">
                    <form action="{{ route('user.support') }}" method="GET" id="statusFilterForm">
                        <select name="status" id="statusFilter"
                            class="appearance-none bg-white border border-gray-300 rounded-md pl-3 pr-8 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            onchange="document.getElementById('statusFilterForm').submit()">
                            <option value="">All Statuses</option>
                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </form>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 pointer-events-none">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:px-6 whitespace-nowrap">
                                Ticket #</th>
                            <th scope="col"
                                class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:px-6">
                                Subject</th>
                            <th scope="col"
                                class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:px-6 whitespace-nowrap">
                                Status</th>
                            <th scope="col"
                                class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:px-6 whitespace-nowrap">
                                Submitted</th>
                            <th scope="col"
                                class="px-4 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase sm:px-6">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if (count($tickets) > 0)
                            @foreach ($tickets as $ticket)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 text-sm font-medium text-gray-900 sm:px-6 whitespace-nowrap">
                                        {{ $ticket->ticket_number }}</td>
                                    <td class="max-w-xs px-4 py-4 text-sm text-gray-500 truncate sm:px-6">
                                        {{ $ticket->subject }}</td>
                                    <td class="px-4 py-4 sm:px-6 whitespace-nowrap">
                                        @if ($ticket->status == 'open')
                                            <span
                                                class="px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Open</span>
                                        @elseif($ticket->status == 'closed')
                                            <span
                                                class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Closed</span>
                                        @elseif($ticket->status == 'pending')
                                            <span
                                                class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500 sm:px-6 whitespace-nowrap">
                                        {{ $ticket->created_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-4 text-sm font-medium text-right sm:px-6 whitespace-nowrap">
                                        <a href="{{ route('user.support.show', $ticket->id) }}"
                                            class="text-green-600 hover:text-green-900">View Details</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-sm text-center text-gray-400 sm:px-6">
                                    <div class="flex flex-col items-center justify-center py-8">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 text-gray-300"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-gray-500">No support tickets found</p>
                                        <p class="mt-1 text-xs text-gray-400">Submit your first ticket using the form above</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            @if (count($tickets) > 0 && $tickets instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="flex items-center justify-between px-4 py-3 border-t border-gray-200 bg-gray-50 sm:px-6">
                    <div class="flex justify-between flex-1 sm:hidden">
                        @if ($tickets->onFirstPage())
                            <span
                                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">
                                Previous
                            </span>
                        @else
                            <a href="{{ $tickets->previousPageUrl() }}"
                                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Previous
                            </a>
                        @endif

                        @if ($tickets->hasMorePages())
                            <a href="{{ $tickets->nextPageUrl() }}"
                                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Next
                            </a>
                        @else
                            <span
                                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">
                                Next
                            </span>
                        @endif
                    </div>

                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing
                                <span class="font-medium">{{ $tickets->firstItem() ?? 0 }}</span>
                                to
                                <span class="font-medium">{{ $tickets->lastItem() ?? 0 }}</span>
                                of
                                <span class="font-medium">{{ $tickets->total() }}</span>
                                results
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                {{-- Previous Page Link --}}
                                @if ($tickets->onFirstPage())
                                    <span
                                        class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-gray-100 border border-gray-300 cursor-not-allowed rounded-l-md">
                                        <span class="sr-only">Previous</span>
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @else
                                    <a href="{{ $tickets->previousPageUrl() }}"
                                        class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">
                                        <span class="sr-only">Previous</span>
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($tickets->getUrlRange(1, $tickets->lastPage()) as $page => $url)
                                    @if ($page == $tickets->currentPage())
                                        <span aria-current="page"
                                            class="relative z-10 inline-flex items-center px-4 py-2 text-sm font-medium text-green-600 border border-green-500 bg-green-50">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}"
                                            class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 hover:bg-gray-50">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($tickets->hasMorePages())
                                    <a href="{{ $tickets->nextPageUrl() }}"
                                        class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">
                                        <span class="sr-only">Next</span>
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                @else
                                    <span
                                        class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-gray-100 border border-gray-300 cursor-not-allowed rounded-r-md">
                                        <span class="sr-only">Next</span>
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @endif
                            </nav>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection