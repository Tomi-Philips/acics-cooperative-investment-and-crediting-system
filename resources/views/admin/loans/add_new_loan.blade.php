@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col items-start justify-between gap-4 mb-8 md:flex-row md:items-center">
            <div>
                <h1 class="flex items-center gap-2 text-2xl font-bold text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    New Loan Application
                </h1>
                <p class="mt-1 text-sm text-gray-500">Create a new loan application for a member.</p>
            </div>
            <a href="{{ route('admin.loans.all') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                View All Loans
            </a>
        </div>

        <div class="p-8 overflow-hidden bg-white rounded-lg shadow-md">
            <form class="space-y-6" action="#" method="POST">
                @csrf

                {{-- General Error Message Display (Optional) --}}
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
                        <p class="font-bold">Please correct the following errors:</p>
                        <ul class="list-disc ml-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label for="borrower" class="block mb-2 text-sm font-semibold text-gray-700">
                        Borrower <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <select id="borrower" name="borrower" required
                                class="block w-full px-4 py-2 pl-10 transition-all duration-200 ease-in-out bg-gray-100 border
                                {{ $errors->has('borrower') ? 'border-red-500' : 'border-gray-300' }}
                                rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600 sm:text-sm">
                            <option value="" disabled selected>Select Borrower</option>
                            {{-- Dynamically populate options from your database --}}
                            <option value="1" {{ old('borrower') == '1' ? 'selected' : '' }}>Mike Benson</option>
                            <option value="2" {{ old('borrower') == '2' ? 'selected' : '' }}>Alice Brown</option>
                            <option value="3" {{ old('borrower') == '3' ? 'selected' : '' }}>John Doe</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    @error('borrower')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="loan_amount" class="block mb-2 text-sm font-semibold text-gray-700">
                        Loan Amount <span class="text-red-500">*</span>
                    </label>
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">₦</span>
                        </div>
                        <input type="number" id="loan_amount" name="loan_amount" min="1000" max="10000000" step="100" required
                               class="block w-full py-2 pl-8 pr-12 transition-all duration-200 ease-in-out bg-gray-100 border
                               {{ $errors->has('loan_amount') ? 'border-red-500' : 'border-gray-300' }}
                               rounded-md shadow-sm focus:ring-2 focus:ring-green-600 focus:border-green-600 sm:text-sm"
                               placeholder="0.00" value="{{ old('loan_amount') }}">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">NGN</span>
                        </div>
                    </div>
                    @error('loan_amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Enter amount between ₦1,000 and ₦10,000,000.</p>
                </div>

                <div>
                    <label for="interest_rate" class="block mb-2 text-sm font-semibold text-gray-700">Interest Rate</label>
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <input type="text" id="interest_rate" name="interest_rate"
                               class="block w-full px-4 py-2 pl-10 pr-8 text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm cursor-not-allowed sm:text-sm"
                               value="10" readonly> {{-- Value is hardcoded as per your comment --}}
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 pointer-events-none sm:text-sm">
                            %
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Fixed interest rate of 10% for all loans.</p>
                </div>

                <div>
                    <label for="term_months" class="block mb-2 text-sm font-semibold text-gray-700">
                        Term (months) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="number" id="term_months" name="term_months" min="1" max="24" required
                               class="block w-full px-4 py-2 pl-10 pr-12 transition-all duration-200 ease-in-out bg-gray-100 border
                               {{ $errors->has('term_months') ? 'border-red-500' : 'border-gray-300' }}
                               rounded-md shadow-sm focus:ring-2 focus:ring-green-600 focus:border-green-600 sm:text-sm"
                               placeholder="6" value="{{ old('term_months') }}">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 pointer-events-none sm:text-sm">
                            months
                        </div>
                    </div>
                    @error('term_months')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="mt-3">
                        <input type="range" id="term_slider" min="1" max="24" value="{{ old('term_months', 6) }}"
                               class="w-full h-2 bg-green-200 rounded-lg appearance-none cursor-pointer accent-green-600">
                    </div>
                    <div class="flex justify-between mt-1 text-xs text-gray-500">
                        <span>1 month</span>
                        <span>6 months</span>
                        <span>12 months</span>
                        <span>18 months</span>
                        <span>24 months</span>
                    </div>
                </div>

                <div class="flex items-center justify-end pt-6 space-x-4">
                    <button type="reset" class="px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-200 ease-in-out bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Clear Form
                    </button>
                    <button type="submit" id="submit-btn" class="inline-flex items-center px-6 py-2 text-sm font-medium text-white transition-all duration-200 ease-in-out bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span id="submit-text">Submit Application</span>
                        <svg id="submit-spinner" class="hidden w-4 h-4 ml-2 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-8 overflow-hidden bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Loan Information</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="p-4 rounded-lg bg-gray-50">
                        <h4 class="mb-3 text-sm font-semibold text-gray-700">Loan Terms</h4>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 w-5 h-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-600">Fixed interest rate of 10% for all loans.</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 w-5 h-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-600">Loan terms from 1 to 24 months.</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 w-5 h-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-600">No early repayment penalties.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="p-4 rounded-lg bg-gray-50">
                        <h4 class="mb-3 text-sm font-semibold text-gray-700">Eligibility Requirements</h4>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 w-5 h-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-600">Minimum 6 months membership.</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 w-5 h-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-600">Maximum loan amount: 2 × (Savings + Shares).</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 w-5 h-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-600">No outstanding loans or defaults.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Sync the number input and range slider for 'Term (months)'
            document.getElementById('term_months').addEventListener('input', function() {
                document.getElementById('term_slider').value = this.value;
            });

            document.getElementById('term_slider').addEventListener('input', function() {
                document.getElementById('term_months').value = this.value;
            });

            // Handle form submission with loading state (optional, for visual feedback)
            document.querySelector('form').addEventListener('submit', function() {
                const submitBtn = document.getElementById('submit-btn');
                const submitText = document.getElementById('submit-text');
                const submitSpinner = document.getElementById('submit-spinner');

                submitBtn.disabled = true; // Disable button to prevent multiple submissions
                submitText.textContent = 'Submitting...'; // Change text
                submitSpinner.classList.remove('hidden'); // Show spinner
            });

            // You might want to pre-fill the form fields with `old()` helper if there's a validation error
            // This is already conceptually added in the input elements: `value="{{ old('input_name') }}"`
            // And for select: `{{ old('select_name') == $option->value ? 'selected' : '' }}`
        </script>
    @endpush
@endsection