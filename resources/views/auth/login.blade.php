<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACICS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Modal transition effects */
        .modal-enter {
            opacity: 0;
        }
        .modal-enter-active {
            opacity: 1;
            transition: opacity 200ms;
        }
        .modal-exit {
            opacity: 1;
        }
        .modal-exit-active {
            opacity: 0;
            transition: opacity 200ms;
        }
    </style>
</head>
<body>
    <main class="flex">
        <div class="absolute inset-0 -z-10 h-full w-full bg-white bg-[linear-gradient(to_right,#4ade80_1px,transparent_1px),linear-gradient(to_bottom,#4ade80_1px,transparent_1px)] bg-[size:6rem_4rem]">
            <div class="absolute bottom-0 left-0 right-0 top-0 bg-[radial-gradient(circle_800px_at_100%_200px,#86efac,transparent)]">
            </div>
        </div>

        <div class="w-full md:w-[50%] lg:w-full hidden md:flex justify-center h-[100vh] relative">
            <div class="absolute right-0 inset-y-0 w-[2px] bg-gradient-to-b from-transparent via-slate-600 to-transparent"></div>
            <img src="{{ asset('images/Tablet login-rafiki.png') }}" alt="Login Illustration" class="object-contain w-4/5 max-w-xl">
        </div>

        <section class="flex items-center justify-center w-full h-screen bg-gray-50">
            <div class="w-full max-w-md px-4">
                <div class="p-8 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <div class="mb-4 text-center">
                        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full">
                            <span class="text-2xl">🔐</span>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900"> Welcome Back </h1>
                        <p class="mt-2 text-gray-500"> Sign in to your account </p>
                    </div>

                    <form class="space-y-5" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div>
                            <label for="login_identifier" class="block mb-2 text-sm font-medium text-gray-700"> Email Address or Member Number <span class="text-red-500">*</span> </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                </div>
                                <input type="text" id="login_identifier" name="login_identifier" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full pl-10 p-2.5 @error('login_identifier') border-red-500 @enderror" placeholder="name@company.com or Member Number" required value="{{ old('login_identifier') }}">
                            </div>
                            @error('login_identifier')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-700"> Password <span class="text-red-500">*</span> </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <input type="password" id="password" name="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full pl-10 p-2.5 pr-10 @error('password') border-red-500 @enderror" required>
                                <button type="button" id="password-toggle" class="absolute inset-y-0 right-0 flex items-center pr-3 focus:outline-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path id="eye-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path id="eye-closed" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 12a2 2 0 104 0 2 2 0 00-4 0z"></path>
                                        <path id="eye-slash" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.958 9.958 0 011.563-2.75"></path>
                                        <path id="eye-slash-2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.257 12H9a2 2 0 002 2h.257m-4.514 0A7.464 7.464 0 015 10a7.466 7.466 0 01.757-3.243M17.879 14H18a2 2 0 002-2h-.086a2 2 0 00-2-2zM16.68 7.32l-1.53 1.53"></path>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember" name="remember" type="checkbox" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <label for="remember" class="block ml-2 text-sm text-gray-700"> Remember me </label>
                            </div>
                            <a href="{{ route('reset') }}" class="text-sm font-medium text-green-600 hover:text-green-500 hover:underline"> Forgot password? </a>
                        </div>

                        <button type="submit" class="flex items-center justify-center w-full gap-2 px-4 py-3 text-sm font-medium text-white transition-colors duration-200 bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            <span>🔓</span> Sign In
                        </button>

                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 text-gray-500 bg-white"> Not a member yet? </span>
                            </div>
                        </div>

                        <div class="flex justify-center space-x-4">
                            <a href="{{ route('membership_registration') }}" class="text-sm font-medium text-green-600 hover:text-green-500 hover:underline"> Apply for Membership </a>
                            <span class="text-gray-400">|</span>
                            <a href="#" id="track-application-btn" class="text-sm font-medium text-blue-600 hover:text-blue-500 hover:underline"> Track Application </a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <div id="tracking-modal" class="fixed inset-0 z-50 items-center justify-center hidden p-4 overflow-x-hidden overflow-y-auto">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"></div>

        <div class="relative w-full max-w-md p-6 mx-auto bg-white rounded-lg shadow-xl">
            <button type="button" id="close-modal-btn" class="absolute text-gray-400 top-4 right-4 hover:text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <div class="flex items-start">
                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Track Your Application</h3>
                    <p class="mt-1 text-sm text-gray-500">Enter the email you used for registration to check your application status.</p>
                </div>
            </div>

            <div class="mt-4">
                <div>
                    <label for="track-email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" id="track-email" required class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="Enter your registered email">
                </div>
                <div class="mt-4">
                    <label for="track-reference" class="block text-sm font-medium text-gray-700">Reference Number <span class="text-xs text-gray-500">(Optional)</span></label>
                    <input type="text" id="track-reference" class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="e.g., ACICS-20250521-1234">
                    <p class="mt-1 text-xs text-gray-500">If you have multiple applications, enter the reference number to check a specific one.</p>
                </div>

                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="button" id="check-status-btn" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:col-start-2 sm:text-sm"> Check Status </button>
                    <button type="button" id="cancel-btn" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:col-start-1 sm:text-sm"> Cancel </button>
                </div>
            </div>
        </div>
    </div>

    <a id="tracking-link" href="" style="display: none;"></a>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            const passwordInput = document.getElementById('password');
            const passwordToggle = document.getElementById('password-toggle');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
            const eyeSlash = document.getElementById('eye-slash');
            const eyeSlash2 = document.getElementById('eye-slash-2');

            // Initially hide the 'eye-closed', 'eye-slash' and 'eye-slash-2' paths within the SVG
            eyeClosed.style.display = 'none';
            eyeSlash.style.display = 'none';
            eyeSlash2.style.display = 'none';

            passwordToggle.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                if (type === 'password') {
                    eyeOpen.style.display = 'block';
                    eyeClosed.style.display = 'none';
                    eyeSlash.style.display = 'none';
                    eyeSlash2.style.display = 'none';
                } else {
                    eyeOpen.style.display = 'none';
                    eyeClosed.style.display = 'block';
                    eyeSlash.style.display = 'block';
                    eyeSlash2.style.display = 'block';
                }
            });

            // Modal functionality
            const trackBtn = document.getElementById('track-application-btn');
            const modal = document.getElementById('tracking-modal');
            const closeBtn = document.getElementById('close-modal-btn');
            const cancelBtn = document.getElementById('cancel-btn');

            function toggleModal() {
                modal.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden');
            }

            trackBtn.addEventListener('click', function(e) {
                e.preventDefault();
                toggleModal();
            });

            closeBtn.addEventListener('click', toggleModal);
            cancelBtn.addEventListener('click', toggleModal);

            // Handle check status button click
            const checkStatusBtn = document.getElementById('check-status-btn');
            const trackEmail = document.getElementById('track-email');
            const trackingLink = document.getElementById('tracking-link');

            if (checkStatusBtn && trackingLink) {
                checkStatusBtn.addEventListener('click', function() {
                    const email = trackEmail.value;
                    if (email && email.trim() !== '') {
                        // Get reference number if provided
                        const reference = document.getElementById('track-reference').value.trim();

                        // Build the URL with email and optional reference number
                        let url = '{{ route("application.status.check.email", ["email" => "__EMAIL__"]) }}'.replace('__EMAIL__', encodeURIComponent(email));

                        // Add reference as a query parameter if provided
                        if (reference) {
                            url = url + '?reference=' + encodeURIComponent(reference);
                        }

                        window.location.href = url;
                    } else {
                        alert('Please enter your email address');
                    }
                });
            }

            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    toggleModal();
                }
            });

            // Close with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    toggleModal();
                }
            });
        });
    </script>
</body>
</html>