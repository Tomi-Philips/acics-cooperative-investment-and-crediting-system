@extends('layouts.user')

@section('content')
<div class="mx-auto max-w-7xl">
    <div class="flex flex-col items-start justify-between gap-4 mb-8 md:flex-row md:items-center">
        <div>
            <h1 class="flex items-center gap-2 text-2xl font-bold text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="text-amber-600 h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                Commodity Marketplace
            </h1>
            <p class="mt-1 text-sm text-gray-500">Browse and purchase available commodities</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('user.commodity') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-amber-700 transition-colors duration-150 bg-amber-100 border border-amber-300 rounded-md hover:bg-amber-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                My Balance
            </a>
        </div>
    </div>

    @if($availableCommodities->count() > 0)
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($availableCommodities as $commodity)
                <div class="overflow-hidden bg-white shadow-sm rounded-xl">
                    @if($commodity->image)
                        <div class="aspect-w-16 aspect-h-9">
                            <img src="{{ asset('storage/' . $commodity->image) }}" alt="{{ $commodity->name }}" class="object-cover w-full h-48">
                        </div>
                    @else
                        <div class="flex items-center justify-center h-48 bg-gray-100">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $commodity->name }}</h3>
                            <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                {{ ucfirst($commodity->status) }}
                            </span>
                        </div>
                        
                        @if($commodity->description)
                            <p class="mb-4 text-sm text-gray-600">{{ Str::limit($commodity->description, 100) }}</p>
                        @endif
                        
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm text-gray-500">Price</p>
                                <p class="text-xl font-bold text-amber-600">₦{{ number_format($commodity->price, 2) }}</p>
                            </div>
                            @if($commodity->quantity_available)
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Available</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $commodity->quantity_available }}</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="{{ route('user.view_commodity', $commodity->id) }}" class="flex-1 px-4 py-2 text-sm font-medium text-center text-white transition-colors duration-150 bg-amber-600 border border-transparent rounded-md hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="p-12 text-center bg-white shadow-sm rounded-xl">
            <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No commodities available</h3>
            <p class="mt-2 text-sm text-gray-500">Check back later for new commodity listings.</p>
        </div>
    @endif
</div>
@endsection
