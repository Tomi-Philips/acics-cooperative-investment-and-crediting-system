// Comprehensive JavaScript functionality for ACICS
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all functionality with error handling
    try {
        initializeNotifications();
    } catch (e) {
        // Silent error handling for production
    }

    try {
        initializeUserDropdown();
    } catch (e) {
        // Silent error handling for production
    }

    try {
        initializeSidebarDropdowns();
    } catch (e) {
        // Silent error handling for production
    }

    try {
        initializeMobileSidebar();
        console.log('Mobile sidebar initialized');
    } catch (e) {
        console.error('Error initializing mobile sidebar:', e);
    }

    try {
        initializeMobileMenu();
        console.log('Mobile menu initialized');
    } catch (e) {
        console.error('Error initializing mobile menu:', e);
    }

    // ===== NOTIFICATION FUNCTIONALITY =====
    function initializeNotifications() {
        const notificationButton = document.getElementById('adminNotificationDropdownButton');
        const notificationDropdown = document.getElementById('adminNotificationDropdown');
        const notificationBadge = document.getElementById('adminNotificationBadge');
        const markAllAsReadBtn = document.getElementById('adminMarkAllAsReadBtn');
        const notificationList = document.getElementById('adminNotificationList');

        // Load notifications when dropdown is opened
        if (notificationButton && notificationDropdown) {
            notificationButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Close other dropdowns first
                closeAllDropdowns(notificationDropdown);

                // Toggle dropdown visibility
                if (notificationDropdown.classList.contains('hidden')) {
                    showDropdown(notificationDropdown);
                    loadNotifications();
                } else {
                    hideDropdown(notificationDropdown);
                }
            });
        }

        // Mark all notifications as read
        if (markAllAsReadBtn) {
            markAllAsReadBtn.addEventListener('click', function(e) {
                e.preventDefault();
                markAllNotificationsAsRead();
            });
        }

        // Load notifications function
        function loadNotifications() {
            if (!notificationList) return;

            fetch('/notifications')
                .then(response => response.json())
                .then(data => {
                    updateNotificationBadge(data.unreadCount);
                    renderNotifications(data.notifications);
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                });
        }

        // Update notification badge
        function updateNotificationBadge(count) {
            if (notificationBadge) {
                if (count > 0) {
                    notificationBadge.classList.remove('hidden');
                    notificationBadge.textContent = count > 9 ? '9+' : count;
                } else {
                    notificationBadge.classList.add('hidden');
                }
            }
        }

        // Render notifications
        function renderNotifications(notifications) {
            if (!notificationList) return;

            if (notifications.length === 0) {
                notificationList.innerHTML = `
                    <div class="py-8 text-sm text-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p>No notifications yet</p>
                    </div>
                `;
                return;
            }

            const notificationHTML = notifications.map(notification => `
                <div class="px-4 py-3 hover:bg-gray-50 ${!notification.read_at ? 'bg-blue-50' : ''}" data-notification-id="${notification.id}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            ${getNotificationIcon(notification.type)}
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-900">${notification.title}</p>
                            <p class="text-sm text-gray-500">${notification.message}</p>
                            <p class="text-xs text-gray-400 mt-1">${formatTimeAgo(notification.created_at)}</p>
                        </div>
                        ${!notification.read_at ? '<div class="flex-shrink-0"><div class="w-2 h-2 bg-blue-500 rounded-full"></div></div>' : ''}
                    </div>
                </div>
            `).join('');

            notificationList.innerHTML = notificationHTML;

            // Add click handlers for individual notifications
            notificationList.querySelectorAll('[data-notification-id]').forEach(element => {
                element.addEventListener('click', function() {
                    const notificationId = this.dataset.notificationId;
                    markNotificationAsRead(notificationId);
                });
            });
        }

        // Mark all notifications as read
        function markAllNotificationsAsRead() {
            fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateNotificationBadge(0);
                    loadNotifications();
                }
            })
            .catch(error => {
                console.error('Error marking notifications as read:', error);
            });
        }

        // Mark single notification as read
        function markNotificationAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                }
            })
            .catch(error => {
                console.error('Error marking notification as read:', error);
            });
        }

        // Get notification icon based on type
        function getNotificationIcon(type) {
            const icons = {
                'loan_approval': '<svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'loan_rejection': '<svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'support_ticket': '<svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'system': '<svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
            };
            return icons[type] || icons['system'];
        }

        // Format time ago
        function formatTimeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);

            if (diffInSeconds < 60) return 'Just now';
            if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
            if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
            return `${Math.floor(diffInSeconds / 86400)}d ago`;
        }

        // Load notifications on page load
        loadNotifications();
    }

    // ===== USER DROPDOWN FUNCTIONALITY =====
    function initializeUserDropdown() {
        const dropdownToggle = document.getElementById('dropdownToggle');
        const dropdownMenu = document.getElementById('dropdownMenu');

        if (dropdownToggle && dropdownMenu) {
            dropdownToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Close other dropdowns first
                closeAllDropdowns(dropdownMenu);

                // Toggle dropdown visibility
                if (dropdownMenu.classList.contains('hidden')) {
                    showDropdown(dropdownMenu);
                } else {
                    hideDropdown(dropdownMenu);
                }
            });
        }
    }

    // ===== SIDEBAR DROPDOWN FUNCTIONALITY =====
    function initializeSidebarDropdowns() {
        const dropdownButtons = document.querySelectorAll('.dropdown-toggle');

        dropdownButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const dropdown = this.closest('.dropdown');
                const menu = dropdown.querySelector('.dropdown-menu');
                const arrow = dropdown.querySelector('.dropdown-arrow');

                if (menu) {
                    // Close other sidebar dropdowns
                    document.querySelectorAll('.dropdown-menu').forEach(otherMenu => {
                        if (otherMenu !== menu && !otherMenu.classList.contains('hidden')) {
                            otherMenu.classList.add('hidden');
                            const otherArrow = otherMenu.closest('.dropdown').querySelector('.dropdown-arrow');
                            if (otherArrow) {
                                otherArrow.style.transform = 'rotate(0deg)';
                            }
                        }
                    });

                    // Toggle current dropdown
                    menu.classList.toggle('hidden');
                    if (arrow) {
                        arrow.style.transform = menu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
                    }
                }
            });
        });
    }

    // ===== MOBILE SIDEBAR TOGGLE =====
    function initializeMobileSidebar() {
        const sidebarToggle = document.querySelector('[data-drawer-toggle="default-sidebar"]');
        const sidebar = document.getElementById('default-sidebar');

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Sidebar toggle clicked');
                sidebar.classList.toggle('-translate-x-full');
            });
        }
    }

    // ===== MOBILE MENU FUNCTIONALITY =====
    function initializeMobileMenu() {
        const mobileMenuBtn = document.getElementById("menuToggleBtn");
        const mobileMenu = document.getElementById("mobile-menu");

        if (mobileMenuBtn && mobileMenu) {
            // Toggle mobile menu when clicking the button
            mobileMenuBtn.addEventListener("click", function (event) {
                event.preventDefault();
                event.stopPropagation();
                mobileMenu.classList.toggle("hidden");
                console.log('Mobile menu toggled');
            });

            // Close the mobile menu when clicking outside
            document.addEventListener("click", function (event) {
                const isClickInside = mobileMenuBtn.contains(event.target) || mobileMenu.contains(event.target);
                if (!isClickInside) {
                    mobileMenu.classList.add("hidden");
                }
            });
        }
    }

    // ===== NAVBAR SCROLL EFFECTS =====
    try {
        initializeNavbarEffects();
        console.log('Navbar effects initialized');
    } catch (e) {
        console.error('Error initializing navbar effects:', e);
    }

    function initializeNavbarEffects() {
        // Make navbar sticky on scroll
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (header) {
                if (window.scrollY > 10) {
                    header.classList.add('shadow-lg');
                    header.classList.remove('shadow-md');
                } else {
                    header.classList.remove('shadow-lg');
                    header.classList.add('shadow-md');
                }
            }
        });
    }

    // ===== UTILITY FUNCTIONS =====
    function showDropdown(dropdown) {
        dropdown.classList.remove('hidden', 'opacity-0', 'scale-95');
        dropdown.classList.add('opacity-100', 'scale-100');
    }

    function hideDropdown(dropdown) {
        dropdown.classList.remove('opacity-100', 'scale-100');
        dropdown.classList.add('opacity-0', 'scale-95');
        setTimeout(() => {
            dropdown.classList.add('hidden');
        }, 200);
    }

    function closeAllDropdowns(except = null) {
        const dropdowns = [
            document.getElementById('adminNotificationDropdown'),
            document.getElementById('dropdownMenu')
        ];

        dropdowns.forEach(dropdown => {
            if (dropdown && dropdown !== except && !dropdown.classList.contains('hidden')) {
                hideDropdown(dropdown);
            }
        });
    }

    // Global click handler to close dropdowns
    document.addEventListener('click', function(e) {
        // Check if click is outside any dropdown
        const isClickInsideDropdown = e.target.closest('.relative') ||
                                     e.target.closest('[id$="Dropdown"]') ||
                                     e.target.closest('[id$="DropdownButton"]');

        if (!isClickInsideDropdown) {
            closeAllDropdowns();
        }

        // Close sidebar dropdowns when clicking outside
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
                const arrow = menu.closest('.dropdown').querySelector('.dropdown-arrow');
                if (arrow) {
                    arrow.style.transform = 'rotate(0deg)';
                }
            });
        }
    });

    // Prevent dropdown from closing when clicking inside
    document.querySelectorAll('[id$="Dropdown"]').forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });

    // ===== LOAN CALCULATOR LOGIC =====
    initializeLoanCalculator();

    // ===== FORM TOGGLE LOGIC =====
    initializeFormToggles();

    function initializeLoanCalculator() {
        const calcBtn = document.getElementById('calculate_button');
        const amortBtn = document.getElementById('amortization_button');
        const loanProduct = document.getElementById('loan_product');
        const interestRate = document.getElementById('interest_rate');
        const termSlider = document.getElementById('term_slider');
        const termMonths = document.getElementById('term_months');
        const searchInput = document.getElementById('product-search');

        if (calcBtn) {
            calcBtn.addEventListener('click', function () {
                const loanAmount = parseFloat(document.getElementById('loan_amount').value);
                const term = parseInt(termMonths.value);
                const rate = parseFloat(interestRate.value) / 100 / 12;

                if (isNaN(loanAmount) || isNaN(term) || isNaN(rate)) {
                    alert('Please enter valid numbers for all fields.');
                    return;
                }

                if (loanAmount <= 0 || term <= 0 || rate < 0) {
                    alert('Please enter positive values.');
                    return;
                }

                let monthlyPayment = rate === 0
                    ? loanAmount / term
                    : (loanAmount * rate) / (1 - Math.pow(1 + rate, -term));

                const totalRepayment = monthlyPayment * term;
                const totalInterest = totalRepayment - loanAmount;

                document.getElementById('monthly_payment').textContent = monthlyPayment.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                document.getElementById('total_repayment').textContent = totalRepayment.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                document.getElementById('total_interest').textContent = totalInterest.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            });
        }

        if (amortBtn) {
            amortBtn.addEventListener('click', function () {
                alert('Amortization schedule feature coming soon!');
            });
        }

        if (loanProduct && interestRate) {
            loanProduct.addEventListener('change', function () {
                const rates = {'1': '10', '2': '8', '3': '5'};
                interestRate.value = rates[this.value] || '';
            });
        }

        if (termSlider && termMonths) {
            termSlider.addEventListener('input', function () {
                termMonths.value = this.value;
            });

            termMonths.addEventListener('input', function () {
                termSlider.value = this.value;
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', function (e) {
                const searchTerm = e.target.value.toLowerCase();
                console.log(`Search for: ${searchTerm}`);
            });
        }
    }

    function initializeFormToggles() {
        // Toggle between New User and Existing User forms
        const newUserBtn = document.getElementById('newUserBtn');
        const existingUserBtn = document.getElementById('existingUserBtn');
        const bulkUploadBtn = document.getElementById('bulkUploadBtn');
        const newUserForm = document.getElementById('newUserForm');
        const existingUserForm = document.getElementById('existingUserForm');

        if (newUserBtn && existingUserBtn && newUserForm && existingUserForm) {
            newUserBtn.addEventListener('click', () => {
                newUserForm.classList.remove('hidden');
                existingUserForm.classList.add('hidden');
                newUserBtn.classList.add('bg-green-600', 'text-white');
                newUserBtn.classList.remove('bg-white', 'text-gray-900');
                existingUserBtn.classList.add('bg-white', 'text-gray-900');
                existingUserBtn.classList.remove('bg-green-600', 'text-white');
            });

            existingUserBtn.addEventListener('click', () => {
                existingUserForm.classList.remove('hidden');
                newUserForm.classList.add('hidden');
                existingUserBtn.classList.add('bg-green-600', 'text-white');
                existingUserBtn.classList.remove('bg-white', 'text-gray-900');
                newUserBtn.classList.add('bg-white', 'text-gray-900');
                newUserBtn.classList.remove('bg-green-600', 'text-white');
            });
        }

        // Add tab functionality for admin user add page
        initializeUserAddTabs();
    }

    // ===== USER ADD PAGE TAB FUNCTIONALITY =====
    function initializeUserAddTabs() {
        const newUserBtn = document.getElementById('newUserBtn');
        const existingUserBtn = document.getElementById('existingUserBtn');
        const bulkUploadBtn = document.getElementById('bulkUploadBtn');

        if (newUserBtn) {
            newUserBtn.addEventListener('click', function(e) {
                e.preventDefault();
                setNewUserMode();
            });
        }

        if (existingUserBtn) {
            existingUserBtn.addEventListener('click', function(e) {
                e.preventDefault();
                setExistingUserMode();
            });
        }

        if (bulkUploadBtn) {
            bulkUploadBtn.addEventListener('click', function(e) {
                e.preventDefault();
                setBulkUploadMode();
            });
        }
    }

    // Make these functions global so they can be called from inline onclick handlers
    window.setNewUserMode = function() {
        const newUserBtn = document.getElementById('newUserBtn');
        const existingUserBtn = document.getElementById('existingUserBtn');
        const bulkUploadBtn = document.getElementById('bulkUploadBtn');

        // Use the new form structure
        const newUserForm = document.getElementById('newUserForm');
        const existingUserForm = document.getElementById('existingUserForm');
        const bulkUploadForm = document.getElementById('bulkUploadForm');
        const userTypeInput = document.getElementById('user_type');

        if (!newUserBtn || !existingUserBtn || !bulkUploadBtn) {
            console.error('Required tab buttons not found');
            return false;
        }

        // Update button styles
        newUserBtn.classList.add('text-white', 'bg-green-600', 'border-green-600');
        newUserBtn.classList.remove('text-gray-900', 'bg-white', 'border-gray-300');
        existingUserBtn.classList.add('text-gray-900', 'bg-white', 'border-gray-300');
        existingUserBtn.classList.remove('text-white', 'bg-green-600', 'border-green-600');
        bulkUploadBtn.classList.add('text-gray-900', 'bg-white', 'border-gray-300');
        bulkUploadBtn.classList.remove('text-white', 'bg-green-600', 'border-green-600');

        // Show new user form and hide others
        if (newUserForm) {
            newUserForm.style.setProperty('display', 'block', 'important');
        }
        if (existingUserForm) {
            existingUserForm.style.setProperty('display', 'none', 'important');
        }
        if (bulkUploadForm) {
            bulkUploadForm.style.setProperty('display', 'none', 'important');
        }

        // Update debug
        const debugElement = document.getElementById('activeForm');
        if (debugElement) debugElement.textContent = 'New User';

        // Update form state
        if (userTypeInput) {
            userTypeInput.value = 'new';
        }

        console.log('Switched to New User mode');
        return false;
    };

    window.setExistingUserMode = function() {
        const newUserBtn = document.getElementById('newUserBtn');
        const existingUserBtn = document.getElementById('existingUserBtn');
        const bulkUploadBtn = document.getElementById('bulkUploadBtn');

        // Use the new form structure
        const newUserForm = document.getElementById('newUserForm');
        const existingUserForm = document.getElementById('existingUserForm');
        const bulkUploadForm = document.getElementById('bulkUploadForm');

        if (!newUserBtn || !existingUserBtn || !bulkUploadBtn) {
            console.error('Required tab buttons not found');
            return false;
        }

        // Update button styles
        existingUserBtn.classList.add('text-white', 'bg-green-600', 'border-green-600');
        existingUserBtn.classList.remove('text-gray-900', 'bg-white', 'border-gray-300');
        newUserBtn.classList.add('text-gray-900', 'bg-white', 'border-gray-300');
        newUserBtn.classList.remove('text-white', 'bg-green-600', 'border-green-600');
        bulkUploadBtn.classList.add('text-gray-900', 'bg-white', 'border-gray-300');
        bulkUploadBtn.classList.remove('text-white', 'bg-green-600', 'border-green-600');

        // Show existing user form and hide others
        if (newUserForm) {
            newUserForm.style.setProperty('display', 'none', 'important');
        }
        if (existingUserForm) {
            existingUserForm.style.setProperty('display', 'block', 'important');
        }
        if (bulkUploadForm) {
            bulkUploadForm.style.setProperty('display', 'none', 'important');
        }

        // Update debug
        const debugElement = document.getElementById('activeForm');
        if (debugElement) debugElement.textContent = 'Add Finances';

        console.log('Switched to Existing User mode');
        return false;
    };

    window.setBulkUploadMode = function() {
        const newUserBtn = document.getElementById('newUserBtn');
        const existingUserBtn = document.getElementById('existingUserBtn');
        const bulkUploadBtn = document.getElementById('bulkUploadBtn');

        // Use the new form structure
        const newUserForm = document.getElementById('newUserForm');
        const existingUserForm = document.getElementById('existingUserForm');
        const bulkUploadForm = document.getElementById('bulkUploadForm');

        if (!newUserBtn || !existingUserBtn || !bulkUploadBtn) {
            console.error('Required tab buttons not found');
            return false;
        }

        // Update button styles
        bulkUploadBtn.classList.add('text-white', 'bg-green-600', 'border-green-600');
        bulkUploadBtn.classList.remove('text-gray-900', 'bg-white', 'border-gray-300');
        newUserBtn.classList.add('text-gray-900', 'bg-white', 'border-gray-300');
        newUserBtn.classList.remove('text-white', 'bg-green-600', 'border-green-600');
        existingUserBtn.classList.add('text-gray-900', 'bg-white', 'border-gray-300');
        existingUserBtn.classList.remove('text-white', 'bg-green-600', 'border-green-600');

        // Hide other forms and show bulk upload form
        if (newUserForm) {
            newUserForm.style.setProperty('display', 'none', 'important');
        }
        if (existingUserForm) {
            existingUserForm.style.setProperty('display', 'none', 'important');
        }
        if (bulkUploadForm) {
            bulkUploadForm.style.setProperty('display', 'block', 'important');
        }

        // Update debug
        const debugElement = document.getElementById('activeForm');
        if (debugElement) debugElement.textContent = 'Bulk Upload';

        console.log('Switched to Bulk Upload mode');
        return false;
    };

    // ===== MODAL FUNCTIONALITY =====
    try {
        initializeModals();
        console.log('Modals initialized');
    } catch (e) {
        console.error('Error initializing modals:', e);
    }

    function initializeModals() {
        // Handle modal triggers
        const modalButtons = document.querySelectorAll('[data-modal-target]');
        const modalCloses = document.querySelectorAll('[data-modal-hide]');

        modalButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const target = this.getAttribute('data-modal-target');
                const modal = document.getElementById(target);
                if (modal) {
                    showModal(modal);
                }
            });
        });

        modalCloses.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const target = this.getAttribute('data-modal-hide');
                const modal = document.getElementById(target);
                if (modal) {
                    hideModal(modal);
                }
            });
        });

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('fixed') && e.target.getAttribute('tabindex') === '-1') {
                hideModal(e.target);
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const openModals = document.querySelectorAll('.fixed:not(.hidden)[tabindex="-1"]');
                openModals.forEach(modal => {
                    hideModal(modal);
                });
            }
        });
    }

    function showModal(modal) {
        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';

        // Focus the modal for accessibility
        modal.focus();

        // Add animation
        setTimeout(() => {
            const modalContent = modal.querySelector('.relative');
            if (modalContent) {
                modalContent.style.transform = 'scale(1)';
                modalContent.style.opacity = '1';
            }
        }, 10);
    }

    function hideModal(modal) {
        const modalContent = modal.querySelector('.relative');
        if (modalContent) {
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
        }

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }, 150);
    }

    // Make modal functions globally available
    window.showModal = showModal;
    window.hideModal = hideModal;

    console.log('All JavaScript functionality initialized successfully');
});
