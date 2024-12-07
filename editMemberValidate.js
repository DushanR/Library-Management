function validateFullName() {
    const fullName = document.getElementById('editFullName');
    const fullNameError = document.getElementById('editFullNameError');
    const fullNamePattern = /^[a-zA-Z\s]+$/; // Regex pattern to allow only letters and spaces

    if (fullName.value.trim() === "") {
        fullName.classList.add('invalid');
        fullNameError.textContent = 'Full Name is required';
    } else if (!fullNamePattern.test(fullName.value)) {
        fullName.classList.add('invalid');
        fullNameError.textContent = 'Full Name cannot contain numbers or special characters';
    } else {
        fullName.classList.remove('invalid');
        fullNameError.textContent = '';
    }
}

function validateNIC() {
    const nic = document.getElementById('editNIC');
    const nicError = document.getElementById('editNICError');
    // Updated NIC number validation logic to handle both 10-character and 12-digit formats
    const nicPattern10 = /^[0-9]{9}[vVxX]$/;
    const nicPattern12 = /^[0-9]{12}$/;
    if (!nicPattern10.test(nic.value) && !nicPattern12.test(nic.value)) {
        nic.classList.add('invalid');
        nicError.textContent = 'Invalid NIC Number';
    } else {
        nic.classList.remove('invalid');
        nicError.textContent = '';
    }
}

function validateMobile() {
    const mobile = document.getElementById('editMobile');
    const mobileError = document.getElementById('editMobileError');
    // Regex pattern for Sri Lankan mobile numbers (10 digits starting with 0)
    const mobilePattern = /^0[0-9]{9}$/;
    if (!mobilePattern.test(mobile.value)) {
        mobile.classList.add('invalid');
        mobileError.textContent = 'Invalid Mobile Number';
    } else {
        mobile.classList.remove('invalid');
        mobileError.textContent = '';
    }
}

function validateEmail() {
    const email = document.getElementById('editEmail');
    const emailError = document.getElementById('editEmailError');
    // Add email validation logic
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email.value)) {
        email.classList.add('invalid');
        emailError.textContent = 'Invalid Email Address';
    } else {
        email.classList.remove('invalid');
        emailError.textContent = '';
    }
}

// Event listeners for real-time validation
document.getElementById('editFullName').addEventListener('input', validateFullName);
document.getElementById('editNIC').addEventListener('input', validateNIC);
document.getElementById('editMobile').addEventListener('input', validateMobile);
document.getElementById('editEmail').addEventListener('input', validateEmail);
