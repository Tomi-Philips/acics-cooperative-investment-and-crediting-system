<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACICS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="text-gray-900 bg-white">
<header class="sticky top-0 z-50">
    @php
        use App\Models\Event;
        $activeEvent = Event::where('is_active', true)
            ->where('end_date', '>', now())
            ->orderBy('end_date')
            ->first();
    @endphp

    @if($activeEvent && $activeEvent->end_date)
        <div class="flex justify-center items-center gap-2.5 text-xs py-1.5 text-slate-600 bg-green-100 border-b border-green-200">
            <span class="flex items-center gap-1.5">
                <span class="text-sm">🎉</span> {{ $activeEvent->title }}
            </span>
            <button class="rounded-full py-1 px-2.5 border border-slate-600 border-dashed text-xs font-medium hover:bg-green-50 transition-colors">
                <span id="event-countdown"></span> remaining
            </button>
        </div>
    @else
        <div class="flex justify-center items-center gap-2.5 text-xs py-1.5 text-slate-600 bg-gray-100 border-b border-gray-200">
            <span class="flex items-center gap-1.5">
                <span class="text-sm">ℹ️</span> No upcoming events scheduled.
            </span>
        </div>
    @endif

    <nav class="w-full bg-white shadow-md">
        <div class="container px-4 py-3 mx-auto md:px-6 md:py-4">
            <div class="flex items-center justify-between">
                <a href="#" class="font-semibold text-green-900">
                    <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="w-36 md:w-40" />
                </a>

                <div class="hidden md:flex md:items-center md:gap-8">
                    <ul class="flex items-center gap-8 text-sm font-medium text-black">
                        <li><a href="{{ route('home') }}" class="px-1 py-2 transition-colors hover:text-green-700">Home</a></li>
                        <li><a href="{{ route('about') }}" class="px-1 py-2 transition-colors hover:text-green-700">About</a></li>
                        <li><a href="{{ route('faq') }}" class="px-1 py-2 transition-colors hover:text-green-700">FAQ</a></li>
                        <li><a href="{{ route('business_rules') }}" class="px-1 py-2 transition-colors hover:text-green-700">Business Rules</a></li>
                        <li><a href="{{ route('contact') }}" class="px-1 py-2 transition-colors hover:text-green-700">Contact</a></li>
                        <li><a href="{{ route('testimonial') }}" class="px-1 py-2 transition-colors hover:text-green-700">Testimonial</a></li>
                    </ul>

                    {{-- <button id="theme-toggle" type="button" class="p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        <svg id="theme-toggle-dark-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button> --}}
                </div>

                <div class="flex items-center gap-2 md:hidden">
                    {{-- <button id="theme-toggle-mobile" type="button" class="p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        <svg id="theme-toggle-dark-icon-mobile" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon-mobile" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button> --}}
                    <button id="menuToggleBtn" class="p-1 text-slate-700 focus:outline-none" aria-label="Toggle menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100">
            <div class="container px-4 py-3 mx-auto">
                <ul class="flex flex-col space-y-3 text-sm font-medium text-black">
                    <li><a href="{{ route('home') }}" class="block py-2 transition-colors hover:text-green-700">Home</a></li>
                    <li><a href="{{ route('about') }}" class="block py-2 transition-colors hover:text-green-700">About</a></li>
                    <li><a href="{{ route('faq') }}" class="block py-2 transition-colors hover:text-green-700">FAQ</a></li>
                    <li><a href="{{ route('business_rules') }}" class="block py-2 transition-colors hover:text-green-700">Business Rules</a></li>
                    <li><a href="{{ route('contact') }}" class="block py-2 transition-colors hover:text-green-700">Contact</a></li>
                    <li><a href="{{ route('testimonial') }}" class="block py-2 transition-colors hover:text-green-700">Testimonial</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>



<main >
    @yield('content')
</main>

<footer class="bg-gray-200">
    <div class="container px-4 py-12 mx-auto md:px-8 md:py-16">
        <div class="grid grid-cols-1 gap-10 md:grid-cols-3 lg:gap-16">
            <div class="space-y-4">
                <a href="#" class="inline-block">
                    <img src="{{ asset('images/logo.png') }}" alt="ASUP CICS Logo" class="w-40" />
                </a>
                <div class="space-y-1">
                    <p class="font-semibold text-gray-800">ASUP CICS</p>
                    <p class="text-gray-600">The Federal Polytechnic Ilaro, 2024</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8">
                <div>
                    <h3 class="mb-4 text-lg font-bold tracking-wider text-gray-800 uppercase">Links</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('about') }}" class="text-gray-600 transition-colors hover:text-green-700">About us</a></li>
                        <li><a href="{{ route('faq') }}" class="text-gray-600 transition-colors hover:text-green-700">FAQs</a></li>
                        <li><a href="{{ route('business_rules') }}" class="text-gray-600 transition-colors hover:text-green-700">Business Rules</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-600 transition-colors hover:text-green-700">Contact</a></li>
                        <li><a href="{{ route('testimonial') }}" class="text-gray-600 transition-colors hover:text-green-700">Testimonial</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="mb-4 text-lg font-bold tracking-wider text-gray-800 uppercase">Legal</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('terms') }}" class="text-gray-600 transition-colors hover:text-green-700">Terms of use</a></li>
                        <li><a href="{{ route('privacy') }}" class="text-gray-600 transition-colors hover:text-green-700">Privacy policy</a></li>
                        <li><a href="{{ route('cookie_policy') }}" class="text-gray-600 transition-colors hover:text-green-700">Cookie policy</a></li>
                    </ul>
                </div>
            </div>

            <div>
                <h3 class="mb-4 text-lg font-bold tracking-wider text-gray-800 uppercase">Connect With Us</h3>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-600 transition-colors hover:text-green-700" aria-label="Twitter">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8.29 20c7.547 0 11.675-6.155 11.675-11.49 0-.175 0-.349-.012-.522A8.18 8.18 0 0022 5.92a8.27 8.27 0 01-2.357.637A4.07 4.07 0 0021.448 4a8.18 8.18 0 01-2.605.975A4.1 4.1 0 0015.447 4c-2.266 0-4.1 1.823-4.1 4.07 0 .32.036.63.106.928A11.65 11.65 0 013 5.16a4.02 4.02 0 00-.555 2.046c0 1.41.725 2.655 1.83 3.385a4.08 4.08 0 01-1.856-.506v.05c0 1.968 1.413 3.61 3.292 3.984a4.1 4.1 0 01-1.848.07c.521 1.6 2.033 2.767 3.828 2.8a8.23 8.23 0 01-5.075 1.74A8.35 8.35 0 012 18.72 11.62 11.62 0 008.29 20"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-600 transition-colors hover:text-green-700" aria-label="YouTube">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.615 3.184A4.991 4.991 0 0016.724 2H7.276a4.991 4.991 0 00-2.891 1.184A4.986 4.986 0 002 6.074v11.852a4.986 4.986 0 001.184 2.891A4.991 4.991 0 007.276 22h9.448a4.991 4.991 0 002.891-1.184A4.986 4.986 0 0022 17.926V6.074a4.986 4.986 0 00-1.184-2.89zM10 15V9l6 3-6 3z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-600 transition-colors hover:text-green-700" aria-label="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22 12a10 10 0 10-11.5 9.9v-7h-2v-3h2v-2.3c0-2 1.2-3.1 3-3.1.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V11h2.2l-.4 3H14v7A10 10 0 0022 12"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6 text-gray-300 bg-black">
        <div class="container flex flex-col items-center justify-between gap-4 px-4 mx-auto md:px-8 md:flex-row">
            <p class="text-sm text-center md:text-left">Copyright © 2025 ASUP CICS - All rights reserved</p>
            <div class="flex space-x-6">
                <a href="#" class="text-gray-400 transition-colors hover:text-white" aria-label="Twitter">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8.29 20c7.547 0 11.675-6.155 11.675-11.49 0-.175 0-.349-.012-.522A8.18 8.18 0 0022 5.92a8.27 8.27 0 01-2.357.637A4.07 4.07 0 0021.448 4a8.18 8.18 0 01-2.605.975A4.1 4.1 0 0015.447 4c-2.266 0-4.1 1.823-4.1 4.07 0 .32.036.63.106.928A11.65 11.65 0 013 5.16a4.02 4.02 0 00-.555 2.046c0 1.41.725 2.655 1.83 3.385a4.08 4.08 0 01-1.856-.506v.05c0 1.968 1.413 3.61 3.292 3.984a4.1 4.1 0 01-1.848.07c.521 1.6 2.033 2.767 3.828 2.8a8.23 8.23 0 01-5.075 1.74A8.35 8.35 0 012 18.72 11.62 11.62 0 008.29 20"/>
                    </svg>
                </a>
                <a href="#" class="text-gray-400 transition-colors hover:text-white" aria-label="YouTube">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19.615 3.184A4.991 4.991 0 0016.724 2H7.276a4.991 4.991 0 00-2.891 1.184A4.986 4.986 0 002 6.074v11.852a4.986 4.986 0 001.184 2.891A4.991 4.991 0 007.276 22h9.448a4.991 4.991 0 002.891-1.184A4.986 4.986 0 0022 17.926V6.074a4.986 4.986 0 00-1.184-2.89zM10 15V9l6 3-6 3z"/>
                    </svg>
                </a>
                <a href="#" class="text-gray-400 transition-colors hover:text-white" aria-label="Facebook">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22 12a10 10 0 10-11.5 9.9v-7h-2v-3h2v-2.3c0-2 1.2-3.1 3-3.1.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V11h2.2l-.4 3H14v7A10 10 0 0022 12"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</footer>

@if($activeEvent && $activeEvent->end_date)
    <script>
        // Set the date and time the countdown ends using the event's end_date
        const endDate = new Date("{{ $activeEvent->end_date->toISOString() }}").getTime();
        const countdownSpan = document.getElementById('event-countdown');

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = endDate - now;

            // Calculate time components
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result
            if (countdownSpan) {
                if (distance < 0) {
                    countdownSpan.innerHTML = "ENDED";
                } else {
                    countdownSpan.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
                }
            }
        }

        // Update the countdown every 1 second
        const countdownInterval = setInterval(updateCountdown, 1000);

        // Initial call to display the countdown immediately
        updateCountdown();
    </script>
@endif
</body>
</html>