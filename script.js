// Show popup message
function showPopup(message, type = 'info') {
    const popup = document.getElementById('popup');
    const popupMessage = popup.querySelector('.popup-message');
    
    // Set message
    popupMessage.textContent = message;
    
    // Remove all type classes
    popup.classList.remove('success', 'error', 'info', 'warning');
    
    // Add appropriate type class
    popup.classList.add(type);
    
    // Show popup
    popup.classList.add('show');
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        closePopup();
    }, 5000);
}

// Close popup
function closePopup() {
    const popup = document.getElementById('popup');
    popup.classList.add('hide');
    
    setTimeout(() => {
        popup.classList.remove('show', 'hide');
    }, 400);
}

// Confirm delete
document.addEventListener('DOMContentLoaded', function() {
    const deleteForms = document.querySelectorAll('.delete-form');
    
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to delete this user?')) {
                this.submit();
            }
        });
    });
});

// Form validation
const userForm = document.getElementById('userForm');
if (userForm) {
    userForm.addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        
        if (name === '' || email === '') {
            e.preventDefault();
            showPopup('Please fill in all fields!', 'warning');
            return false;
        }
        
        // Basic email validation
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            e.preventDefault();
            showPopup('Please enter a valid email address!', 'warning');
            return false;
        }
    });
}

// Clear URL parameters after showing message
if (window.location.search.includes('msg=')) {
    // Wait a bit then clear URL
    setTimeout(() => {
        const url = window.location.origin + window.location.pathname;
        window.history.replaceState({}, document.title, url);
    }, 100);
}