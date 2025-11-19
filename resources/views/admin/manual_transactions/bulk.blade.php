@extends('layouts.admin')

@section('title', 'Bulk Transaction Upload')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Enhanced Header Section -->
    <div class="p-6 mb-8 border border-gray-200 shadow-lg bg-gradient-to-r from-green-50 to-blue-50 rounded-xl">
        <div class="flex flex-col items-center justify-between lg:flex-row">
            <div class="mb-6 lg:mb-0">
                <h1 class="flex items-center text-2xl font-bold text-gray-800">
                    <div class="p-2 mr-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                        </svg>
                    </div>
                    Bulk Transaction Upload
                </h1>
                <p class="mt-2 text-sm text-gray-600">Upload multiple transactions of the same type using Excel/CSV template</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.manual_transactions.index') }}"
                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Upload Form Card -->
        <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
            <!-- Card Header -->
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                <div class="flex items-center">
                    <div class="p-2 mr-4 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Upload Transaction File</h2>
                        <p class="mt-1 text-sm text-gray-600">Select file and transaction details</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-8">
                <form method="POST" action="{{ route('admin.manual_transactions.bulk_store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Transaction Type -->
                    <div class="mb-6">
                        <label for="transaction_type" class="flex items-center text-sm font-semibold text-gray-700">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Transaction Type
                        </label>
                        <select id="transaction_type"
                                name="transaction_type"
                                class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm"
                                required>
                            <option value="">Select transaction type...</option>
                            <option value="entrance">Entrance Fee</option>
                            <option value="shares">Shares</option>
                            <option value="savings">Savings</option>
                            <option value="loan_repay">Loan Repayment</option>
                            <option value="loan_interest">Loan Interest</option>
                            <option value="essential">Essential Commodity</option>
                            <option value="non_essential">Non-Essential Commodity</option>
                            <option value="electronics">Electronics</option>
                        </select>
                    </div>

                    <!-- Transaction Operation -->
                    <div class="mb-6" id="bulk_operation_section" style="display: none;">
                        <label class="flex items-center text-sm font-semibold text-gray-700" id="bulk_operation_label">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                            </svg>
                            Transaction Operation
                        </label>
                        <div class="grid grid-cols-2 gap-4 mt-1" id="bulk_operation_options">
                            <!-- Options will be populated by JavaScript based on transaction type -->
                        </div>
                        <input type="hidden" name="operation" id="bulk_operation_value" value="addition" required>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-6">
                        <label for="excel_file" class="flex items-center text-sm font-semibold text-gray-700">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            Upload File
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="excel_file" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                        <span>Upload a file</span>
                                        <input id="excel_file" name="excel_file" type="file" class="sr-only" accept=".xlsx,.xls,.csv" required>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">Excel or CSV files up to 10MB</p>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="flex items-center text-sm font-semibold text-gray-700">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            Description
                        </label>
                        <textarea id="description"
                                  name="description"
                                  rows="3"
                                  class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm"
                                  placeholder="Enter batch description..."
                                  required></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3">
                        <button type="button"
                                onclick="window.history.back()"
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Upload & Process
                        </button>

                        <!-- Debug: Test basic form submission -->
                        <button type="button"
                                onclick="testBasicSubmit()"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors ml-2">
                            Test Submit (No File)
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Instructions & Template -->
        <div class="space-y-6">
            <!-- Template Download -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Download Template</h3>
                <p class="text-gray-600 mb-4">Use our Excel template to ensure proper formatting for bulk uploads.</p>

                <div class="space-y-3">
                    <a href="{{ route('admin.manual_transactions.template') }}" class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div>
                            <div class="font-medium text-gray-900">Bulk Transaction Template</div>
                            <div class="text-sm text-gray-500">CSV format with sample data</div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Instructions -->
            <div class="bg-blue-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">Upload Instructions</h3>
                <div class="space-y-3 text-sm text-blue-800">
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-blue-200 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <span class="text-xs font-bold">1</span>
                        </div>
                        <div>Download the template and fill in member numbers and amounts</div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-blue-200 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <span class="text-xs font-bold">2</span>
                        </div>
                        <div>Select the transaction type and operation (addition/subtraction)</div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-blue-200 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <span class="text-xs font-bold">3</span>
                        </div>
                        <div>Upload your completed file and provide a description</div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-blue-200 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <span class="text-xs font-bold">4</span>
                        </div>
                        <div>Review the preview and confirm processing</div>
                    </div>
                </div>
            </div>

            <!-- Business Rules -->
            <div class="bg-yellow-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-yellow-900 mb-4">Important Notes</h3>
                <div class="space-y-2 text-sm text-yellow-800">
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-yellow-600 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                        <div>All business rules will be validated during processing</div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-yellow-600 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                        <div>Invalid records will be skipped with detailed error reporting</div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-yellow-600 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                        <div>Member numbers must exist in the system</div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-yellow-600 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                        <div>Amounts must be positive numbers</div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-yellow-600 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                        <div>Operation is automatically determined based on transaction context</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Debug form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const descriptionField = document.getElementById('description');

    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submit event triggered');
            console.log('Form data:', new FormData(this));
            console.log('Description value:', descriptionField ? descriptionField.value : 'N/A');

            // Don't prevent default, just log
        });
    }

    if (descriptionField) {
        descriptionField.addEventListener('input', function() {
            console.log('Description field changed:', this.value);
        });
    }
});
</script>

<script>
// File upload handling
const fileInput = document.getElementById('excel_file');
const fileLabel = fileInput.parentElement;

fileInput.addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name;
    if (fileName) {
        fileLabel.innerHTML = `<span>Selected: ${fileName}</span>`;
        fileLabel.classList.add('text-green-600');
    }
});

// Transaction type change handler for bulk operations
document.getElementById('transaction_type').addEventListener('change', function() {
    const transactionType = this.value;
    const operationSection = document.getElementById('bulk_operation_section');
    const operationLabel = document.getElementById('bulk_operation_label');
    const operationOptions = document.getElementById('bulk_operation_options');
    const operationValue = document.getElementById('bulk_operation_value');

    if (!transactionType) {
        operationSection.style.display = 'none';
        return;
    }

    operationSection.style.display = 'block';

    // Define operation options based on transaction type (same as individual form)
    const operationConfigs = {
        'entrance': {
            label: 'Entrance Fee Payment',
            options: [
                { value: 'addition', label: 'Record Payment', description: 'One-time membership fee payment', color: 'green', default: true }
            ]
        },
        'shares': {
            label: 'Share Transaction',
            options: [
                { value: 'addition', label: 'Purchase Shares', description: 'Increase member share capital', color: 'green', default: true }
            ]
        },
        'savings': {
            label: 'Savings Transaction',
            options: [
                { value: 'subtraction', label: 'Withdrawal', description: 'Member withdraws savings', color: 'red', default: true },
                { value: 'addition', label: 'Deposit', description: 'Member deposits savings', color: 'green' }
            ]
        },
        'loan_repay': {
            label: 'Loan Transaction',
            options: [
                { value: 'addition', label: 'Loan Disbursement', description: 'Issue new loan to member', color: 'green', default: true },
                { value: 'subtraction', label: 'Loan Repayment', description: 'Record loan payment', color: 'red' }
            ]
        },
        'loan_interest': {
            label: 'Loan Interest Transaction',
            options: [
                { value: 'addition', label: 'Add Interest', description: 'Add interest to loan balance', color: 'red', default: true },
                { value: 'subtraction', label: 'Interest Payment', description: 'Record interest payment', color: 'green' }
            ]
        },
        'essential': {
            label: 'Essential Commodity Transaction',
            options: [
                { value: 'addition', label: 'Issue on Credit', description: 'Member receives goods on credit', color: 'red', default: true },
                { value: 'subtraction', label: 'Record Repayment', description: 'Member pays for goods', color: 'green' }
            ]
        },
        'non_essential': {
            label: 'Non-Essential Commodity Transaction',
            options: [
                { value: 'addition', label: 'Issue on Credit', description: 'Member receives goods on credit', color: 'red', default: true },
                { value: 'subtraction', label: 'Record Repayment', description: 'Member pays for goods', color: 'green' }
            ]
        },
        'electronics': {
            label: 'Electronics Transaction',
            options: [
                { value: 'addition', label: 'Issue on Credit', description: 'Member receives electronics on credit', color: 'red', default: true },
                { value: 'subtraction', label: 'Record Repayment', description: 'Member pays for electronics', color: 'green' }
            ]
        }
    };

    const config = operationConfigs[transactionType];
    if (!config) return;

    operationLabel.textContent = config.label;

    // Generate operation options HTML
    const optionsHtml = config.options.map((option, index) => `
        <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 bulk-operation-option" data-value="${option.value}">
            <input type="radio"
                   name="bulk_operation_radio"
                   value="${option.value}"
                   class="text-${option.color}-600 focus:ring-${option.color}-500"
                   ${option.default ? 'checked' : ''}>
            <div class="ml-3">
                <div class="text-sm font-medium text-gray-900">${option.label}</div>
                <div class="text-xs text-gray-500">${option.description}</div>
            </div>
        </label>
    `).join('');

    operationOptions.innerHTML = optionsHtml;

    // Set default value
    const defaultOption = config.options.find(opt => opt.default);
    if (defaultOption) {
        operationValue.value = defaultOption.value;
    }

    // Add event listeners to radio buttons
    document.querySelectorAll('input[name="bulk_operation_radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            operationValue.value = this.value;
        });
    });
});

// Test basic form submission without file
function testBasicSubmit() {
    const form = document.querySelector('form');
    const formData = new FormData(form);

    // Remove file from form data for testing
    formData.delete('excel_file');

    console.log('Testing basic form submission...');
    console.log('Form data (no file):', Object.fromEntries(formData));

    // Submit the form normally
    form.submit();
}
</script>
@endsection
