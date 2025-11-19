@extends('layouts.admin')

@section('content')
<div class="min-h-screen py-8 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="p-3 shadow-lg bg-gradient-to-r from-green-500 to-green-600 rounded-xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $commodity->name }}</h1>
                        <p class="mt-1 text-lg text-gray-600">Commodity Details & Information</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.commodities.edit', $commodity) }}"
                        class="inline-flex items-center px-4 py-2 font-medium text-white transition-colors duration-150 bg-green-600 rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Commodity
                    </a>
                    <a href="{{ route('admin.commodities.index') }}"
                        class="inline-flex items-center px-4 py-2 font-medium text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <!-- Main Content -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Basic Information Card -->
                <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                        <div class="flex items-center">
                            <div class="p-2 mr-3 bg-green-100 rounded-lg">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">Basic Information</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-1">
                                <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Commodity Name</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $commodity->name }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Category</label>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $commodity->commodity_type === 'Essential Commodity' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $commodity->commodity_type }}
                                </span>
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Price</label>
                                <p class="text-2xl font-bold text-green-600">₦{{ number_format($commodity->price, 2) }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Stock Quantity</label>
                                <div class="flex items-center space-x-2">
                                    <p class="text-lg font-semibold text-gray-900">{{ $commodity->quantity }}</p>
                                    @if($commodity->quantity < 10)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Low Stock
                                        </span>
                                    @elseif($commodity->quantity < 50)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Medium Stock
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            In Stock
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($commodity->description)
                            <div class="pt-6 mt-6 border-t border-gray-200">
                                <label class="text-sm font-medium tracking-wide text-gray-500 uppercase">Description</label>
                                <p class="mt-2 leading-relaxed text-gray-700">{{ $commodity->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Additional Details Card -->
                <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                        <div class="flex items-center">
                            <div class="p-2 mr-3 bg-blue-100 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">Additional Details</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <div class="p-4 text-center rounded-lg bg-gray-50">
                                <div class="text-2xl font-bold text-gray-900">{{ $commodity->id }}</div>
                                <div class="mt-1 text-sm text-gray-500">Commodity ID</div>
                            </div>
                            <div class="p-4 text-center rounded-lg bg-gray-50">
                                <div class="text-2xl font-bold text-green-600">₦{{ number_format($commodity->price * $commodity->quantity, 2) }}</div>
                                <div class="mt-1 text-sm text-gray-500">Total Value</div>
                            </div>
                            <div class="p-4 text-center rounded-lg bg-gray-50">
                                <div class="flex items-center justify-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $commodity->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <div class="w-2 h-2 rounded-full mr-2
                                            {{ $commodity->status === 'active' ? 'bg-green-400' : 'bg-red-400' }}"></div>
                                        {{ ucfirst($commodity->status) }}
                                    </span>
                                </div>
                                <div class="mt-1 text-sm text-gray-500">Status</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6 lg:col-span-1">
                <!-- Image Card -->
                <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-purple-100">
                        <div class="flex items-center">
                            <div class="p-2 mr-3 bg-purple-100 rounded-lg">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">Product Image</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($commodity->image)
                            <div class="relative cursor-pointer group" onclick="openImageModal()">
                                <img src="{{ asset('storage/' . $commodity->image) }}"
                                     alt="{{ $commodity->name }}"
                                     class="object-cover w-full h-64 transition-all duration-300 rounded-lg shadow-md group-hover:scale-105 group-hover:shadow-lg">
                                
                                <div class="flex justify-center gap-3 p-3 transition-all duration-300 transform scale-75 bg-white border-2 border-green-500 rounded-full shadow-lg opacity-0 group-hover:opacity-100 bg-opacity-90 hover:bg-opacity-100 group-hover:scale-100">
                                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                    </svg>
                                    <span>Click to view full size</span>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center justify-center w-full h-64 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200">
                                <div class="text-center">
                                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="font-medium text-gray-500">No Image Available</p>
                                    <p class="mt-1 text-sm text-gray-400">Upload an image to display here</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-orange-100">
                        <div class="flex items-center">
                            <div class="p-2 mr-3 bg-orange-100 rounded-lg">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">Quick Actions</h2>
                        </div>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('admin.commodities.edit', $commodity) }}"
                            class="inline-flex items-center justify-center w-full px-4 py-3 font-medium text-white transition-colors duration-150 bg-green-600 rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Commodity
                        </a>

                        <form action="{{ route('admin.commodities.destroy', $commodity) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('Are you sure you want to delete this commodity? This action cannot be undone.')"
                                class="inline-flex items-center justify-center w-full px-4 py-3 font-medium text-white transition-colors duration-150 bg-red-600 rounded-lg shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Commodity
                            </button>
                        </form>

                        <a href="{{ route('admin.commodities.index') }}"
                            class="inline-flex items-center justify-center w-full px-4 py-3 font-medium text-white transition-colors duration-150 bg-gray-600 rounded-lg shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                            View All Commodities
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
@if($commodity->image)
<div id="imageModal" class="fixed inset-0 z-50 flex items-center justify-center hidden p-4 bg-black bg-opacity-75">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeImageModal()" class="absolute z-10 text-white top-4 right-4 hover:text-gray-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img src="{{ asset('storage/' . $commodity->image) }}"
             alt="{{ $commodity->name }}"
             class="object-contain max-w-full max-h-full rounded-lg shadow-2xl">
    </div>
</div>

<script>
function openImageModal() {
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside the image
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>
@endif
@endsection