let currentStep = 1;
const totalSteps = 3;

// Form validation state (no middleName because optional)
const validationState = {
  email: false,
  password: false,
  confirmPassword: false,
  firstName: false,
  lastName: false,
  phone: false,
  course: false,
  yearGrad: false,
  resume: false,
  linkedin_profile: false
};

// Initialize form
document.addEventListener('DOMContentLoaded', function() {
  setupEventListeners();
  updateProgress();
});

function setupEventListeners() {
  // Step navigation
  document.getElementById('nextStep1').addEventListener('click', () => validateAndNext(1));
  document.getElementById('nextStep2').addEventListener('click', () => validateAndNext(2));
  document.getElementById('prevStep2').addEventListener('click', () => goToStep(1));
  document.getElementById('prevStep3').addEventListener('click', () => goToStep(2));

  // Form validation
  document.getElementById('email').addEventListener('input', validateEmail);
  document.getElementById('password').addEventListener('input', validatePassword);
  document.getElementById('confirmPassword').addEventListener('input', validateConfirmPassword);
  document.getElementById('firstName').addEventListener('input', validateRequired);
  // middleName is optional → no validation event
  document.getElementById('lastName').addEventListener('input', validateRequired);
  document.getElementById('phone').addEventListener('input', validatePhone);
  document.getElementById('course').addEventListener('input', validateRequired);
  document.getElementById('yearGrad').addEventListener('input', validateYear);
  document.getElementById('resume').addEventListener('change', validateResume);
  document.getElementById('linkedin_profile').addEventListener('input', validateRequired);

  // Password toggle
  document.getElementById('passwordToggle').addEventListener('click', togglePassword);

  // Form submission
  document.getElementById('registrationForm').addEventListener('submit', handleSubmit);

  // File upload styling
  setupFileUpload();
}

function validateAndNext(step) {
  if (step === 1) {
    if (validationState.email && validationState.password && validationState.confirmPassword) {
      goToStep(2);
    } else {
      showError('Please fill all required fields correctly');
    }
  } else if (step === 2) {
    // middleName not included since optional
    const requiredFields = ['firstName', 'lastName', 'phone', 'course', 'yearGrad', 'resume', 'linkedin_profile'];
    const isValid = requiredFields.every(field => validationState[field]);
    
    if (isValid) {
      populateReview();
      goToStep(3);
    } else {
      showError('Please fill all required fields correctly');
    }
  }
}

function goToStep(step) {
  document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
  document.getElementById(`formStep${step}`).classList.add('active');

  document.querySelectorAll('.step').forEach((el, index) => {
    el.classList.remove('active', 'completed');
    if (index + 1 < step) {
      el.classList.add('completed');
    } else if (index + 1 === step) {
      el.classList.add('active');
    }
  });

  currentStep = step;
  updateProgress();
}

function updateProgress() {
  const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
  document.getElementById('progressFill').style.width = `${progress}%`;
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
  const strengthEl = document.getElementById('passwordStrength');
  const strengthBar = strengthEl.querySelector('.strength-bar');

  let strength = 0;
  let feedback = [];

  if (password.length >= 8) strength++; else feedback.push('8+ characters');
  if (/[A-Z]/.test(password)) strength++; else feedback.push('uppercase letter');
  if (/[0-9]/.test(password)) strength++; else feedback.push('number');
  if (/[^A-Za-z0-9]/.test(password)) strength++;

  strengthBar.className = 'strength-bar';
  if (strength >= 3) {
    strengthBar.classList.add('strength-strong');
    validationState.password = true;
    setFieldState(this, null, true);
  } else if (strength >= 2) {
    strengthBar.classList.add('strength-medium');
    validationState.password = false;
    setFieldState(this, null, false);
  } else {
    strengthBar.classList.add('strength-weak');
    validationState.password = false;
    setFieldState(this, null, false);
  }

  if (feedback.length > 0) {
    strengthEl.querySelector('small').textContent = `Missing: ${feedback.join(', ')}`;
  } else {
    strengthEl.querySelector('small').textContent = 'Strong password!';
  }

  const confirmPassword = document.getElementById('confirmPassword');
  if (confirmPassword.value) {
    validateConfirmPassword.call(confirmPassword);
  }
}

function validateConfirmPassword() {
  const password = document.getElementById('password').value;
  const confirmPassword = this.value;
  const feedback = document.getElementById('confirmFeedback');

  if (!confirmPassword) {
    setFieldState(this, feedback, false, '');
    validationState.confirmPassword = false;
  } else if (password !== confirmPassword) {
    setFieldState(this, feedback, false, 'Passwords do not match');
    validationState.confirmPassword = false;
  } else {
    setFieldState(this, feedback, true, 'Passwords match!');
    validationState.confirmPassword = true;
  }
}

function validateRequired() {
  const fieldName = this.id;
  const isValid = this.value.trim() !== '';
  validationState[fieldName] = isValid;
  setFieldState(this, null, isValid);
}

function validatePhone() {
  const phone = this.value;
  const phoneRegex = /^[\+]?[\d\s\-\(\)]{10,}$/;
  const isValid = phoneRegex.test(phone.replace(/\s/g, ''));
  validationState.phone = isValid;
  setFieldState(this, null, isValid);
}

function validateYear() {
  const year = parseInt(this.value);
  const currentYear = new Date().getFullYear();
  const isValid = year >= 1990 && year <= currentYear + 5;
  validationState.yearGrad = isValid;
  setFieldState(this, null, isValid);
}

function validateResume() {
  const file = this.files[0];
  const fileUpload = document.getElementById('fileUpload');
  const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
  const maxSize = 5 * 1024 * 1024;

  if (!file) {
    fileUpload.classList.remove('has-file');
    validationState.resume = false;
    return;
  }

  if (!allowedTypes.includes(file.type)) {
    showError('Please upload a PDF, DOC, or DOCX file');
    this.value = '';
    fileUpload.classList.remove('has-file');
    validationState.resume = false;
    return;
  }

  if (file.size > maxSize) {
    showError('File size must be less than 5MB');
    this.value = '';
    fileUpload.classList.remove('has-file');
    validationState.resume = false;
    return;
  }

  fileUpload.classList.add('has-file');
  const label = fileUpload.querySelector('.file-upload-label');
  label.innerHTML = `
    <i class="fas fa-file-check fa-2x"></i>
    <div>
      <strong>${file.name}</strong><br>
      <small>${(file.size / 1024 / 1024).toFixed(2)} MB</small>
    </div>
  `;
  validationState.resume = true;
}

function setFieldState(field, feedback, isValid, message = '') {
  field.classList.remove('is-valid', 'is-invalid');

  if (isValid) {
    field.classList.add('is-valid');
    if (feedback) {
      feedback.className = 'form-feedback valid';
      feedback.innerHTML = `<i class="fas fa-check"></i> ${message}`;
    }
  } else if (message) {
    field.classList.add('is-invalid');
    if (feedback) {
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

function setupFileUpload() {
  const fileUpload = document.getElementById('fileUpload');
  const fileInput = document.getElementById('resume');

  ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    fileUpload.addEventListener(eventName, preventDefaults, false);
  });

  ['dragenter', 'dragover'].forEach(eventName => {
    fileUpload.addEventListener(eventName, highlight, false);
  });

  ['dragleave', 'drop'].forEach(eventName => {
    fileUpload.addEventListener(eventName, unhighlight, false);
  });

  fileUpload.addEventListener('drop', handleDrop, false);

  function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
  }

  function highlight() {
    fileUpload.style.borderColor = 'var(--primary-color)';
    fileUpload.style.backgroundColor = 'rgba(102, 126, 234, 0.05)';
  }

  function unhighlight() {
    fileUpload.style.borderColor = '#d1d5db';
    fileUpload.style.backgroundColor = '#f9fafb';
  }

  function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;

    if (files.length > 0) {
      fileInput.files = files;
      validateResume.call(fileInput);
    }
  }
}

function populateReview() {
  const reviewData = document.getElementById('reviewData');
  const formData = new FormData(document.getElementById('registrationForm'));
  const resumeFile = document.getElementById('resume').files[0];

  reviewData.innerHTML = `
    <div class="review-card">
      <h5><i class="fas fa-user"></i> Personal Information</h5>
      <div class="review-grid">
        <div class="review-item"><strong>First Name:</strong> ${formData.get('first_name')}</div>
        <div class="review-item"><strong>Middle Name:</strong> ${formData.get('middle_name') || '-'}</div>
        <div class="review-item"><strong>Last Name:</strong> ${formData.get('last_name')}</div>
        <div class="review-item"><strong>Email:</strong> ${formData.get('email')}</div>
        <div class="review-item"><strong>Phone:</strong> ${formData.get('phone')}</div>
        <div class="review-item"><strong>Course:</strong> ${formData.get('course')}</div>
        <div class="review-item"><strong>Year:</strong> ${formData.get('year_grad')}</div>
        <div class="review-item"><strong>Resume:</strong> ${resumeFile ? resumeFile.name : 'Not uploaded'}</div>
        <div class="review-item"><strong>LinkedIn:</strong> ${formData.get('linkedin_profile')}</div>
        ${formData.get('skills') ? `
          <div class="review-skills">
            <strong>Skills:</strong><br>
            <span style="color: #6b7280;">${formData.get('skills')}</span>
          </div>
        ` : ''}
      </div>
    </div>
  `;
}

function handleSubmit(e) {
  e.preventDefault();

  const submitBtn = document.getElementById('submitForm');
  const submitText = submitBtn.querySelector('.submit-text');
  const loading = submitBtn.querySelector('.loading');

  // Show loading state
  submitBtn.disabled = true;
  submitText.style.display = 'none';
  loading.style.display = 'inline-block';

  // Prepare form data
  const formData = new FormData(document.getElementById('registrationForm'));

  // Send AJAX request
  fetch('register_handler.php', {
    method: 'POST',
    body: formData
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.json();
  })
  .then(data => {
    // Reset button state
    submitBtn.disabled = false;
    submitText.style.display = 'flex';
    loading.style.display = 'none';

    if (data.success) {
      showSuccess(data.message, data.user_id);
    } else {
      showError(data.message);
    }
  })
  .catch(error => {
    // Reset button state
    submitBtn.disabled = false;
    submitText.style.display = 'flex';
    loading.style.display = 'none';

    console.error('Error:', error);
    showError('An error occurred during registration. Please try again.');
  });
}

function showError(message) {
  Swal.fire({
    icon: 'error',
    title: 'Registration Failed',
    text: message,
    confirmButtonText: 'Try Again',
    confirmButtonColor: '#dc3545',
    customClass: {
      confirmButton: 'custom-swal-button'
    },
    buttonsStyling: false
  });
}

function showSuccess(message, userId = null) {
  Swal.fire({
    icon: 'success',
    title: 'Registration Successful!',
    html: `
      <div style="text-align: center;">
        <p>${message}</p>
        ${userId ? `<p style="margin-top: 15px;"><strong>Your User ID:</strong> <span style="color: #28a745; font-weight: bold;">${userId}</span></p>` : ''}
        <p style="margin-top: 15px; color: #6c757d; font-size: 14px;">Please keep your User ID safe for future reference.</p>
      </div>
    `,
    confirmButtonText: 'Continue to Login',
    confirmButtonColor: '#28a745',
    allowOutsideClick: false,
    customClass: {
      confirmButton: 'custom-swal-button'
    },
    buttonsStyling: false
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = 'login.php';
    }
  });
}