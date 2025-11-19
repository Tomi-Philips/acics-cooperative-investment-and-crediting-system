@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto my-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ isset($commodity) ? 'Edit Commodity' : 'Add New Commodity' }}</h1>
                <p class="mt-1 text-sm text-gray-600">{{ isset($commodity) ? 'Update commodity information' : 'Create a new commodity for your inventory' }}</p>
            </div>
            <a href="{{ route('admin.commodities.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-colors bg-gray-600 border border-transparent rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to List
            </a>
        </div>

        <div class="overflow-hidden bg-white shadow-xl rounded-xl">
            <div class="px-6 py-4 border-b border-green-800 bg-gradient-to-r from-green-600 to-green-700">
                <h2 class="text-xl font-semibold text-white">Commodity Information</h2>
            </div>

            <form action="{{ isset($commodity) ? route('admin.commodities.update', $commodity) : route('admin.commodities.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @if(isset($commodity))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="commodity_name" class="block mb-1 text-sm font-medium text-gray-700">Commodity Name <span class="text-red-500">*</span></label>
                        <input type="text" id="commodity_name" name="commodity_name" required value="{{ $commodity->commodity_name ?? old('commodity_name') }}" class="block w-full px-4 py-3 mt-1 transition duration-150 ease-in-out border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" placeholder="Enter commodity name">
                        @error('commodity_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block mb-1 text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                        <div class="relative mt-1">
                            <select id="category" name="category" required class="block w-full px-4 py-3 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-lg shadow-sm appearance-none focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                <option value="" disabled {{ !isset($commodity) ? 'selected' : '' }}>Select a category</option>
                                @php
                                    $categories = ['Food Item', 'Electronics', 'Furniture', 'Clothing', 'Agricultural', 'Other'];
                                @endphp
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ (isset($commodity) && $commodity->category == $cat) || old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-600 pointer-events-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="price" class="block mb-1 text-sm font-medium text-gray-700">Price (₦) <span class="text-red-500">*</span></label>
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 pointer-events-none">
                                <span class="sm:text-sm">₦</span>
                            </div>
                            <input type="number" id="price" name="price" min="0" step="0.01" required value="{{ $commodity->price ?? old('price') }}" class="block w-full py-3 transition duration-150 ease-in-out border border-gray-300 rounded-lg pl-9 pr-14 focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-500 pointer-events-none">
                                <span class="sm:text-sm">NGN</span>
                            </div>
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="quantity" class="block mb-1 text-sm font-medium text-gray-700">Quantity <span class="text-red-500">*</span></label>
                        <input type="number" id="quantity" name="quantity" min="0" required value="{{ $commodity->quantity ?? old('quantity') }}" class="block w-full px-4 py-3 mt-1 transition duration-150 ease-in-out border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" placeholder="Enter quantity">
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                    <div class="flex flex-wrap gap-6 mt-1">
                        @php
                            $statuses = [
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'out_of_stock' => 'Out of Stock'
                            ];
                            $currentStatus = $commodity->status ?? old('status', 'active');
                        @endphp
                        @foreach($statuses as $value => $label)
                            <label class="inline-flex items-center px-4 py-2 transition-colors bg-white border border-gray-300 rounded-lg shadow-sm cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="status" value="{{ $value }}" {{ $currentStatus == $value ? 'checked' : '' }} class="w-4 h-4 text-green-600 transition duration-150 ease-in-out border-gray-300 focus:ring-green-500">
                                <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block mb-1 text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" rows="4" class="block w-full px-4 py-3 mt-1 transition duration-150 ease-in-out border border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" placeholder="Add details about the commodity">{{ $commodity->description ?? old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Commodity Image</label>
                    <div class="flex items-center mt-2 space-x-6">
                        <div class="flex-shrink-0">
                            @if(isset($commodity) && $commodity->image_path)
                                <img src="{{ asset('storage/' . $commodity->image_path) }}" alt="{{ $commodity->commodity_name }}" class="object-cover w-24 h-24 border border-gray-300 rounded-lg">
                            @else
                                <div class="flex items-center justify-center w-24 h-24 bg-gray-100 border-2 border-gray-300 border-dashed rounded-lg">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col">
                            <label for="image" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-colors bg-green-600 border border-transparent rounded-md shadow-sm cursor-pointer hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Upload Image
                                <input id="image" name="image" type="file" accept="image/*" class="sr-only">
                            </label>
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                        </div>
                    </div>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end pt-6 space-x-4 border-t border-gray-200">
                    <a href="{{ route('admin.commodities.index') }}" class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"> Cancel </a>
                    <button type="submit" class="px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        {{ isset($commodity) ? 'Update Commodity' : 'Save Commodity' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Preview image before upload
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector('.flex-shrink-0');
                    preview.innerHTML = `<img src="${e.target.result}" class="object-cover w-24 h-24 border border-gray-300 rounded-lg">`;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
    @endpush
@endsection