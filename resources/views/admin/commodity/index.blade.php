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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    Commodity Management
                </h1>
                <p class="mt-2 text-sm text-gray-600">Manage your inventory of available commodities and track stock levels</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.commodities.create') }}"
                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white transition-all duration-200 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 border border-transparent rounded-lg shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Commodity
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced Stats Cards -->
    <div class="grid gap-6 mb-8 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        <!-- Total Commodities Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-blue-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-blue-50 to-blue-100 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-blue-100 rounded-full opacity-20"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="p-4 shadow-lg bg-gradient-to-br from-blue-400 to-blue-500 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div class="absolute w-4 h-4 bg-blue-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Total Commodities</p>
                            <p class="mt-1 text-3xl font-bold text-gray-800">{{ $availableCommodities->total() }}</p>
                            <p class="mt-1 text-xs font-medium text-blue-600">All items</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Items Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-green-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-green-50 to-green-100 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-green-100 rounded-full opacity-20"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="p-4 shadow-lg bg-gradient-to-br from-green-400 to-green-500 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="absolute w-4 h-4 bg-green-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Active Items</p>
                            <p class="mt-1 text-3xl font-bold text-gray-800">{{ $availableCommodities->where('status', 'active')->count() }}</p>
                            <p class="mt-1 text-xs font-medium text-green-600">Available for sale</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-orange-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-orange-50 to-orange-100 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-orange-100 rounded-full opacity-20"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="p-4 shadow-lg bg-gradient-to-br from-orange-400 to-orange-500 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="absolute w-4 h-4 bg-orange-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Low Stock</p>
                            <p class="mt-1 text-3xl font-bold text-gray-800">{{ $availableCommodities->where('quantity', '<', 10)->count() }}</p>
                            <p class="mt-1 text-xs font-medium text-orange-600">Needs restocking</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Value Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white border border-purple-100 shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-2">
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-purple-50 to-purple-100 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 w-20 h-20 -mt-10 -mr-10 bg-purple-100 rounded-full opacity-20"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="p-4 shadow-lg bg-gradient-to-br from-purple-400 to-purple-500 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="absolute w-4 h-4 bg-purple-500 rounded-full -top-1 -right-1 animate-pulse"></div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Total Value</p>
                            <p class="mt-1 text-3xl font-bold text-gray-800">₦{{ number_format($availableCommodities->sum(function($item) { return $item->price * $item->quantity; }), 2) }}</p>
                            <p class="mt-1 text-xs font-medium text-purple-600">Inventory worth</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Commodities Table Card -->
    <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-2xl">
        <!-- Card Header with Search and Filters -->
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-green-50 to-blue-50">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center">
                    <div class="p-2 mr-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">All Commodities</h2>
                        <p class="text-sm text-gray-600">{{ $availableCommodities->total() }} total commodities</p>
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <!-- Search -->
                    <form action="{{ route('admin.commodities.index') }}" method="GET" class="flex">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" placeholder="Search commodities..." value="{{ request('search') }}"
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm transition duration-150 ease-in-out">
                        </div>
                        <button type="submit" class="ml-2 px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                            Search
                        </button>
                        @if(request('search'))
                        <a href="{{ route('admin.commodities.index') }}" class="ml-2 px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 border border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200 shadow-sm hover:shadow-md">
                            Clear
                        </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            @if($availableCommodities->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">Commodity</th>
                            <th scope="col" class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">Category</th>
                            <th scope="col" class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">Price</th>
                            <th scope="col" class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">Stock</th>
                            <th scope="col" class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">Status</th>
                            <th scope="col" class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($availableCommodities as $commodity)
                            <tr class="transition-all duration-200 border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-12 h-12">
                                            @if ($commodity->image)
                                                <img class="object-cover w-12 h-12 border border-gray-200 shadow-sm rounded-xl"
                                                    src="{{ asset('storage/' . $commodity->image) }}"
                                                    alt="{{ $commodity->name }}">
                                            @else
                                                <div class="flex items-center justify-center w-12 h-12 border border-gray-200 shadow-sm rounded-xl bg-gradient-to-br from-gray-100 to-gray-200">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $commodity->name }}</div>
                                            <div class="text-xs text-gray-500">{{ Str::limit($commodity->description, 40) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                                        {{ $commodity->commodity_type === 'Essential Commodity' ? 'bg-green-100 text-green-800' : 'bg-teal-100 text-teal-800' }}">
                                        <div class="w-2 h-2 mr-2 {{ $commodity->commodity_type === 'Essential Commodity' ? 'bg-green-400' : 'bg-teal-400' }} rounded-full"></div>
                                        {{ $commodity->commodity_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">₦{{ number_format($commodity->price, 2) }}</div>
                                    <div class="text-xs text-gray-500">Per unit</div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="text-sm font-semibold text-gray-900">{{ $commodity->quantity }}</span>
                                        @if($commodity->quantity < 10)
                                            <span class="inline-flex items-center px-2 py-1 ml-2 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                                <div class="w-2 h-2 mr-1 bg-red-400 rounded-full animate-pulse"></div>
                                                Low Stock
                                            </span>
                                        @elseif($commodity->quantity < 50)
                                            <span class="inline-flex items-center px-2 py-1 ml-2 text-xs font-semibold text-orange-800 bg-orange-100 rounded-full">
                                                <div class="w-2 h-2 mr-1 bg-orange-400 rounded-full"></div>
                                                Medium
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 ml-2 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                                <div class="w-2 h-2 mr-1 bg-green-400 rounded-full"></div>
                                                In Stock
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                                        {{ $commodity->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <div class="w-2 h-2 mr-2 {{ $commodity->status === 'active' ? 'bg-green-400' : 'bg-red-400' }} rounded-full"></div>
                                        {{ ucfirst($commodity->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-sm font-medium whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.commodities.show', $commodity) }}"
                                            class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white transition-all duration-200 rounded-lg shadow-sm bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 hover:shadow-md"
                                            title="View Details">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View
                                        </a>
                                        <a href="{{ route('admin.commodities.edit', $commodity) }}"
                                            class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white transition-all duration-200 rounded-lg shadow-sm bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 hover:shadow-md"
                                            title="Edit">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.commodities.destroy', $commodity) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white transition-all duration-200 rounded-lg shadow-sm bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 hover:shadow-md"
                                                title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this commodity?')">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="p-4 mb-4 bg-gray-100 rounded-full">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h3 class="mb-2 text-lg font-semibold text-gray-900">No commodities found</h3>
                        <p class="mb-4 text-sm text-gray-500">Try adjusting your search criteria or add a new commodity.</p>
                        <a href="{{ route('admin.commodities.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-all duration-200 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add New Commodity
                        </a>
                    </div>
                </div>
            @endif
    </div>

    <!-- Pagination -->
    @if($availableCommodities->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $availableCommodities->links() }}
        </div>
    @endif
</div>
</div>


@endsection