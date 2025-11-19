// Comprehensive JavaScript functionality for ACICS
document.addEventListener('DOMContentLoaded', function() {
    // Force light mode - prevent any dark mode activation
    forceLightMode();

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



    // Notification functionality
    function initializeNotifications() {
        const notificationButton = document.getElementById('adminNotificationDropdownButton');
        const notificationDropdown = document.getElementById('adminNotificationDropdown');
        const notificationBadge = document.getElementById('adminNotificationBadge');
        const markAllAsReadBtn = document.getElementById('adminMarkAllAsReadBtn');

        // Toggle notification dropdown
        if (notificationButton && notificationDropdown) {
            notificationButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Close other dropdowns first
                closeAllDropdowns(notificationDropdown);

                // Toggle dropdown visibility
                if (notificationDropdown.classList.contains('hidden')) {
                    showDropdown(notificationDropdown);
                } else {
                    hideDropdown(notificationDropdown);
                }
            });
        }

        // Mark all notifications as read
        if (markAllAsReadBtn) {
            markAllAsReadBtn.addEventListener('click', function(e) {
                e.preventDefault();

                // Hide the badge
                if (notificationBadge) {
                    notificationBadge.classList.add('hidden');
                }

                // You can add AJAX call here for actual functionality
                console.log('Mark all notifications as read');
            });
        }
    }

    // User dropdown functionality
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

    // Sidebar dropdown functionality
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

    // Mobile sidebar toggle
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



    // Utility functions
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
    });

    // Prevent dropdown from closing when clicking inside
    document.querySelectorAll('[id$="Dropdown"]').forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });

    // Force light mode function
    function forceLightMode() {
        // Remove any dark mode classes
        document.documentElement.classList.remove('dark');
        document.body.classList.remove('dark');

        // Clear any dark mode localStorage
        localStorage.removeItem('darkMode');
        localStorage.removeItem('theme');
        localStorage.removeItem('dark-mode');

        // Set color scheme to light
        document.documentElement.style.colorScheme = 'light only';

        // Monitor for any attempts to add dark mode classes
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    const target = mutation.target;
                    if (target.classList.contains('dark')) {
                        target.classList.remove('dark');
                    }
                }
            });
        });

        // Start observing
        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        });

        observer.observe(document.body, {
            attributes: true,
            attributeFilter: ['class']
        });

        // Light mode enforced
    }
});