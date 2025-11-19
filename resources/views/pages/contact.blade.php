@extends('layouts.app')

@section('content')
<div class="relative px-4 py-12 sm:px-6 lg:px-8 bg-slate-100">
    <!-- Background Elements -->
    <div class="absolute inset-0 overflow-hidden -z-10">
        <div class="absolute inset-0 bg-white bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px]"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 h-[310px] w-[310px] rounded-full bg-green-400 opacity-20 blur-[100px]"></div>
    </div>

    <!-- Main Content Container -->
    <div class="mx-auto max-w-7xl">
        <div class="flex flex-col gap-10 lg:flex-row xl:gap-16">
            <!-- Contact Information (Left Side) -->
            <div class="lg:w-1/2">
                <!-- Image/Graphic -->
                <div class="hidden mb-8 overflow-hidden shadow-lg lg:block rounded-2xl">
                    <img src="{{ asset('images/file_1682645452.jpg') }}" alt="Contact Us" class="object-cover w-full h-auto">
                </div>

                <!-- Contact Cards -->
                <div class="p-6 bg-white shadow-md sm:p-8 rounded-xl">
                    <h3 class="mb-6 text-xl font-bold text-gray-800">Get in touch</h3>

                    <div class="space-y-6">
                        <!-- Address Card -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 p-3 text-white bg-green-700 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="mb-1 text-lg font-semibold text-gray-800">Our Address</h4>
                                <p class="text-gray-600">The Federal Polytechnic, P. M. B. 50, Ilaro, Ogun State</p>
                                <p class="text-gray-600">BANKERS: SKYE BANK PLC, ILARO, UBA PLC, ILARO, IPNB, ILARO</p>
                            </div>
                        </div>

                        <!-- Contact Card -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 p-3 text-white bg-green-700 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="mb-1 text-lg font-semibold text-gray-800">Contact</h4>
                                <p class="text-gray-600">Phone: +234 8139566626</p>
                                <p class="text-gray-600">Phone: +234 8030773214</p>
                                <p class="text-gray-600">Email: asfepil.cics@yahoo.com</p>
                            </div>
                        </div>

                        <!-- Hours Card -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 p-3 text-white bg-green-700 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="mb-1 text-lg font-semibold text-gray-800">Working Hours</h4>
                                <p class="text-gray-600">Monday - Friday: 8:00 AM - 4:00 PM</p>
                                <p class="text-gray-600">No Weekend Service</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form (Right Side) -->
            <div class="lg:w-1/2">
                <div class="p-6 bg-white shadow-md sm:p-8 rounded-xl">
                    <h2 class="mb-4 text-3xl font-bold text-center text-green-800 sm:text-4xl">Contact Us</h2>
                    <p class="mb-8 text-lg text-center text-gray-600">
                        Got questions? Want to provide feedback? Need information about our services? We're here to help.
                    </p>

                    <form class="space-y-6">
                        <!-- Email Input -->
                        <div>
                            <label for="email" class="block mb-1 text-sm font-medium text-gray-700">Your Email</label>
                            <input type="email" id="email" class="w-full px-4 py-3 transition-all border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="your.email@example.com" required>
                        </div>

                        <!-- Subject Input -->
                        <div>
                            <label for="subject" class="block mb-1 text-sm font-medium text-gray-700">Subject</label>
                            <input type="text" id="subject" class="w-full px-4 py-3 transition-all border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="How can we help you?" required>
                        </div>

                        <!-- Message Textarea -->
                        <div>
                            <label for="message" class="block mb-1 text-sm font-medium text-gray-700">Your Message</label>
                            <textarea id="message" rows="5" class="w-full px-4 py-3 transition-all border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Tell us more about your inquiry..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="flex items-center justify-center w-full gap-2 px-6 py-3 font-medium text-white transition-colors duration-300 bg-green-700 rounded-lg hover:bg-green-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection