<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACICS - Password Reset</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
    <main class="flex flex-col min-h-screen lg:flex-row bg-gray-50">
        <div class="items-center justify-center hidden p-8 md:flex lg:w-1/2 bg-gradient-to-br from-green-50 to-green-100">
            <div class="max-w-md">
                <img src="{{ asset('images/Sign up-pana.png') }}" alt="Password recovery illustration"
                    class="object-contain w-full h-auto" loading="lazy">
                <div class="mt-8 text-center">
                    <h3 class="text-xl font-semibold text-gray-800">Trouble with your password?</h3>
                    <p class="mt-2 text-gray-600">We'll help you reset it and get back to your account securely.</p>
                </div>
            </div>
        </div>

        <section class="flex items-center justify-center w-full p-4 lg:w-1/2 sm:p-8">
            <div class="w-full max-w-md overflow-hidden bg-white border border-green-100 shadow-lg rounded-xl">
                <div class="p-6 sm:p-8">
                    <div class="mb-8 text-center">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-green-100 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-800 sm:text-3xl">Reset your password</h1>
                        <p class="mt-3 text-sm text-gray-600">
                            Remember your password?
                            <a href="{{ route('login') }}"
                                class="font-medium text-green-600 transition-colors hover:text-green-500">
                                Sign in here
                            </a>
                        </p>
                    </div>

                    <form class="space-y-6" action="{{ route('reset.post') }}" method="POST">
                        @csrf

                        @if (session('status'))
                            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div>
                            <label for="email" class="block mb-1 text-sm font-medium text-gray-700">Email address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input type="email" id="email" name="email" required
                                    class="block w-full px-4 py-3 pl-10 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @enderror"
                                    placeholder="your@email.com" value="{{ old('email') }}">
                            </div>
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <button type="submit"
                                class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition-colors bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Send reset link
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 text-sm text-center text-gray-500">
                        <p>Need help? <a href="#"
                                class="font-medium text-green-600 hover:text-green-500">Contact support</a></p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    @vite('resources/js/app.js')
</body>

</html>