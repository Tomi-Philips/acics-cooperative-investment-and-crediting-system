@extends('layouts.app')

@section('content')
<div class="absolute inset-0 -z-10 h-full w-full bg-slate-50 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px]"><div class="absolute left-0 right-0 top-0 -z-10 m-auto h-[310px] w-[310px] rounded-full bg-green-400 opacity-20 blur-[100px]"></div></div>
  
<div class="bg-gradient-to-br from-green-50 to-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-12">
            <!-- GIF/Image: Enhanced styling -->
            <div class="hidden lg:block w-full lg:w-1/2">
                <div class="overflow-hidden rounded-2xl shadow-xl transform hover:scale-[1.02] transition duration-500">
                    <img 
                        src="{{ asset('images/file_1682645452.jpg') }}" 
                        alt="ASUP CICS Team" 
                        class="w-full h-auto object-cover rounded-2xl"
                        loading="lazy"
                    />
                </div>
            </div>

            <!-- Text Content: Improved typography and spacing -->
            <div class="w-full lg:w-1/2">
                <div class="text-center lg:text-left mb-8">
                    <span class="inline-block mb-2 text-sm font-semibold text-green-600 uppercase tracking-wider">
                        Our Story
                    </span>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        <span class="text-green-700">Empowering</span> Financial Growth Together
                    </h1>
                    <div class="w-20 h-1 bg-green-600 mx-auto lg:mx-0 mb-6"></div>
                </div>

                <div class="prose prose-lg text-gray-600 max-w-none mb-8">
                    <p class="mb-6 leading-relaxed">
                        At <strong>ASUP CICS</strong>, we're more than a cooperative - we're a financial growth partner for over 500 active members. Our mission is to create opportunities through disciplined savings, smart investments, and accessible credit solutions.
                    </p>
                    
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start">
                            <span class="text-green-600 mr-2">✓</span>
                            <span>Monthly contributions that build financial discipline</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-600 mr-2">✓</span>
                            <span>Share investment opportunities with dividends</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-600 mr-2">✓</span>
                            <span>Flexible savings plans for all financial goals</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-600 mr-2">✓</span>
                            <span>Commodity access at favorable terms</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-600 mr-2">✓</span>
                            <span>Quick and fair loan facilities</span>
                        </li>
                    </ul>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <button class="px-8 py-3 bg-green-700 hover:bg-green-800 text-white font-medium rounded-lg shadow-md transition duration-300 transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <span>📜</span> Explore Our Services
                    </button>
                    <button class="px-8 py-3 border-2 border-green-700 text-green-700 hover:bg-green-50 font-medium rounded-lg transition duration-300 flex items-center justify-center gap-2">
                        <span>👥</span> Join Our Community
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-slate-100">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto">
        <!-- Header -->
        <div class="border-b pb-4 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="mr-2">🏛️</span> About ASUP CICS
            </h2>
            <p class="text-gray-600">The Academic Staff of Federal Polytechnic Ilaro</p>
        </div>
      
        <!-- Mission Section -->
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-gray-700 mb-4 flex items-center">
                <span class="mr-2">🎯</span> Our Mission
            </h3>
            <div class="bg-blue-50 rounded-lg p-5 text-sm">
                <p class="text-gray-700 leading-relaxed">
                    To empower our members through cooperative financial services that promote savings culture, 
                    provide investment opportunities, and offer accessible credit facilities. We foster financial 
                    independence, community support, and economic growth through our shared resources.
                </p>
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div class="flex items-start">
                        <span class="mr-2">✅</span>
                        <span>Promote regular savings culture</span>
                    </div>
                    <div class="flex items-start">
                        <span class="mr-2">✅</span>
                        <span>Provide accessible credit facilities</span>
                    </div>
                    <div class="flex items-start">
                        <span class="mr-2">✅</span>
                        <span>Offer investment opportunities</span>
                    </div>
                    <div class="flex items-start">
                        <span class="mr-2">✅</span>
                        <span>Support members' financial needs</span>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Services Section -->
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-gray-700 mb-4 flex items-center">
                <span class="mr-2">🛠️</span> Our Services
            </h3>
            <div class="bg-gray-50 rounded-lg overflow-hidden text-sm">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Service</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Description</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Availability</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-3 font-medium flex items-center">🔁 Monthly Contribution</td>
                            <td class="px-4 py-3">Regular savings plan for financial security</td>
                            <td class="px-4 py-3 text-green-600">Monthly</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-medium flex items-center">📈 Share Investment</td>
                            <td class="px-4 py-3">Ownership shares with dividend earnings</td>
                            <td class="px-4 py-3 text-green-600">Ongoing</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-medium flex items-center">💰 Savings</td>
                            <td class="px-4 py-3">Flexible savings plans for all goals</td>
                            <td class="px-4 py-3 text-blue-600">Daily</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-medium flex items-center">🛒 Commodity Support</td>
                            <td class="px-4 py-3">Essential goods at favorable terms</td>
                            <td class="px-4 py-3 text-orange-600">Seasonal</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-medium flex items-center">💳 Loans</td>
                            <td class="px-4 py-3">Flexible credit facilities</td>
                            <td class="px-4 py-3 text-green-600">On Approval</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    
        <!-- Community Impact Section -->
        <div>
            <h3 class="text-xl font-semibold text-gray-700 mb-4 flex items-center">
                <span class="mr-2">🌍</span> Community Impact
            </h3>
            <div class="bg-green-50 rounded-lg p-5">
                <div class="grid md:grid-cols-3 gap-6 text-sm">
                    <!-- Impact Card 1 -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-green-100">
                        <div class="text-3xl font-bold text-green-600 mb-2">500+</div>
                        <div class="font-medium">Active Members</div>
                        <div class="text-sm text-gray-500 mt-1">Growing community</div>
                    </div>
                    
                    <!-- Impact Card 2 -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-green-100">
                        <div class="text-3xl font-bold text-green-600 mb-2">₦25M+</div>
                        <div class="font-medium">Loans Disbursed</div>
                        <div class="text-sm text-gray-500 mt-1">To members annually</div>
                    </div>
                    
                    <!-- Impact Card 3 -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-green-100">
                        <div class="text-3xl font-bold text-green-600 mb-2">98%</div>
                        <div class="font-medium">Repayment Rate</div>
                        <div class="text-sm text-gray-500 mt-1">Excellent track record</div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h4 class="font-medium mb-2">Our Value Proposition:</h4>
                    <ul class="list-disc pl-5 space-y-2 text-gray-700 text-sm">
                        <li>Cooperative ownership and shared benefits</li>
                        <li>Investment opportunities with dividends</li>
                        <li>Crediting services for all financial needs</li>
                        <li>We offer reliable services including monthly contributions, share investments, savings plans, access to essential commodities, and flexible loan options</li>
                    </ul>
                </div>
            </div>
        </div>
    
        <!-- Call to Action -->
        <div class="mt-8 text-center">
            <button class="px-6 py-3 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                Become a Member
            </button>
            <p class="mt-2 text-sm text-gray-500">Join our thriving community of savers and investors</p>
        </div>
    </div>
</div>
@endsection