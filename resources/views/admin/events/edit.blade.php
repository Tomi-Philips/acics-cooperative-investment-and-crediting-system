@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex flex-col items-start justify-between gap-4 mb-8 md:flex-row md:items-center">
            <div>
                <h1 class="flex items-center gap-2 text-2xl font-bold text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Event
                </h1>
                <p class="mt-1 text-sm text-gray-500">Update the event details</p>
            </div>
            <div>
                <a href="{{ route('admin.events.index') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2 -ml-1 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    View All Events
                </a>
            </div>
        </div>

        <div class="overflow-hidden bg-white rounded-lg shadow-md">
            <div class="p-6">
                <form action="{{ route('admin.events.update', $event->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="title" class="block mb-1 text-sm font-medium text-gray-700">
                                Event Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title"
                                class="block w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                placeholder="e.g. Annual Conference, Workshop" required value="{{ $event->title }}" />
                            @error('title')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="start_date" class="block mb-1 text-sm font-medium text-gray-700">
                                Start Date & Time <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" id="start_date" name="start_date"
                                class="block w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                required value="{{ $event->start_date ? $event->start_date->format('Y-m-d\TH:i') : '' }}" />
                            @error('start_date')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block mb-1 text-sm font-medium text-gray-700">
                                End Date & Time <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" id="end_date" name="end_date"
                                class="block w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                required value="{{ $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '' }}" />
                            @error('end_date')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block mb-1 text-sm font-medium text-gray-700">
                                Description <span class="text-gray-400">(Optional)</span>
                            </label>
                            <textarea id="description" name="description" rows="3"
                                class="block w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                placeholder="Brief description of the event...">{{ $event->description }}</textarea>
                            @error('description')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                {{ $event->is_active ? 'checked' : '' }}>
                            <label for="is_active" class="block ml-2 text-sm text-gray-700">
                                Active Event
                            </label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Inactive events won't be visible to users</p>
                        @error('is_active')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end pt-4 space-x-4">
                        <a href="{{ route('admin.events.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-150 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-all duration-150 bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Update Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection