// Modal handling functions
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    console.log('Modal opened:', modalId);
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Add event listeners to open modal buttons
document.addEventListener('DOMContentLoaded', function() {
    // Edit profile modal
    document.querySelectorAll('[data-modal="edit-profile-modal"]').forEach(button => {
        button.addEventListener('click', function() {
            openModal('edit-profile-modal');
        });
    });

    // Profile photo modal
    document.querySelectorAll('[data-modal="profile-photo-modal"]').forEach(button => {
        button.addEventListener('click', function() {
            openModal('profile-photo-modal');
        });
    });

    // Next of kin modal
    document.querySelectorAll('[data-modal="next-of-kin-modal"]').forEach(button => {
        button.addEventListener('click', function() {
            openModal('next-of-kin-modal');
        });
    });

    // Close modal when clicking on the overlay
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal-id');
            closeModal(modalId);
        });
    });

    // Close modal when clicking on close buttons
    document.querySelectorAll('.modal-close').forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal-id');
            closeModal(modalId);
        });
    });

    // Close modal when pressing Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.modal').forEach(modal => {
                if (!modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            });
        }
    });
});