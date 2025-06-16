(() => {
    // DOM Elements
    const elements = {
        container: document.getElementById("container"),
        signUpForm: document.getElementById("signUpForm"),
        signInForm: document.getElementById("signInForm"),
        overlayContainer: document.getElementById("overlayContainer"),
        overlay: document.getElementById("overlay"),
        overlayBtn: document.getElementById("overlayBtn"),
        overlayTitle: document.getElementById("overlayTitle"),
        overlayText: document.getElementById("overlayText")
    };

    // Validate DOM elements
    function validateElements() {
        for (const [key, element] of Object.entries(elements)) {
            if (!element) {
                console.error(`Required element ${key} not found in the DOM`);
                return false;
            }
        }
        return true;
    }

    // Exit if elements are not found
    if (!validateElements()) {
        console.error('Required elements missing, initialization stopped');
        return;
    }

    // Show error message with better UI
    function showError(message, inputElement) {
        // Remove any existing error message
        const existingError = inputElement.parentElement.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }

        // Create and insert error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message text-red-500 text-sm mt-1 mb-2';
        errorDiv.textContent = message;
        inputElement.parentElement.insertBefore(errorDiv, inputElement.nextSibling);

        // Add error styling to input
        inputElement.classList.add('border-red-500');
    }

    // Clear error message and styling
    function clearError(inputElement) {
        const errorMessage = inputElement.parentElement.querySelector('.error-message');
        if (errorMessage) {
            errorMessage.remove();
        }
        inputElement.classList.remove('border-red-500');
    }

    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Validate email format
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Check if email exists
    async function checkEmailExists(email) {
        try {
            const response = await fetch('/check-email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ email })
            });
            const data = await response.json();
            return data.exists;
        } catch (error) {
            console.error('Error checking email:', error);
            return false;
        }
    }

    // Debounced email check
    const debouncedEmailCheck = debounce(async (email, inputElement) => {
        if (isValidEmail(email)) {
            const exists = await checkEmailExists(email);
            if (exists) {
                showError('Email sudah terdaftar, silakan gunakan email lain', inputElement);
                return false;
            } else {
                clearError(inputElement);
                return true;
            }
        }
        return true;
    }, 500);

    // Password requirements check
    function validatePassword(password) {
        const minLength = 8;
        const hasUpperCase = /[A-Z]/.test(password);
        const hasLowerCase = /[a-z]/.test(password);
        const hasNumbers = /\d/.test(password);
        const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

        const errors = [];
        if (password.length < minLength) errors.push('minimal 8 karakter');
        if (!hasUpperCase) errors.push('minimal 1 huruf besar');
        if (!hasLowerCase) errors.push('minimal 1 huruf kecil');
        if (!hasNumbers) errors.push('minimal 1 angka');
        if (!hasSpecialChar) errors.push('minimal 1 karakter spesial');

        return {
            isValid: errors.length === 0,
            errors: errors
        };
    }

    // FUNGSI BARU: Toggle password visibility
    window.togglePasswordVisibility = function(inputId, svgId) { // Menerima svgId
        const passwordInput = document.getElementById(inputId);
        const svgElement = document.getElementById(svgId); // Mengambil elemen SVG

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            // Path untuk ikon mata terbuka
            svgElement.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>`;
        } else {
            passwordInput.type = 'password';
            // Path untuk ikon mata tertutup (dengan coretan)
            svgElement.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.616-1.079a3 3 0 114.5-4.5M10.287 14.113A7.001 7.001 0 0012 14c2.972 0 5.426-1.571 6.558-3.957m-9.754-5.321C11.458 4.606 12 5 12 5s.458-.394.942-.843A10.05 10.05 0 0112 3c4.478 0 8.268 2.943 9.543 7a9.97 9.97 0 01-1.563 3.029m-5.616 1.079a3 3 0 11-4.5 4.5"></path>`;
        }
    };


    let isSignUp = false;

    function toggleForm() {
        isSignUp = !isSignUp;
        document.title = isSignUp ? "Sign Up - BacaPoint" : "Login - BacaPoint";

        if (isSignUp) {
            // Geser overlay ke kiri
            elements.overlayContainer.classList.remove("left-1/2");
            elements.overlayContainer.classList.add("left-0");

            // Rounded kanan
            elements.overlay.classList.remove("rounded-l-[150px]");
            elements.overlay.classList.add("rounded-r-[150px]");

            // Ubah konten overlay
            elements.overlayTitle.textContent = "Welcome Back!";
            elements.overlayText.textContent = "Enter your personal details to use all of site features";
            elements.overlayBtn.textContent = "Sign In";

            // Tampilkan form Sign Up
            elements.signUpForm.classList.remove("opacity-0", "pointer-events-none", "z-10");
            elements.signUpForm.classList.add("opacity-100", "z-20");

            elements.signInForm.classList.remove("opacity-100", "z-20");
            elements.signInForm.classList.add("opacity-0", "pointer-events-none", "z-10");
        } else {
            // Geser overlay ke kanan
            elements.overlayContainer.classList.remove("left-0");
            elements.overlayContainer.classList.add("left-1/2");

            // Rounded kiri
            elements.overlay.classList.remove("rounded-r-[150px]");
            elements.overlay.classList.add("rounded-l-[150px]");

            // Ubah konten overlay
            elements.overlayTitle.textContent = "Hello, Friend!";
            elements.overlayText.textContent = "Register with your personal details to start journey";
            elements.overlayBtn.textContent = "Sign Up";

            // Tampilkan form Sign In
            elements.signInForm.classList.remove("opacity-0", "pointer-events-none", "z-10");
            elements.signInForm.classList.add("opacity-100", "z-20");

            elements.signUpForm.classList.remove("opacity-100", "z-20");
            elements.signUpForm.classList.add("opacity-0", "pointer-events-none", "z-10");
        }
    }

    // Event Listeners
    elements.overlayBtn.addEventListener("click", toggleForm);

    // Sign Up Form Validation
    elements.signUpForm.addEventListener('submit', async function(e) {
        e.preventDefault(); // Prevent form submission initially
        let hasError = false;
        const nameInput = this.querySelector('input[name="name"]');
        const emailInput = this.querySelector('input[name="email"]');
        const passwordInput = this.querySelector('input[name="password"]');
        const confirmInput = this.querySelector('input[name="password_confirmation"]');

        // Clear previous errors
        [nameInput, emailInput, passwordInput, confirmInput].forEach(input => clearError(input));

        // Name validation
        if (nameInput.value.trim().length < 3) {
            showError('Nama harus minimal 3 karakter', nameInput);
            hasError = true;
        }

        // Email validation
        if (!isValidEmail(emailInput.value)) {
            showError('Format email tidak valid', emailInput);
            hasError = true;
        } else {
            // Check if email exists
            const emailExists = await checkEmailExists(emailInput.value);
            if (emailExists) {
                showError('Email sudah terdaftar, silakan gunakan email lain', emailInput);
                hasError = true;
            }
        }

        // Password validation
        const passwordValidation = validatePassword(passwordInput.value);
        if (!passwordValidation.isValid) {
            showError('Password harus mengandung ' + passwordValidation.errors.join(', '), passwordInput);
            hasError = true;
        }

        // Password confirmation
        if (passwordInput.value !== confirmInput.value) {
            showError('Password dan konfirmasi password tidak cocok', confirmInput);
            hasError = true;
        }

        if (!hasError) {
            this.submit(); // Submit form if no errors
        }
    });

    // Real-time email validation on Sign Up form
    elements.signUpForm.querySelector('input[name="email"]').addEventListener('input', function() {
        if (this.value) {
            debouncedEmailCheck(this.value, this);
        }
    });

    // Sign In Form Validation
    elements.signInForm.addEventListener('submit', function(e) {
        let hasError = false;
        const emailInput = this.querySelector('input[name="email"]');
        const passwordInput = this.querySelector('input[name="password"]');

        // Clear previous errors
        [emailInput, passwordInput].forEach(input => clearError(input));

        // Email validation
        if (!isValidEmail(emailInput.value)) {
            showError('Format email tidak valid', emailInput);
            hasError = true;
        }

        // Password validation
        if (passwordInput.value.trim().length < 8) {
            showError('Password minimal 8 karakter', passwordInput);
            hasError = true;
        }

        if (hasError) {
            e.preventDefault();
        }
    });
})();