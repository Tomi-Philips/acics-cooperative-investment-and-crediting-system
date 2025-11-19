@extends('layouts.admin')

@section('title', 'User Bulk Upload Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">User Bulk Upload Details</h1>
                <p class="mt-2 text-gray-600">Detailed information about the bulk upload process</p>
            </div>
            <a href="{{ route('admin.users.bulk_upload') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Bulk Upload
            </a>
        </div>
    </div>

    <!-- Upload Summary Card -->
    <div class="bg-white overflow-hidden shadow-lg rounded-lg mb-8">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h2 class="text-xl font-semibold text-gray-900">Upload Summary</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- File Information -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">File Name</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $uploadSummary['file_name'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Records -->
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Records</p>
                            <p class="text-lg font-semibold text-blue-900">{{ number_format($uploadSummary['total_records']) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Processed Records -->
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Processed Successfully</p>
                            <p class="text-lg font-semibold text-green-900">{{ number_format($uploadSummary['processed_records']) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Failed Records -->
                <div class="bg-red-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Failed Records</p>
                            <p class="text-lg font-semibold text-red-900">{{ number_format($uploadSummary['failed_records']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Success Rate</span>
                    <span class="text-sm font-medium text-gray-700">{{ number_format($uploadSummary['success_rate'], 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full transition-all duration-300" 
                         style="width: {{ $uploadSummary['success_rate'] }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Upload Information -->
        <div class="bg-white overflow-hidden shadow-lg rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Upload Information</h3>
            </div>
            <div class="p-6">
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $upload->status_badge_class }}">
                                {{ $upload->status_display }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Uploaded By</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $uploadSummary['uploaded_by'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Upload Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $uploadSummary['upload_date']->format('M d, Y \a\t g:i A') }}</dd>
                    </div>
                    @if($upload->upload_completed_at)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Completed At</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $upload->upload_completed_at->format('M d, Y \a\t g:i A') }}</dd>
                    </div>
                    @endif
                    @if($upload->error_message)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Error Message</dt>
                        <dd class="mt-1 text-sm text-red-600">{{ $upload->error_message }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Processing Summary -->
        <div class="bg-white overflow-hidden shadow-lg rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Processing Summary</h3>
            </div>
            <div class="p-6">
                @if(!empty($uploadSummary['processing_summary']))
                    <div class="space-y-3">
                        @foreach($uploadSummary['processing_summary'] as $key => $value)
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                                <span class="text-sm text-gray-900">{{ is_array($value) ? json_encode($value) : $value }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No processing details available</h3>
                        <p class="mt-1 text-sm text-gray-500">Processing summary was not recorded for this upload.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detailed Records Section -->
    <div class="mt-8 space-y-8">
        <!-- Successful Records -->
        @if(count($successfulRecords) > 0)
        <div class="bg-white overflow-hidden shadow-lg rounded-lg">
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-green-50 to-blue-50">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center">
                        <div class="p-2 mr-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Successfully Processed Records</h3>
                            <p class="text-sm text-gray-600">{{ count($successfulRecords) }} records processed successfully</p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <!-- Transaction Type Filter -->
                        <div class="relative">
                            <select id="success-type-filter" class="pl-10 pr-8 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white cursor-pointer transition-all duration-200" onchange="filterSuccessfulByType(this.value)">
                                <option value="all">All Types</option>
                                <option value="general">General Transactions</option>
                                <option value="share">Share Transactions</option>
                                <option value="saving">Saving Transactions</option>
                                <option value="loan">Loan Payments</option>
                                <option value="commodity">Commodity Transactions</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Search -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" id="success-search-input" placeholder="Search records..."
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm transition duration-150 ease-in-out"
                                onkeyup="searchSuccessfulRecords(this.value)">
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="successful-records-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Member Number</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Transaction Type</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created At</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($successfulRecords as $record)
                        <tr class="hover:bg-gray-50 transition-all duration-200 border-b border-gray-100" data-transaction-type="{{ strtolower($record['transaction_type'] ?? 'general') }}">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center w-10 h-10 text-sm font-bold text-white rounded-full bg-gradient-to-r from-green-500 to-green-600">
                                        {{ strtoupper(substr($record['name'], 0, 1)) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-semibold text-gray-900">{{ $record['name'] }}</div>
                                        <div class="text-xs text-gray-500">Member</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $record['email'] }}</div>
                                <div class="text-xs text-gray-500">Email Address</div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $record['member_number'] }}</div>
                                <div class="text-xs text-gray-500">Member ID</div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">
                                    <div class="w-2 h-2 mr-2 bg-blue-400 rounded-full"></div>
                                    {{ ucfirst($record['transaction_type'] ?? 'General Transaction') }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $record['created_at']->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $record['created_at']->format('g:i A') }}</div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                    <div class="w-2 h-2 mr-2 bg-green-400 rounded-full"></div>
                                    {{ ucfirst($record['status']) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Failed Records -->
        @if(count($failedRecords) > 0)
        <div class="bg-white overflow-hidden shadow-lg rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-red-100">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Failed Records with Issues ({{ count($failedRecords) }})
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Row</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issue Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($failedRecords as $record)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $record['row'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record['field'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record['value'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ $record['issue_type'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $record['message'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- No Records Message -->
        @if(count($successfulRecords) == 0 && count($failedRecords) == 0)
        <div class="bg-white overflow-hidden shadow-lg rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Record Details</h3>
            </div>
            <div class="p-6">
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No detailed records available</h3>
                    <p class="mt-1 text-sm text-gray-500">Record details are not available for this upload.</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Actions -->
    <div class="mt-8 flex justify-center space-x-4">
        <a href="{{ route('admin.users.bulk_upload') }}"
           class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Upload Another File
        </a>
        <a href="{{ route('admin.users.all') }}"
           class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            View All Users
        </a>
    </div>
</div>

@push('scripts')
<script>
    function filterSuccessfulByType(type) {
        const rows = document.querySelectorAll('#successful-records-table tbody tr');

        rows.forEach(row => {
            if (row.querySelector('td[colspan]')) return; // Skip empty state row

            const transactionType = row.getAttribute('data-transaction-type') || '';

            if (type === 'all') {
                row.style.display = '';
            } else {
                const shouldShow = transactionType.includes(type.toLowerCase());
                row.style.display = shouldShow ? '' : 'none';
            }
        });

        updateSuccessfulEmptyState();
    }

    function searchSuccessfulRecords(searchTerm) {
        const rows = document.querySelectorAll('#successful-records-table tbody tr');
        const term = searchTerm.toLowerCase();

        rows.forEach(row => {
            if (row.querySelector('td[colspan]')) return; // Skip empty state row

            const nameCell = row.querySelector('td:first-child .text-sm.font-semibold');
            const emailCell = row.querySelector('td:nth-child(2) .text-sm.font-medium');
            const memberNumberCell = row.querySelector('td:nth-child(3) .text-sm.font-semibold');

            if (!nameCell || !emailCell || !memberNumberCell) return;

            const name = nameCell.textContent.toLowerCase();
            const email = emailCell.textContent.toLowerCase();
            const memberNumber = memberNumberCell.textContent.toLowerCase();

            const shouldShow = name.includes(term) || email.includes(term) || memberNumber.includes(term);
            row.style.display = shouldShow ? '' : 'none';
        });

        updateSuccessfulEmptyState();
    }

    function updateSuccessfulEmptyState() {
        const table = document.querySelector('#successful-records-table');
        if (!table) return;

        const tbody = table.querySelector('tbody');
        const rows = tbody.querySelectorAll('tr:not([style*="display: none"])');
        const emptyRow = tbody.querySelector('tr td[colspan]');
        const visibleDataRows = Array.from(rows).filter(row => !row.querySelector('td[colspan]'));

        if (visibleDataRows.length === 0 && !emptyRow) {
            // Create and show empty state
            const emptyStateRow = document.createElement('tr');
            emptyStateRow.innerHTML = `
                <td colspan="6" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center">
                        <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <h3 class="mb-2 text-lg font-medium text-gray-900">No records found</h3>
                        <p class="text-gray-500">Try adjusting your search or filter criteria.</p>
                    </div>
                </td>
            `;
            tbody.appendChild(emptyStateRow);
        } else if (visibleDataRows.length > 0 && emptyRow) {
            // Remove empty state if there are visible rows
            emptyRow.parentElement.remove();
        }
    }
</script>
@endpush

@endsection
