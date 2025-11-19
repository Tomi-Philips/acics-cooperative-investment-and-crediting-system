@extends('layouts.admin')

@section('content')
    <div class="max-w-3xl mx-auto overflow-hidden bg-white border border-gray-100 shadow-xl rounded-xl">
        {{-- Header Section --}}
        <div class="px-6 py-5 bg-gradient-to-r from-green-600 to-green-500">
            <div class="flex items-center justify-between">
                <h2 class="flex items-center text-2xl font-bold text-white">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Financial Reports
                </h2>
                <span class="px-3 py-1 text-xs font-semibold text-green-100 bg-green-700 bg-opacity-50 rounded-full">Live Data</span>
            </div>
            <p class="mt-1 text-sm text-green-100">Generate and analyze financial reports</p>
        </div>

        {{-- Report Controls --}}
        <div class="px-6 py-5 space-y-6">
            {{-- Report Type Selection and Date Range Selection --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="report_type" class="block mb-1 text-sm font-medium text-gray-700">Report Type</label>
                    <div class="relative">
                        <select id="report_type" class="appearance-none block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm cursor-pointer">
                            <option value="summary">Loan Summary</option>
                            <option value="borrower">Borrower Report</option>
                            <option value="product">Loan Product Report</option>
                            <option value="transaction">Transaction History</option>
                            <option value="delinquency">Delinquency Report</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Date Range</label>
                    <div class="flex items-center space-x-2">
                        <div class="relative flex-grow">
                            <input type="date" id="date_from" class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm">
                        </div>
                        <span class="text-gray-500">to</span>
                        <div class="relative flex-grow">
                            <input type="date" id="date_to" class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap gap-3">
                <button type="button" class="inline-flex items-center px-5 py-3 text-sm font-medium text-white transition-colors duration-200 bg-green-600 border border-transparent rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Generate Report
                </button>
                <button type="button" class="inline-flex items-center px-5 py-3 text-sm font-medium text-gray-700 transition-colors duration-200 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2 -ml-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download PDF
                </button>
                <button type="button" class="inline-flex items-center px-5 py-3 text-sm font-medium text-gray-700 transition-colors duration-200 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2 -ml-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </button>
                <button type="button" class="inline-flex items-center px-5 py-3 text-sm font-medium text-gray-700 transition-colors duration-200 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2 -ml-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    Export Excel
                </button>
            </div>
        </div>

        {{-- Report Summary Section --}}
        <div class="px-6 pb-6">
            <div class="pt-6 border-t border-gray-200">
                <h3 class="flex items-center mb-4 text-xl font-semibold text-gray-800">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Loan Summary Report
                </h3>

                {{-- Summary Cards --}}
                <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3">
                    <div class="p-4 border border-green-100 rounded-lg bg-green-50">
                        <p class="mb-1 text-sm font-medium text-green-800">Total Loans</p>
                        <p class="text-2xl font-bold text-green-700">₦2,450,000</p>
                        <p class="text-xs text-green-600">120 active loans</p>
                    </div>
                    <div class="p-4 border border-blue-100 rounded-lg bg-blue-50">
                        <p class="mb-1 text-sm font-medium text-blue-800">Total Repayments</p>
                        <p class="text-2xl font-bold text-blue-700">₦1,870,000</p>
                        <p class="text-xs text-blue-600">76% collection rate</p>
                    </div>
                    <div class="p-4 border border-purple-100 rounded-lg bg-purple-50">
                        <p class="mb-1 text-sm font-medium text-purple-800">Delinquent Loans</p>
                        <p class="text-2xl font-bold text-purple-700">₦580,000</p>
                        <p class="text-xs text-purple-600">24% overdue</p>
                    </div>
                </div>

                {{-- Chart Placeholder --}}
                <div class="p-4 mb-6 border border-gray-200 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-medium text-gray-700">Loan Performance</h4>
                        <select class="text-xs bg-transparent border-0 focus:ring-2 focus:ring-green-500">
                            <option>Last 30 Days</option>
                            <option>Last 90 Days</option>
                            <option>This Year</option>
                        </select>
                    </div>
                    <div class="h-64 p-2 bg-white rounded">
                        {{-- Chart would go here --}}
                        <div class="flex items-center justify-center h-full text-gray-400">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Detailed Metrics Table --}}
                <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Metric</th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Value</th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Change</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Average Loan Size</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">₦20,417</td>
                                <td class="px-6 py-4 text-sm text-green-600 whitespace-nowrap">+12%</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Repayment Rate</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">76%</td>
                                <td class="px-6 py-4 text-sm text-red-600 whitespace-nowrap">-4%</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">New Loans This Month</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">24</td>
                                <td class="px-6 py-4 text-sm text-green-600 whitespace-nowrap">+8%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection