@extends('layouts.admin')

@section('content')
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Upload Successful</h1>
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
                                class="ml-1 text-sm font-medium text-gray-700 hover:text-green-600 md:ml-2">Bulk Updates</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Upload Success</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        {{-- Success Summary --}}
        <div class="p-6 mb-6 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h2 class="text-xl font-semibold text-green-800">Monthly Upload Completed Successfully!</h2>
                    <p class="text-sm text-green-700">{{ $monthlyUpload->formatted_date }} financial records have been processed.</p>
                </div>
            </div>
        </div>

        {{-- Upload Statistics --}}
        <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-4">
            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Records</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $monthlyUpload->total_records }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Successfully Processed</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $monthlyUpload->processed_records }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Failed Records</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $monthlyUpload->failed_records }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Success Rate</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $monthlyUpload->success_rate }}%</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Upload Details --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            {{-- Upload Information --}}
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Upload Information</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-600">File Name:</dt>
                        <dd class="text-sm text-gray-900">{{ $monthlyUpload->file_name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-600">Upload Type:</dt>
                        <dd class="text-sm text-gray-900">
                            @if(in_array('upload_type', $monthlyUpload->update_fields ?? []) && $monthlyUpload->update_fields['upload_type'] === 'cumulative_balances')
                                <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Cumulative Balances</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Monthly Contributions</span>
                            @endif
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-600">Fields Updated:</dt>
                        <dd class="text-sm text-gray-900">{{ implode(', ', $monthlyUpload->update_fields ?? []) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-600">Uploaded By:</dt>
                        <dd class="text-sm text-gray-900">{{ $monthlyUpload->uploader->name ?? 'Unknown' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-600">Upload Time:</dt>
                        <dd class="text-sm text-gray-900">{{ $monthlyUpload->created_at->format('M j, Y g:i A') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-600">Processing Time:</dt>
                        <dd class="text-sm text-gray-900">
                            @if($monthlyUpload->upload_completed_at && $monthlyUpload->upload_started_at)
                                {{ $monthlyUpload->upload_completed_at->diffInSeconds($monthlyUpload->upload_started_at) }} seconds
                            @else
                                N/A
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Processing Summary --}}
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Processing Summary</h3>
                @if($monthlyUpload->processing_summary)
                    <div class="space-y-3">
                        @foreach($monthlyUpload->processing_summary as $key => $value)
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-600">{{ ucwords(str_replace('_', ' ', $key)) }}:</dt>
                                <dd class="text-sm text-gray-900">{{ is_array($value) ? implode(', ', $value) : $value }}</dd>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">No processing summary available.</p>
                @endif
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex justify-between mt-6">
            <a href="{{ route('admin.bulk_updates') }}" 
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                ← Back to Bulk Updates
            </a>
            
            <div class="space-x-3">
                <a href="{{ route('admin.bulk_updates.transactions', $monthlyUpload) }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    View Transaction Details
                </a>

                <a href="{{ route('admin.bulk_updates.integrity', $monthlyUpload) }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-purple-600 border border-transparent rounded-md shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    Verify Data Integrity
                </a>

                <a href="{{ route('admin.users.all') }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    View Updated Members
                </a>
            </div>
        </div>
    </div>
@endsection
