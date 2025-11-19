@extends('layouts.app')

@section('content')
<div class="max-w-4xl px-4 py-8 mx-auto sm:px-6 lg:px-8">
    <div class="overflow-hidden bg-white rounded-lg shadow-lg">
        <div class="px-6 py-8">
            <h1 class="mb-6 text-3xl font-bold text-gray-900">Membership Registration</h1>

            <div class="p-4 mb-6 border border-blue-200 rounded-lg bg-blue-50">
                <div class="flex items-start">  
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Membership Application Process</h3>
                        <div class="mt-2 space-y-1 text-sm text-blue-700">
                            <p>• Complete this online form to register your interest</p>
                            <p>• Your application will be pending approval</p>
                            <p>• Visit our branch office for physical verification and document submission</p>
                            <p>• Once approved, you'll receive full membership status</p>
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->any())
            <div class="p-4 mb-6 border border-red-200 rounded-lg bg-red-50">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="pl-5 space-y-1 list-disc">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                                @if ($errors->has('general'))
                                <li>{{ $errors->first('general') }}</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <form class="space-y-6" action="{{ route('membership_registration.post') }}" method="POST">
                @csrf

                <div class="hidden p-4 mb-6 border border-blue-200 rounded-lg bg-blue-50">
                    <p>Departments count: {{ count($departments ?? []) }}</p>
                    <p>Relationships count: {{ count($relationships ?? []) }}</p>
                </div>

                <div>
                    <h2 class="mb-4 text-xl font-semibold text-gray-800">Personal Information</h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="full_name" class="block mb-1 text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" class="block w-full p-3 transition-all border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('full_name') border-red-500 @enderror" required />
                            @error('full_name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block mb-1 text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="block w-full p-3 transition-all border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('email') border-red-500 @enderror" required />
                            @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="phone" class="block mb-1 text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="block w-full p-3 transition-all border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('phone') border-red-500 @enderror" placeholder="e.g., 08012345678" required />
                            @error('phone')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="address" class="block mb-1 text-sm font-medium text-gray-700">Residential Address</label>
                            <input type="text" id="address" name="address" value="{{ old('address') }}" class="block w-full p-3 transition-all border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('address') border-red-500 @enderror" required />
                            @error('address')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="date_of_birth" class="block mb-1 text-sm font-medium text-gray-700">Date of Birth</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" class="block w-full p-3 transition-all border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('date_of_birth') border-red-500 @enderror" required />
                            @error('date_of_birth')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="gender" class="block mb-1 text-sm font-medium text-gray-700">Gender</label>
                            <select id="gender" name="gender" class="block w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all appearance-none bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPjxwb2x5bGluZSBwb2ludHM9IjYgOSAxMiAxNSAxOCA5Ij48L3BvbHlsaW5lPjwvc3ZnPg==')] bg-no-repeat bg-[right_1rem_center] @error('gender') border-red-500 @enderror" required >
                                <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="mb-4 text-xl font-semibold text-gray-800">Employment Information</h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="department" class="block mb-1 text-sm font-medium text-gray-700">Department</label>
                            <select id="department" name="department" class="block w-full p-3 transition-all border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('department') border-red-500 @enderror" required>
                                <option value="">Select your department</option>
                                @if(isset($departments) && count($departments) > 0)
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department') == $department->id ? 'selected' : '' }}>{{ $department->title }}</option>
                                    @endforeach
                                @else
                                    {{-- Fallback departments if not passed from controller (for development/testing) --}}
                                    @php
                                        // This part should ideally be handled in the controller or a service.
                                        // For demonstration, directly querying the model here.
                                        $fallbackDepartments = \App\Models\Department::where('is_active', true)->get();
                                    @endphp
                                    @if(count($fallbackDepartments) > 0)
                                        @foreach($fallbackDepartments as $department)
                                            <option value="{{ $department->id }}" {{ old('department') == $department->id ? 'selected' : '' }}>{{ $department->title }}</option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>No departments available</option>
                                    @endif
                                @endif
                            </select>
                            @error('department')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="staff_id" class="block mb-1 text-sm font-medium text-gray-700">Staff ID</label>
                            <input type="text" id="staff_id" name="staff_id" value="{{ old('staff_id') }}" class="block w-full p-3 transition-all border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('staff_id') border-red-500 @enderror" required />
                            @error('staff_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="position" class="block mb-1 text-sm font-medium text-gray-700">Position/Role</label>
                            <input type="text" id="position" name="position" value="{{ old('position') }}" class="block w-full p-3 transition-all border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('position') border-red-500 @enderror" required />
                            @error('position')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="employment_date" class="block mb-1 text-sm font-medium text-gray-700">Employment Date</label>
                            <input type="date" id="employment_date" name="employment_date" value="{{ old('employment_date') }}" class="block w-full p-3 transition-all border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('employment_date') border-red-500 @enderror" required />
                            @error('employment_date')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="mb-4 text-xl font-semibold text-gray-800">Next of Kin Information</h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="next_of_kin_name" class="block mb-1 text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" id="next_of_kin_name" name="next_of_kin_name" value="{{ old('next_of_kin_name') }}" class="block w-full p-3 transition-all border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('next_of_kin_name') border-red-500 @enderror" required />
                            @error('next_of_kin_name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="next_of_kin_relationship" class="block mb-1 text-sm font-medium text-gray-700">Relationship</label>
                            <select id="next_of_kin_relationship" name="next_of_kin_relationship" class="block w-full p-3 transition-all border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('next_of_kin_relationship') border-red-500 @enderror" required>
                                <option value="">Select relationship</option>
                                @if(isset($relationships) && count($relationships) > 0)
                                    @foreach($relationships as $value => $label)
                                        <option value="{{ $value }}" {{ old('next_of_kin_relationship') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                @else
                                    {{-- Fallback relationships if not passed from controller (for development/testing) --}}
                                    <option value="spouse" {{ old('next_of_kin_relationship') == 'spouse' ? 'selected' : '' }}>Spouse</option>
                                    <option value="parent" {{ old('next_of_kin_relationship') == 'parent' ? 'selected' : '' }}>Parent</option>
                                    <option value="child" {{ old('next_of_kin_relationship') == 'child' ? 'selected' : '' }}>Child</option>
                                    <option value="sibling" {{ old('next_of_kin_relationship') == 'sibling' ? 'selected' : '' }}>Sibling</option>
                                    <option value="relative" {{ old('next_of_kin_relationship') == 'relative' ? 'selected' : '' }}>Other Relative</option>
                                    <option value="friend" {{ old('next_of_kin_relationship') == 'friend' ? 'selected' : '' }}>Friend</option>
                                    <option value="colleague" {{ old('next_of_kin_relationship') == 'colleague' ? 'selected' : '' }}>Colleague</option>
                                @endif
                            </select>
                            @error('next_of_kin_relationship')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="next_of_kin_phone" class="block mb-1 text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="tel" id="next_of_kin_phone" name="next_of_kin_phone" value="{{ old('next_of_kin_phone') }}" class="block w-full p-3 transition-all border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('next_of_kin_phone') border-red-500 @enderror" placeholder="e.g., 08012345678" required />
                            @error('next_of_kin_phone')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="next_of_kin_address" class="block mb-1 text-sm font-medium text-gray-700">Address</label>
                            <input type="text" id="next_of_kin_address" name="next_of_kin_address" value="{{ old('next_of_kin_address') }}" class="block w-full p-3 transition-all border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('next_of_kin_address') border-red-500 @enderror" required />
                            @error('next_of_kin_address')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="terms" name="terms" type="checkbox" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500 @error('terms') border-red-500 @enderror" {{ old('terms') ? 'checked' : '' }} required />
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="font-medium text-gray-700">I agree to the <a href="{{ route('terms') }}" class="text-green-600 hover:underline">terms and conditions</a></label>
                        <p class="text-gray-500">By submitting, you acknowledge our membership policies and business rules</p>
                        @error('terms')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="p-4 border rounded-lg bg-amber-50 border-amber-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-amber-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-amber-800">Important Note</h3>
                            <div class="mt-2 text-sm text-amber-700">
                                <p>This online application serves as notification of your intent to join. After submission:</p>
                                <ul class="pl-5 mt-2 space-y-1 list-disc">
                                    <li>Your application will be marked as "Pending Verification"</li>
                                    <li>You must visit our secretariat within 14 days to fill authority deduction form via bursary</li>
                                    <li>After physical verification, your membership will be processed for approval</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2" >
                        Submit Membership Application
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div class="p-4 mt-8 border border-gray-200 rounded-lg bg-gray-50">
                    <h3 class="mb-3 text-lg font-medium text-gray-800">Application Status Flow</h3>
                    <div class="flex flex-col items-start sm:flex-row sm:items-center">
                        <div class="relative flex flex-col items-center">
                            <div class="flex items-center mb-2 sm:mb-0">
                                <div class="z-10 flex items-center justify-center w-10 h-10 bg-green-500 rounded-full">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 text-sm font-medium text-gray-700">
                                    <span class="font-semibold">Step 1:</span> Application Submitted
                                </div>
                            </div>
                            <div class="hidden sm:block absolute top-5 left-5 h-0.5 w-full bg-gray-300 z-0"></div>
                        </div>

                        <div class="relative flex flex-col items-center mt-6 sm:mt-0 sm:ml-12">
                            <div class="flex items-center mb-2 sm:mb-0">
                                <div class="z-10 flex items-center justify-center w-10 h-10 bg-yellow-500 rounded-full">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 text-sm font-medium text-gray-700">
                                    <span class="font-semibold">Step 2:</span> Physical Verification
                                    <p class="mt-1 text-xs text-gray-500">Visit our office within 14 days</p>
                                </div>
                            </div>
                            <div class="hidden sm:block absolute top-5 left-5 h-0.5 w-full bg-gray-300 z-0"></div>
                        </div>

                        <div class="relative flex flex-col items-center mt-6 sm:mt-0 sm:ml-12">
                            <div class="flex items-center">
                                <div class="z-10 flex items-center justify-center w-10 h-10 bg-gray-300 rounded-full">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 text-sm font-medium text-gray-500">
                                    <span class="font-semibold">Step 3:</span> Membership Approved
                                    <p class="mt-1 text-xs text-gray-500">Full access to all services</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 mt-6 border border-yellow-100 rounded-lg bg-yellow-50">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-yellow-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Important Note</h3>
                                <div class="mt-1 text-sm text-yellow-700">
                                    <p>After submitting this form, you must visit our office within 14 days with the following documents:</p>
                                    <ul class="pl-5 mt-1 space-y-1 list-disc">
                                        <li>Staff ID card</li>
                                        <li>Recent passport photograph</li>
                                        <li>Proof of address</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection