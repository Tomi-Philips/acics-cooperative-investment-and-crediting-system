@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl px-4 py-8 mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-col items-start justify-between gap-4 mb-8 md:flex-row md:items-center">
            <div>
                <h1 class="flex items-center gap-2 text-2xl font-bold text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Edit Department
                </h1>
                <p class="mt-1 text-sm text-gray-500">Update department information.</p>
            </div>
            <a href="{{ route('admin.departments.all') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                View All Departments
            </a>
        </div>

        <div class="overflow-hidden bg-white rounded-lg shadow-md">
            <div class="p-6">
                <form action="{{ route('admin.departments.update', $department->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="p-4 mb-4 text-red-700 bg-red-100 border-l-4 border-red-500" role="alert">
                            <p class="font-bold">Please correct the following errors:</p>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="code" class="block mb-1 text-sm font-medium text-gray-700">
                                Department Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="code" name="code"
                                   class="block w-full border {{ $errors->has('code') ? 'border-red-500' : 'border-gray-300' }} rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                   placeholder="e.g. CSC, BUS, MTH" required maxlength="10" {{-- Changed to 10 as per common practice for codes --}}
                                   value="{{ old('code', $department->code) }}" />
                            @error('code')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Enter a unique code for the department (max 10 characters).</p>
                        </div>

                        <div>
                            <label for="title" class="block mb-1 text-sm font-medium text-gray-700">
                                Department Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title"
                                   class="block w-full border {{ $errors->has('title') ? 'border-red-500' : 'border-gray-300' }} rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                   placeholder="e.g. Computer Science" required
                                   value="{{ old('title', $department->title) }}" />
                            @error('title')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Enter the full name of the department.</p>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block mb-1 text-sm font-medium text-gray-700">
                            Description <span class="text-gray-400">(Optional)</span>
                        </label>
                        <textarea id="description" name="description" rows="3"
                                  class="block w-full border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-300' }} rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                  placeholder="Brief description of the department..."
                        >{{ old('description', $department->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                   class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                   {{ old('is_active', $department->is_active) ? 'checked' : '' }} >
                            <label for="is_active" class="block ml-2 text-sm text-gray-700"> Active Department </label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Inactive departments won't be available for selection.</p>
                        @error('is_active')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                        <h3 class="mb-2 text-sm font-medium text-gray-700">Department Statistics</h3>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <p class="text-xs text-gray-500">Total Members</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $department->users()->count() }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Created On</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $department->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-4 space-x-4">
                        <a href="{{ route('admin.departments.all') }}" class="px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-150 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-all duration-150 bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Update Department
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-8">
            <h2 class="mb-4 text-lg font-semibold text-gray-800">Department Members</h2>
            <div class="overflow-hidden bg-white rounded-lg shadow-md">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Member ID </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Name </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Email </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase"> Actions </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($department->users()->take(5)->get() as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap"> {{ $user->id }} </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap"> {{ $user->name }} </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap"> {{ $user->email }} </td>
                                    <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                        <a href="{{ route('admin.users.view', $user->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-sm text-center text-gray-500"> No members in this department yet. </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if($department->users()->count() > 5)
                        <div class="px-6 py-3 text-center border-t border-gray-200 bg-gray-50"> {{-- Centered the "View all" link --}}
                            <a href="{{ route('admin.departments.members', $department->id) }}" class="text-sm text-green-600 hover:text-green-700"> {{-- Added route for all members --}}
                                View all {{ $department->users()->count() }} members
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection