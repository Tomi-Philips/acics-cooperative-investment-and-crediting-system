@extends('layouts.admin')

@section('title', 'Bulk User Upload - Validation Results')

@section('content')
<div class="container px-4 py-8 mx-auto max-w-7xl">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Bulk User Upload - Validation Results</h1>
                <p class="mt-2 text-gray-600">Review validation results and choose how to proceed</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.add') }}" 
                   class="px-4 py-2 text-gray-700 transition-colors bg-gray-100 rounded-lg hover:bg-gray-200">
                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Upload
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 gap-4 mb-8 md:grid-cols-4">
        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Records</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalRecords }}</p>
                </div>
            </div>
        </div>

        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Valid Records</p>
                    <p class="text-2xl font-bold text-green-600">{{ $validRecords }}</p>
                </div>
            </div>
        </div>

        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Issues Found</p>
                    <p class="text-2xl font-bold text-red-600">{{ $totalIssues }}</p>
                </div>
            </div>
        </div>

        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Success Rate</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $successRate }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- File Information -->
    <div class="p-4 mb-6 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <div>
                <p class="font-medium text-blue-900">File: {{ $fileName }}</p>
                <p class="text-sm text-blue-700">Processed {{ $totalRecords }} records with {{ $validRecords }} valid and {{ $totalIssues }} issues found</p>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px space-x-8">
                <button onclick="showTab('issues')" id="issues-tab" class="px-1 py-2 text-sm font-medium text-red-600 border-b-2 border-red-500 tab-button active">
                    Issues ({{ $totalIssues }})
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
                        <li>• Ensure all required fields are filled: Name, Member Number, Department</li>
                        <li>• Check for empty rows or cells in your CSV file</li>
                        <li>• Verify that member numbers follow the format: P/SS/XXX</li>
                    </ul>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Row</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Issue</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Data</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($validationIssues['missing_required'] as $issue)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $issue['row'] }}</td>
                                <td class="px-6 py-4 text-sm text-red-600 whitespace-nowrap">{{ $issue['issue'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <div class="max-w-xs truncate">{{ implode(', ', array_slice($issue['data'], 0, 5)) }}{{ count($issue['data']) > 5 ? '...' : '' }}</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                    Duplicate Records ({{ count($validationIssues['duplicates']) }} conflicts)
                </h3>
            </div>
            <div class="p-6">
                <div class="p-4 mb-4 border border-yellow-200 rounded-lg bg-yellow-50">
                    <h4 class="mb-2 font-semibold text-yellow-800">🔧 How to Fix:</h4>
                    <ul class="space-y-1 text-sm text-yellow-700">
                        <li>• Remove duplicate member numbers from your CSV file</li>
                        <li>• Check if users already exist in the system</li>
                        <li>• Ensure each member number is unique</li>
                    </ul>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Member Number</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">First Row</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Duplicate Row</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($validationIssues['duplicates'] as $duplicate)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $duplicate['member_number'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $duplicate['first_row'] }}</td>
                                <td class="px-6 py-4 text-sm text-red-600 whitespace-nowrap">{{ $duplicate['duplicate_row'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">Remove duplicate row</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                    Invalid Data Format ({{ count($validationIssues['invalid_data']) }} records)
                </h3>
            </div>
            <div class="p-6">
                <div class="p-4 mb-4 border border-orange-200 rounded-lg bg-orange-50">
                    <h4 class="mb-2 font-semibold text-orange-800">🔧 How to Fix:</h4>
                    <ul class="space-y-1 text-sm text-orange-700">
                        <li>• Check date formats (should be YYYY-MM-DD)</li>
                        <li>• Ensure numeric fields contain valid numbers</li>
                        <li>• Verify email addresses are in correct format</li>
                        <li>• Check that entrance fee field contains "Yes" or "No"</li>
                    </ul>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Row</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Field</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Issue</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Value</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($validationIssues['invalid_data'] as $issue)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $issue['row'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $issue['field'] }}</td>
                                <td class="px-6 py-4 text-sm text-orange-600 whitespace-nowrap">{{ $issue['issue'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <div class="max-w-xs truncate">{{ $issue['value'] }}</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                    Department Not Found ({{ count($validationIssues['not_found']) }} records)
                </h3>
            </div>
            <div class="p-6">
                <div class="p-4 mb-4 border border-red-200 rounded-lg bg-red-50">
                    <h4 class="mb-2 font-semibold text-red-800">🔧 How to Fix:</h4>
                    <ul class="space-y-1 text-sm text-red-700">
                        <li>• Check department names match exactly with system departments</li>
                        <li>• Create missing departments in the system first</li>
                        <li>• Verify spelling and capitalization</li>
                        <li>• <strong>Note:</strong> Department field is optional - you can leave it empty if not needed</li>
                    </ul>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Row</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Department</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Member Number</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($validationIssues['not_found'] as $issue)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $issue['row'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $issue['name'] }}</td>
                                <td class="px-6 py-4 text-sm text-red-600 whitespace-nowrap">{{ $issue['department'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $issue['member_number'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        @if(empty($validationIssues['missing_required']) && empty($validationIssues['duplicates']) && empty($validationIssues['invalid_data']) && empty($validationIssues['not_found']))
        <div class="p-8 text-center bg-white border border-gray-200 rounded-lg shadow-sm">
            <svg class="w-12 h-12 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No Issues Found</h3>
            <p class="mt-2 text-gray-500">All records passed validation successfully!</p>
        </div>
        @endif
    </div>

    <!-- Valid Records Tab Content -->
    <div id="valid-content" class="hidden tab-content">
        @if($validRecords > 0)
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
                        <li>• All required fields are present and valid</li>
                        <li>• No duplicate member numbers or emails</li>
                        <li>• Departments exist in the system</li>
                        <li>• Financial amounts are properly formatted</li>
                        <li>• Loan balances will match CSV amounts exactly (no additional interest)</li>
                    </ul>
                </div>

                @if(!empty($validRecordsPreview))
                <div class="mb-6">
                    <h4 class="mb-3 font-medium text-gray-900">Preview of Valid Records:</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Member Number</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Department</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Loan Amount</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Entrance Fee</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach(array_slice($validRecordsPreview, 0, 10) as $record)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $record['name'] }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $record['member_number'] }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $record['department'] }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">₦{{ number_format($record['loan_amount'] ?? 0, 2) }}</td>
                                    <td class="px-6 py-4 text-sm whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ ($record['entrance_fee_paid'] ?? false) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ($record['entrance_fee_paid'] ?? false) ? 'Paid' : 'Not Paid' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                                @if(count($validRecordsPreview) > 10)
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-sm text-center text-gray-500">
                                        ... and {{ count($validRecordsPreview) - 10 }} more records
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <div class="flex items-center justify-between p-4 border border-green-200 rounded-lg bg-green-50">
                    <div>
                        <h4 class="font-semibold text-green-800">Ready to Process</h4>
                        <p class="text-sm text-green-700">{{ $validRecords }} valid records will be created as new users with their financial data.</p>
                        @if($totalIssues > 0)
                        <p class="text-sm text-green-700">{{ $totalIssues }} problematic records will be skipped.</p>
                        @endif
                    </div>
                    <div class="ml-4">
                        <form action="{{ route('admin.users.bulk_upload.process') }}" method="POST" class="inline">
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
        </div>
        @else
        <div class="p-8 text-center bg-white border border-gray-200 rounded-lg shadow-sm">
            <svg class="w-12 h-12 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No Valid Records</h3>
            <p class="mt-2 text-gray-500">All records have validation issues that need to be fixed before processing.</p>
            <div class="mt-4">
                <a href="{{ route('admin.users.add') }}"
                   class="px-4 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                    Upload Corrected File
                </a>
            </div>
        </div>
        @endif
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
