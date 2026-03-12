@extends('layouts.admin')

@section('content')
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Preview Data</h1>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}"
                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-green-600">
                            <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <a href="{{ route('admin.bulk_updates') }}"
                                class="ml-1 text-sm font-medium text-gray-700 hover:text-green-600 md:ml-2">Bulk
                                Updates</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Preview</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="p-6 mb-6 bg-white rounded-lg shadow-md">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Data Preview</h2>
                    <p class="mt-1 text-sm text-gray-600">Review the data before processing</p>
                </div>
                <div class="text-sm text-gray-600">
                    <div>File: <span class="font-medium">{{ $fileName }}</span></div>
                    <div>Total Records: <span class="font-medium">{{ $totalRecords }}</span></div>
                </div>
            </div>

            <div
                class="mb-6 p-4 rounded-lg {{ $hasErrors ? 'bg-yellow-50 border border-yellow-200' : 'bg-green-50 border border-green-200' }}">
                <h3 class="font-medium {{ $hasErrors ? 'text-yellow-800' : 'text-green-800' }} mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if ($hasErrors)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        @endif
                    </svg>
                    Validation Results
                </h3>
                @if ($hasErrors)
                    <ul class="space-y-1 text-sm text-yellow-700 list-disc list-inside">
                        @if ($notFoundCount > 0)
                            <li>{{ $notFoundCount }} members could not be found with the provided COOPNO</li>
                        @endif
                        @if ($invalidDataCount > 0)
                            <li>{{ $invalidDataCount }} records have invalid data formats in financial fields</li>
                        @endif
                        <li>All other records ({{ $validRecords }}) are valid and ready for import</li>
                    </ul>
                @else
                    <p class="text-sm text-green-700">All {{ $totalRecords }} records are valid and ready for import</p>
                @endif
            </div>

            <div class="p-4 mb-6 rounded-lg bg-gray-50">
                <h3 class="mb-2 font-medium text-gray-800">Upload Settings</h3>
                <div class="grid grid-cols-1 gap-4 text-sm md:grid-cols-3">
                    <div>
                        <p class="text-gray-600">Transaction Date:</p>
                        <p class="font-medium">{{ $transactionDate }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Description:</p>
                        <p class="font-medium">{{ $description }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Missing Data Handling:</p>
                        <p class="font-medium">{{ $missingDataHandling == 'skip' ? 'Skip (No Change)' : 'Use Zero' }}</p>
                    </div>
                    <div class="md:col-span-3">
                        <p class="text-gray-600">Fields to Update:</p>
                        <div class="flex flex-wrap gap-2 mt-1">
                            @foreach ($updateFields as $field)
                                <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded">
                                    {{ ucfirst(str_replace('_', ' ', $field)) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                S/NO</th>
                            <th class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                COOPNO</th>
                            <th class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                NAME</th>
                            @if (in_array('entrance', $updateFields))
                                <th class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    ENTRANCE</th>
                            @endif
                            @if (in_array('shares', $updateFields))
                                <th class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    SHARES</th>
                            @endif
                            @if (in_array('savings', $updateFields))
                                <th class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    SAVINGS</th>
                            @endif
                            @if (in_array('loan_repay', $updateFields))
                                <th class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    LOAN REPAY</th>
                            @endif
                            @if (in_array('loan_int', $updateFields))
                                <th class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    LOAN INT</th>
                            @endif
                            @if (in_array('essential', $updateFields))
                                <th class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    ESSENTIAL</th>
                            @endif
                            @if (in_array('non_essential', $updateFields))
                                <th class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    NON-ESSENTIAL</th>
                            @endif
                            @if (in_array('electronics', $updateFields))
                                <th class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    ELECTRONICS</th>
                            @endif
                            <th class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                TOTAL</th>
                            <th class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($previewData as $index => $row)
                            <tr class="{{ $row['status'] === 'valid' ? '' : 'bg-red-50' }}">
                                <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $row['sno'] }}</td>
                                <td
                                    class="px-3 py-4 whitespace-nowrap text-sm {{ $row['status'] === 'valid' ? 'text-gray-500' : 'text-red-500' }}">
                                    {{ $row['coopno'] }}</td>
                                <td
                                    class="px-3 py-4 whitespace-nowrap text-sm {{ $row['status'] === 'valid' ? 'text-gray-900' : 'text-red-500' }}">
                                    {{ $row['name'] }}</td>
                                @if (in_array('entrance', $updateFields))
                                    <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        {{ $row['entrance'] ?? '-' }}</td>
                                @endif
                                @if (in_array('shares', $updateFields))
                                    <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        {{ $row['shares'] ?? '-' }}</td>
                                @endif
                                @if (in_array('savings', $updateFields))
                                    <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        {{ $row['savings'] ?? '-' }}</td>
                                @endif
                                @if (in_array('loan_repay', $updateFields))
                                    <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        {{ $row['loan_repay'] ?? '-' }}</td>
                                @endif
                                @if (in_array('loan_int', $updateFields))
                                    <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        {{ $row['loan_int'] ?? '-' }}</td>
                                @endif
                                @if (in_array('essential', $updateFields))
                                    <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        {{ $row['essential'] ?? '-' }}</td>
                                @endif
                                @if (in_array('non_essential', $updateFields))
                                    <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        {{ $row['non_essential'] ?? '-' }}</td>
                                @endif
                                @if (in_array('electronics', $updateFields))
                                    <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        {{ $row['electronics'] ?? '-' }}</td>
                                @endif
                                <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $row['total'] ?? '-' }}
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    @if ($row['status'] === 'valid')
                                        <span
                                            class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Valid</span>
                                    @elseif($row['status'] === 'not_found')
                                        <span
                                            class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Member
                                            Not Found</span>
                                    @elseif($row['status'] === 'invalid_data')
                                        <span
                                            class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Invalid
                                            Data</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('admin.bulk_updates') }}"
                    class="px-4 py-2 text-gray-700 transition-colors bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Back to Upload
                </a>
                <div class="flex space-x-3">
                    <form action="{{ route('admin.bulk_updates.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="session_id" value="{{ $sessionId }}">
                        <button type="submit"
                            class="px-4 py-2 text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                            Process Updates
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection