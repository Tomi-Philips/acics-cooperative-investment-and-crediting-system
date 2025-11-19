@extends('layouts.app')

@section('content')
    <div class="max-w-4xl px-4 py-10 mx-auto sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white rounded-lg shadow-lg">
            <div class="px-6 py-8">
                <div class="flex flex-col items-start justify-between mb-6 md:items-center md:flex-row">
                    <h1 class="mb-2 text-2xl font-bold text-gray-900 md:mb-0">Application Status</h1>
                    <div>
                        <span
                            class="inline-flex items-center px-3 py-1 text-sm font-medium text-white rounded-full @if ($applicationData['status'] == 'pending') bg-yellow-500 @elseif($applicationData['status'] == 'verified') bg-blue-500 @elseif($applicationData['status'] == 'approved') bg-green-500 @elseif($applicationData['status'] == 'rejected') bg-red-500 @else bg-gray-500 @endif">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                @if ($applicationData['status'] == 'pending')
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd"></path>
                                @elseif($applicationData['status'] == 'verified')
                                    <path fill-rule="evenodd"
                                        d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                @elseif($applicationData['status'] == 'approved')
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                @elseif($applicationData['status'] == 'rejected')
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"></path>
                                @else
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                        clip-rule="evenodd"></path>
                                @endif
                            </svg>
                            @if ($applicationData['status'] == 'pending')
                                Pending Approval
                            @elseif($applicationData['status'] == 'approved')
                                Approved
                            @elseif($applicationData['status'] == 'rejected')
                                Rejected
                            @else
                                {{ ucfirst($applicationData['status']) }}
                            @endif
                        </span>
                        @if ($applicationData['status'] == 'approved')
                            @php
                                $token = $applicationData['password_token'] ?? session('password_reset_token', '');
                                $url = route('set_password', ['email' => $applicationData['email'], 'token' => $token]);
                            @endphp
                            <a href="{{ $url }}"
                                class="inline-flex items-center px-3 py-1 ml-2 text-sm font-medium text-white bg-green-600 rounded-full hover:bg-green-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                Set Password & Login
                            </a>
                        @endif
                    </div>
                </div>

                <div class="p-4 mb-6 border border-gray-200 rounded-lg bg-gray-50">
                    <h2 class="mb-2 text-lg font-semibold text-gray-800">Applicant Details</h2>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-sm text-gray-500">Reference Number</p>
                            <p class="font-medium text-gray-800">{{ $applicationData['reference_number'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Application Date</p>
                            <p class="font-medium text-gray-800">{{ $applicationData['application_date'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Full Name</p>
                            <p class="font-medium text-gray-800">{{ $applicationData['name'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email Address</p>
                            <p class="font-medium text-gray-800">{{ $applicationData['email'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Department</p>
                            <p class="font-medium text-gray-800">{{ $applicationData['department'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Staff ID</p>
                            <p class="font-medium text-gray-800">{{ $applicationData['staff_id'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="mb-4 text-lg font-semibold text-gray-800">Application Progress</h2>
                    <div class="space-y-4">
                        @foreach ($applicationData['status_history'] as $step)
                            <div class="relative flex items-start pb-4 @if (!$loop->last) border-l-2 border-gray-200 @endif ml-3">
                                <div class="absolute -left-3.5 mt-1">
                                    <div
                                        class="flex items-center justify-center w-7 h-7 rounded-full @if (isset($step['rejected']) && $step['rejected']) bg-red-100 @elseif($step['completed']) bg-green-100 @else bg-gray-100 @endif">
                                        @if (isset($step['rejected']) && $step['rejected'])
                                            <svg class="w-4 h-4 text-red-600" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @elseif($step['completed'])
                                            <svg class="w-4 h-4 text-green-600" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="ml-6">
                                    <div class="flex items-center">
                                        <h3
                                            class="text-base font-medium @if (isset($step['rejected']) && $step['rejected']) text-red-800 @elseif($step['completed']) text-green-800 @else text-gray-800 @endif">
                                            {{ isset($step['label']) ? $step['label'] : $step['status'] }}
                                        </h3>
                                        @if ($step['date'])
                                            <p class="ml-2 text-sm text-gray-500">{{ $step['date'] }}</p>
                                        @endif
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600">{{ $step['description'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div
                    class="p-4 mb-6 border rounded-lg @if ($applicationData['status'] == 'pending') border-yellow-200 bg-yellow-50 @elseif($applicationData['status'] == 'verified') border-blue-200 bg-blue-50 @elseif($applicationData['status'] == 'approved') border-green-200 bg-green-50 @elseif($applicationData['status'] == 'rejected') border-red-200 bg-red-50 @else border-gray-200 bg-gray-50 @endif">
                    <h3
                        class="text-sm font-medium @if ($applicationData['status'] == 'pending') text-yellow-800 @elseif($applicationData['status'] == 'verified') text-blue-800 @elseif($applicationData['status'] == 'approved') text-green-800 @elseif($applicationData['status'] == 'rejected') text-red-800 @else text-gray-800 @endif">
                        Next Steps</h3>
                    <div
                        class="mt-2 text-sm @if ($applicationData['status'] == 'pending') text-yellow-700 @elseif($applicationData['status'] == 'verified') text-blue-700 @elseif($applicationData['status'] == 'approved') text-green-700 @elseif($applicationData['status'] == 'rejected') text-red-700 @else text-gray-700 @endif">
                        @if ($applicationData['status'] == 'pending')
                            <p>Your application is currently **pending approval** by the committee.</p>
                            <p class="mt-2">This usually takes 1-3 business days. You'll be able to check the status here or
                                receive an email notification once a decision has been made.</p>
                        @elseif($applicationData['status'] == 'approved')
                            <p>Congratulations! Your membership application has been **approved**.</p>
                            <p class="mt-2">Click the <span class="font-semibold">"Set Password & Login"</span> button
                                above to create your password and access your account.</p>
                        @elseif($applicationData['status'] == 'rejected')
                            <p>We regret to inform you that your application has been **rejected**. For more information,
                                please contact our office.</p>
                        @else
                            <p>Please check back later for updates on your application.</p>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col mt-8 space-y-3 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Return to Home
                    </a>
                    <a href="#" onclick="window.print();return false;"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print Status
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection