@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Enhanced Header Section -->
    <div class="p-6 mb-8 border border-gray-200 shadow-lg bg-gradient-to-r from-green-50 to-blue-50 rounded-xl">
        <div class="flex flex-col items-center justify-between lg:flex-row">
            <div class="mb-6 lg:mb-0">
                <h1 class="flex items-center text-2xl font-bold text-gray-800">
                    <div class="p-2 mr-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    Manage FAQs
                </h1>
                <p class="mt-2 text-sm text-gray-600">Add and manage frequently asked questions that appear on the public FAQ page</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('faq') }}" target="_blank"
                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    View Public FAQ Page
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Add New FAQ Card -->
        <div class="lg:col-span-1">
            <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
                <!-- Card Header -->
                <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                    <div class="flex items-center">
                        <div class="p-2 mr-4 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Add New FAQ</h2>
                            <p class="mt-1 text-sm text-gray-600">Create a new frequently asked question</p>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-8">
                    <form action="{{ route('admin.faqs.store') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="category_id" class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                Category
                            </label>
                            <select id="category_id" name="category_id"
                                class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm bg-white cursor-pointer transition-all duration-200">
                                <option value="">Select a category</option>
                                @foreach ($allCategories as $category)
                                    {{-- Use allCategories here --}}
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-6">
                            <label for="question" class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Question
                            </label>
                            <input type="text" id="question" name="question"
                                class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm transition-all duration-200"
                                placeholder="Enter the question" required>
                        </div>
                        <div class="mb-6">
                            <label for="answer" class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Answer
                            </label>
                            <textarea id="answer" name="answer" rows="6"
                                class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm transition-all duration-200"
                                placeholder="Enter the answer" required></textarea>
                            <p class="mt-1 text-xs text-gray-500">You can use basic HTML formatting in your answer</p>
                        </div>
                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_important" id="is_important"
                                    class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <span class="ml-2 text-sm text-gray-700">Mark as important</span>
                            </label>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white transition-all duration-200 bg-green-600 border border-transparent rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add FAQ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Existing FAQs Card -->
        <div class="lg:col-span-2">
            <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
                <!-- Card Header -->
                <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-2 mr-4 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">Existing FAQs</h2>
                                <p class="mt-1 text-sm text-gray-600">Manage your frequently asked questions</p>
                            </div>
                        </div>
                        <form action="{{ route('admin.faqs.index') }}" method="GET" class="relative">
                            <input type="text" name="search" placeholder="Search FAQs..."
                                class="w-full px-4 py-2.5 pl-10 pr-4 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 sm:w-64"
                                value="{{ $search }}">
                            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </form>
                    </div>
                </div>

                <!-- Category Tabs -->
                <div class="px-8 py-4 border-b border-gray-200">
                    <div class="flex flex-wrap gap-2">
                        <button
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-full transition-all duration-200 category-tab"
                            data-category-id="all">
                            All
                        </button>
                        @foreach ($categories as $category)
                            <button
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-full hover:bg-gray-200 transition-all duration-200 category-tab"
                                data-category-id="{{ $category->id }}">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- FAQs List -->
                <div class="p-8">
                    <div class="divide-y divide-gray-200">
                        @if ($search)
                            <div class="p-6 text-gray-700">
                                Showing results for "{{ $search }}"
                            </div>
                        @endif
                        @forelse($categories as $category)
                            @if ($category->faqs->count() > 0)
                                <div class="p-6 bg-gray-100 faq-category-section" data-category-id="{{ $category->id }}">
                                    <h4 class="text-lg font-semibold text-gray-800">{{ $category->name }}</h4>
                                    {{-- Removed category edit/delete buttons as per user feedback --}}
                                </div>
                                @foreach ($category->faqs as $faq)
                                    <div class="faq-item faq-category-section" data-category-id="{{ $category->id }}">
                                        <div class="p-6">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center mb-2">
                                                        @php
                                                            $colors = ['blue', 'green', 'purple', 'red', 'indigo', 'pink', 'teal'];
                                                            $colorIndex = ($loop->parent->index + $loop->index) % count($colors);
                                                            $color = $colors[$colorIndex];
                                                        @endphp
                                                        <span
                                                            class="px-2 py-1 mr-2 text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800 rounded-full">{{ $category->name }}</span>
                                                        @if ($faq->is_important)
                                                            <span
                                                                class="px-2 py-1 mr-2 text-xs font-medium text-red-800 bg-red-100 rounded-full">Important</span>
                                                        @endif
                                                    </div>
                                                    <h4 class="text-lg font-medium text-gray-900">{{ $faq->question }}</h4>
                                                    <div class="mt-2 text-sm text-gray-600">
                                                        <p>{{ $faq->answer }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex flex-shrink-0 ml-4 space-x-2">
                                                    <a href="{{ route('admin.faqs.edit', $faq->id) }}"
                                                        class="p-2 text-blue-600 hover:text-blue-900 focus:outline-none">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('admin.faqs.destroy', $faq->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this FAQ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="p-2 text-red-600 hover:text-red-900 focus:outline-none">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @empty
                            <div class="p-6 text-gray-700">
                                No FAQs found.
                            </div>
                        @endforelse
                    </div>

                    {{-- You can add pagination here if needed --}}
                    <div class="px-8 py-4 border-t border-gray-200">
                        <div class="text-sm text-gray-500">
                            {{-- Showing <span class="font-medium">1</span> to <span class="font-medium">3</span> of <span class="font-medium">3</span> FAQs --}}
                            Displaying FAQs by category.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add New FAQ Category Card -->
            <div class="mt-6 overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
                <!-- Card Header -->
                <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                    <div class="flex items-center">
                        <div class="p-2 mr-4 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Add New FAQ Category</h2>
                            <p class="mt-1 text-sm text-gray-600">Create a new category for organizing FAQs</p>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-8">
                    <form action="{{ route('admin.faqs.storeCategory') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="name" class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                Category Name
                            </label>
                            <input type="text" id="name" name="name"
                                class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm transition-all duration-200"
                                placeholder="Enter category name" required>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white transition-all duration-200 bg-green-600 border border-transparent rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.category-tab');
        const faqSections = document.querySelectorAll('.faq-category-section');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const categoryId = this.getAttribute('data-category-id');

                // Remove active classes from all tabs
                tabs.forEach(t => {
                    t.classList.remove('bg-green-600', 'text-white');
                    t.classList.add('bg-gray-100', 'text-gray-700');
                });

                // Add active classes to the clicked tab
                this.classList.add('bg-green-600', 'text-white');
                this.classList.remove('bg-gray-100', 'text-gray-700');

                faqSections.forEach(section => {
                    if (categoryId === 'all') {
                        section.style.display = 'block';
                    } else {
                        if (section.getAttribute('data-category-id') === categoryId) {
                            section.style.display = 'block';
                        } else {
                            section.style.display = 'none';
                        }
                    }
                });
            });
        });

        // Trigger click on the 'All' tab by default to show all FAQs initially
        const allTab = document.querySelector('.category-tab[data-category-id="all"]');
        if (allTab) {
            allTab.click();
        }
    });
</script>
@endpush
@endsection