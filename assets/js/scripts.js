document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const emailField = document.getElementById('email');
    const passwordField = document.getElementById('password');
    const passwordToggle = document.getElementById('passwordToggle');
    const loginBtn = document.getElementById('loginBtn');

    // Setup event listeners
    setupEventListeners();

    function setupEventListeners() {
        // Form validation
        emailField.addEventListener('input', validateEmail);
        passwordField.addEventListener('input', validatePassword);
        
        // Password toggle
        passwordToggle.addEventListener('click', togglePassword);
        
        // Form submission
        loginForm.addEventListener('submit', handleLogin);
    }

    function validateEmail() {
        const email = this.value;
        const feedback = document.getElementById('emailFeedback');
        const emailRegex = /^[^\s@]+@mstip\.edu\.ph$/;

        if (!email) {
            setFieldState(this, feedback, false, '');
            validationState.email = false;
        } else if (!emailRegex.test(email)) {
            setFieldState(this, feedback, false, 'Only @mstip.edu.ph email addresses are accepted');
            validationState.email = false;
        } else {
            setFieldState(this, feedback, true, 'Email looks good!');
            validationState.email = true;
        }
    }

    function validatePassword() {
        const password = this.value;
        const feedback = document.getElementById('passwordFeedback');

        if (!password) {
            setFieldState(this, feedback, null, '');
        } else if (password.length < 6) {
            setFieldState(this, feedback, false, 'Password is too short');
        } else {
            setFieldState(this, feedback, true, '');
        }
    }

    function setFieldState(field, feedback, isValid, message = '') {
        field.classList.remove('is-valid', 'is-invalid');

        if (isValid === true) {
            field.classList.add('is-valid');
            if (feedback && message) {
                feedback.className = 'form-feedback valid';
                feedback.innerHTML = `<i class="fas fa-check"></i> ${message}`;
            } else if (feedback) {
                feedback.innerHTML = '';
            }
        } else if (isValid === false) {
            field.classList.add('is-invalid');
            if (feedback && message) {
                feedback.className = 'form-feedback invalid';
                feedback.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
            }
        } else if (feedback) {
            feedback.innerHTML = '';
        }
    }

    function togglePassword() {
        const passwordField = document.getElementById('password');
        const icon = this;

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    function handleLogin(e) {
        e.preventDefault();

        const submitText = loginBtn.querySelector('.submit-text');
        const loading = loginBtn.querySelector('.loading');
        
        // Validate form
        if (!emailField.value || !passwordField.value) {
            showError('Please fill in all required fields');
            return;
        }

        // Show loading state
        loginBtn.disabled = true;
        submitText.style.display = 'none';
        loading.style.display = 'inline-block';

        // Prepare form data
        const formData = new FormData(loginForm);
        
        // Add remember me value
        const rememberMe = document.getElementById('rememberMe');
        if (rememberMe && rememberMe.checked) {
            formData.append('remember_me', '1');
        }

        // Send AJAX request
        fetch('login_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(text => {
            console.log('Raw response:', text);
            
            try {
                const data = JSON.parse(text);
                return data;
            } catch (e) {
                throw new Error('Invalid JSON response: ' + text);
            }
        })
        .then(data => {
            // Reset button state
            loginBtn.disabled = false;
            submitText.style.display = 'flex';
            loading.style.display = 'none';

            console.log('Login response:', data);

            if (data.success) {
                showSuccess(data.message, data.user, data.redirect_url);
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            // Reset button state
            loginBtn.disabled = false;
            submitText.style.display = 'flex';
            loading.style.display = 'none';

            console.error('Login error:', error);
            showError('An error occurred during login. Please try again.');
        });
    }

    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            text: message,
            confirmButtonText: 'Try Again',
            confirmButtonColor: '#dc3545',
            customClass: {
                confirmButton: 'custom-swal-button'
            },
            buttonsStyling: false
        });
    }

    function showSuccess(message, user, redirectUrl) {
        Swal.fire({
            icon: 'success',
            title: 'Welcome Back!',
            html: `
                <div style="text-align: center;">
                    <p>${message}</p>
                    <p style="margin-top: 15px;"><strong>Welcome, ${user.name}!</strong></p>
                    <p style="color: #6c757d; font-size: 14px;">You will be redirected to your dashboard...</p>
                </div>
            `,
            confirmButtonText: 'Continue',
            confirmButtonColor: '#28a745',
            allowOutsideClick: false,
            timer: 2000,
            timerProgressBar: true,
            customClass: {
                confirmButton: 'custom-swal-button'
            },
            buttonsStyling: false
        }).then((result) => {
            // Redirect to appropriate dashboard
            window.location.href = redirectUrl;
        });

        // Auto redirect after 2 seconds even if user doesn't click
        setTimeout(() => {
            window.location.href = redirectUrl;
        }, 2000);
    }
});