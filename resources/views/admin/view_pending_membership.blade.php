@extends('layouts.admin')

@section('content')
    <div class="overflow-hidden bg-white border border-gray-100 shadow-lg rounded-xl">
        <div class="flex flex-col px-6 py-4 border-b border-gray-200 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="flex items-center justify-center flex-shrink-0 w-16 h-16 text-gray-500 bg-gray-200 rounded-full">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $pendingMembership->name }}</h1>
                    <div class="flex flex-col mt-1 text-sm text-gray-500 sm:flex-row sm:items-center">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            {{ $pendingMembership->email }}
                        </span>
                        <span class="hidden mx-2 sm:inline">•</span>
                        <span class="flex items-center mt-1 sm:mt-0">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                            </svg>
                            {{ $pendingMembership->reference_number ?? 'No Reference #' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex space-x-3">
                @if($pendingMembership->status === 'pending')
                    <form action="{{ route('admin.pending-memberships.verify', $pendingMembership->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Verify
                        </button>
                    </form>
                @endif

                @if($pendingMembership->status === 'verified')
                    <button type="button" onclick="openApprovalModal('{{ $pendingMembership->id }}')" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Approve
                    </button>
                @endif

                @if($pendingMembership->status === 'pending' || $pendingMembership->status === 'verified')
                    <button type="button" onclick="openRejectModal('{{ $pendingMembership->id }}')" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Reject
                    </button>
                @endif
            </div>
        </div>

        ---

        <div class="px-6 py-4">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <h2 class="mb-4 text-lg font-medium text-gray-900">Application Details</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Status</span>
                            <span class="text-sm text-gray-900">
                                @if($pendingMembership->status === 'pending')
                                    <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">
                                        Pending Verification
                                    </span>
                                @elseif($pendingMembership->status === 'verified')
                                    <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">
                                        Verified
                                    </span>
                                @elseif($pendingMembership->status === 'approved')
                                    <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                        Approved
                                    </span>
                                @elseif($pendingMembership->status === 'rejected')
                                    <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                        Rejected
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full">
                                        {{ ucfirst($pendingMembership->status) }}
                                    </span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Department</span>
                            <span class="text-sm text-gray-900">{{ $pendingMembership->department->title ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Submitted On</span>
                            <span class="text-sm text-gray-900">{{ $pendingMembership->created_at->format('M d, Y \a\t g:i A') }}</span>
                        </div>
                        @if($pendingMembership->verified_at)
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Verified On</span>
                                <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($pendingMembership->verified_at)->format('M d, Y \a\t g:i A') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Verified By</span>
                                <span class="text-sm text-gray-900">{{ $pendingMembership->verifiedBy->name ?? 'N/A' }}</span>
                            </div>
                        @endif
                        @if($pendingMembership->approved_at)
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Approved On</span>
                                <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($pendingMembership->approved_at)->format('M d, Y \a\t g:i A') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Approved By</span>
                                <span class="text-sm text-gray-900">{{ $pendingMembership->approvedBy->name ?? 'N/A' }}</span>
                            </div>
                        @endif
                        @if($pendingMembership->rejected_at)
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Rejected On</span>
                                <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($pendingMembership->rejected_at)->format('M d, Y \a\t g:i A') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Rejected By</span>
                                <span class="text-sm text-gray-900">{{ $pendingMembership->rejectedBy->name ?? 'N/A' }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <h2 class="mb-4 text-lg font-medium text-gray-900">Additional Information</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Email Verified</span>
                            <span class="text-sm text-gray-900">
                                @if($pendingMembership->email_verified_at)
                                    <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                        Yes
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                        No
                                    </span>
                                @endif
                            </span>
                        </div>
                        @if($pendingMembership->status === 'rejected' && $pendingMembership->rejection_reason)
                            <div class="mt-4">
                                <h3 class="mb-2 text-sm font-medium text-gray-500">Rejection Reason</h3>
                                <div class="p-3 text-sm text-gray-700 bg-white border border-gray-200 rounded">
                                    {{ $pendingMembership->rejection_reason }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    ---

    <div class="fixed inset-0 z-50 hidden overflow-y-auto" id="approval-modal">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative w-full max-w-lg overflow-hidden bg-white rounded-lg shadow-xl">
                <form action="{{ route('admin.pending-memberships.approve', $pendingMembership->id) }}" method="POST" id="approval-form">
                    @csrf
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-start">
                            <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-green-100 rounded-full sm:mx-0">
                                <svg class="w-6 h-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">
                                    Approve Membership Application
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to approve this membership application? This will send an email to the applicant with a link to set their password.
                                    </p>
                                </div>
                                <div class="mt-4">
                                    <div class="flex items-center">
                                        <input id="send_email" name="send_email" type="checkbox" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" checked>
                                        <label for="send_email" class="block ml-2 text-sm text-gray-900">
                                            Send approval email
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto">
                            Approve
                        </button>
                        <button type="button" onclick="closeApprovalModal()" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    ---

    <div class="fixed inset-0 z-50 hidden overflow-y-auto" id="rejection-modal">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative w-full max-w-lg overflow-hidden bg-white rounded-lg shadow-xl">
                <form action="{{ route('admin.pending-memberships.reject', $pendingMembership->id) }}" method="POST" id="rejection-form">
                    @csrf
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-start">
                            <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0">
                                <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">
                                    Reject Membership Application
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to reject this membership application? Please provide a reason for the rejection.
                                    </p>
                                    <div class="mt-4">
                                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Rejection Reason</label>
                                        <textarea id="rejection_reason" name="rejection_reason" rows="3" class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" required></textarea>
                                    </div>
                                    <div class="mt-4">
                                        <div class="flex items-center">
                                            <input id="send_rejection_email" name="send_email" type="checkbox" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500" checked>
                                            <label for="send_rejection_email" class="block ml-2 text-sm text-gray-900">
                                                Send rejection email
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto">
                            Reject
                        </button>
                        <button type="button" onclick="closeRejectionModal()" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:ml-3 sm:w-auto">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openApprovalModal(id) {
                // The ID parameter is not needed here since the form action is already set with the correct ID
                document.getElementById('approval-modal').classList.remove('hidden');
            }

            function closeApprovalModal() {
                document.getElementById('approval-modal').classList.add('hidden');
            }

            function openRejectModal(id) {
                // The ID parameter is not needed here since the form action is already set with the correct ID
                document.getElementById('rejection-modal').classList.remove('hidden');
            }

            function closeRejectionModal() {
                document.getElementById('rejection-modal').classList.add('hidden');
            }
        </script>
    @endpush
@endsection