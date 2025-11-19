<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - ACICS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('build/assets/app-LF8p98BY.css') }}">
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md overflow-hidden bg-white rounded-lg shadow-md">
            <div class="p-6">
                {{-- Icon and Title --}}
                <div class="flex justify-center mb-6">
                    <div class="flex items-center justify-center w-20 h-20 bg-red-100 rounded-full">
                        <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <h1 class="mb-2 text-2xl font-bold text-center text-gray-900">Access Denied</h1>
                <p class="mb-6 text-center text-gray-600">
                    {{ $exception->getMessage() ?: 'You do not have permission to access this page.' }}
                </p>

                {{-- Action Buttons --}}
                <div class="flex flex-col space-y-3">
                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="w-full px-4 py-2 font-medium text-center text-white transition-colors duration-200 bg-green-600 rounded-lg hover:bg-green-700">
                                Return to Admin Dashboard
                            </a>
                        @else
                            <a href="{{ route('user.dashboard') }}" class="w-full px-4 py-2 font-medium text-center text-white transition-colors duration-200 bg-green-600 rounded-lg hover:bg-green-700">
                                Return to Dashboard
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 font-medium text-center text-gray-700 transition-colors duration-200 border border-gray-300 rounded-lg hover:bg-gray-50">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="w-full px-4 py-2 font-medium text-center text-white transition-colors duration-200 bg-green-600 rounded-lg hover:bg-green-700">
                            Login
                        </a>
                        <a href="{{ route('home') }}" class="w-full px-4 py-2 font-medium text-center text-gray-700 transition-colors duration-200 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Return to Home
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</body>
</html>