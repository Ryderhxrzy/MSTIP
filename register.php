<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Register - MSTIP Graduate Job Search</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="assets/css/register.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/sweetalert.css">
</head>

<body>
  <div class="register-container">
    <!-- Header -->
    <div class="register-header">
      <span>
        <img src="assets/images/logocap.png" alt="logo" width="80" height="64">
      </span>
      <h2><i class="fas fa-user-graduate"></i> Join MSTIP</h2>
      <p style="margin-bottom: 20px;">Start your journey to find the perfect job</p>
    </div>

    <!-- Progress Steps -->
    <div class="progress-container">
      <div class="progress-steps">
        <div class="progress-line">
          <div class="progress-line-fill" id="progressFill"></div>
        </div>
        <div class="step active" id="step1">
          <i class="fas fa-user"></i>
          <div class="step-label">Account</div>
        </div>
        <div class="step" id="step2">
          <i class="fas fa-info-circle"></i>
          <div class="step-label">Details</div>
        </div>
        <div class="step" id="step3">
          <i class="fas fa-check"></i>
          <div class="step-label">Complete</div>
        </div>
      </div>
    </div>

    <!-- Form Container -->
    <div class="form-container">
      <form id="registrationForm" method="POST" enctype="multipart/form-data">
        
        <!-- Step 1: Account Information -->
        <div class="form-step active" id="formStep1">
          <div class="form-group">
            <label class="form-label">Email Address <span class="asterisk"> *</span></label>
            <div class="input-group">
              <input type="email" class="form-control" name="email" id="email" required>
              <i class="input-icon fas fa-envelope"></i>
            </div>
            <div class="form-feedback" id="emailFeedback"></div>
          </div>

          <div class="form-group">
            <label class="form-label">Password <span class="asterisk"> *</span></label>
            <div class="input-group">
              <input type="password" class="form-control" name="password" id="password" required>
              <i class="input-icon fas fa-eye" id="passwordToggle"></i>
            </div>
            <div class="password-strength" id="passwordStrength">
              <div class="strength-bar">
                <div class="strength-fill"></div>
              </div>
              <small class="text-muted">Must be at least 8 characters with uppercase letter & number</small>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Confirm Password <span class="asterisk"> *</span></label>
            <div class="input-group">
              <input type="password" class="form-control" id="confirmPassword" required>
              <i class="input-icon fas fa-lock"></i>
            </div>
            <div class="form-feedback" id="confirmFeedback"></div>
          </div>

          <div class="btn-group">
            <button type="button" class="btn btn-primary" id="nextStep1">
              Next <i class="fas fa-arrow-right"></i>
            </button>
          </div>
        </div>

        <!-- Step 2: Personal & Professional Information -->
        <div class="form-step" id="formStep2">
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label class="form-label">First Name <span class="asterisk"> *</span></label>
                <input type="text" class="form-control" name="first_name" id="firstName" required>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label class="form-label">Middle Name</label>
                <input type="text" class="form-control" name="middle_name" id="middleName">
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label class="form-label">Last Name <span class="asterisk"> *</span></label>
                <input type="text" class="form-control" name="last_name" id="lastName" required>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Phone Number <span class="asterisk"> *</span></label>
            <div class="input-group">
              <input type="tel" class="form-control" name="phone" id="phone" required>
              <i class="input-icon fas fa-phone"></i>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="form-group">
                <label class="form-label">Course <span class="asterisk"> *</span></label>
                <input type="text" class="form-control" name="course" id="course" required>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label class="form-label">Year Graduated <span class="asterisk"> *</span></label>
                <input type="number" class="form-control" name="year_grad" id="yearGrad" min="1990" max="2030" required>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Skills <span class="asterisk"> *</span></label>
            <textarea class="form-control" name="skills" id="skills" rows="3" placeholder="e.g., Programming, Communication, Leadership, Project Management"></textarea>
          </div>

          <div class="form-group">
            <label class="form-label">Upload Resume <span class="asterisk"> *</span></label>
            <div class="file-upload" id="fileUpload">
              <input type="file" name="resume" id="resume" accept=".pdf,.doc,.docx" required>
              <label class="file-upload-label" for="resume">
                <i class="fas fa-cloud-upload-alt fa-2x"></i>
                <div>
                  <strong>Choose file or drag here</strong><br>
                  <small>PDF, DOC, DOCX (Max 5MB)</small>
                </div>
              </label>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">LinkedIn Profile <span class="asterisk"> *</span></label>
            <div class="input-group">
              <input type="text" class="form-control" name="linkedin_profile" id="linkedin_profile" required>
              <i class="input-icon fas fa-user"></i>
            </div>
          </div>

          <div class="btn-group">
            <button type="button" class="btn btn-outline" id="prevStep2">
              <i class="fas fa-arrow-left"></i> Back
            </button>
            <button type="button" class="btn btn-primary" id="nextStep2">
              Next <i class="fas fa-arrow-right"></i>
            </button>
          </div>
        </div>

        <!-- Step 3: Review & Submit -->
        <div class="form-step" id="formStep3">
          <div style="text-align: center; margin-bottom: 24px;">
            <i class="fas fa-check-circle success-icon"></i>
            <h3 style="color: var(--dark-color); margin-bottom: 8px; font-size: 1.3rem;">Review Your Information</h3>
            <p style="color: #6b7280; font-size: 0.9rem;">Please review your details before submitting</p>
          </div>

          <div id="reviewData" class="mb-4">
            <!-- Review data will be populated here -->
          </div>

          <div class="btn-group">
            <button type="button" class="btn btn-outline" id="prevStep3">
              <i class="fas fa-arrow-left"></i> Back
            </button>
            <button type="submit" class="btn btn-primary" id="submitForm">
              <span class="submit-text">
                <i class="fas fa-paper-plane"></i> Submit
              </span>
              <div class="loading" style="display: none;"></div>
            </button>
          </div>
        </div>

      </form>

      <!-- Login Link -->
      <div class="login-link">
        Already have an account? <a href="login.php">Sign in here</a>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="assets/js/script.js"></script>
</body>
</html>