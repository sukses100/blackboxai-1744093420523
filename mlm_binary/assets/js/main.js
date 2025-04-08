// Copy referral link to clipboard
function copyReferralLink(elementId) {
    const element = document.getElementById(elementId);
    const textArea = document.createElement('textarea');
    textArea.value = element.textContent;
    document.body.appendChild(textArea);
    textArea.select();
    document.execCommand('copy');
    document.body.removeChild(textArea);
    
    // Show tooltip
    const tooltip = document.getElementById('copyTooltip');
    tooltip.innerHTML = 'Tersalin!';
    setTimeout(() => {
        tooltip.innerHTML = 'Salin Link';
    }, 2000);
}

// Form validation
function validateRegistrationForm() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    const username = document.getElementById('username');
    const email = document.getElementById('email');
    
    if (!username.value.trim()) {
        alert('Username harus diisi!');
        return false;
    }
    
    if (!email.value.trim()) {
        alert('Email harus diisi!');
        return false;
    }
    
    if (password.value !== confirmPassword.value) {
        alert('Password tidak cocok!');
        return false;
    }
    
    if (password.value.length < 6) {
        alert('Password minimal 6 karakter!');
        return false;
    }
    
    return true;
}

// Format currency (IDR)
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

// Toggle password visibility
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize package selection handlers
    const packageRadios = document.querySelectorAll('input[name="package_type"]');
    if (packageRadios.length) {
        packageRadios.forEach(radio => {
            radio.addEventListener('change', updatePackageDetails);
        });
    }
});

// Update package details when selection changes
function updatePackageDetails() {
    const selectedPackage = document.querySelector('input[name="package_type"]:checked').value;
    const priceElement = document.getElementById('selected-package-price');
    
    const prices = {
        'basic': 300000,
        'silver': 600000,
        'gold': 1000000,
        'platinum': 1500000
    };
    
    if (priceElement && prices[selectedPackage]) {
        priceElement.textContent = formatCurrency(prices[selectedPackage]);
    }
}

// Confirm deletion
function confirmDelete(message) {
    return confirm(message || 'Apakah Anda yakin ingin menghapus item ini?');
}

// Show loading spinner
function showLoading() {
    const spinner = document.createElement('div');
    spinner.className = 'spinner-overlay';
    spinner.innerHTML = '<div class="spinner"></div>';
    document.body.appendChild(spinner);
}

// Hide loading spinner
function hideLoading() {
    const spinner = document.querySelector('.spinner-overlay');
    if (spinner) {
        spinner.remove();
    }
}