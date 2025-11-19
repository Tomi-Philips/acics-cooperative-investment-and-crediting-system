// Loan Application Form JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize loan application form functionality
    initializeLoanApplicationForm();
});

function initializeLoanApplicationForm() {
    // Get form elements
    const termSlider = document.getElementById('term_slider');
    const durationInput = document.getElementById('duration');
    const durationDisplay = document.getElementById('repayment_duration_display');
    const monthsDisplay = document.getElementById('months_display');
    const loanAmountInput = document.getElementById('loan_amount');
    
    // Initialize slider functionality
    if (termSlider && durationInput && durationDisplay && monthsDisplay) {
        // Set initial values
        updateDurationDisplay(termSlider.value);
        
        // Add event listener for slider changes
        termSlider.addEventListener('input', function() {
            const months = parseInt(this.value);
            updateDurationDisplay(months);
            updateHiddenInput(months);
        });
        
        // Function to update all duration-related displays
        function updateDurationDisplay(months) {
            const interestRate = 10; // Fixed 10% interest rate
            
            // Update the display input
            durationDisplay.value = `${months} Months (${interestRate}% interest)`;
            
            // Update the months display
            monthsDisplay.textContent = `${months} month${months !== 1 ? 's' : ''} selected`;
            
            // Update slider track fill effect
            updateSliderTrackFill(months);
        }
        
        // Function to update hidden input
        function updateHiddenInput(months) {
            durationInput.value = months;
        }
        
        // Function to update slider visual track fill
        function updateSliderTrackFill(months) {
            const percentage = ((months - 1) / (24 - 1)) * 100;
            const trackFill = document.querySelector('.slider-track-fill');
            if (trackFill) {
                trackFill.style.width = percentage + '%';
            }
        }
        
        // Add real-time loan calculation
        if (loanAmountInput) {
            function calculateLoanDetails() {
                const amount = parseFloat(loanAmountInput.value) || 0;
                const months = parseInt(termSlider.value) || 24;
                const interestRate = 0.10; // 10%
                
                if (amount > 0) {
                    const interestAmount = amount * interestRate;
                    const totalPayment = amount + interestAmount;
                    const monthlyPayment = totalPayment / months;
                    
                    // Update display with calculation preview
                    const calculationPreview = document.getElementById('calculation_preview');
                    if (calculationPreview) {
                        calculationPreview.innerHTML = `
                            <div class="text-xs text-gray-600 mt-2 p-2 bg-gray-50 rounded">
                                <div class="grid grid-cols-2 gap-2">
                                    <div>Principal: ₦${amount.toLocaleString()}</div>
                                    <div>Interest: ₦${interestAmount.toLocaleString()}</div>
                                    <div>Total: ₦${totalPayment.toLocaleString()}</div>
                                    <div>Monthly: ₦${monthlyPayment.toLocaleString()}</div>
                                </div>
                            </div>
                        `;
                    }
                }
            }
            
            // Add calculation preview
            loanAmountInput.addEventListener('input', calculateLoanDetails);
            termSlider.addEventListener('input', calculateLoanDetails);
            
            // Initial calculation
            calculateLoanDetails();
        }
    }
    
    // Initialize terms and conditions modal
    initializeTermsModal();
}

function initializeTermsModal() {
    // Create terms and conditions modal
    const termsLink = document.querySelector('a[href="#"]');
    if (termsLink && termsLink.textContent.includes('terms and conditions')) {
        termsLink.addEventListener('click', function(e) {
            e.preventDefault();
            showTermsModal();
        });
    }
}

function showTermsModal() {
    // Create modal HTML
    const modalHTML = `
        <div id="termsModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Loan Terms and Conditions
                                </h3>
                                <div class="mt-2">
                                    <div class="text-sm text-gray-500 max-h-96 overflow-y-auto">
                                        <div class="space-y-4">
                                            <div>
                                                <h4 class="font-semibold text-gray-700">1. Loan Eligibility</h4>
                                                <p>• You must be a member for at least 6 months</p>
                                                <p>• Maximum loan amount is 2 times your (Savings + Shares - Existing Loans - Commodity - Electronics)</p>
                                                <p>• You must have no outstanding loan defaults</p>
                                            </div>
                                            
                                            <div>
                                                <h4 class="font-semibold text-gray-700">2. Interest Rate and Terms</h4>
                                                <p>• Fixed interest rate of 10% per annum</p>
                                                <p>• Repayment period: 1 to 24 months</p>
                                                <p>• Interest is calculated on the principal amount</p>
                                            </div>
                                            
                                            <div>
                                                <h4 class="font-semibold text-gray-700">3. Repayment Method</h4>
                                                <p>• All loans are repaid through bursary deduction</p>
                                                <p>• Monthly deductions will be made automatically</p>
                                                <p>• Ensure sufficient bursary balance for deductions</p>
                                            </div>
                                            
                                            <div>
                                                <h4 class="font-semibold text-gray-700">4. Application Process</h4>
                                                <p>• Online application serves as notification of intent</p>
                                                <p>• Physical form with signatures required for finalization</p>
                                                <p>• Loan committee approval required</p>
                                                <p>• Processing time: 5-10 business days</p>
                                            </div>
                                            
                                            <div>
                                                <h4 class="font-semibold text-gray-700">5. Default and Penalties</h4>
                                                <p>• Late payment may incur additional charges</p>
                                                <p>• Default may affect future loan eligibility</p>
                                                <p>• Cooperative reserves the right to recover outstanding amounts</p>
                                            </div>
                                            
                                            <div>
                                                <h4 class="font-semibold text-gray-700">6. General Terms</h4>
                                                <p>• Loan terms are subject to cooperative policies</p>
                                                <p>• Terms may be updated with proper notification</p>
                                                <p>• Disputes will be resolved according to cooperative bylaws</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" onclick="closeTermsModal()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            I Understand
                        </button>
                        <button type="button" onclick="closeTermsModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Show modal
    const modal = document.getElementById('termsModal');
    if (modal) {
        modal.style.display = 'block';
        
        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeTermsModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeTermsModal();
            }
        });
    }
}

function closeTermsModal() {
    const modal = document.getElementById('termsModal');
    if (modal) {
        modal.remove();
    }
}

// Add CSS for slider track fill effect
const style = document.createElement('style');
style.textContent = `
    .slider-track-fill {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        background: linear-gradient(to right, #10b981, #059669);
        border-radius: 0.5rem;
        pointer-events: none;
        transition: width 0.2s ease;
    }
    
    #term_slider {
        position: relative;
        background: #e5e7eb;
    }
    
    #term_slider::-webkit-slider-thumb {
        appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #10b981;
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    #term_slider::-moz-range-thumb {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #10b981;
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
`;
document.head.appendChild(style);
