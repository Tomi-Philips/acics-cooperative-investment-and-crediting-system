@extends('layouts.app')

@section('content')
<div class="py-4 bg-gradient-to-br from-gray-50 to-green-50 lg:py-24">
    <div class="container px-4 mx-auto sm:px-6 lg:px-8">
        <div class="mb-16 text-center">
            <span class="inline-block px-4 py-2 mb-4 text-sm font-medium text-green-700 bg-green-100 rounded-full">
                Testimonials
            </span>
            <h2 class="text-3xl font-bold tracking-tight text-green-800 sm:text-4xl">
                Voices of Our Community
            </h2>
            <p class="max-w-2xl mx-auto mt-4 text-lg text-gray-600">
                Discover how ASUP CICS is making a difference in the lives of our members
            </p>
        </div>

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
            <div class="overflow-hidden transition-all duration-300 bg-white shadow-md rounded-xl hover:-translate-y-1 hover:shadow-lg">
                <div class="flex flex-col h-full p-6">
                    <div class="flex-1">
                        <div class="mb-4 text-4xl text-green-500">“</div>
                        <p class="mb-6 leading-relaxed text-gray-700">
                            The cooperative has transformed the way I save and access funds. The web app makes everything so easy and transparent!
                        </p>
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

            <div class="overflow-hidden transition-all duration-300 bg-white shadow-md rounded-xl hover:-translate-y-1 hover:shadow-lg">
                <div class="flex flex-col h-full p-6">
                    <div class="flex-1">
                        <div class="mb-4 text-4xl text-green-500">“</div>
                        <p class="mb-6 leading-relaxed text-gray-700">
                            This site makes it so easy to check my loan report without leaving my doorstep. The mobile experience is exceptional!
                        </p>
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

            <div class="overflow-hidden transition-all duration-300 bg-white shadow-md rounded-xl hover:-translate-y-1 hover:shadow-lg">
                <div class="flex flex-col h-full p-6">
                    <div class="flex-1">
                        <div class="mb-4 text-4xl text-green-500">“</div>
                        <p class="mb-6 leading-relaxed text-gray-700">
                            I love how I can track my contributions and dividends without stress. Very user-friendly interface!
                        </p>
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

            <div class="overflow-hidden transition-all duration-300 bg-white shadow-md rounded-xl hover:-translate-y-1 hover:shadow-lg">
                <div class="flex flex-col h-full p-6">
                    <div class="flex-1">
                        <div class="mb-4 text-4xl text-green-500">“</div>
                        <p class="mb-6 leading-relaxed text-gray-700">
                            Joining the cooperative has helped my small business grow. The mobile-friendly site is a big plus!
                        </p>
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
    </div>
</div>
@endsection