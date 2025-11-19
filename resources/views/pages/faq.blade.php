@extends('layouts.app')

@section('content')
    <div>
        <div class="absolute inset-0 -z-10 h-full w-full bg-white bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px]">
            <div class="absolute left-0 right-0 top-0 -z-10 m-auto h-[310px] w-[310px] rounded-full bg-green-400 opacity-20 blur-[100px]"></div>
        </div>

        <div class="px-4 sm:px-8 md:px-[10%] my-8 md:my-15 flex flex-col items-center text-center">
            <h2 class="text-3xl font-bold text-green-800 sm:text-4xl md:text-5xl lg:text-6xl">
                Frequently Asked Questions
            </h2>
            <p class="max-w-3xl mt-3 text-base text-gray-600 sm:text-lg md:text-xl">
                Frequently Asked Questions – Common problems and how to fix.
            </p>
        </div>

        ---

        <div class="px-4 py-8 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="mb-8">
                    <div class="relative">
                        <input type="text" placeholder="Search questions..." class="w-full px-4 py-3 transition border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <svg class="absolute right-3 top-3.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 mb-8">
                    <button class="px-4 py-2 text-sm font-medium text-green-800 transition bg-green-100 rounded-full hover:bg-green-200">All</button>
                    @foreach($categories as $index => $category)
                        @php
                            $colors = ['red', 'blue', 'green', 'purple', 'yellow', 'indigo'];
                            $colorIndex = $index % count($colors);
                            $color = $colors[$colorIndex];
                        @endphp
                        <button class="px-4 py-2 bg-gray-100 text-gray-800 rounded-full text-sm font-medium hover:bg-{{ $color }}-200 transition">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>

                <div class="space-y-4">
                    @forelse($faqs as $faq)
                        @php
                            // Determine color based on category
                            $colors = ['red', 'blue', 'green', 'purple', 'yellow', 'indigo'];
                            $colorIndex = $faq->category_id % count($colors);
                            $color = $colors[$colorIndex];
                        @endphp
                        <div x-data="{ open: false }" class="bg-white p-6 rounded-xl border border-gray-200 hover:border-{{ $color }}-300 shadow-xs hover:shadow-md transition-all">
                            <button @click="open = !open" class="flex items-start justify-between w-full text-left">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 p-2 bg-{{ $color }}-50 rounded-lg mr-4">
                                        <svg class="h-6 w-6 text-{{ $color }}-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="flex items-center text-lg font-semibold text-gray-800">
                                            <span>{{ $faq->question }}</span>
                                            @if($faq->is_important)
                                                <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-red-100 text-red-800 rounded-full">Important</span>
                                            @endif
                                        </h2>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 ml-4 text-gray-500 transition-transform duration-200 transform" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="pr-4 mt-4 pl-14">
                                <p class="text-gray-600">
                                    {!! $faq->answer !!}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center bg-white border border-gray-200 rounded-xl">
                            <p class="text-gray-500">No FAQs available at the moment.</p>
                        </div>
                    @endforelse
                </div>

                <div class="p-6 mt-12 text-center bg-green-50 rounded-xl">
                    <h3 class="mb-2 text-xl font-semibold text-gray-900">Still need help?</h3>
                    <p class="mb-4 text-gray-600">Can't find the answer you're looking for? Our support team is here to help.</p>
                    <button class="px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                        Contact Support
                    </button>
                </div>
            </div>
        </div>

        {{-- The original script provided here conflicts with Alpine.js's x-data and x-collapse directives.
             Alpine.js handles the accordion functionality automatically, so this script is not needed.
             If you're not using Alpine.js, you would need a more robust JavaScript solution
             to manage multiple accordions.
        <script>
            // Simple accordion functionality
            document.querySelectorAll('button').forEach(button => {
                button.addEventListener('click', () => {
                    const content = button.nextElementSibling;
                    const icon = button.querySelector('svg');

                    // Toggle content visibility
                    content.classList.toggle('hidden');

                    // Rotate icon
                    icon.classList.toggle('rotate-180');
                });
            });
        </script>
        --}}
    </div>
@endsection