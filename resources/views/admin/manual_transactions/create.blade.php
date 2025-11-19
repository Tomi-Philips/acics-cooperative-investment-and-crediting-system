@extends('layouts.admin')

@section('title', 'Process Individual Transaction')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Enhanced Header Section -->
    <div class="p-6 mb-8 border border-gray-200 shadow-lg bg-gradient-to-r from-green-50 to-blue-50 rounded-xl">
        <div class="flex flex-col items-center justify-between lg:flex-row">
            <div class="mb-6 lg:mb-0">
                <h1 class="flex items-center text-2xl font-bold text-gray-800">
                    <div class="p-2 mr-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    Process Individual Transaction
                </h1>
                <p class="mt-2 text-sm text-gray-600">Record in-person member transactions with proper validation</p>
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

    <!-- Session Messages -->
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Transaction Form Card -->
        <div class="lg:col-span-2">
            <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
                <!-- Card Header -->
                <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                    <div class="flex items-center">
                        <div class="p-2 mr-4 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Transaction Details</h2>
                            <p class="mt-1 text-sm text-gray-600">Enter transaction information</p>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="p-8">
                    <form id="transactionForm" method="POST" action="{{ route('admin.manual_transactions.store') }}">
                        @csrf

                        <!-- Member Search -->
                        <div class="mb-6">
                            <label for="member_search" class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Search Member
                            </label>
                            <div class="relative mt-1">
                                <input type="text"
                                       id="member_search"
                                       placeholder="Search by name or member number..."
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Search Results -->
                            <div id="search_results" class="hidden absolute z-10 w-full bg-white border border-gray-300 rounded-lg shadow-lg mt-1 max-h-60 overflow-y-auto">
                                <!-- Results will be populated by JavaScript -->
                            </div>

                            <!-- Selected Member -->
                            <input type="hidden" id="user_id" name="user_id" value="{{ old('user_id') }}">
                            <div id="selected_member" class="hidden mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <!-- Selected member info will be displayed here -->
                            </div>
                        </div>

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
                                    class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm bg-white"
                                    required>
                                <option value="">Select transaction type...</option>

                                <!-- Core Financial Services -->
                                <optgroup label="🏦 Core Financial Services">
                                    <option value="entrance" {{ old('transaction_type') === 'entrance' ? 'selected' : '' }}>💳 Entrance Fee</option>
                                    <option value="shares" {{ old('transaction_type') === 'shares' ? 'selected' : '' }}>📈 Shares</option>
                                    <option value="savings" {{ old('transaction_type') === 'savings' ? 'selected' : '' }}>💰 Savings</option>
                                </optgroup>

                                <!-- Loan Services -->
                                <optgroup label="🏛️ Loan Services">
                                    <option value="loan_repay" {{ old('transaction_type') === 'loan_repay' ? 'selected' : '' }}>💸 Loan Repayment</option>
                                    <option value="loan_interest" {{ old('transaction_type') === 'loan_interest' ? 'selected' : '' }}>📊 Loan Interest</option>
                                    <option value="loan_disbursement" {{ old('transaction_type') === 'loan_disbursement' ? 'selected' : '' }}>💵 Loan Disbursement</option>
                                </optgroup>

                                <!-- Commodity Services -->
                                <optgroup label="🛒 Commodity Services">
                                    <option value="essential" {{ old('transaction_type') === 'essential' ? 'selected' : '' }}>🥬 Essential Commodities</option>
                                    <option value="non_essential" {{ old('transaction_type') === 'non_essential' ? 'selected' : '' }}>🍫 Non-Essential Commodities</option>
                                    <option value="electronics" {{ old('transaction_type') === 'electronics' ? 'selected' : '' }}>📱 Electronics</option>
                                </optgroup>
                            </select>
                        </div>

                        <!-- Transaction Operation -->
                        <div class="mb-6" id="operation_section" style="display: none;">
                            <label class="flex items-center text-sm font-semibold text-gray-700" id="operation_label">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                                Transaction Operation
                            </label>
                            <div class="grid grid-cols-2 gap-4 mt-1" id="operation_options">
                                <!-- Options will be populated by JavaScript based on transaction type -->
                            </div>
                            <input type="hidden" name="operation" id="operation_value">
                        </div>

                        <!-- Amount -->
                        <div class="mb-6">
                            <label for="amount" class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Amount (₦)
                            </label>
                            <input type="number"
                                   id="amount"
                                   name="amount"
                                   step="0.01"
                                   min="0.01"
                                   value="{{ old('amount') }}"
                                   class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm"
                                   placeholder="0.00"
                                   required>
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
                                      placeholder="Enter transaction description..."
                                      required>{{ old('description') }}</textarea>
                        </div>

                        <!-- Approval Required -->
                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox"
                                       name="approval_required"
                                       value="1"
                                       class="text-green-600 focus:ring-green-500"
                                       {{ old('approval_required') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Requires additional approval for high-value transactions</span>
                            </label>
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
                                Process Transaction
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Member Information Panel -->
        <div class="lg:col-span-1">
            <div id="member_info_panel" class="bg-white rounded-lg shadow-md p-6 hidden mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Member Information</h3>
                <div id="member_details">
                    <!-- Member details will be populated by JavaScript -->
                </div>
            </div>

            <!-- Business Rules Panel -->
            <div class="bg-blue-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">Manual Transaction Guidelines</h3>
                <div class="space-y-3 text-sm text-blue-800">
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                        <div><strong>Entrance Fee:</strong> One-time membership payment only</div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                        <div><strong>Shares:</strong> Maximum ₦10,000 per member, purchase only</div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                        <div><strong>Savings:</strong> Typically withdrawals (deposits via MAB upload)</div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                        <div><strong>Loans:</strong> Typically disbursements (repayments via MAB upload)</div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                        <div><strong>Commodities/Electronics:</strong> Issue on credit or record repayments</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Member search functionality
let searchTimeout;
const memberSearch = document.getElementById('member_search');
const searchResults = document.getElementById('search_results');
const selectedMember = document.getElementById('selected_member');
const userIdInput = document.getElementById('user_id');
const memberInfoPanel = document.getElementById('member_info_panel');
const memberDetails = document.getElementById('member_details');

memberSearch.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const query = this.value.trim();

    if (query.length < 2) {
        searchResults.classList.add('hidden');
        return;
    }

    searchTimeout = setTimeout(() => {
        searchMembers(query);
    }, 300);
});

function searchMembers(query) {
    fetch(`{{ route('admin.manual_transactions.search_members') }}?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            displaySearchResults(data);
        })
        .catch(error => {
            console.error('Search error:', error);
        });
}

function displaySearchResults(members) {
    if (members.length === 0) {
        searchResults.innerHTML = '<div class="p-3 text-gray-500">No members found</div>';
        searchResults.classList.remove('hidden');
        return;
    }

    const resultsHtml = members.map(member => `
        <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0"
             onclick="selectMember(${member.id}, '${member.name}', '${member.member_number}', '${member.email}')">
            <div class="font-medium text-gray-900">${member.name}</div>
            <div class="text-sm text-gray-500">${member.member_number} • ${member.email}</div>
            <div class="text-xs text-gray-400 mt-1">
                Shares: ₦${parseFloat(member.balances.shares).toLocaleString()} |
                Savings: ₦${parseFloat(member.balances.savings).toLocaleString()}
            </div>
        </div>
    `).join('');

    searchResults.innerHTML = resultsHtml;
    searchResults.classList.remove('hidden');
}

function selectMember(id, name, memberNumber, email) {
    userIdInput.value = id;
    memberSearch.value = `${name} (${memberNumber})`;
    searchResults.classList.add('hidden');

    selectedMember.innerHTML = `
        <div class="flex items-center">
            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                <span class="text-green-600 font-semibold">${name.charAt(0)}</span>
            </div>
            <div class="ml-3">
                <div class="font-medium text-gray-900">${name}</div>
                <div class="text-sm text-gray-500">${memberNumber} • ${email}</div>
            </div>
        </div>
    `;
    selectedMember.classList.remove('hidden');

    // Load member details
    loadMemberDetails(id);
}

function loadMemberDetails(userId) {
    console.log('Loading member details for user ID:', userId);
    const url = `{{ route('admin.manual_transactions.member_details', ['userId' => ':userId']) }}`.replace(':userId', userId);
    console.log('API URL:', url);

    fetch(url)
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Member details API response:', data);
            displayMemberDetails(data);
        })
        .catch(error => {
            console.error('Error loading member details:', error);
            // Show error message in the panel
            memberDetails.innerHTML = `
                <div class="text-center py-4">
                    <div class="text-red-600 text-sm">
                        <p>Error loading member details</p>
                        <p class="text-xs mt-1">${error.message}</p>
                    </div>
                </div>
            `;
            memberInfoPanel.classList.remove('hidden');
        });
}

function displayMemberDetails(member) {
    console.log('displayMemberDetails called with:', member);
    console.log('Member balances:', member.balances);

    // Safe parsing of balance values
    const sharesAmount = member.balances.shares || 0;
    const savingsAmount = member.balances.savings || 0;
    const loanInterest = member.balances.loan_interest || 0;

    console.log('Parsed amounts - Shares:', sharesAmount, 'Savings:', savingsAmount, 'Loan Interest:', loanInterest);

    const detailsHtml = `
        <div class="space-y-6">
            <div>
                <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Current Balances
                </h4>
                <div class="space-y-3 text-sm bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="flex items-center">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                            Shares:
                        </span>
                        <span class="font-semibold text-blue-600">₦${parseFloat(sharesAmount).toLocaleString()}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                            Savings:
                        </span>
                        <span class="font-semibold text-green-600">₦${parseFloat(savingsAmount).toLocaleString()}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="flex items-center">
                            <span class="w-2 h-2 ${member.balances.entrance_paid ? 'bg-green-500' : 'bg-red-500'} rounded-full mr-2"></span>
                            Entrance Fee:
                        </span>
                        <span class="font-semibold ${member.balances.entrance_paid ? 'text-green-600' : 'text-red-600'}">
                            ${member.balances.entrance_paid ? 'Paid' : 'Unpaid'}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="flex items-center">
                            <span class="w-2 h-2 bg-orange-500 rounded-full mr-2"></span>
                            Loan Interest:
                        </span>
                        <span class="font-semibold text-orange-600">₦${parseFloat(loanInterest).toLocaleString()}</span>
                    </div>
                </div>
            </div>

            <!-- Commodity Balances -->
            ${(member.commodities && member.commodities.length > 0) || (member.electronics && member.electronics.length > 0) ? `
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Commodity Balances
                    </h4>
                    <div class="space-y-2 text-sm bg-purple-50 p-4 rounded-lg">
                        ${member.commodities.map(commodity => `
                            <div class="flex justify-between items-center">
                                <span class="flex items-center">
                                    <span class="w-2 h-2 ${commodity.type === 'essential' ? 'bg-green-500' : 'bg-yellow-500'} rounded-full mr-2"></span>
                                    ${commodity.type === 'essential' ? '🥬 Essential' : '🍫 Non-Essential'}:
                                </span>
                                <span class="font-semibold text-purple-600">₦${parseFloat(commodity.balance || 0).toLocaleString()}</span>
                            </div>
                        `).join('')}
                        ${member.electronics.map(electronic => `
                            <div class="flex justify-between items-center">
                                <span class="flex items-center">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                    📱 ${electronic.name}:
                                </span>
                                <span class="font-semibold text-blue-600">₦${parseFloat(electronic.remaining_balance || 0).toLocaleString()}</span>
                            </div>
                        `).join('')}
                    </div>
                </div>
            ` : ''}

            <!-- Active Loans -->
            ${member.loans.length > 0 ? `
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Active Loans
                    </h4>
                    <div class="space-y-2 text-sm bg-red-50 p-4 rounded-lg">
                        ${member.loans.map(loan => `
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="font-semibold text-red-700">${loan.loan_number}</div>
                                    <div class="text-xs text-red-500">Principal: ₦${parseFloat(loan.amount).toLocaleString()}</div>
                                </div>
                                <span class="font-semibold text-red-600">₦${parseFloat(loan.remaining_balance).toLocaleString()}</span>
                            </div>
                        `).join('')}
                    </div>
                </div>
            ` : ''}
        </div>
    `;

    memberDetails.innerHTML = detailsHtml;
    memberInfoPanel.classList.remove('hidden');
}

// Hide search results when clicking outside
document.addEventListener('click', function(event) {
    if (!memberSearch.contains(event.target) && !searchResults.contains(event.target)) {
        searchResults.classList.add('hidden');
    }
});

// Transaction type change handler for operation selection
function handleTransactionTypeChange() {
    const transactionType = document.getElementById('transaction_type').value;
    const operationSection = document.getElementById('operation_section');
    const operationLabel = document.getElementById('operation_label');
    const operationOptions = document.getElementById('operation_options');
    const operationValue = document.getElementById('operation_value');

    console.log('handleTransactionTypeChange called with:', transactionType);

    if (!transactionType) {
        console.log('No transaction type, hiding operation section');
        operationSection.style.display = 'none';
        return;
    }

    console.log('Showing operation section for:', transactionType);
    operationSection.style.display = 'block';



    // Define operation options based on transaction type
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
            label: 'Loan Repayment',
            options: [
                { value: 'subtraction', label: 'Loan Repayment', description: 'Record loan payment', color: 'red', default: true }
            ]
        },
        'loan_interest': {
            label: 'Loan Interest Payment',
            options: [
                { value: 'subtraction', label: 'Interest Payment', description: 'Record interest payment', color: 'green', default: true }
            ]
        },
        'loan_disbursement': {
            label: 'Loan Disbursement',
            options: [
                { value: 'addition', label: 'Disburse Loan', description: 'Issue new loan to member', color: 'green', default: true }
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
        <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 operation-option" data-value="${option.value}">
            <input type="radio"
                   name="operation_radio"
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
    document.querySelectorAll('input[name="operation_radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            operationValue.value = this.value;
        });
    });
}

document.getElementById('transaction_type').addEventListener('change', handleTransactionTypeChange);

// Initialize operation field on page load if transaction type is already selected
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, checking transaction type...');
    const transactionType = document.getElementById('transaction_type').value;
    console.log('Current transaction type:', transactionType);
    if (transactionType) {
        console.log('Initializing operation field for:', transactionType);
        handleTransactionTypeChange();
    }
});
</script>
@endsection
