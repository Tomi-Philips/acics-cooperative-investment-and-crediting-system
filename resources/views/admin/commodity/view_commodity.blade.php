@extends('layouts.admin')

@section('content')
    <div class="max-w-2xl p-8 mx-auto my-8 overflow-hidden bg-white shadow-xl rounded-2xl">
        <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">Commodity Details</h2>
            <a href="{{ route('admin.commodities.edit', $commodity->id) }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-colors bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                    </path>
                </svg>
                Edit Commodity
            </a>
        </div>

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
            <div class="flex items-center justify-center">
                @if ($commodity->image_path)
                    <img src="{{ asset('storage/' . $commodity->image_path) }}" alt="{{ $commodity->commodity_name }}"
                        class="object-cover rounded-lg max-h-64">
                @else
                    <div class="flex items-center justify-center w-full h-64 text-gray-500 bg-gray-200 rounded-lg">
                        No Image Available
                    </div>
                @endif
            </div>

            <div>
                <div class="mb-4">
                    <p class="text-sm font-semibold text-gray-600">Commodity Name:</p>
                    <p class="text-lg text-gray-900">{{ $commodity->commodity_name }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm font-semibold text-gray-600">Category:</p>
                    <p class="text-lg text-gray-900">{{ $commodity->category }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm font-semibold text-gray-600">Price:</p>
                    <p class="text-lg text-gray-900">₦{{ number_format($commodity->price, 2) }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm font-semibold text-gray-600">Quantity:</p>
                    <p class="text-lg text-gray-900">{{ $commodity->quantity }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm font-semibold text-gray-600">Status:</p>
                    @php
                        $statusClass = '';
                        switch ($commodity->status) {
                            case 'active':
                                $statusClass = 'bg-green-100 text-green-800';
                                break;
                            case 'inactive':
                                $statusClass = 'bg-red-100 text-red-800';
                                break;
                            case 'out_of_stock':
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                break;
                            default:
                                $statusClass = 'bg-gray-100 text-gray-800';
                                break;
                        }
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $statusClass }}">
                        {{ ucfirst($commodity->status) }}
                    </span>
                </div>

                @if ($commodity->description)
                    <div class="mb-4">
                        <p class="text-sm font-semibold text-gray-600">Description:</p>
                        <p class="text-base text-gray-900">{{ $commodity->description }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection