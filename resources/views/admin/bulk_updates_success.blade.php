@extends('layouts.admin')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Bulk Update Complete</h1>
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-green-600">
                        <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="{{ route('admin.bulk_updates') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-green-600 md:ml-2">Bulk Updates</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Success</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="p-6 mb-6 bg-white rounded-lg shadow-md">
        <div class="mb-8 text-center">
            <div class="flex items-center justify-center w-20 h-20 mx-auto mb-4 bg-green-100 rounded-full">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="mb-2 text-2xl font-bold text-gray-900">Update Successful!</h2>
            <p class="text-gray-600">The member data has been successfully updated.</p>
        </div>

        <div class="max-w-2xl p-6 mx-auto mb-8 rounded-lg bg-gray-50">
            <h3 class="mb-4 text-lg font-semibold text-gray-800">Update Summary</h3>
            <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-2">
                <div>
                    <p class="text-sm text-gray-600">File Name:</p>
                    <p class="font-medium">{{ $fileName }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Transaction Date:</p>
                    <p class="font-medium">{{ $transactionDate }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Description:</p>
                    <p class="font-medium">{{ $description }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Processed At:</p>
                    <p class="font-medium">{{ $processedAt }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6 md:grid-cols-4">
                <div class="p-4 bg-white rounded-lg shadow-sm">
                    <div class="text-2xl font-bold text-green-600">{{ $totalRecords }}</div>
                    <div class="text-sm text-gray-600">Total Records</div>
                </div>
                <div class="p-4 bg-white rounded-lg shadow-sm">
                    <div class="text-2xl font-bold text-green-600">{{ $successCount }}</div>
                    <div class="text-sm text-gray-600">Successfully Updated</div>
                </div>
                <div class="p-4 bg-white rounded-lg shadow-sm">
                    <div class="text-2xl font-bold text-yellow-600">{{ $skippedCount }}</div>
                    <div class="text-sm text-gray-600">Skipped Records</div>
                </div>
                <div class="p-4 bg-white rounded-lg shadow-sm">
                    <div class="text-2xl font-bold text-red-600">{{ $errorCount }}</div>
                    <div class="text-sm text-gray-600">Failed Updates</div>
                </div>
            </div>

            <div>
                <h4 class="mb-2 font-medium text-gray-800">Fields Updated:</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach($updatedFields as $field)
                    <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded">
                        {{ ucfirst(str_replace('_', ' ', $field)) }}
                    </span>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex flex-col justify-center gap-4 sm:flex-row">
            <a href="{{ route('admin.bulk_updates') }}" class="px-6 py-3 text-center text-gray-700 transition-colors bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                Upload Another File
            </a>
            <a href="{{ route('admin.bulk_updates.details', ['id' => $uploadId]) }}" class="px-6 py-3 text-center text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                View Detailed Report
            </a>
        </div>
    </div>

    <div class="p-6 bg-white rounded-lg shadow-md">
        <h2 class="mb-4 text-xl font-semibold text-gray-800">Next Steps</h2>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="p-4 transition-all border border-gray-200 rounded-lg hover:border-green-300 hover:shadow-md">
                <div class="flex items-center mb-3">
                    <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 mr-3 text-green-600 bg-green-100 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="font-medium text-gray-800">Generate Reports</h3>
                </div>
                <p class="text-sm text-gray-600">Generate reports based on the updated data to share with management or for record keeping.</p>
                <div class="mt-4">
                    <a href="{{ route('admin.reports') }}" class="text-sm font-medium text-green-600 hover:text-green-800"> Go to Reports → </a>
                </div>
            </div>
            <div class="p-4 transition-all border border-gray-200 rounded-lg hover:border-green-300 hover:shadow-md">
                <div class="flex items-center mb-3">
                    <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 mr-3 text-green-600 bg-green-100 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-medium text-gray-800">View Member Accounts</h3>
                </div>
                <p class="text-sm text-gray-600">Check individual member accounts to verify the updates have been applied correctly.</p>
                <div class="mt-4">
                    <a href="{{ route('admin.users.all') }}" class="text-sm font-medium text-green-600 hover:text-green-800"> View Members → </a>
                </div>
            </div>
            <div class="p-4 transition-all border border-gray-200 rounded-lg hover:border-green-300 hover:shadow-md">
                <div class="flex items-center mb-3">
                    <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 mr-3 text-green-600 bg-green-100 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <h3 class="font-medium text-gray-800">Send Notifications</h3>
                </div>
                <p class="text-sm text-gray-600">Notify members about the updates to their accounts via email or SMS.</p>
                <div class="mt-4">
                    <a href="#" class="text-sm font-medium text-green-600 hover:text-green-800"> Send Notifications → </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection