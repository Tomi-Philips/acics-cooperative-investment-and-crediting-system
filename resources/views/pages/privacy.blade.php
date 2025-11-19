@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
    <div class="py-12 bg-white">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            {{-- Page Header --}}
            <div class="mb-10 lg:text-center">
                <h2 class="text-base font-semibold tracking-wide text-green-600 uppercase">Legal</h2>
                <p class="mt-2 text-3xl font-extrabold leading-8 tracking-tight text-gray-900 sm:text-4xl">
                    Privacy Policy
                </p>
                <p class="max-w-2xl mt-4 text-xl text-gray-500 lg:mx-auto">
                    How we collect, use, and protect your personal information.
                </p>
            </div>

            {{-- Privacy Policy Content --}}
            <div class="p-8 mx-auto prose prose-lg bg-white shadow-lg rounded-xl">
                <h2>1. Introduction</h2>
                <p>
                    ACICS Cooperative Society ("we", "our", or "us") is committed to protecting your privacy and personal data. This Privacy Policy explains how we collect, use, and protect your personal information when you use our services.
                </p>

                <h2>2. Information We Collect</h2>
                <p>
                    We collect the following types of information:
                </p>
                <ul>
                    <li><strong>Personal Information:</strong> Name, email address, phone number, residential address, date of birth, gender, staff ID, employment details, and next of kin information.</li>
                    <li><strong>Financial Information:</strong> Savings, loans, shares, and commodity transactions.</li>
                    <li><strong>Account Information:</strong> Login credentials and account preferences.</li>
                    <li><strong>Usage Information:</strong> How you interact with our website and services.</li>
                </ul>

                <h2>3. How We Use Your Information</h2>
                <p>
                    We use your information for the following purposes:
                </p>
                <ul>
                    <li>To provide and manage our cooperative services</li>
                    <li>To process membership applications and maintain member records</li>
                    <li>To manage savings, loans, shares, and commodity transactions</li>
                    <li>To communicate with you about your account and our services</li>
                    <li>To improve our website and services</li>
                    <li>To comply with legal and regulatory requirements</li>
                </ul>

                <h2>4. Information Sharing and Disclosure</h2>
                <p>
                    We may share your information with:
                </p>
                <ul>
                    <li>Service providers who help us operate our business</li>
                    <li>Financial institutions for processing transactions</li>
                    <li>Regulatory authorities when required by law</li>
                    <li>Your employer for payroll deduction purposes (with your consent)</li>
                </ul>
                <p>
                    We do not sell, rent, or trade your personal information to third parties for marketing purposes.
                </p>

                <h2>5. Data Security</h2>
                <p>
                    We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, accidental loss, alteration, or destruction. However, no method of transmission over the internet or electronic storage is 100% secure, and we cannot guarantee absolute security.
                </p>

                <h2>6. Your Rights</h2>
                <p>
                    You have the following rights regarding your personal information:
                </p>
                <ul>
                    <li>The right to access your personal information</li>
                    <li>The right to correct inaccurate or incomplete information</li>
                    <li>The right to request deletion of your information</li>
                    <li>The right to restrict or object to processing</li>
                    <li>The right to data portability</li>
                    <li>The right to withdraw consent</li>
                </ul>

                <h2>7. Cookies and Similar Technologies</h2>
                <p>
                    We use cookies and similar technologies to enhance your experience on our website. For more information, please see our <a href="{{ route('cookie_policy') }}">Cookie Policy</a>.
                </p>

                <h2>8. Changes to This Privacy Policy</h2>
                <p>
                    We may update this Privacy Policy from time to time to reflect changes in our practices or legal requirements. We will notify you of any material changes by posting the updated policy on our website.
                </p>

                <h2>9. Contact Us</h2>
                <p>
                    If you have any questions or concerns about this Privacy Policy or our data practices, please contact us at:
                </p>
                <ul>
                    <li>Email: privacy@acics.com</li>
                    <li>Phone: +234 123 456 7890</li>
                    <li>Address: 123 Main Street, Lagos, Nigeria</li>
                </ul>
                <p class="mt-8 text-sm text-gray-500">
                    Last updated: May 16, 2025
                </p>
            </div>
        </div>
    </div>
@endsection