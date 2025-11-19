@extends('layouts.app')

@section('content')
    <div class="max-w-3xl px-4 py-10 mx-auto sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white rounded-lg shadow-lg">
            <div class="px-6 py-8">
                {{-- Page Title and Description --}}
                <h1 class="mb-6 text-2xl font-bold text-center text-gray-900">Check Application Status</h1>
                <p class="mb-6 text-sm text-gray-600">
                    Enter your application reference number and email address to check the status of your membership application.
                </p>

                {{-- Error Message Display --}}
                @if ($errors->any())
                    <div class="p-4 mb-6 border border-red-200 rounded-lg bg-red-50">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">There were errors with your request</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="pl-5 space-y-1 list-disc">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Application Status Check Form --}}
                <form method="POST" action="{{ route('application.status.check') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block mb-1 text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="The email you used for registration" required
                            class="block w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="reference_number" class="block mb-1 text-sm font-medium text-gray-700">Reference Number <span class="text-sm text-gray-500">(Optional)</span></label>
                        <input type="text" id="reference_number" name="reference_number" value="{{ old('reference_number') }}" placeholder="e.g., ACICS-20250521-1234"
                            class="block w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">If you have multiple applications, enter your reference number to check a specific one.</p>
                    </div>
                    <div>
                        <button type="submit" class="w-full py-3 text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            Check Status
                        </button>
                    </div>
                </form>

                {{-- Contact Support Link --}}
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-600">
                        Don't have your reference number? <a href="{{ route('login') }}" class="text-green-600 hover:underline">Contact support</a>
                    </p>
                </div>

                {{-- Application Process Section --}}
                <div class="p-4 mt-8 border border-blue-200 rounded-lg bg-blue-50">
                    <h3 class="text-sm font-medium text-blue-800">Application Process</h3>
                    <ol class="mt-2 ml-5 text-sm text-blue-700 list-decimal">
                        <li class="mb-1">Submit your online application</li>
                        <li class="mb-1">Visit our office for physical verification within 14 days</li>
                        <li class="mb-1">Verification and approval by the membership committee</li>
                        <li class="mb-1">Receive membership approval and set up your password</li>
                        <li>Begin using your membership benefits</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection