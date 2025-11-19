@extends('layouts.admin')

@section('title', 'Bulk Upload - User Management')

@section('content')
<div class="container max-w-6xl mx-auto">
    <!-- Header Section -->
    <div class="p-6 mb-8 border border-gray-200 shadow-lg bg-gradient-to-r from-green-50 to-blue-50 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center mb-4">
                    <div class="p-3 mr-4 bg-green-100 rounded-lg">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
                        <p class="mt-2 text-gray-600">Create new users, manage existing member finances, or bulk upload multiple users</p>
                    </div>
                </div>

            </div>
            <div>
                <a href="{{ route('admin.users.all') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    View All Users
                </a>
            </div>
        </div>
        <!-- Enhanced Tab Navigation -->
        <div class="inline-flex gap-2 p-1 mt-2 bg-gray-100 rounded-lg shadow-inner" role="group">
            <a href="{{ route('admin.users.add') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-transparent border-0 rounded-md user-type-btn hover:bg-white hover:text-green-700 focus:z-10 focus:ring-2 focus:ring-green-500 transition-all duration-200">
                <svg class="inline w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                New User
            </a>
            <a href="{{ route('admin.users.add_finances') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-transparent border-0 rounded-md user-type-btn hover:bg-white hover:text-green-700 focus:z-10 focus:ring-2 focus:ring-green-500 transition-all duration-200">
                <svg class="inline w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
                Add Finances
            </a>
            <a href="{{ route('admin.users.bulk_upload') }}" class="px-4 py-2.5 text-sm font-medium text-white bg-green-600 border-0 rounded-md user-type-btn hover:bg-green-700 focus:z-10 focus:ring-2 focus:ring-green-500 transition-all duration-200 shadow-sm">
                <svg class="inline w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                </svg>
                Bulk Upload
            </a>
        </div>
    </div>

    <!-- Bulk Upload Form -->
    <div class="p-8 overflow-hidden bg-white border border-gray-100 shadow-lg rounded-xl">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800">Bulk User Upload</h2>
            <p class="mt-1 text-sm text-gray-500">Upload multiple users at once using a CSV or Excel file</p>
        </div>

        <!-- Upload Instructions -->
        <div class="p-6 mb-6 border border-blue-200 rounded-lg bg-blue-50">
            <h3 class="mb-3 text-lg font-semibold text-blue-800">Upload Instructions</h3>
            <ul class="space-y-2 text-sm text-blue-700">
                <li class="flex items-start">
                    <svg class="w-4 h-4 mt-0.5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Download the CSV template below and fill in the user information (you can also save it as .xlsx)
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 mt-0.5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Supports both CSV and Excel (.xlsx, .xls) file formats
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 mt-0.5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Ensure all required fields are filled (Name, Member Number, Department)
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 mt-0.5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Member numbers should follow the format: P/SS/759
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 mt-0.5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Financial amounts should be numeric (use 0 if no initial amount)
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 mt-0.5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <strong>Loan Amount:</strong> Enter the current outstanding loan balance (no interest will be added automatically)
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 mt-0.5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <strong>Loan Interest Amount:</strong> Enter any interest payments made separately (stored as payment history)
                </li>
            </ul>
        </div>

        <!-- Download Template -->
        <div class="mb-6">
            <a href="{{ route('admin.users.download_template') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-700 bg-blue-100 border border-blue-300 rounded-lg hover:bg-blue-200 focus:ring-4 focus:ring-blue-300 transition-all duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download Template (CSV)
            </a>
        </div>

        <!-- Upload Form -->
        <form action="{{ route('admin.users.bulk_upload.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- File Upload -->
            <div>
                <label for="excel_file" class="block mb-2 text-sm font-medium text-gray-700">
                    <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Select CSV or Excel File <span class="text-red-500">*</span>
                </label>
                <input type="file" id="excel_file" name="excel_file" accept=".csv,.xlsx,.xls" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100" required>
                @error('excel_file')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-6 border-t border-gray-200">
                <button type="submit" class="px-8 py-3 text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium text-sm transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                    </svg>
                    Upload Users
                </button>
            </div>
        </form>
    </div>

    <!-- Recent User Bulk Uploads -->
    <div class="mt-8 overflow-hidden bg-white border border-gray-200 shadow-lg rounded-xl">
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
            <h2 class="text-xl font-bold text-gray-800">Recent User Bulk Uploads</h2>
            <p class="mt-1 text-sm text-gray-600">Track your recent bulk upload activities</p>
        </div>

        <div class="p-8">
            @if($recentUploads->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">File Name</th>
                                <th scope="col" class="px-6 py-3">Total Records</th>
                                <th scope="col" class="px-6 py-3">Processed</th>
                                <th scope="col" class="px-6 py-3">Failed</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Uploaded By</th>
                                <th scope="col" class="px-6 py-3">Upload Date</th>
                                <th scope="col" class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentUploads as $upload)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $upload->file_name }}</td>
                                <td class="px-6 py-4">{{ $upload->total_records }}</td>
                                <td class="px-6 py-4 text-green-600">{{ $upload->processed_records }}</td>
                                <td class="px-6 py-4 text-red-600">{{ $upload->failed_records }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        @if($upload->status === 'completed') bg-green-100 text-green-800
                                        @elseif($upload->status === 'processing') bg-yellow-100 text-yellow-800
                                        @elseif($upload->status === 'failed') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($upload->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $upload->uploader->name ?? 'Unknown' }}</td>
                                <td class="px-6 py-4">{{ $upload->created_at->format('M d, Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.users.bulk_upload_details', $upload->id) }}"
                                       class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-700 bg-blue-100 border border-blue-200 rounded-md hover:bg-blue-200 transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        View Details
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-12 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No bulk uploads found</h3>
                    <p class="mt-1 text-sm text-gray-500">Upload your first CSV or Excel file to see history here.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
