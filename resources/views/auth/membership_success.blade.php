@extends('layouts.app')

@section('content')
    <div class="max-w-3xl px-4 py-12 mx-auto sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white rounded-lg shadow-lg">
            <div class="px-6 py-8">
                {{-- Submission Confirmation --}}
                <div class="text-center">
                    <div class="flex items-center justify-center w-16 h-16 mx-auto bg-green-100 rounded-full">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h1 class="mt-4 text-3xl font-bold text-gray-900">Application Submitted!</h1>
                    <p class="mt-2 text-lg text-gray-600">Your membership application has been received.</p>
                </div>

                {{-- Application Status Section --}}
                <div class="p-4 mt-8 border border-gray-200 rounded-lg bg-gray-50">
                    <h3 class="mb-3 text-lg font-medium text-gray-800">Application Status</h3>
                    <div class="flex items-center justify-center">
                        <div class="relative">
                            {{-- Step 1: Submitted --}}
                            <div class="flex items-center">
                                <div class="z-10 flex items-center justify-center w-10 h-10 bg-green-500 rounded-full">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 text-sm font-medium text-gray-700">Submitted</div>
                            </div>
                            <div class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-300 z-0"></div>
                        </div>
                        <div class="relative ml-16">
                            {{-- Step 2: Approved (Placeholder) --}}
                            <div class="flex items-center">
                                <div class="z-10 flex items-center justify-center w-10 h-10 bg-gray-300 rounded-full">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 text-sm font-medium text-gray-500">Approved</div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 text-center">
                        <div class="text-sm font-medium text-yellow-600">Current Status: Pending Approval</div>
                    </div>
                </div>

                {{-- Next Steps Section --}}
                <div class="mt-8">
                    <h3 class="mb-3 text-lg font-medium text-gray-800">Next Steps</h3>
                    <div class="p-4 border border-blue-200 rounded-lg bg-blue-50">
                        <ol class="pl-5 space-y-2 text-sm text-blue-700 list-decimal">
                            <li>Your application will be reviewed by the membership committee (1-3 business days)</li>
                            <li>Once approved, you'll receive a notification to set your password</li>
                            <li>Set your password to complete the registration and gain full access to your account</li>
                        </ol>
                    </div>
                </div>

                {{-- Application Reference Section --}}
                <div class="p-4 mt-8 border border-green-200 rounded-lg bg-green-50">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Application Reference</h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p>Your application reference number: <span class="font-bold">{{ $reference_number }}</span></p>
                                <p class="mt-1">Please save this reference number to check your application status later.</p>
                                <div class="mt-3">
                                    <a href="{{ route('application.status') }}" class="inline-flex items-center text-sm font-medium text-green-700 hover:text-green-900">
                                        <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                        Check Application Status
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col mt-8 space-y-3 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Return to Home
                    </a>
                    <a href="#" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Print Application
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection