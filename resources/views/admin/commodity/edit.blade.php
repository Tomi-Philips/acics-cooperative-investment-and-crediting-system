@extends('layouts.admin')

@section('content')
<div class="max-w-4xl px-4 py-8 mx-auto sm:px-6 lg:px-8">
    <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <h2 class="text-xl font-bold text-gray-800">Edit Commodity</h2>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.commodities.show', $commodity->id) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        View Details
                    </a>
                    <a href="{{ route('admin.commodities.index') }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        View All Commodities
                    </a>
                </div>
            </div>
            <p class="mt-1 text-sm text-gray-600">Update commodity information and settings</p>
        </div>

        @if ($errors->any())
            <div class="p-4 mx-6 mt-6 border-l-4 border-red-500 bg-red-50">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">There were {{ $errors->count() }} errors with your submission</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="pl-5 space-y-1 list-disc">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="p-6">
            <form action="{{ route('admin.commodities.update', $commodity->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Basic Information</h3>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Commodity Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $commodity->name) }}" 
                                       class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('name') border-red-300 @enderror" 
                                       placeholder="Enter commodity name" required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                                <select name="category" id="category" 
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('category') border-red-300 @enderror" required>
                                    <option value="">Select Category</option>
                                    <option value="essential" {{ old('category', $commodity->category) == 'essential' ? 'selected' : '' }}>Essential</option>
                                    <option value="electronics" {{ old('category', $commodity->category) == 'electronics' ? 'selected' : '' }}>Electronics</option>
                                </select>
                                @error('category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Information -->
                    <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Pricing Information</h3>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Price (₦)</label>
                                <input type="number" name="price" id="price" value="{{ old('price', $commodity->price) }}" 
                                       min="0" step="0.01"
                                       class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('price') border-red-300 @enderror" 
                                       placeholder="0.00" required>
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700">Available Quantity</label>
                                <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $commodity->quantity) }}" 
                                       min="0"
                                       class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('quantity') border-red-300 @enderror" 
                                       placeholder="0" required>
                                @error('quantity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Description</h3>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Commodity Description</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('description') border-red-300 @enderror" 
                                      placeholder="Enter detailed description of the commodity">{{ old('description', $commodity->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Commodity Image</h3>
                        
                        @if($commodity->image)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                                <img src="{{ asset('storage/' . $commodity->image) }}" alt="{{ $commodity->name }}" class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                            </div>
                        @endif
                        
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">{{ $commodity->image ? 'Replace Image' : 'Upload Image' }}</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                   class="block w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 @error('image') border-red-300 @enderror">
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Status</h3>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" 
                                   {{ old('is_active', $commodity->is_active) ? 'checked' : '' }}
                                   class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">
                                Active (Available for purchase)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end pt-6 space-x-3 border-t border-gray-200">
                    <a href="{{ route('admin.commodities.show', $commodity->id) }}" 
                       class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Commodity
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
