<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - ACICS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('build/assets/app-LF8p98BY.css') }}">
</head>

<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md overflow-hidden bg-white rounded-lg shadow-md">
            <div class="p-6">
                <div class="flex justify-center mb-6">
                    <div class="flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full">
                        <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h1 class="mb-2 text-2xl font-bold text-center text-gray-900">Page Not Found</h1>
                <p class="mb-6 text-center text-gray-600">
                    The page you are looking for doesn't exist or has been moved.
                </p>
                <div class="flex flex-col space-y-3">
                    @auth
                        @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                                class="w-full px-4 py-2 font-medium text-center text-white transition-colors duration-200 bg-green-600 rounded-lg hover:bg-green-700">
                                Return to Admin Dashboard
                            </a>
                        @else
                            <a href="{{ route('user.dashboard') }}"
                                class="w-full px-4 py-2 font-medium text-center text-white transition-colors duration-200 bg-green-600 rounded-lg hover:bg-green-700">
                                Return to Dashboard
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                            class="w-full px-4 py-2 font-medium text-center text-white transition-colors duration-200 bg-green-600 rounded-lg hover:bg-green-700">
                            Login
                        </a>
                    @endauth
                    <a href="{{ route('home') }}"
                        class="w-full px-4 py-2 font-medium text-center text-gray-700 transition-colors duration-200 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Return to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>