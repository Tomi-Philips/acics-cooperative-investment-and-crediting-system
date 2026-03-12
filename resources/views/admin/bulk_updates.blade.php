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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                    </div>
                    Bulk Member Updates
                </h1>
                <p class="mt-2 text-sm text-gray-600">Upload monthly financial records for members efficiently</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.bulk_updates.template') }}" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white transition-all duration-200 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 border border-transparent rounded-lg shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download Template
                </a>
            </div>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-red-800 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-red-800 font-medium">Please fix the following errors:</p>
            </div>
            <ul class="list-disc list-inside text-red-700 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Monthly Upload Status --}}
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex items-center mb-2">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-blue-800">Monthly Financial Records</h3>
        </div>
        <p class="text-sm text-blue-700 mb-3">
            Uploads must follow month order (no skipping). You can re-upload a failed or reversed month for the same period.
        </p>
        <div class="text-sm text-blue-600">
            <strong>Current Month:</strong> {{ now()->format('F Y') }}
            @php
                $currentMonthUploaded = \App\Models\MonthlyUpload::existsForMonth(now()->year, now()->month);
            @endphp
            @if($currentMonthUploaded)
                <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">✓ Uploaded</span>
            @else
                <span class="ml-2 px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">⏳ Pending</span>
            @endif
            @if(isset($nextAllowed))
                <div class="mt-1 text-sm text-blue-700"><strong>Next allowed month:</strong> {{ $nextAllowed }}</div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Main Upload Section --}}
        <div class="lg:col-span-2">
            <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
                <!-- Card Header -->
                <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                    <div class="flex items-center">
                        <div class="p-2 mr-4 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Upload Member Data</h2>
                            <p class="mt-1 text-sm text-gray-600">Upload Excel files to update member financial records</p>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-8">
                    {{-- Upload Area Form --}}
                    <form id="uploadForm" action="{{ route('admin.bulk_updates.upload') }}" method="POST" enctype="multipart/form-data" class="mb-6">
                        @csrf

                        {{-- Beautiful File Upload --}}
                        <div class="mb-6">
                            <label class="block mb-3 text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Upload MAB Excel File
                            </label>

                            <div class="relative">
                                <input type="file" id="excelUpload" name="excel_file" accept=".csv,.xlsx,.xls" required
                                    class="block w-full px-3 py-2 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-25 file:bg-green-600 file:text-white file:border-0 file:py-2 file:px-4 file:rounded-l-lg file:font-medium file:cursor-pointer hover:file:bg-green-700">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                </div>
                            </div>

                            <p class="mt-2 text-xs text-gray-500 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Supports Excel files (.xlsx, .xls) and CSV files. Maximum file size: 10MB
                            </p>

                            {{-- Beautiful file info display --}}
                            <div id="fileInfo" class="hidden mt-3">
                                <div class="flex items-center p-3 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg shadow-sm">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-green-800">File Ready for Upload</p>
                                        <p class="text-xs text-green-600">
                                            <span class="font-medium">Selected:</span> <span id="selectedFileName"></span>
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <button type="button" id="clearFile" class="text-green-600 hover:text-green-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Upload Options --}}
                        <div class="p-6 mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                    </svg>
                                </div>
                                <h3 class="ml-3 text-lg font-semibold text-gray-800">Upload Configuration</h3>
                            </div>

                            <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label for="transactionDate" class="flex items-center text-sm font-medium text-gray-700">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Transaction Date
                                    </label>
                                    <input type="date" id="transactionDate" name="transaction_date"
                                        class="w-full px-4 py-3 border-2 border-yellow-300 bg-yellow-50 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 transition-all duration-200"
                                        value="{{ date('Y-m-') }}01" required>
                                    <div class="mt-2 p-3 bg-yellow-100 border border-yellow-300 rounded-lg">
                                        <p class="flex items-center text-sm font-medium text-yellow-800">
                                            <svg class="w-4 h-4 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            Important: Set the correct month/year
                                        </p>
                                        <p class="mt-1 text-xs text-yellow-700">
                                            This date determines which month the records belong to. For June MAB, set to June 2025 (2025-06-01).
                                        </p>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label for="description" class="flex items-center text-sm font-medium text-gray-700">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-1l-4 4z"></path>
                                        </svg>
                                        Description
                                    </label>
                                    <input type="text" id="description" name="description"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 transition-all duration-200"
                                        placeholder="e.g., Monthly Contributions Update">
                                    <p class="flex flex-col mt-1 text-xs text-amber-700">
                                        <span class="flex">
                                            <svg class="w-3 h-3 mr-1 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <strong>Important:</strong>
                                        </span>
                                        <span>
                                            This description will be used as the label for this bulk operation in transaction history and reports. Choose a clear, descriptive name.
                                        </span> 
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Upload Type
                                </label>

                                <div class="space-y-3">
                                    <div class="relative">
                                        <input type="radio" id="monthly_contributions" name="upload_type" value="monthly_contributions" checked
                                            class="sr-only peer">
                                        <label for="monthly_contributions" class="flex items-start p-4 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-green-500 peer-checked:bg-green-50 transition-all duration-200">
                                            <div class="flex items-center justify-center w-5 h-5 mt-0.5 mr-3 border-2 border-gray-300 rounded-full radio-circle">
                                                <div class="w-2 h-2 bg-white rounded-full opacity-0 radio-dot"></div>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center mb-1">
                                                    <span class="font-semibold text-gray-800">Monthly Contributions</span>
                                                    <span class="ml-2 px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">Recommended</span>
                                                </div>
                                                <p class="text-sm text-gray-600">Add amounts to existing balances</p>
                                                <p class="mt-1 text-xs text-gray-500">Use this when your Excel file contains monthly contribution amounts that should be added to members' current balances.</p>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="relative">
                                        <input type="radio" id="cumulative_balances" name="upload_type" value="cumulative_balances"
                                            class="sr-only peer">
                                        <label for="cumulative_balances" class="flex items-start p-4 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200">
                                            <div class="flex items-center justify-center w-5 h-5 mt-0.5 mr-3 border-2 border-gray-300 rounded-full radio-circle">
                                                <div class="w-2 h-2 bg-white rounded-full opacity-0 radio-dot"></div>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center mb-1">
                                                    <span class="font-semibold text-gray-800">Cumulative Balances</span>
                                                    <span class="ml-2 px-2 py-1 text-xs font-medium text-orange-700 bg-orange-100 rounded-full">Advanced</span>
                                                </div>
                                                <p class="text-sm text-gray-600">Replace existing balances with uploaded amounts</p>
                                                <p class="mt-1 text-xs text-gray-500">Use this when your Excel file contains total balance amounts that should replace members' current balances entirely.</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 mt-0.5 mr-2 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="text-sm text-blue-800">
                                            <p class="font-medium mb-1">💡 Quick Guide:</p>
                                            <p><strong>Monthly Contributions:</strong> Your Excel shows June contributions (e.g., ₦10,000 shares) → System adds ₦10,000 to existing balance</p>
                                            <p><strong>Cumulative Balances:</strong> Your Excel shows total balance (e.g., ₦50,000 total shares) → System sets balance to exactly ₦50,000</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block mb-1 text-sm font-medium text-gray-700">Fields to Update</label>
                                <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="update_fields[]" value="entrance" class="text-green-600 border-gray-300 rounded shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50" checked>
                                        <span class="ml-2 text-sm text-gray-700">Entrance Fee</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="update_fields[]" value="shares" class="text-green-600 border-gray-300 rounded shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50" checked>
                                        <span class="ml-2 text-sm text-gray-700">Shares</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="update_fields[]" value="savings" class="text-green-600 border-gray-300 rounded shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50" checked>
                                        <span class="ml-2 text-sm text-gray-700">Savings</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="update_fields[]" value="loan_repay" class="text-green-600 border-gray-300 rounded shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50" checked>
                                        <span class="ml-2 text-sm text-gray-700">Loan Repayment</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="update_fields[]" value="loan_int" class="text-green-600 border-gray-300 rounded shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50" checked>
                                        <span class="ml-2 text-sm text-gray-700">Loan Interest</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="update_fields[]" value="essential" class="text-green-600 border-gray-300 rounded shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50" checked>
                                        <span class="ml-2 text-sm text-gray-700">Essential</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="update_fields[]" value="non_essential" class="text-green-600 border-gray-300 rounded shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50" checked>
                                        <span class="ml-2 text-sm text-gray-700">Non-Essential</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="update_fields[]" value="electronics" class="text-green-600 border-gray-300 rounded shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50" checked>
                                        <span class="ml-2 text-sm text-gray-700">Electronics</span>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-700">Missing Data Handling</label>
                                <div class="flex space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="missing_data" value="skip" class="text-green-600 border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50" checked>
                                        <span class="ml-2 text-sm text-gray-700">Skip (No Change)</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="missing_data" value="zero" class="text-green-600 border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">Use Zero</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- File Selection Display and Action Buttons --}}
                        <div class="flex items-center justify-between">
                            <div id="fileSelected" class="hidden text-sm text-gray-600">
                                Selected file: <span id="fileName" class="font-medium"></span>
                            </div>
                            <div class="flex space-x-3">
                                <button type="button" id="resetButton" class="px-4 py-2 text-white transition-all duration-200 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 border border-transparent rounded-lg shadow-sm hover:shadow-md">
                                    Reset
                                </button>
                                <button type="submit" id="uploadButton" class="px-4 py-2 text-white transition-all duration-200 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 rounded-lg shadow-sm hover:shadow-md" disabled>
                                    Upload & Process
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Template Download Link --}}
                    <div class="flex items-center justify-between p-4 rounded-lg bg-gray-50">
                        <div>
                            <h3 class="font-medium text-gray-800">Need a template?</h3>
                            <p class="text-sm text-gray-600">Download our CSV template with the correct format</p>
                        </div>
                        <a href="{{ route('admin.bulk_updates.template') }}" class="flex items-center px-4 py-2 text-white transition-all duration-200 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 border border-transparent rounded-lg shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download Template
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Help Section --}}
        <div class="lg:col-span-1">
            <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
                <!-- Card Header -->
                <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                    <div class="flex items-center">
                        <div class="p-2 mr-4 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">How It Works</h2>
                            <p class="mt-1 text-sm text-gray-600">Step-by-step guide for bulk updates</p>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-8">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 mr-3 text-green-600 bg-green-100 rounded-full"> 1 </div>
                            <div>
                                <h3 class="font-medium text-gray-800">Prepare Your Excel File</h3>
                                <p class="mt-1 text-sm text-gray-600">Ensure your Excel file follows the required format with the correct column headers.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 mr-3 text-green-600 bg-green-100 rounded-full"> 2 </div>
                            <div>
                                <h3 class="font-medium text-gray-800">Upload & Configure</h3>
                                <p class="mt-1 text-sm text-gray-600">Upload your file and select which fields to update and how to handle missing data.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 mr-3 text-green-600 bg-green-100 rounded-full"> 3 </div>
                            <div>
                                <h3 class="font-medium text-gray-800">Preview & Validate</h3>
                                <p class="mt-1 text-sm text-gray-600">Review the data before processing to ensure everything is correct.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 mr-3 text-green-600 bg-green-100 rounded-full"> 4 </div>
                            <div>
                                <h3 class="font-medium text-gray-800">Process Updates</h3>
                                <p class="mt-1 text-sm text-gray-600">Confirm to update all member accounts with the new data.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Important Notes Section --}}
                    <div class="p-4 mt-6 border border-yellow-200 rounded-lg bg-yellow-50">
                        <h3 class="flex items-center font-medium text-yellow-800">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Important Notes
                        </h3>
                        <ul class="mt-2 space-y-1 text-sm text-yellow-700 list-disc list-inside">
                            <li>Members are matched by their Cooperative Number (COOPNO)</li>
                            <li>Empty cells will be handled according to your "Missing Data" selection</li>
                            <li>All updates are logged for audit purposes</li>
                            <li>Members will receive notifications about updates to their accounts</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Monthly Uploads Section --}}
    <div class="mt-6 overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
        <!-- Card Header -->
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
            <div class="flex items-center">
                <div class="p-2 mr-4 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Recent Monthly Uploads</h2>
                    <p class="mt-1 text-sm text-gray-600">View history of bulk financial record uploads</p>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="p-8">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden border-b border-gray-200 shadow-sm sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Month</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Description</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">File Name</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Records</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Uploaded By</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Upload Date</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentUploads ?? [] as $upload)
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                            {{ $upload->formatted_date }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $upload->description }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $upload->file_name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ $upload->processed_records }}/{{ $upload->total_records }}
                                            @if($upload->failed_records > 0)
                                                <span class="text-red-600">({{ $upload->failed_records }} failed)</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($upload->status === 'completed')
                                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Completed</span>
                                            @elseif($upload->status === 'processing')
                                                <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Processing</span>
                                            @elseif($upload->status === 'failed')
                                                <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Failed</span>
                                            @elseif($upload->status === 'reversed')
                                                <span class="px-2 py-1 text-xs font-semibold text-orange-800 bg-orange-100 rounded-full">Reversed</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full">{{ ucfirst($upload->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ $upload->uploader->name ?? 'Unknown' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ $upload->created_at->format('M j, Y g:i A') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                @if($upload->status === 'completed' && $upload->processed_records > 0)
                                                    <a href="{{ route('admin.bulk_updates.transactions', $upload) }}"
                                                        class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-700 bg-blue-100 border border-blue-200 rounded-md hover:bg-blue-200 transition-colors">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        View Details
                                                    </a>

                                                    @php
                                                        // Check if this is the most recent upload (only allow reversal of most recent)
                                                        $isLatestUpload = $recentUploads->first() && $recentUploads->first()->id === $upload->id;
                                                    @endphp

                                                    @if($isLatestUpload)
                                                        <button onclick="showReversalModal({{ $upload->id }}, {{ json_encode($upload->formatted_date) }}, {{ $upload->processed_records }}); return false;"
                                                                class="reversal-button inline-flex items-center px-3 py-1 text-xs font-medium text-red-700 rounded-md transition-all duration-200"
                                                                type="button">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                            </svg>
                                                            Reverse Upload
                                                        </button>
                                                    @else
                                                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-gray-500 bg-gray-100 border border-gray-200 rounded-md cursor-not-allowed" title="Only the most recent upload can be reversed">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                                            </svg>
                                                            Cannot Reverse
                                                        </span>
                                                    @endif
                                                @elseif($upload->status === 'failed')
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-700 bg-red-100 border border-red-200 rounded-md">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                                                        </svg>
                                                        Failed
                                                    </span>
                                                @elseif($upload->status === 'reversed')
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-orange-700 bg-orange-100 border border-orange-200 rounded-md">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                        </svg>
                                                        Reversed
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-gray-700 bg-gray-100 border border-gray-200 rounded-md">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        {{ ucfirst($upload->status) }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-12 h-12 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    <p class="text-lg font-medium text-gray-900 mb-2">No bulk uploads found</p>
                                                    <p class="text-sm text-gray-500">Upload your first CSV file to get started</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Reversal Confirmation Modal --}}
<div id="reversalModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 border border-red-200">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                    <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Reverse Upload
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" onclick="hideReversalModal()">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form id="reversalForm" method="POST" class="p-4 md:p-5">
                @csrf
                @method('DELETE')
                <div class="mb-4 bg-red-50 p-3 rounded-lg border border-red-100">
                    <p class="text-sm text-red-800">
                        <strong>Warning:</strong> This will undo all financial updates processed for <span id="reversalMonthText" class="font-bold underline"></span>. This action is permanent.
                    </p>
                    <p class="mt-2 text-xs text-red-600">
                        Affected records: <span id="reversalCountText" class="font-bold"></span>
                    </p>
                </div>
                
                <div class="mb-4">
                    <label for="reversal_reason" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Reason for Reversal</label>
                    <textarea id="reversal_reason" name="reversal_reason" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-red-500 focus:border-red-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Provide a reason for reversing this upload (minimum 10 characters)..." required></textarea>
                </div>
                
                <div class="flex items-center space-x-3">
                    <button type="submit" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                        Yes, Reverse Upload
                    </button>
                    <button type="button" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" onclick="hideReversalModal()">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('excelUpload');
        const uploadBtn = document.getElementById('uploadButton');
        const resetBtn = document.getElementById('resetButton');
        const fileInfo = document.getElementById('fileInfo');
        const selectedFileName = document.getElementById('selectedFileName');
        const fileSelected = document.getElementById('fileSelected');
        const fileName = document.getElementById('fileName');

        function updateState() {
            const hasFile = fileInput && fileInput.files && fileInput.files.length > 0;
            if (hasFile) {
                uploadBtn.removeAttribute('disabled');
                const name = fileInput.files[0].name;
                if (selectedFileName) selectedFileName.textContent = name;
                if (fileName) fileName.textContent = name;
                if (fileInfo) fileInfo.classList.remove('hidden');
                if (fileSelected) fileSelected.classList.remove('hidden');
            } else {
                uploadBtn.setAttribute('disabled', 'disabled');
                if (fileInfo) fileInfo.classList.add('hidden');
                if (fileSelected) fileSelected.classList.add('hidden');
            }
        }

        if (fileInput) {
            fileInput.addEventListener('change', updateState);
        }
        if (resetBtn) {
            resetBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (fileInput) fileInput.value = '';
                updateState();
            });
        }

        // Initialize state on load
        updateState();
    });

    function showReversalModal(uploadId, monthName, recordCount) {
        const modalEl = document.getElementById('reversalModal');
        if (!modalEl) return;
        
        // Set content
        const form = document.getElementById('reversalForm');
        form.action = `/admin/bulk-updates/${uploadId}/reverse`;
        
        document.getElementById('reversalMonthText').textContent = monthName;
        document.getElementById('reversalCountText').textContent = recordCount;
        
        // Clear reason
        document.getElementById('reversal_reason').value = '';
        
        // Use project's native showModal function
        if (window.showModal) {
            window.showModal(modalEl);
        } else {
            modalEl.classList.remove('hidden');
        }
    }

    function hideReversalModal() {
        const modalEl = document.getElementById('reversalModal');
        if (!modalEl) return;

        if (window.hideModal) {
            window.hideModal(modalEl);
        } else {
            modalEl.classList.add('hidden');
        }
    }
</script>
@endpush

@endsection
