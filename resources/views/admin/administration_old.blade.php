@extends('layouts.admin')

@section('content')
    <div class="max-w-2xl p-8 mx-auto overflow-hidden bg-white rounded-lg shadow">
        <div class="mb-6">
            <h2 class="flex items-center space-x-2 text-2xl font-bold tracking-tight text-green-700">
                <svg>...</svg>
                Administration
            </h2>
            <p class="text-sm text-gray-500">Manage system permissions and access controls.</p>
        </div>

        <div class="mb-6">
            <div class="p-4 border-l-4 border-yellow-400 bg-yellow-50">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg>...</svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">...</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-8 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Role Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Users Assigned</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td>1</td>
                        <td>
                            <span class="px-2 py-1 text-xs font-medium text-purple-800 bg-purple-100 rounded-full">Admin</span>
                        </td>
                        <td>Manages members and system settings</td>
                        <td>{{ $stats['users_count'] > 0 ? $stats['users_count'] - ($stats['users_count'] - 3) : 0 }}</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>
                            <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">Member</span>
                        </td>
                        <td>Performs financial transactions (loans, savings, shares, commodities)</td>
                        <td>{{ $stats['users_count'] > 3 ? $stats['users_count'] - 3 : 0 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="pt-6 border-t border-gray-200">
            <h3 class="flex items-center mb-4 space-x-2 text-lg font-semibold text-gray-900">
                <svg>...</svg>
                Role Privileges
            </h3>

            <div class="relative mb-4">
                <label for="role_privileges" class="block text-sm font-medium text-gray-700">Role:</label>
                <select id="role_privileges" class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                    <option value="admin">Admin</option>
                    <option value="member">Member</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pointer-events-none">
                    <svg>...</svg>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Permissions:</label>
                <div class="grid grid-cols-1 gap-3 mt-2 sm:grid-cols-2 md:grid-cols-3">
                    <div class="flex items-center">
                        <input id="view_users" type="checkbox" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" checked>
                        <label for="view_users" class="block ml-2 text-sm text-gray-700">View Users</label>
                    </div>
                    </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-3">
            <form action="{{ route('admin.administration.clear_cache') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Clear Application Cache
                </button>
            </form>
            <form action="{{ route('admin.administration.run_migrations') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-purple-600 border border-transparent rounded-md shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    Run Database Migrations
                </button>
            </form>
            <form action="{{ route('admin.administration.backup_database') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Backup Database
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="p-4 mt-4 border-l-4 border-green-400 bg-green-50">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg>...</svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mt-4 border-l-4 border-red-400 bg-red-50">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg>...</svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection