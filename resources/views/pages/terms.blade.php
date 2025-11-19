@extends('layouts.app')

@section('content')
    <div class="py-12 bg-white">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            {{-- Page Header --}}
            <div class="mb-10 lg:text-center">
                <h2 class="text-base font-semibold tracking-wide text-green-600 uppercase">Legal</h2>
                <p class="mt-2 text-3xl font-extrabold leading-8 tracking-tight text-gray-900 sm:text-4xl">
                    Terms and Conditions
                </p>
                <p class="max-w-2xl mt-4 text-xl text-gray-500 lg:mx-auto">
                    Terms of service for ACICS Cooperative Society members and services.
                </p>
            </div>

            {{-- Terms and Conditions Content --}}
            <div class="p-8 mx-auto prose prose-lg bg-white shadow-lg rounded-xl">
                <h2>1. Introduction</h2>
                <p>
                    These Terms and Conditions ("Terms") govern your use of ACICS Cooperative Society services and your membership with us. By becoming a member or using our services, you agree to be bound by these Terms.
                </p>

                <h2>2. Membership Terms</h2>
                <p>
                    By becoming a member of ACICS Cooperative Society, you agree to:
                </p>
                <ul>
                    <li><strong>Eligibility:</strong> You must be a staff member of ACICS to be eligible for membership.</li>
                    <li><strong>Initial Deposits:</strong> Pay the required entrance fee and minimum initial deposits for savings and shares.</li>
                    <li><strong>Monthly Contributions:</strong> Make regular monthly contributions to your savings and shares accounts.</li>
                    <li><strong>Compliance:</strong> Comply with all cooperative bylaws, policies, and business rules.</li>
                    <li><strong>Good Standing:</strong> Maintain your membership in good standing by fulfilling all obligations.</li>
                </ul>

                <h2>3. Financial Services Terms</h2>
                <p>
                    Our financial services are subject to the following terms:
                </p>
                <ul>
                    <li><strong>Savings:</strong> Earn interest on your savings balance as determined by the cooperative's policies</li>
                    <li><strong>Shares:</strong> Purchase shares to become a shareholder and earn dividends</li>
                    <li><strong>Loans:</strong> Access loans subject to eligibility criteria and repayment terms</li>
                    <li><strong>Commodities:</strong> Purchase commodities at favorable rates through the cooperative</li>
                    <li><strong>Electronics:</strong> Access electronics purchase programs when available</li>
                </ul>

                <h2>4. Loan Terms and Conditions</h2>
                <p>
                    All loans are subject to the following terms:
                </p>
                <ul>
                    <li><strong>Eligibility:</strong> You must be a member for at least 6 months</li>
                    <li><strong>Loan Amount:</strong> Maximum loan amount is 2 times your (Savings + Shares - Existing Loans - Commodity - Electronics)</li>
                    <li><strong>Interest Rate:</strong> Fixed interest rate of 10% per annum</li>
                    <li><strong>Repayment:</strong> All loans are repaid through bursary deduction only</li>
                    <li><strong>Term:</strong> No fixed term; flexible repayment within 24 months via bursary deduction</li>
                    <li><strong>Default:</strong> Late payments may incur penalties and affect future loan eligibility</li>
                </ul>

                <h2>5. Website and Platform Usage</h2>
                <p>
                    When using our website and online platform, you agree to:
                </p>
                <ul>
                    <li><strong>Account Security:</strong> Keep your login credentials secure and confidential</li>
                    <li><strong>Accurate Information:</strong> Provide accurate and up-to-date information</li>
                    <li><strong>Prohibited Activities:</strong> Not engage in any fraudulent, illegal, or harmful activities</li>
                    <li><strong>System Integrity:</strong> Not attempt to compromise the security or integrity of our systems</li>
                    <li><strong>Appropriate Use:</strong> Use the platform only for legitimate cooperative business</li>
                </ul>

                <h2>6. Privacy and Data Protection</h2>
                <p>
                    Your privacy is important to us. Please review our <a href="{{ route('privacy') }}" class="text-green-600 hover:underline">Privacy Policy</a> to understand how we collect, use, and protect your personal information.
                </p>

                <h2>7. Dispute Resolution</h2>
                <p>
                    Any disputes arising from these Terms or your membership will be resolved through:
                </p>
                <ul>
                    <li>First, direct discussion with cooperative management</li>
                    <li>If unresolved, mediation through the cooperative's dispute resolution process</li>
                    <li>Final resolution through arbitration or applicable legal proceedings</li>
                </ul>

                <h2>8. Limitation of Liability</h2>
                <p>
                    The cooperative's liability is limited to the extent permitted by law. We are not liable for indirect, incidental, or consequential damages arising from your use of our services.
                </p>

                <h2>9. Changes to Terms</h2>
                <p>
                    We may update these Terms from time to time. Material changes will be communicated to members through official channels. Continued use of our services after changes constitutes acceptance of the new Terms.
                </p>

                <h2>10. Termination</h2>
                <p>
                    Membership may be terminated by either party with appropriate notice. Upon termination, all outstanding obligations must be settled according to cooperative policies.
                </p>

                <h2>11. Contact Information</h2>
                <p>
                    For questions about these Terms and Conditions, please contact us at:
                </p>
                <ul>
                    <li>Email: info@acics.com</li>
                    <li>Phone: +234 123 456 7890</li>
                    <li>Address: ACICS Cooperative Society Office</li>
                </ul>
                <p class="mt-8 text-sm text-gray-500">
                    Last updated: September 18, 2025
                </p>
            </div>
        </div>
    </div>
@endsection