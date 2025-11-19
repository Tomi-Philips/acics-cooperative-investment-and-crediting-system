@extends('layouts.app')

@section('content')
    <div class="absolute inset-0 w-full h-full overflow-hidden -z-10">
        <div
            class="absolute inset-0 bg-white bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px]">
        </div>
        <div
            class="absolute left-1/2 top-1/2 h-[310px] w-[310px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-green-400 opacity-20 blur-[100px]">
        </div>
    </div>

    <div class="relative z-10">
        <div class="flex justify-center animate-fade-in">
            <div
                class="transition-shadow hover:shadow-md border border-green-300 bg-white px-3 py-1.5 font-medium text-green-700 shadow-sm rounded-full">
                <span class="text-xs sm:text-sm">Cooperative, Investment and Crediting</span> <span
                    class="ml-1.5">💼🤝💰</span>
            </div>
        </div>
        <div class="flex justify-center px-4 mt-6 sm:px-6 sm:mt-8 lg:px-8">
            <div class="flex flex-col items-center w-full max-w-3xl space-y-4 text-center">
                <h1 class="text-2xl font-bold leading-tight text-green-900 sm:text-4xl md:text-5xl"> Welcome to
                    <span class="text-green-600">FPI ASUP CICS</span>
                </h1>
                <p class="text-2xl font-medium text-gray-800 sm:text-3xl md:text-4xl">
                    Toward a<span class="relative inline-block"> <span class="relative z-10">stable Finance</span>
                        <span class="absolute bottom-0 left-0 w-full h-2 bg-green-200 opacity-50 -z-1"></span>
                    </span>
                </p>
                <p class="max-w-2xl mt-2 text-base leading-relaxed text-gray-600 sm:text-lg md:text-xl">
                    ASUP CICS proudly serves over 500 members and customers, empowering them to achieve their financial
                    goals through easy, reliable saving and investment solutions.
                </p>
            </div>
        </div>
        <div class="flex flex-col items-center justify-center gap-4 px-4 mt-8 sm:mt-10 sm:flex-row">
            <div class="relative group">
                <button type="button" id="getAppBtn"
                    class="relative inline-flex items-center justify-center rounded-lg bg-gray-800 px-6 py-3.5 text-sm font-medium text-white shadow-sm transition-all duration-200 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    <span class="mr-2">📱</span> Get App <span
                        class="absolute w-3 h-3 bg-yellow-400 rounded-full -right-1 -top-1 animate-pulse"></span>
                </button>
                <div
                    class="absolute z-10 invisible w-64 p-3 mb-2 text-sm text-white transition-all duration-300 -translate-x-1/2 bg-gray-900 rounded-lg shadow-lg opacity-0 bottom-full left-1/2 group-hover:visible group-hover:opacity-100">
                    <div class="relative">
                        <div
                            class="absolute w-4 h-4 rotate-45 -translate-x-1/2 bg-gray-900 -bottom-2 left-1/2"></div>
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 w-5 h-5 mr-2 text-yellow-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <p class="font-medium">App Not Available</p>
                                <p class="mt-1 text-xs text-gray-300">Click for more information</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('login') }}"
                class="group relative inline-flex items-center justify-center overflow-hidden rounded-lg bg-gradient-to-br from-green-500 to-amber-300 p-0.5 text-sm font-medium text-green-900 transition-all duration-300 hover:from-green-600 hover:to-amber-400 hover:text-white focus:outline-none focus:ring-4 focus:ring-green-200">
                <span
                    class="relative flex items-center gap-2 rounded-md bg-white px-6 py-3.5 transition-all ease-in duration-75 group-hover:bg-opacity-0">
                    <span>Member's Login</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                        <path d="M6.376 18.91a6 6 0 0 1 11.249.003" />
                        <circle cx="12" cy="11" r="4" />
                    </svg>
                </span>
            </a>
            <a href="{{ route('membership_registration') }}"
                class="relative inline-flex items-center justify-center rounded-lg bg-green-600 px-6 py-3.5 text-sm font-medium text-white shadow-sm transition-all duration-200 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                <span class="mr-2">🌱</span> Become a Member
            </a>
        </div>
    </div>

    <div class="py-16 bg-gradient-to-b from-white to-green-50 sm:py-24">
        <div class="container px-4 mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col items-center gap-8 lg:flex-row lg:gap-12 xl:gap-16">
                <div class="hidden w-full lg:block lg:w-1/2 xl:w-2/5">
                    <div class="relative overflow-hidden border-8 border-white shadow-xl rounded-3xl">
                        <img src="{{ asset('gifs/laughing-gif.gif') }}" alt="ASUP CICS Community"
                            class="object-cover w-full h-auto" loading="lazy" />
                        <div class="absolute inset-0 bg-green-700 opacity-10 mix-blend-multiply"></div>
                    </div>
                </div>
                <div class="w-full lg:w-1/2 xl:w-3/5">
                    <div class="max-w-2xl mx-auto lg:mx-0">
                        <div class="mb-8 text-center lg:text-left">
                            <span
                                class="inline-block px-4 py-2 mb-3 text-sm font-medium text-green-700 bg-green-100 rounded-full">
                                Our Story
                            </span>
                            <h2 class="mb-4 text-3xl font-bold text-green-800 sm:text-4xl md:text-5xl">
                                <span class="text-4xl font-handwriting sm:text-5xl md:text-6xl">About</span> ASUP CICS
                            </h2>
                            <div class="w-24 h-1 mx-auto bg-green-600 lg:mx-0"></div>
                        </div>
                        <div class="prose prose-lg prose-green max-w-none">
                            <p class="mb-6 leading-relaxed text-gray-700">
                                At ASUP CICS, we are driven by one mission: to empower financial growth for every member
                                and customer we serve. With over 500 active members, we stand as a trusted cooperative
                                committed to financial inclusion, discipline, and community-driven success.
                            </p>
                            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-gray-700">Monthly contribution plans</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-gray-700">Share investment opportunities</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-gray-700">Flexible savings plans</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-gray-700">Commodity access services</p>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-6 leading-relaxed text-gray-700">
                                Our core services are thoughtfully designed to meet the evolving needs of our community.
                                From monthly contributions that promote consistent saving habits to share investments
                                that build ownership and wealth, we provide the tools and support needed to take control
                                of your financial journey.
                            </p>
                            <p class="leading-relaxed text-gray-700">
                                At ASUP CICS, we combine integrity, innovation, and a strong sense of community to
                                deliver simple, secure, and sustainable financial solutions. We don't just handle money
                                — we help build futures.
                            </p>
                        </div>
                        <div class="mt-10">
                            <a href="#"
                                class="inline-flex items-center px-6 py-3 text-base font-medium text-white transition-colors duration-200 bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700">
                                Learn more about our services
                                <svg class="w-5 h-5 ml-3 -mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-amber-50/80 px-4 py-6 sm:px-8 md:px-10 lg:px-12 xl:px-[10%]">
        <div class="flex flex-col items-start max-w-6xl gap-6 mx-auto md:flex-row md:gap-8">
            <div
                class="flex-shrink-0 p-4 transition-shadow duration-300 bg-white shadow-sm rounded-xl hover:shadow-md md:p-5">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                    class="w-10 h-10 text-green-700 md:h-12 md:w-12">
                    <path fill-rule="evenodd"
                        d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold leading-tight text-green-800 md:text-2xl"> Your financial growth is our
                    priority </h2>
                <p class="mt-3 leading-relaxed text-gray-700"> We offer reliable services including monthly
                    contributions, share investments, savings plans, access to essential commodities, and flexible loan
                    options — all designed to help you save smarter, invest wisely, and build a secure financial future.
                </p>
                <div class="mt-4 md:mt-5">
                    <a href="#"
                        class="inline-flex items-center gap-2 font-medium text-green-700 transition-colors group hover:text-green-800">
                        <span class="group-hover:underline">More on ASUP CICS Solutions</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="transition-transform group-hover:translate-x-1">
                            <polyline points="15 10 20 15 15 20" />
                            <path d="M4 4v7a4 4 0 0 0 4 4h12" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 py-12 bg-slate-50 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="max-w-3xl mx-auto mb-12 text-center">
                <h1 class="text-3xl font-bold leading-tight text-gray-900 sm:text-4xl md:text-5xl"> Many ways to build
                    your savings </h1>
                <p class="mt-4 text-lg leading-relaxed text-gray-600 sm:text-xl md:text-2xl"> Flexible saving options
                    designed to help you plan for today and tomorrow. </p>
            </div>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2">
                <div
                    class="relative p-6 overflow-hidden transition-all duration-300 bg-white border border-green-100 shadow-sm group rounded-xl sm:p-8 hover:shadow-md">
                    <div
                        class="absolute inset-0 transition-opacity duration-300 opacity-0 group-hover:opacity-100 bg-gradient-to-br from-green-50 to-white">
                    </div>
                    <div class="relative z-10">
                        <h2 class="mb-4 text-2xl font-bold text-green-700 sm:text-3xl"> Automated Savings </h2>
                        <p class="text-gray-600"> Build a dedicated savings faster on your terms, automatically or
                            manually. </p>
                        <div class="mt-6">
                            <a href="#"
                                class="inline-flex items-center font-medium text-green-600 transition-colors hover:text-green-800">
                                Learn more
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-1" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div
                    class="relative p-6 overflow-hidden transition-all duration-300 bg-white border shadow-sm group rounded-xl border-amber-100 sm:p-8 hover:shadow-md">
                    <div
                        class="absolute inset-0 transition-opacity duration-300 opacity-0 group-hover:opacity-100 bg-gradient-to-br from-amber-50 to-white">
                    </div>
                    <div class="relative z-10">
                        <h2 class="mb-4 text-2xl font-bold text-amber-700 sm:text-3xl"> Fixed Savings </h2>
                        <p class="text-gray-600"> Lock money away for a fixed duration without access until maturity.
                            It's like having a custom fixed deposit. </p>
                        <div class="mt-6">
                            <a href="#"
                                class="inline-flex items-center font-medium transition-colors text-amber-600 hover:text-amber-800">
                                Learn more
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-1" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div
                    class="relative p-6 overflow-hidden transition-all duration-300 bg-white border border-pink-100 shadow-sm group rounded-xl sm:p-8 hover:shadow-md">
                    <div
                        class="absolute inset-0 transition-opacity duration-300 opacity-0 group-hover:opacity-100 bg-gradient-to-br from-pink-50 to-white">
                    </div>
                    <div class="relative z-10">
                        <h2 class="mb-4 text-2xl font-bold text-pink-700 sm:text-3xl"> Goal-oriented Savings </h2>
                        <p class="text-gray-600"> Reach all your savings goals faster. Save towards multiple goals on
                            your own or with a group. </p>
                        <div class="mt-6">
                            <a href="#"
                                class="inline-flex items-center font-medium text-pink-600 transition-colors hover:text-pink-800">
                                Learn more
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-1" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div
                    class="relative p-6 overflow-hidden transition-all duration-300 bg-white border border-blue-100 shadow-sm group rounded-xl sm:p-8 hover:shadow-md">
                    <div
                        class="absolute inset-0 transition-opacity duration-300 opacity-0 group-hover:opacity-100 bg-gradient-to-br from-blue-50 to-white">
                    </div>
                    <div class="relative z-10">
                        <h2 class="mb-4 text-2xl font-bold text-blue-700 sm:text-3xl"> Flex Naira </h2>
                        <p class="text-gray-600"> Save, transfer, manage, organize, and withdraw your money at any time.
                        </p>
                        <div class="mt-6">
                            <a href="#"
                                class="inline-flex items-center font-medium text-blue-600 transition-colors hover:text-blue-800">
                                Learn more
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-1" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-16 bg-gradient-to-br from-gray-50 to-green-50 lg:py-24">
        <div class="container px-4 mx-auto sm:px-6 lg:px-8">
            <div class="mb-16 text-center">
                <span
                    class="inline-block px-4 py-2 mb-4 text-sm font-medium text-green-700 bg-green-100 rounded-full">
                    Testimonials
                </span>
                <h2 class="text-3xl font-bold tracking-tight text-green-800 sm:text-4xl"> Voices of Our Community </h2>
                <p class="max-w-2xl mx-auto mt-4 text-lg text-gray-600"> Discover how ASUP CICS is making a difference
                    in the lives of our members </p>
            </div>
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
                <div
                    class="overflow-hidden transition-all duration-300 transform bg-white shadow-md rounded-xl hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex flex-col h-full p-6">
                        <div class="flex-1">
                            <div class="mb-4 text-4xl text-green-500">“</div>
                            <p class="mb-6 leading-relaxed text-gray-700"> The cooperative has transformed the way I
                                save and access funds. The web app makes everything so easy and transparent! </p>
                        </div>
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-full">
                                    <span class="text-lg font-semibold text-green-600">JA</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="font-semibold text-green-700">John Alawode</p>
                                <p class="text-sm text-gray-500">Member since 2012</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="overflow-hidden transition-all duration-300 transform bg-white shadow-md rounded-xl hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex flex-col h-full p-6">
                        <div class="flex-1">
                            <div class="mb-4 text-4xl text-green-500">“</div>
                            <p class="mb-6 leading-relaxed text-gray-700"> This site makes it so easy to check my loan
                                report without leaving my doorstep. The mobile experience is exceptional! </p>
                        </div>
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-full">
                                    <span class="text-lg font-semibold text-green-600">MD</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="font-semibold text-green-700">Mrs. Desmond</p>
                                <p class="text-sm text-gray-500">Lecturer</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="overflow-hidden transition-all duration-300 transform bg-white shadow-md rounded-xl hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex flex-col h-full p-6">
                        <div class="flex-1">
                            <div class="mb-4 text-4xl text-green-500">“</div>
                            <p class="mb-6 leading-relaxed text-gray-700"> I love how I can track my contributions and
                                dividends without stress. Very user-friendly interface! </p>
                        </div>
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-full">
                                    <span class="text-lg font-semibold text-green-600">EI</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="font-semibold text-green-700">Emeka I.</p>
                                <p class="text-sm text-gray-500">Senior Lecturer</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="overflow-hidden transition-all duration-300 transform bg-white shadow-md rounded-xl hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex flex-col h-full p-6">
                        <div class="flex-1">
                            <div class="mb-4 text-4xl text-green-500">“</div>
                            <p class="mb-6 leading-relaxed text-gray-700"> Joining the cooperative has helped my small
                                business grow. The mobile-friendly site is a big plus! </p>
                        </div>
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-full">
                                    <span class="text-lg font-semibold text-green-600">FK</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="font-semibold text-green-700">Fatima K.</p>
                                <p class="text-sm text-gray-500">Polytechnic Staff</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-16 text-center">
                <a href="{{ route('testimonial') }}"
                    class="inline-flex items-center px-6 py-3 text-base font-medium text-white transition-colors duration-200 bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700">
                    Read more testimonials
                    <svg class="w-5 h-5 ml-3 -mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <div class="px-4 py-12 bg-gradient-to-b from-green-50 to-white sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="flex flex-col items-center gap-8 lg:flex-row lg:gap-12">
                <div class="hidden w-full lg:block lg:w-1/2">
                    <div class="p-6 lg:p-12">
                        <img src="{{ asset('images/Saving money-pana.png') }}" alt="Financial Growth Illustration"
                            class="object-contain w-full h-auto animate-float" loading="lazy">
                    </div>
                </div>
                <div class="w-full lg:w-1/2">
                    <div class="mb-8 text-center lg:text-left">
                        <h1 class="mb-4 text-3xl font-bold text-gray-800 md:text-4xl">
                            <span class="text-green-600">Our Key</span> Functions
                        </h1>
                        <p class="max-w-lg mx-auto text-lg text-gray-600 lg:mx-0"> Discover the financial services that
                            help our members grow and prosper </p>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div
                            class="p-5 transition-all duration-300 transform bg-white border border-green-100 shadow-sm rounded-xl hover:-translate-y-1 hover:border-green-300 hover:shadow-md">
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex items-center justify-center flex-shrink-0 w-12 h-12 text-2xl bg-green-100 rounded-full">
                                    🔁 </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Monthly Contribution</h3>
                                    <p class="mt-1 text-sm text-gray-500">Build savings through regular contributions
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="p-5 transition-all duration-300 transform bg-white border border-green-100 shadow-sm rounded-xl hover:-translate-y-1 hover:border-green-300 hover:shadow-md">
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex items-center justify-center flex-shrink-0 w-12 h-12 text-2xl bg-green-100 rounded-full">
                                    📈 </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Share Investment</h3>
                                    <p class="mt-1 text-sm text-gray-500">Grow your wealth through cooperative shares
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="p-5 transition-all duration-300 transform bg-white border border-green-100 shadow-sm rounded-xl hover:-translate-y-1 hover:border-green-300 hover:shadow-md">
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex items-center justify-center flex-shrink-0 w-12 h-12 text-2xl bg-green-100 rounded-full">
                                    💰 </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Savings</h3>
                                    <p class="mt-1 text-sm text-gray-500">Flexible plans for all your financial goals
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="p-5 transition-all duration-300 transform bg-white border border-green-100 shadow-sm rounded-xl hover:-translate-y-1 hover:border-green-300 hover:shadow-md">
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex items-center justify-center flex-shrink-0 w-12 h-12 text-2xl bg-green-100 rounded-full">
                                    🛒 </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Commodity Support</h3>
                                    <p class="mt-1 text-sm text-gray-500">Access essential goods at favorable terms
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="p-5 transition-all duration-300 transform bg-white border border-green-100 shadow-sm rounded-xl hover:-translate-y-1 hover:border-green-300 hover:shadow-md sm:col-span-2">
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex items-center justify-center flex-shrink-0 w-12 h-12 text-2xl bg-green-100 rounded-full">
                                    💳 </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Loans</h3>
                                    <p class="mt-1 text-sm text-gray-500">Quick and fair credit facilities for your
                                        needs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes float {
            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>

    <div id="appModal" class="fixed inset-0 z-50 items-center justify-center hidden">
        <div class="absolute inset-0 transition-opacity bg-black bg-opacity-50 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-md mx-4 transition-all transform bg-white rounded-lg shadow-xl">
            <div class="flex items-center justify-between p-4 border-b rounded-t md:p-5">
                <h3 class="text-xl font-semibold text-gray-900"> Mobile App Coming Soon </h3>
                <button type="button" id="closeAppModal"
                    class="inline-flex items-center justify-center w-8 h-8 text-sm text-gray-400 bg-transparent rounded-lg ms-auto hover:bg-gray-200 hover:text-gray-900">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-4 space-y-4 md:p-5">
                <div class="flex justify-center">
                    <div class="flex items-center justify-center w-24 h-24 bg-yellow-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-yellow-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-base leading-relaxed text-gray-500"> Our mobile application is currently under
                    development and will be available soon. The app will provide convenient access to your account,
                    allowing you to: </p>
                <ul class="space-y-2 text-gray-500 list-disc list-inside">
                    <li>Check your savings, shares, and loan balances</li>
                    <li>Apply for loans on the go</li>
                    <li>Track your contributions and transactions</li>
                    <li>Receive important notifications</li>
                    <li>Access commodity services</li>
                </ul>
                <p class="text-base leading-relaxed text-gray-500"> In the meantime, you can access all these features
                    through our web platform, which is fully optimized for mobile devices. </p>
            </div>
            <div class="flex items-center justify-between p-4 border-t border-gray-200 rounded-b md:p-5">
                <div class="text-sm text-gray-500">
                    <span class="font-medium">Estimated release:</span> Q3 2024
                </div>
                <button type="button" id="closeAppModalBtn"
                    class="rounded-lg bg-green-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300">
                    Got it </button>
            </div>
        </div>
    </div>

    <script>
        // App Modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            const appModal = document.getElementById('appModal');
            const getAppBtn = document.getElementById('getAppBtn');
            const closeAppModal = document.getElementById('closeAppModal');
            const closeAppModalBtn = document.getElementById('closeAppModalBtn');

            // Open modal when Get App button is clicked
            getAppBtn.addEventListener('click', function() {
                appModal.classList.remove('hidden');
                appModal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
            });

            // Close modal functions
            function closeModal() {
                appModal.classList.add('hidden');
                appModal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            }

            closeAppModal.addEventListener('click', closeModal);
            closeAppModalBtn.addEventListener('click', closeModal);

            // Close modal when clicking outside
            appModal.addEventListener('click', function(e) {
                if (e.target === appModal) {
                    closeModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !appModal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    </script>
@endsection