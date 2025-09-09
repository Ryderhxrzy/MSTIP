<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Login - MSTIP Graduate Job Search</title>
  
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
  <div class="login-container">
    <!-- Header -->
    <div class="login-header">
      <span>
        <img src="assets/images/mstip_logo.png" alt="logo" width="110" height="100">
      </span>
      <h2><i class="fas fa-user-graduate"></i> Welcome Back</h2>
      <p style="margin-bottom: 20px;">Sign in to continue your job search</p>
    </div>

    <!-- Form Container -->
    <div class="form-container">
      <form id="loginForm" method="POST">
        <div class="form-group">
          <label class="form-label">Email Address</label>
          <div class="input-group">
            <input type="email" class="form-control" name="email" id="email" required>
            <i class="input-icon fas fa-envelope"></i>
          </div>
          <div class="form-feedback" id="emailFeedback"></div>
        </div>

        <div class="form-group">
          <label class="form-label">Password</label>
          <div class="input-group">
            <input type="password" class="form-control" name="password" id="password" required>
            <i class="input-icon fas fa-eye" id="passwordToggle"></i>
          </div>
          <div class="form-feedback" id="passwordFeedback"></div>
        </div>

        <div class="login-options">
          <div class="remember-me">
            <input type="checkbox" id="rememberMe">
            <label for="rememberMe">Remember me</label>
          </div>
          <a href="forgot-password.html" class="forgot-password">Forgot Password?</a>
        </div>

        <div class="btn-group">
        <button type="submit" class="btn btn-primary" id="loginBtn">
          <span class="submit-text">
            <i class="fas fa-sign-in-alt"></i> Sign In
          </span>
          <div class="loading" style="display: none;"></div>
        </button>
        </div>

        <div class="register-link">
          Don't have an account? <a href="register.php">Register here</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="assets/js/scripts.js"></script>
</body>
</html>