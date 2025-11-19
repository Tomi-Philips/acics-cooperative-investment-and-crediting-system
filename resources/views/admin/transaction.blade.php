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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                    Transaction History
                </h1>
                <p class="mt-2 text-sm text-gray-600">View and manage all financial transactions</p>
            </div>
        </div>
    </div>

    <!-- Summary Section -->
    <div class="mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Transaction Groups</h2>
                    <p class="text-sm text-gray-600">Grouped transactions with smart organization</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $transactions->total() }}</div>
                        <div class="text-xs text-gray-500">Total Groups</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions List Card -->
    <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-xl">
        <!-- Card Header -->
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
            <div class="flex items-center">
                <div class="p-2 mr-4 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">All Transactions</h2>
                    <p class="mt-1 text-sm text-gray-600">Complete list of all financial transactions</p>
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
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> # </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> User </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Type </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Amount </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Date </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"> Reference # </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase"> Actions </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($transactions as $index => $group)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ $index + $transactions->firstItem() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-10 h-10">
                                                    <div class="flex items-center justify-center w-10 h-10 font-bold text-blue-800 bg-blue-100 rounded-full">
                                                        {{ strtoupper(substr($group->processedBy->name ?? 'S', 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $group->processedBy->name ?? 'System' }}</div>
                                                    <div class="text-sm text-gray-500">{{ $group->processedBy->email ?? 'System Process' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $groupTypeColors = [
                                                    'user_bulk_upload' => 'blue',
                                                    'mab_bulk_upload' => 'purple',
                                                    'manual_transaction' => 'green',
                                                    'admin_approval' => 'yellow',
                                                    'system_transaction' => 'gray',
                                                    'bulk_operation' => 'indigo'
                                                ];
                                                $color = $groupTypeColors[$group->group_type] ?? 'gray';

                                                // Get the primary transaction type from the group for icon selection
                                                $primaryTransaction = $group->transactions->first();
                                                $transactionType = $primaryTransaction ? $primaryTransaction->type : 'unknown';

                                                $transactionIcons = [
                                                    'loan_disbursement' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                                                    'loan_repayment' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>',
                                                    'loan_interest' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                                                    'entrance_fee' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>',
                                                    'commodity' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
                                                    'electronics' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
                                                    'saving_credit' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>',
                                                    'saving_debit' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>',
                                                    'share_credit' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                                                    'share_debit' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                                                    'default' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>'
                                                ];
                                                $iconPath = $transactionIcons[$transactionType] ?? $transactionIcons['default'];
                                            @endphp
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-{{ $color }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    {!! $iconPath !!}
                                                </svg>
                                                <span class="px-2 py-1 text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800 rounded-full">
                                                    {{ $group->group_type_display }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                            ₦{{ number_format($group->total_amount ?? 0, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ $group->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="font-medium">{{ $group->group_reference }}</span>
                                                <span class="text-xs text-blue-600">{{ $group->total_records ?? 0 }} records</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('admin.transactions.show', 'GROUP-' . $group->id) }}" class="p-1 text-blue-600 rounded-full hover:text-blue-900 hover:bg-blue-50" title="View Group Details">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            No transactions found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if ($transactions->hasPages())
                <div class="flex items-center justify-center px-4 py-3 bg-white border-t border-gray-200 sm:px-6 sm:py-4 sm:justify-between">
                    <div class="hidden sm:block">
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium">{{ $transactions->firstItem() }}</span> to <span class="font-medium">{{ $transactions->lastItem() }}</span> of <span class="font-medium">{{ $transactions->total() }}</span> results
                        </p>
                    </div>
                    <div class="flex justify-center w-full sm:w-auto">
                        {{ $transactions->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>


</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Date Range Filter Logic
        const dateFromInput = document.getElementById('date_from');
        const dateToInput = document.getElementById('date_to');

        function applyDateFilter() {
            const url = new URL(window.location.href);
            if (dateFromInput.value) {
                url.searchParams.set('start_date', dateFromInput.value);
            } else {
                url.searchParams.delete('start_date');
            }
            if (dateToInput.value) {
                url.searchParams.set('end_date', dateToInput.value);
            } else {
                url.searchParams.delete('end_date');
            }
            window.location.href = url.toString();
        }

        dateFromInput.addEventListener('change', applyDateFilter);
        dateToInput.addEventListener('change', applyDateFilter);

        // Export CSV Button Logic
        const exportCsvBtn = document.getElementById('exportCsvBtn');
        if (exportCsvBtn) {
            exportCsvBtn.addEventListener('click', function() {
                const userId = document.getElementById('user_id_filter')?.value || '';
                const type = document.getElementById('type_filter')?.value || '';
                const startDate = dateFromInput.value || '';
                const endDate = dateToInput.value || '';

                let url = '{{ route("admin.transactions.report") }}';
                const params = new URLSearchParams();
                if (userId) params.append('user_id', userId);
                if (type) params.append('type', type);
                if (startDate) params.append('start_date', startDate);
                if (endDate) params.append('end_date', endDate);

                window.location.href = url + '?' + params.toString();
            });
        }

        // Add Transaction Modal Logic
        const addTransactionModal = document.getElementById('addTransactionModal');
        const closeAddTransactionModalBtn = document.getElementById('closeAddTransactionModal');

        closeAddTransactionModalBtn.addEventListener('click', function() {
            addTransactionModal.classList.add('hidden');
        });

        // Share Limit Note Logic within the modal
        const modalTypeSelect = document.getElementById('modal_type');
        const shareLimitNote = document.getElementById('share-limit-note');

        if (modalTypeSelect && shareLimitNote) {
            modalTypeSelect.addEventListener('change', function() {
                if (this.value === 'share_purchase') {
                    shareLimitNote.style.display = 'block';
                } else {
                    shareLimitNote.style.display = 'none';
                }
            });
        }
    });
</script>
@endpush
@endsection