@extends('layouts.user')

@section('content')
<div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <!-- Financial Status Sidebar -->
        <div class="lg:col-span-1">
            <div class="overflow-hidden bg-white border border-gray-100 shadow-xl rounded-2xl">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-br from-green-50 via-green-100 to-emerald-50">
                    <h3 class="flex items-center text-lg font-bold text-gray-800">
                        <div class="p-2 mr-3 shadow-lg bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        {{ __('Financial Summary') }}
                    </h3>
                </div>
                <div class="p-6 space-y-5">
                    <!-- Savings Card -->
                    <div class="p-5 transition-all duration-300 border border-green-200 shadow-lg rounded-2xl bg-gradient-to-br from-green-50 to-emerald-50 hover:shadow-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-green-700">{{ __('Total Savings') }}</p>
                                <p class="text-2xl font-bold text-green-600">₦{{ number_format($savingsBalance, 2) }}</p>
                            </div>
                            <div class="p-3 shadow-lg bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Shares Card -->
                    <div class="p-5 transition-all duration-300 border border-purple-200 shadow-lg rounded-2xl bg-gradient-to-br from-purple-50 to-violet-50 hover:shadow-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-purple-700">{{ __('Shares') }}</p>
                                <p class="text-2xl font-bold text-purple-600">₦{{ number_format($sharesBalance, 2) }}</p>
                            </div>
                            <div class="p-3 shadow-lg bg-gradient-to-br from-purple-500 to-violet-600 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Loan Card -->
                    <div class="p-5 transition-all duration-300 border border-red-200 shadow-lg rounded-2xl bg-gradient-to-br from-red-50 to-rose-50 hover:shadow-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-red-700">{{ __('Loan Balance') }}</p>
                                <p class="text-2xl font-bold text-red-600">₦{{ number_format($loanBalance, 2) }}</p>
                            </div>
                            <div class="p-3 shadow-lg bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Commodity Balances -->
                    <div class="space-y-3">
                        <div class="p-4 transition-all duration-300 border border-gray-200 shadow-md rounded-xl bg-gradient-to-br from-gray-50 to-slate-50 hover:shadow-lg">
                            <p class="text-xs font-semibold tracking-wide text-gray-600 uppercase">{{ __('Essential Commodity') }}</p>
                            <p class="text-lg font-bold text-gray-800">₦{{ number_format($essentialBalance ?? 0, 2) }}</p>
                        </div>
                        <div class="p-4 transition-all duration-300 border border-gray-200 shadow-md rounded-xl bg-gradient-to-br from-gray-50 to-slate-50 hover:shadow-lg">
                            <p class="text-xs font-semibold tracking-wide text-gray-600 uppercase">{{ __('Non-essential Commodity') }}</p>
                            <p class="text-lg font-bold text-gray-800">₦{{ number_format($nonEssentialBalance ?? 0, 2) }}</p>
                        </div>
                        <div class="p-4 transition-all duration-300 border border-gray-200 shadow-md rounded-xl bg-gradient-to-br from-gray-50 to-slate-50 hover:shadow-lg">
                            <p class="text-xs font-semibold tracking-wide text-gray-600 uppercase">{{ __('Electronics') }}</p>
                            <p class="text-lg font-bold text-gray-800">₦{{ number_format($electronicsBalance ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="space-y-8 lg:col-span-2">
            <!-- Withdrawal Request Form -->
            <div class="overflow-hidden bg-white border border-gray-100 shadow-xl rounded-2xl">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-br from-green-50 via-green-100 to-emerald-50">
                    <h2 class="flex items-center text-xl font-bold text-gray-800">
                        <div class="p-2 mr-3 shadow-lg bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h.01M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3v-4a3 3 0 013-3h2" />
                            </svg>
                        </div>
                        {{ __('Request Savings Withdrawal') }}
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">{{ __('Fill in the amount you wish to withdraw from your savings.') }}</p>
                </div>

                <div class="p-6">
                    @if (isset($errors) && $errors->any())
                    <div class="p-4 mb-4 border-l-4 border-red-500 bg-red-50">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">{{ __('Please fix the following errors:') }}</h3>
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

                    <form method="POST" action="{{ route('user.saving_withdrawals.store') }}" class="space-y-6">
                        @csrf

                        <!-- Amount Input -->
                        <div>
                            <label for="amount" class="block mb-3 text-sm font-semibold text-gray-700">
                                {{ __('Withdrawal Amount') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative shadow-lg rounded-xl">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <span class="font-medium text-gray-500">₦</span>
                                </div>
                                <input id="amount" name="amount" type="number" step="0.01" min="0.01" max="{{ $savingsBalance }}" class="block w-full pl-8 pr-16 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-green-100 focus:border-green-500 shadow-sm placeholder-gray-400 transition-all duration-300 text-lg font-medium @error('amount') border-red-500 @enderror" placeholder="0.00" value="{{ old('amount') }}" required autofocus>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <span class="font-medium text-gray-500">NGN</span>
                                </div>
                            </div>
                            @error('amount')
                            <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="p-3 mt-3 border border-green-200 rounded-lg bg-green-50">
                                <p class="text-sm text-green-700">
                                    {{ __('Available balance:') }} <span id="available-balance" class="font-bold text-green-600">₦{{ number_format($savingsBalance, 2) }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block mb-3 text-sm font-semibold text-gray-700">
                                {{ __('Notes') }} <span class="text-gray-400">({{ __('Optional') }})</span>
                            </label>
                            <textarea id="notes" name="notes" rows="4" class="block w-full px-4 py-3 placeholder-gray-400 transition-all duration-300 border-2 border-gray-200 shadow-sm resize-none rounded-xl focus:ring-4 focus:ring-green-100 focus:border-green-500" placeholder="{{ __('Any additional information about this withdrawal request') }}">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Form Footer -->
                        <div class="flex flex-col items-center justify-between w-full pt-6 border-t border-gray-200">
                            <button type="submit" class="flex items-center justify-center w-full px-8 py-4 mb-4 text-base font-semibold text-white transition-all duration-300 transform border border-transparent shadow-lg bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl sm:w-auto hover:from-green-700 hover:to-emerald-700 hover:scale-105 focus:outline-none focus:ring-4 focus:ring-green-200 focus:ring-offset-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                {{ __('Submit Request') }}
                            </button>
                            <div class="flex items-center p-3 mb-4 text-sm text-green-700 border border-green-200 rounded-lg bg-green-50 sm:mb-0">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span>{{ __('Your request will be reviewed by an administrator.') }}</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Withdrawal History -->
            <div class="overflow-hidden bg-white border border-gray-100 shadow-xl rounded-2xl">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-br from-green-50 via-green-100 to-emerald-50">
                    <h2 class="flex items-center text-xl font-bold text-gray-800">
                        <div class="p-2 mr-3 shadow-lg bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        {{ __('Withdrawal History') }}
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">{{ __('View your past withdrawal requests and their status.') }}</p>
                </div>

                <div class="p-6">
                    @if(count($withdrawalHistory) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-xs font-bold tracking-wider text-left text-gray-600 uppercase">
                                        {{ __('Date') }}
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-xs font-bold tracking-wider text-left text-gray-600 uppercase">
                                        {{ __('Amount') }}
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-xs font-bold tracking-wider text-left text-gray-600 uppercase">
                                        {{ __('Status') }}
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-xs font-bold tracking-wider text-left text-gray-600 uppercase">
                                        {{ __('Notes') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($withdrawalHistory as $withdrawal)
                                <tr class="transition-all duration-200 hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-600 whitespace-nowrap">
                                        {{ $withdrawal->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900 whitespace-nowrap">
                                        ₦{{ number_format($withdrawal->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full shadow-sm
                                            @if($withdrawal->status === 'approved') bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200
                                            @elseif($withdrawal->status === 'rejected') bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-200
                                            @else bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border border-yellow-200 @endif">
                                            {{ ucfirst($withdrawal->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $withdrawal->notes ?? '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="py-12 text-center">
                        <div class="p-4 mx-auto mb-4 bg-gray-100 rounded-full w-fit">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">{{ __('No withdrawal history') }}</h3>
                        <p class="mt-2 text-sm text-gray-500">{{ __('You haven\'t made any withdrawal requests yet.') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Set available balance for form validation
    const savingsBalance = {{ $savingsBalance }};
    $('#available-balance').text('₦' + formatNumber(savingsBalance));

    // Format numbers with commas
    function formatNumber(num) {
        return num ? num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : '0';
    }

    // Real-time validation as user types
    $('#amount').on('input', function() {
        const amount = parseFloat($(this).val());
        const submitBtn = $('button[type="submit"]');

        if (amount > savingsBalance) {
            $(this).addClass('border-red-500');
            submitBtn.prop('disabled', true);
            // Show error message if it doesn't exist
            if ($('#amount-error').length === 0) {
                $(this).after('<p id="amount-error" class="mt-2 text-sm text-red-600">Withdrawal amount cannot exceed your available savings.</p>');
            }
        } else {
            $(this).removeClass('border-red-500');
            submitBtn.prop('disabled', false);
            $('#amount-error').remove();
        }
    });

    // Final validation on form submission
    $('form').on('submit', function(e) {
        const amount = parseFloat($('#amount').val());
        if (amount > savingsBalance) {
            e.preventDefault();
            alert('Withdrawal amount cannot exceed your available savings of ₦' + formatNumber(savingsBalance));
        }
    });
});
</script>
@endpush