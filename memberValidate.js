// Validation functions
function validateFullName() {
    const fullName = document.getElementById('fullName');
    const fullNameError = document.getElementById('fullNameError');
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
    const nic = document.getElementById('nic');
    const nicError = document.getElementById('nicError');
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

function validateAge() {
    const age = document.getElementById('age');
    const ageError = document.getElementById('ageError');
    if (age.value <= 0) {
        age.classList.add('invalid');
        ageError.textContent = 'Please enter a valid age';
    } else {
        age.classList.remove('invalid');
        ageError.textContent = '';
    }
}

function validateGender() {
    const gender = document.getElementById('gender');
    const genderError = document.getElementById('genderError');
    if (gender.value === "") {
        gender.classList.add('invalid');
        genderError.textContent = 'Please select a gender';
    } else {
        gender.classList.remove('invalid');
        genderError.textContent = '';
    }
}

function validateAddress() {
    const address = document.getElementById('address');
    const addressError = document.getElementById('addressError');
    if (address.value.trim() === "") {
        address.classList.add('invalid');
        addressError.textContent = 'Address is required';
    } else {
        address.classList.remove('invalid');
        addressError.textContent = '';
    }
}

function validateMobile() {
    const mobile = document.getElementById('mobile');
    const mobileError = document.getElementById('mobileError');
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
    const email = document.getElementById('email');
    const emailError = document.getElementById('emailError');
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
document.getElementById('fullName').addEventListener('input', validateFullName);
document.getElementById('nic').addEventListener('input', validateNIC);
document.getElementById('age').addEventListener('input', validateAge);
document.getElementById('gender').addEventListener('change', validateGender);
document.getElementById('address').addEventListener('input', validateAddress);
document.getElementById('mobile').addEventListener('input', validateMobile);
document.getElementById('email').addEventListener('input', validateEmail);
