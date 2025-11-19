@extends('layouts.admin')

@section('content')
<div class="max-w-4xl px-4 py-8 mx-auto sm:px-6 lg:px-8">
    <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h2 class="text-xl font-bold text-gray-800">Add New Commodity</h2>
                </div>
                <a href="{{ route('admin.commodities.index') }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    View All Commodities
                </a>
            </div>
            <p class="mt-1 text-sm text-gray-600">Add a new commodity to your inventory</p>
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
            <form action="{{ route('admin.commodities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="space-y-6">
                    <h3 class="pb-2 text-lg font-medium text-gray-900 border-b border-gray-200">Basic Information</h3>

                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-700">
                            Commodity Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200"
                               placeholder="e.g., Maize, Beans, Fertilizer" required>
                    </div>

                    <div>
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-700">
                            Description <span class="text-gray-400">(Optional)</span>
                        </label>
                        <textarea id="description" name="description" rows="3"
                                  class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200"
                                  placeholder="Detailed description of the commodity">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="pb-2 text-lg font-medium text-gray-900 border-b border-gray-200">Pricing & Inventory</h3>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="price" class="block mb-2 text-sm font-medium text-gray-700">
                                Unit Price <span class="text-red-500">*</span>
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <span class="text-gray-500">₦</span> {{-- Changed to Naira symbol --}}
                                </div>
                                <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0"
                                       class="block w-full pl-7 pr-12 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200"
                                       placeholder="0.00" required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="text-gray-500">NGN</span> {{-- Changed to NGN --}}
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="quantity" class="block mb-2 text-sm font-medium text-gray-700">
                                Available Quantity <span class="text-red-500">*</span>
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" min="1"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200"
                                       placeholder="100" required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="text-gray-500">units</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="pb-2 text-lg font-medium text-gray-900 border-b border-gray-200">Classification</h3>

                    <div>
                        <label for="commodity_type" class="block mb-2 text-sm font-medium text-gray-700">
                            Commodity Type <span class="text-red-500">*</span>
                        </label>
                        <select id="commodity_type" name="commodity_type"
                                class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200" required>
                            <option value="">Select Type</option>
                            <option value="Essential Commodity" {{ old('commodity_type') == 'Essential Commodity' ? 'selected' : '' }}>Essential Commodity</option>
                            <option value="Electronics" {{ old('commodity_type') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                            <option value="Agricultural" {{ old('commodity_type') == 'Agricultural' ? 'selected' : '' }}>Agricultural</option>
                            <option value="Household" {{ old('commodity_type') == 'Household' ? 'selected' : '' }}>Household</option>
                            {{-- Added 'Other' category for broader selection --}}
                            <option value="Other" {{ old('commodity_type') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-2 space-y-2">
                            <div class="flex items-center">
                                <input type="radio" id="status_active" name="status" value="active"
                                       class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500"
                                       {{ old('status', 'active') == 'active' ? 'checked' : '' }} required>
                                <label for="status_active" class="block ml-2 text-sm text-gray-900">
                                    Active (Available for purchase)
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="status_inactive" name="status" value="inactive"
                                       class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500"
                                       {{ old('status') == 'inactive' ? 'checked' : '' }} required>
                                <label for="status_inactive" class="block ml-2 text-sm text-gray-900">
                                    Inactive (Not available for purchase)
                                </label>
                            </div>
                            {{-- Added 'out_of_stock' status for better inventory management --}}
                            <div class="flex items-center">
                                <input type="radio" id="status_out_of_stock" name="status" value="out_of_stock"
                                       class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500"
                                       {{ old('status') == 'out_of_stock' ? 'checked' : '' }} required>
                                <label for="status_out_of_stock" class="block ml-2 text-sm text-gray-900">
                                    Out of Stock
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="pb-2 text-lg font-medium text-gray-900 border-b border-gray-200">Media</h3>

                    <div>
                        <label for="image" class="block mb-2 text-sm font-medium text-gray-700">
                            Commodity Image <span class="text-gray-400">(Optional)</span>
                        </label>
                        <div class="flex justify-center px-6 pt-5 pb-6 mt-1 border-2 border-gray-300 border-dashed rounded-lg">
                            <div class="space-y-1 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="image" class="relative font-medium text-green-600 bg-white rounded-md cursor-pointer hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                        <span>Upload a file</span>
                                        <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500"> PNG, JPG, GIF up to 5MB </p>
                            </div>
                        </div>
                        <div id="image-preview" class="hidden mt-2">
                            <img id="preview-image" class="object-contain h-32 rounded-lg">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4 space-x-3 border-t border-gray-200">
                    <a href="{{ route('admin.commodities.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 transition-all duration-150 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white transition-all duration-150 bg-green-600 border border-transparent rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add Commodity
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview image before upload
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const previewContainer = document.getElementById('image-preview');
        const previewImage = document.getElementById('preview-image');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            previewContainer.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection