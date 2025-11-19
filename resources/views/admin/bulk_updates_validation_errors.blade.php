@extends('layouts.admin')

@section('title', 'Bulk Upload - Validation Errors')

@section('content')
<div class="container px-4 py-8 mx-auto">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Upload Validation Issues</h1>
                    <p class="mt-2 text-gray-600">Please review and fix the following issues before proceeding</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.bulk_updates') }}" class="px-4 py-2 text-white transition-colors bg-gray-500 rounded-lg hover:bg-gray-600">
                        <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Upload
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-4">
            <div class="p-6 border border-green-200 rounded-lg bg-green-50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-600">Valid Records</p>
                        <p class="text-2xl font-bold text-green-900">{{ $validRecords }}</p>
                    </div>
                </div>
            </div>

            @if($notFoundCount > 0)
            <div class="p-6 border border-red-200 rounded-lg bg-red-50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-red-600">Members Not Found</p>
                        <p class="text-2xl font-bold text-red-900">{{ $notFoundCount }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($invalidDataCount > 0)
            <div class="p-6 border border-orange-200 rounded-lg bg-orange-50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-orange-600">Invalid Data</p>
                        <p class="text-2xl font-bold text-orange-900">{{ $invalidDataCount }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($duplicateCount > 0)
            <div class="p-6 border border-yellow-200 rounded-lg bg-yellow-50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-yellow-600">Duplicates</p>
                        <p class="text-2xl font-bold text-yellow-900">{{ $duplicateCount }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Tabs Navigation -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px space-x-8">
                    <button onclick="showTab('issues')" id="issues-tab" class="px-1 py-2 text-sm font-medium text-red-600 border-b-2 border-red-500 tab-button active">
                        Issues ({{ $notFoundCount + $invalidDataCount + $duplicateCount + $missingRequiredCount }})
                    </button>
                    <button onclick="showTab('valid')" id="valid-tab" class="px-1 py-2 text-sm font-medium text-gray-500 border-b-2 border-transparent tab-button hover:text-gray-700 hover:border-gray-300">
                        Valid Records ({{ $validRecords }})
                    </button>
                </nav>
            </div>
        </div>

        <!-- Issues Tab Content -->
        <div id="issues-content" class="tab-content">
        @if(!empty($validationIssues['missing_required']))
        <div class="mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="flex items-center text-lg font-semibold text-gray-900">
                    <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    Missing Required Data ({{ count($validationIssues['missing_required']) }} records)
                </h3>
            </div>
            <div class="p-6">
                <div class="p-4 mb-4 border border-red-200 rounded-lg bg-red-50">
                    <h4 class="mb-2 font-semibold text-red-800">🔧 How to Fix:</h4>
                    <ul class="space-y-1 text-sm text-red-700">
                        <li>• Add member numbers to the empty cells in column B</li>
                        <li>• Ensure all member numbers follow the format: PSS/123, COOP/456, etc.</li>
                        <li>• Remove completely empty rows from your Excel file</li>
                    </ul>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Excel Row</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Issue</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Data Preview</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($validationIssues['missing_required'] as $issue)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Row {{ $issue['row'] }}</td>
                                <td class="px-6 py-4 text-sm text-red-600 whitespace-nowrap">{{ $issue['issue'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ implode(' | ', array_slice($issue['data'], 0, 4)) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="px-6 mt-4">
                        <p class="text-sm font-medium text-gray-700">
                            Showing all {{ count($validationIssues['missing_required']) }} records with missing required data
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(!empty($validationIssues['not_found']))
        <div class="mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="flex items-center text-lg font-semibold text-gray-900">
                    <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Members Not Found in Database ({{ count($validationIssues['not_found']) }} records)
                </h3>
            </div>
            <div class="p-6">
                <div class="p-4 mb-4 border border-red-200 rounded-lg bg-red-50">
                    <h4 class="mb-2 font-semibold text-red-800">🔧 How to Fix:</h4>
                    <ul class="space-y-1 text-sm text-red-700">
                        <li>• Check if member numbers are correctly formatted (PSS/123, COOP/456)</li>
                        <li>• Verify member numbers exist in the system</li>
                        <li>• Register new members first if they don't exist</li>
                        <li>• Remove records for non-existent members from Excel file</li>
                    </ul>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Excel Row</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Member Number</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($validationIssues['not_found'] as $member)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Row {{ $member['row'] }}</td>
                                <td class="px-6 py-4 font-mono text-sm text-red-600 whitespace-nowrap">{{ $member['member_number'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $member['surname'] }} {{ $member['othernames'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="px-6 mt-4">
                        <p class="text-sm font-medium text-gray-700">
                            Showing all {{ count($validationIssues['not_found']) }} members not found in database
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(!empty($validationIssues['invalid_data']))
        <div class="mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="flex items-center text-lg font-semibold text-gray-900">
                    <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Invalid Financial Data ({{ count($validationIssues['invalid_data']) }} records)
                </h3>
            </div>
            <div class="p-6">
                <div class="p-4 mb-4 border border-orange-200 rounded-lg bg-orange-50">
                    <h4 class="mb-2 font-semibold text-orange-800">🔧 How to Fix:</h4>
                    <ul class="space-y-1 text-sm text-orange-700">
                        <li>• Replace text values with numbers (e.g., "N/A" → 0)</li>
                        <li>• Remove currency symbols (₦, $) and commas</li>
                        <li>• Fix negative values if not allowed</li>
                        <li>• Check for extremely large values that might be typos</li>
                    </ul>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Excel Row</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Member</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Issues</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($validationIssues['invalid_data'] as $record)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Row {{ $record['row'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $record['member_number'] }} - {{ $record['surname'] }}</td>
                                <td class="px-6 py-4 text-sm text-orange-600">
                                    @foreach($record['errors'] as $error)
                                        <div class="mb-1">• {{ $error }}</div>
                                    @endforeach
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="px-6 mt-4">
                        <p class="text-sm font-medium text-gray-700">
                            Showing all {{ count($validationIssues['invalid_data']) }} records with invalid financial data
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(!empty($validationIssues['duplicates']))
        <div class="mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="flex items-center text-lg font-semibold text-gray-900">
                    <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    Duplicate Member Numbers ({{ count($validationIssues['duplicates']) }} duplicates)
                </h3>
            </div>
            <div class="p-6">
                <div class="p-4 mb-4 border border-yellow-200 rounded-lg bg-yellow-50">
                    <h4 class="mb-2 font-semibold text-yellow-800">🔧 How to Fix:</h4>
                    <ul class="space-y-1 text-sm text-yellow-700">
                        <li>• Remove duplicate rows from your Excel file</li>
                        <li>• Keep only one record per member number</li>
                        <li>• If both records are valid, combine the amounts</li>
                    </ul>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Member Number</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">First Row</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Duplicate Row</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($validationIssues['duplicates'] as $duplicate)
                            <tr>
                                <td class="px-6 py-4 font-mono text-sm font-medium text-yellow-600 whitespace-nowrap">{{ $duplicate['member_number'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">Row {{ $duplicate['first_row'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">Row {{ $duplicate['duplicate_row'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        </div>

        <!-- Valid Records Tab Content -->
        <div id="valid-content" class="hidden tab-content">
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="flex items-center text-lg font-semibold text-gray-900">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Valid Records Ready for Processing ({{ $validRecords }} records)
                    </h3>
                </div>
                <div class="p-6">
                    <div class="p-4 mb-4 border border-green-200 rounded-lg bg-green-50">
                        <h4 class="mb-2 font-semibold text-green-800">✅ These records will be processed:</h4>
                        <ul class="space-y-1 text-sm text-green-700">
                            <li>• Member numbers exist in the database</li>
                            <li>• All financial data is valid and numeric</li>
                            <li>• No duplicate entries found</li>
                            <li>• Ready for {{ $description }} upload</li>
                        </ul>
                    </div>

                    @if($validRecords > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Member Number</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Shares</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Savings</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Total</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @if(isset($validationIssues['valid_records']))
                                    @foreach($validationIssues['valid_records'] as $record)
                                    <tr>
                                        <td class="px-6 py-4 font-mono text-sm font-medium text-gray-900 whitespace-nowrap">{{ $record['member_number'] ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $record['name'] ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">₦{{ number_format($record['shares'] ?? 0) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">₦{{ number_format($record['savings'] ?? 0) }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">₦{{ number_format($record['total'] ?? 0) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Valid
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-sm text-center text-gray-500">
                                            Valid records data not available in current session. Please re-upload to see detailed valid records.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        @if(isset($validationIssues['valid_records']))
                        <div class="px-6 mt-4">
                            <p class="text-sm font-medium text-green-700">
                                Showing all {{ count($validationIssues['valid_records']) }} valid records ready for processing
                            </p>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="py-8 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No valid records found</h3>
                        <p class="mt-1 text-sm text-gray-500">Please fix the issues in your Excel file first.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 space-y-4">
            @if($validRecords > 0)
            <!-- Option 1: Proceed with Valid Records Only -->
            <div class="p-6 border border-green-200 rounded-lg bg-green-50">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="mb-2 text-lg font-semibold text-green-800">✅ Option 1: Process Valid Records Now</h4>
                        <p class="mb-3 text-sm text-green-700">
                            Process {{ $validRecords }} valid records immediately. Invalid records will be skipped.
                        </p>
                        <div class="space-y-1 text-xs text-green-600">
                            <p>• {{ $validRecords }} records will be processed for {{ $description }}</p>
                            <p>• Invalid records can be fixed and uploaded separately later</p>
                            <p>• New members should be registered first, then included in next month's upload</p>
                        </div>
                    </div>
                    <div class="ml-4">
                        <form action="{{ route('admin.bulk_updates.process') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="session_id" value="{{ $sessionId }}">
                            <input type="hidden" name="process_valid_only" value="1">
                            <button type="submit"
                                onclick="return confirm('Are you sure you want to process {{ $validRecords }} valid records? Invalid records will be skipped.')"
                                class="px-6 py-2 font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                                Process {{ $validRecords }} Valid Records
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            <!-- Option 2: Fix Issues First -->
            <div class="p-6 border border-blue-200 rounded-lg bg-blue-50">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="mb-2 text-lg font-semibold text-blue-800">🔧 Option 2: Fix Issues and Re-upload</h4>
                        <p class="mb-3 text-sm text-blue-700">
                            Fix all issues in your Excel file and upload again for complete processing.
                        </p>
                        <div class="space-y-1 text-xs text-blue-600">
                            <p><strong>For New Members:</strong></p>
                            <p>• Register new members in the system first</p>
                            <p>• Or remove them from Excel and process in next month's upload</p>
                            <p>• Fix data format issues and duplicates</p>
                        </div>
                    </div>
                    <div class="ml-4">
                        <a href="{{ route('admin.bulk_updates') }}"
                           class="px-6 py-2 font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                            Upload Fixed File
                        </a>
                    </div>
                </div>
            </div>

            <!-- New Member Registration Guide -->
            @if($notFoundCount > 0)
            <div class="p-6 border border-yellow-200 rounded-lg bg-yellow-50">
                <h4 class="mb-2 text-lg font-semibold text-yellow-800">👥 New Member Registration Guide</h4>
                <p class="mb-3 text-sm text-yellow-700">
                    {{ $notFoundCount }} members were not found in the system. Here's how to handle them:
                </p>
                <div class="grid grid-cols-1 gap-4 text-sm text-yellow-700 md:grid-cols-2">
                    <div>
                        <p class="mb-2 font-medium">Recommended Approach:</p>
                        <ol class="space-y-1 list-decimal list-inside">
                            <li>Process existing members now ({{ $validRecords }} records)</li>
                            <li>Register new members separately</li>
                            <li>Include new members in next month's upload</li>
                        </ol>
                    </div>
                    <div>
                        <p class="mb-2 font-medium">Alternative Approach:</p>
                        <ol class="space-y-1 list-decimal list-inside">
                            <li>Register all new members first</li>
                            <li>Fix Excel file to remove invalid data</li>
                            <li>Re-upload complete file</li>
                        </ol>
                    </div>
                </div>
                <div class="pt-4 mt-4 border-t border-yellow-200">
                    <a href="{{ route('admin.users.add') }}"
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-colors bg-yellow-600 rounded-lg hover:bg-yellow-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Register New Members
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .tab-button.active {
        border-color: #ef4444;
        color: #dc2626;
    }
    .tab-button.active#valid-tab {
        border-color: #10b981;
        color: #059669;
    }
    .tab-content.hidden {
        display: none;
    }
</style>

<script>
    function showTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        // Remove active class from all tabs
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active');
            button.classList.add('border-transparent', 'text-gray-500');
            button.classList.remove('border-red-500', 'text-red-600', 'border-green-500', 'text-green-600');
        });

        // Show selected tab content
        document.getElementById(tabName + '-content').classList.remove('hidden');

        // Add active class to selected tab
        const activeTab = document.getElementById(tabName + '-tab');
        activeTab.classList.add('active');
        activeTab.classList.remove('border-transparent', 'text-gray-500');

        if (tabName === 'issues') {
            activeTab.classList.add('border-red-500', 'text-red-600');
        } else if (tabName === 'valid') {
            activeTab.classList.add('border-green-500', 'text-green-600');
        }
    }

    // Initialize with issues tab active
    document.addEventListener('DOMContentLoaded', function() {
        showTab('issues');
    });
</script>
@endsection
