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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    Department Management
                </h1>
                <p class="mt-2 text-sm text-gray-600">Create new departments or bulk upload multiple departments</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.departments.all') }}" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    View All Departments
                </a>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="inline-flex gap-2 p-1 mt-2 bg-gray-100 rounded-lg shadow-inner" role="group">
            <button type="button" id="singleAddBtn" class="px-4 py-2.5 text-sm font-medium text-white bg-green-600 border-0 rounded-md department-add-btn hover:bg-green-700 focus:z-10 focus:ring-2 focus:ring-green-500 transition-all duration-200 shadow-sm">
                <svg class="inline w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Single
            </button>
            <button type="button" id="bulkUploadBtn" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-transparent border-0 rounded-md department-add-btn hover:bg-white hover:text-green-700 focus:z-10 focus:ring-2 focus:ring-green-500 transition-all duration-200">
                <svg class="inline w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                </svg>
                Bulk Upload
            </button>
        </div>
    </div>

    <!-- Form Card -->
    <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
        <!-- Card Header -->
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
            <div class="flex items-center">
                <div class="p-2 mr-4 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Add Department</h2>
                    <p class="mt-1 text-sm text-gray-600">Fill in the details to create a new department</p>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <div class="p-8">
            <div id="single_add_form">
                <form action="{{ route('admin.departments.store') }}" method="POST" class="space-y-6">
                    @csrf

                    @if ($errors->hasAny(['code', 'title', 'description', 'is_active']) && old('_token') && request()->routeIs('admin.departments.store'))
                        <div class="p-4 mb-4 text-red-700 bg-red-100 border-l-4 border-red-500" role="alert">
                            <p class="font-bold">Please fix the following errors in the Single Add form:</p>
                            <ul class="ml-5 list-disc">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="code" class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                Department Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="code" name="code"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200 @error('code') border-red-500 bg-red-50 @enderror"
                                placeholder="e.g. CSC, BUS, MTH" required maxlength="10" value="{{ old('code') }}" />
                            @error('code')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500">Enter a unique code for the department (max 10 characters).</p>
                        </div>

                        <div class="space-y-2">
                            <label for="title" class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Department Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200 @error('title') border-red-500 bg-red-50 @enderror"
                                placeholder="e.g. Computer Science" required value="{{ old('title') }}" />
                            @error('title')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500">Enter the full name of the department.</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="description" class="flex items-center text-sm font-semibold text-gray-700">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                            Description <span class="text-xs text-gray-500">(Optional)</span>
                        </label>
                        <textarea id="description" name="description" rows="3"
                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-200 @error('description') border-red-500 bg-red-50 @enderror"
                            placeholder="Brief description of the department..." >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-gray-700">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Active Department
                        </label>
                        <div class="flex items-center p-3 border border-gray-300 rounded-lg bg-gray-50">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                {{ old('is_active', '1') == '1' ? 'checked' : '' }} >
                            <label for="is_active" class="ml-3 text-sm font-medium text-gray-700"> Department is active</label>
                        </div>
                        <p class="text-xs text-gray-500">Inactive departments won't be available for selection.</p>
                        @error('is_active')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end pt-4 space-x-4">
                        <button type="reset" class="px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-150 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Clear Form
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-all duration-150 bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Department
                        </button>
                    </div>
                </form>
            </div>

            <div id="bulk_upload_form" class="hidden">
                <form action="{{ route('admin.departments.bulk_upload') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    @if ($errors->hasAny(['department_csv_file']) && old('_token') && request()->routeIs('admin.departments.bulk_upload'))
                        <div class="p-4 mb-4 text-red-700 bg-red-100 border-l-4 border-red-500" role="alert">
                            <p class="font-bold">Please fix the following errors in the Bulk Upload form:</p>
                            <ul class="ml-5 list-disc">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="p-6 border border-gray-200 rounded-lg bg-gray-50">
                        <div class="flex items-center mb-4">
                            <svg class="w-8 h-8 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Upload Department Data</h3>
                                <p class="text-sm text-gray-500">Upload a CSV file with department data to add multiple departments at once.</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label for="department_csv_file" class="block mb-1 text-sm font-medium text-gray-700">CSV File</label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="department_csv_file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                            <p class="text-xs text-gray-500">CSV files only (.csv)</p>
                                        </div>
                                        <input id="department_csv_file" name="department_csv_file" type="file" class="hidden" accept=".csv" required />
                                    </label>
                                </div>
                                <div id="department_file_name" class="mt-2 text-sm text-gray-500"></div>
                                @error('department_csv_file')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="department_template_download" class="block mb-1 text-sm font-medium text-gray-700">Template</label>
                                <a href="{{ route('admin.departments.download_template') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-green-700 focus:z-10 focus:ring-2 focus:ring-green-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Download Template
                                </a>
                            </div>
                            <div>
                                <label for="department_instructions" class="block mb-1 text-sm font-medium text-gray-700">Instructions</label>
                                <div class="p-4 bg-white border border-gray-200 rounded-lg">
                                    <ul class="space-y-2 text-sm text-gray-700 list-disc list-inside">
                                        <li>Download the template CSV file.</li>
                                        <li>Fill in the department data according to the template.</li>
                                        <li>Required fields: <code class="font-mono text-xs font-semibold text-gray-800 bg-gray-100 px-1 py-0.5 rounded">code</code>, <code class="font-mono text-xs font-semibold text-gray-800 bg-gray-100 px-1 py-0.5 rounded">title</code>.</li>
                                        <li>Optional fields: <code class="font-mono text-xs font-semibold text-gray-800 bg-gray-100 px-1 py-0.5 rounded">description</code>, <code class="font-mono text-xs font-semibold text-gray-800 bg-gray-100 px-1 py-0.5 rounded">is_active</code> (use <code class="font-mono text-xs font-semibold text-gray-800 bg-gray-100 px-1 py-0.5 rounded">TRUE</code> or <code class="font-mono text-xs font-semibold text-gray-800 bg-gray-100 px-1 py-0.5 rounded">FALSE</code>).</li>
                                        <li>Upload the completed file.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.departments.all') }}" class="px-5 py-2.5 text-sm border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 inline-block text-center">
                            Cancel
                        </a>
                        <button type="submit" class="px-5 py-2.5 text-sm bg-green-600 hover:bg-green-700 rounded-lg font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-md hover:shadow-lg">
                            <svg class="inline w-4 h-4 mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                            Upload Departments
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Recently Added Departments Card -->
    <div class="mt-8 overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
        <!-- Card Header -->
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
            <div class="flex items-center">
                <div class="p-2 mr-4 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Recently Added Departments</h2>
                    <p class="mt-1 text-sm text-gray-600">View the latest departments added to the system</p>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="p-8">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden border-b border-gray-200 shadow-sm sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Code </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Department </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Date Added </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase"> Actions </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentDepartments as $department)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap"> {{ $department->code }} </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap"> {{ $department->title }} </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap"> {{ $department->created_at->format('M d, Y') }} </td>
                                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                            <a href="{{ route('admin.departments.edit', $department->id) }}" class="text-green-600 hover:text-green-900">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-sm text-center text-gray-500"> No departments have been added yet. </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const singleAddForm = document.getElementById('single_add_form');
    const bulkUploadForm = document.getElementById('bulk_upload_form');
    const singleAddBtn = document.getElementById('singleAddBtn');
    const bulkUploadBtn = document.getElementById('bulkUploadBtn');
    const fileInput = document.getElementById('department_csv_file');
    const fileNameDisplay = document.getElementById('department_file_name');
    const dropZone = document.querySelector('label[for="department_csv_file"]');

    function setActiveButton(activeButton, inactiveButton) {
        activeButton.classList.add('text-white', 'bg-green-600', 'border-green-600');
        activeButton.classList.remove('text-gray-900', 'bg-white', 'border-gray-300');
        inactiveButton.classList.add('text-gray-900', 'bg-white', 'border-gray-300');
        inactiveButton.classList.remove('text-white', 'bg-green-600', 'border-green-600');
    }

    function setSingleAddMode() {
        singleAddForm.classList.remove('hidden');
        bulkUploadForm.classList.add('hidden');
        setActiveButton(singleAddBtn, bulkUploadBtn);
    }

    function setBulkUploadMode() {
        singleAddForm.classList.add('hidden');
        bulkUploadForm.classList.remove('hidden');
        setActiveButton(bulkUploadBtn, singleAddBtn);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the form state based on validation errors
        // Check if there are errors and if the form submission came from bulk_upload route
        const urlParams = new URLSearchParams(window.location.search);
        const hasBulkErrors = urlParams.get('bulk_errors') === '1'; // Assuming you might append ?bulk_errors=1 on redirect

        if (hasBulkErrors || (bulkUploadForm.querySelector('.text-red-700') && {{ $errors->has('department_csv_file') ? 'true' : 'false' }})) {
             setBulkUploadMode();
        } else {
            setSingleAddMode();
        }

        // Add event listeners to buttons
        singleAddBtn.addEventListener('click', setSingleAddMode);
        bulkUploadBtn.addEventListener('click', setBulkUploadMode);

        // File input change handler for bulk upload
        if (fileInput && fileNameDisplay) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const fileName = file.name;
                    const fileSize = (file.size / 1024).toFixed(2); // in KB

                    fileNameDisplay.innerHTML = `<span class="font-medium text-green-600">Selected file:</span> ${fileName} (${fileSize} KB)`;

                    // Check file extension (client-side validation)
                    const fileExt = fileName.split('.').pop().toLowerCase();
                    if (fileExt !== 'csv') {
                        fileNameDisplay.innerHTML += '<br><span class="text-red-600">Warning: Only CSV files are allowed.</span>';
                        // Optionally disable submit button or prevent submission
                    }
                } else {
                    fileNameDisplay.textContent = '';
                }
            });
        }

        // Add drag and drop functionality for file upload
        if (dropZone && fileInput) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                dropZone.classList.add('border-green-500', 'bg-green-50', 'dark:bg-green-900/10');
            }

            function unhighlight() {
                dropZone.classList.remove('border-green-500', 'bg-green-50', 'dark:bg-green-900/10');
            }

            dropZone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                fileInput.files = dt.files; // Set the dropped files to the input

                // Trigger change event manually as setting files property doesn't do it
                const event = new Event('change', { bubbles: true });
                fileInput.dispatchEvent(event);
            }
        }
    });
</script>
@endpush