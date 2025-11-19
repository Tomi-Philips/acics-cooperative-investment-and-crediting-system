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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    Membership Applications
                </h1>
                <p class="mt-2 text-sm text-gray-600">Review and manage pending membership applications</p>
            </div>
            <div class="flex items-center space-x-4">
                <button type="button"
                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                            clip-rule="evenodd" />
                    </svg>
                    Filter
                </button>
                <button type="button"
                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white transition-all duration-200 bg-green-600 border border-transparent rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Export
                </button>
            </div>
        </div>

        <!-- Status Tabs -->
        <div class="mt-4">
            <nav class="flex space-x-8 bg-gray-100 p-1 rounded-lg shadow-inner" role="group">
                <a href="{{ route('admin.pending-memberships', ['status' => 'pending']) }}"
                    class="flex-1 px-4 py-2.5 text-sm font-medium text-center rounded-md transition-all duration-200 {{ $status === 'pending' ? 'bg-green-600 text-white shadow-sm' : 'text-gray-700 hover:bg-white hover:text-green-700' }}">
                    Pending Approval ({{ $pendingCount }})
                </a>
                <a href="{{ route('admin.pending-memberships', ['status' => 'verified']) }}"
                    class="flex-1 px-4 py-2.5 text-sm font-medium text-center rounded-md transition-all duration-200 {{ $status === 'verified' ? 'bg-green-600 text-white shadow-sm' : 'text-gray-700 hover:bg-white hover:text-green-700' }}">
                    Verified ({{ $verifiedCount }})
                </a>
                <a href="{{ route('admin.pending-memberships', ['status' => 'approved']) }}"
                    class="flex-1 px-4 py-2.5 text-sm font-medium text-center rounded-md transition-all duration-200 {{ $status === 'approved' ? 'bg-green-600 text-white shadow-sm' : 'text-gray-700 hover:bg-white hover:text-green-700' }}">
                    Approved ({{ $approvedCount }})
                </a>
                <a href="{{ route('admin.pending-memberships', ['status' => 'rejected']) }}"
                    class="flex-1 px-4 py-2.5 text-sm font-medium text-center rounded-md transition-all duration-200 {{ $status === 'rejected' ? 'bg-green-600 text-white shadow-sm' : 'text-gray-700 hover:bg-white hover:text-green-700' }}">
                    Rejected ({{ $rejectedCount }})
                </a>
            </nav>
        </div>
    </div>

    <!-- Applications List Card -->
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
                    <h2 class="text-xl font-bold text-gray-800">Membership Applications</h2>
                    <p class="mt-1 text-sm text-gray-600">Review and process membership applications</p>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="p-8 pb-0">
            <div class="flex flex-col space-y-3 md:flex-row md:items-center md:justify-between md:space-y-0 md:space-x-4">
                <div class="flex-1 min-w-0">
                    <form action="{{ route('admin.pending-memberships') }}" method="GET" class="w-full">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <input type="hidden" name="department" value="{{ $department }}">
                        <input type="hidden" name="sort" value="{{ $sort }}">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ $search }}"
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm transition duration-150 ease-in-out"
                                placeholder="Search by name, email, or reference number">
                        </div>
                    </form>
                </div>
                <div class="flex items-center space-x-2">
                    <div>
                        <form action="{{ route('admin.pending-memberships') }}" method="GET" id="department-form">
                            <input type="hidden" name="status" value="{{ $status }}">
                            <input type="hidden" name="search" value="{{ $search }}">
                            <input type="hidden" name="sort" value="{{ $sort }}">
                            <select name="department" onchange="document.getElementById('department-form').submit()"
                                class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm bg-white cursor-pointer transition-all duration-200">
                                <option value="all" {{ $department === 'all' ? 'selected' : '' }}>All Departments
                                </option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ $department == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->title }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <div>
                        <form action="{{ route('admin.pending-memberships') }}" method="GET" id="sort-form">
                            <input type="hidden" name="status" value="{{ $status }}">
                            <input type="hidden" name="search" value="{{ $search }}">
                            <input type="hidden" name="department" value="{{ $department }}">
                            <select name="sort" onchange="document.getElementById('sort-form').submit()"
                                class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm bg-white cursor-pointer transition-all duration-200">
                                <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Sort by: Newest
                                </option>
                                <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Sort by: Oldest
                                </option>
                                <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Sort by: Name
                                    A-Z</option>
                                <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>Sort by: Name
                                    Z-A</option>
                            </select>
                        </form>
                    </div>
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
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Applicant
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Reference
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Department
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Submission Date
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($pendingMemberships as $membership)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-10 h-10">
                                                    <div class="flex items-center justify-center w-10 h-10 bg-gray-200 rounded-full">
                                                        <span class="font-medium text-gray-600">
                                                            {{ strtoupper(substr($membership->name, 0, 1)) }}{{ strtoupper(substr(strpos($membership->name, ' ') !== false ? substr($membership->name, strpos($membership->name, ' ') + 1, 1) : '', 0, 1)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $membership->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $membership->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $membership->reference_number ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $membership->department->title ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $membership->created_at->format('M d, Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $membership->created_at->format('g:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($membership->status === 'pending')
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full">
                                                    Pending Approval
                                                </span>
                                            @elseif($membership->status === 'verified')
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full">
                                                    Verified
                                                </span>
                                            @elseif($membership->status === 'approved')
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                                    Approved
                                                </span>
                                            @elseif($membership->status === 'rejected')
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full">
                                                    Rejected
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-gray-800 bg-gray-100 rounded-full">
                                                    {{ ucfirst($membership->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                            <div class="flex justify-end space-x-3">
                                                <a href="{{ route('admin.pending-memberships.view', $membership->id) }}"
                                                    class="text-green-600 hover:text-green-900" title="View">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                @if ($membership->status === 'pending')
                                                    <form
                                                        action="{{ route('admin.pending-memberships.verify', $membership->id) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-blue-600 hover:text-blue-900"
                                                            title="Mark as Verified">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if ($membership->status === 'verified')
                                                    <button type="button" onclick="openApprovalModal('{{ $membership->id }}')"
                                                        class="text-green-600 hover:text-green-900" title="Approve">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                @endif
                                                @if ($membership->status === 'pending' || $membership->status === 'verified')
                                                    <button type="button" onclick="openRejectModal('{{ $membership->id }}')"
                                                        class="text-red-600 hover:text-red-900" title="Reject">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            No membership applications found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if ($pendingMemberships->hasPages())
                <div class="flex items-center justify-center px-4 py-3 bg-white border-t border-gray-200 sm:px-6 sm:py-4 sm:justify-between">
                    <div class="hidden sm:block">
                        <p class="text-sm text-gray-700">
                            @if ($pendingMemberships->total() > 0)
                                Showing
                                <span class="font-medium">{{ $pendingMemberships->firstItem() }}</span>
                                to
                                <span class="font-medium">{{ $pendingMemberships->lastItem() }}</span>
                                of
                                <span class="font-medium">{{ $pendingMemberships->total() }}</span>
                                applications
                            @else
                                No applications found
                            @endif
                        </p>
                    </div>
                    <div class="flex justify-center w-full sm:w-auto">
                        {{ $pendingMemberships->appends(['search' => $search, 'status' => $status, 'department' => $department, 'sort' => $sort])->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div class="fixed inset-0 z-10 hidden overflow-y-auto" id="approval-modal">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeApprovalModal()">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div
            class="relative z-20 inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="" method="POST" id="approval-form">
                @csrf
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-green-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                Approve Membership Application
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to approve this membership application? This will send an
                                    email to the applicant with a link to set their password.
                                </p>
                            </div>
                            <div class="mt-4">
                                <div class="flex items-center">
                                    <input id="send_email" name="send_email" type="checkbox"
                                        class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                        checked>
                                    <label for="send_email" class="block ml-2 text-sm text-gray-900">
                                        Send approval email
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Approve
                    </button>
                    <button type="button" onclick="closeApprovalModal()"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="fixed inset-0 z-10 hidden overflow-y-auto" id="rejection-modal">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeRejectionModal()">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div
            class="relative z-20 inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="" method="POST" id="rejection-form">
                @csrf
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                Reject Membership Application
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to reject this membership application? Please provide a
                                    reason for the rejection.
                                </p>
                                <div class="mt-4">
                                    <label for="rejection_reason"
                                        class="block text-sm font-medium text-gray-700">Rejection Reason</label>
                                    <textarea id="rejection_reason" name="rejection_reason" rows="3"
                                        class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                        required></textarea>
                                </div>
                                <div class="mt-4">
                                    <div class="flex items-center">
                                        <input id="send_rejection_email" name="send_email" type="checkbox"
                                            class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                                            checked>
                                        <label for="send_rejection_email"
                                            class="block ml-2 text-sm text-gray-900">
                                            Send rejection email
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Reject
                    </button>
                    <button type="button" onclick="closeRejectionModal()"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openApprovalModal(id) {
        document.getElementById('approval-form').action = "{{ url('admin/pending-memberships') }}/" + id + "/approve";
        document.getElementById('approval-modal').classList.remove('hidden');
    }

    function closeApprovalModal() {
        document.getElementById('approval-modal').classList.add('hidden');
    }

    function openRejectModal(id) {
        document.getElementById('rejection-form').action = "{{ url('admin/pending-memberships') }}/" + id + "/reject";
        document.getElementById('rejection-modal').classList.remove('hidden');
    }

    function closeRejectionModal() {
        document.getElementById('rejection-modal').classList.add('hidden');
    }
</script>
@endpush