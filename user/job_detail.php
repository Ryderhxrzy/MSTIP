<?php
session_start();
// job_detail.php
include '../includes/db_config.php'; // your DB connection

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Invalid Job ID.');
}
$job_id = intval($_GET['id']);

// Fetch job details
$sql = "
SELECT jl.*, r.region_code, r.region_name
FROM job_listings jl
LEFT JOIN regions r ON jl.region_id = r.region_id
WHERE jl.job_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Job not found.');
}
$job = $result->fetch_assoc();

// Fetch user information
$user_id = $_SESSION['user_id'];
$user_sql = "SELECT * FROM user_information WHERE user_id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("s", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="stylesheet" href="../assets/css/bootstrap.css" />
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap" rel="stylesheet">
  <link href="../assets/css/font-awesome.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <link rel="stylesheet" href="../assets/css/responsive.css" />
  <title><?php echo htmlspecialchars($job['job_title']); ?> - Job Details</title>

  <link rel="shortcut icon" href="../assets/images/favicon.ico" type="image/x-icon">
  <link rel="icon" href="../assets/images/favicon.ico" type="image/x-icon">
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, rgb(8, 19, 92), rgb(0, 119, 255));
      --hover-primary-gradient: linear-gradient(135deg, rgb(0, 119, 225), rgb(8, 19, 92));
      --white: #ffffff;
    }
    
    .apply-btns {
      height: 45px;
      padding: 10px 45px;
      border: none;
      border-radius: 8px;
      font-size: 0.9rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      text-decoration: none;
      position: relative;
      overflow: hidden;
      line-height: 1;
      background: var(--primary-gradient);
      color: var(--white);
      box-shadow: 0 4px 15px -3px rgba(22, 42, 129, 0.4);
      text-transform: uppercase;
    }
    
    .apply-btns:hover {
      background: var(--hover-primary-gradient);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px -3px rgba(22, 42, 129, 0.5);
    }
    
    /* Complete Modal Styles with Fixed Close Button */
/* Complete Modal Styles with Smooth Transitions */
.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.6);
  opacity: 0;
  transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal.show {
  opacity: 1;
}

.modal-content {
  background-color: #fefefe;
  margin: 5% auto;
  padding: 0;
  border-radius: 12px;
  width: 90%;
  max-width: 700px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  transform: translateY(-50px) scale(0.95);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
}

.modal.show .modal-content {
  transform: translateY(0) scale(1);
}

.modal-header {
  background: var(--primary-gradient);
  color: white;
  padding: 20px 25px 20px 25px;
  position: relative;
  min-height: 80px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.modal-header h2 {
  margin: 0;
  font-size: 1.5rem;
  font-weight: 600;
  padding-right: 50px; /* Give space for close button */
}

.modal-header p {
  margin: 5px 0 0;
  opacity: 0.9;
  font-size: 0.95rem;
  padding-right: 50px; /* Give space for close button */
}

/* Perfectly Centered Close Button */
.close {
  position: absolute;
  top: 50%;
  right: 25px;
  transform: translateY(-50%) scale(0);
  color: white !important;
  font-size: 24px;
  font-weight: bold;
  cursor: pointer;
  opacity: 0;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  padding: 0;
  line-height: 1;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  z-index: 1001;
}

/* Close button appears with delay after modal */
.modal.show .close {
  opacity: 0.8;
  transform: translateY(-50%) scale(1);
  transition-delay: 0.2s;
}

.close:hover,
.close:focus {
  opacity: 1 !important;
  color: white !important;
  background-color: rgba(255, 255, 255, 0.2);
  border-color: rgba(255, 255, 255, 0.3);
  transform: translateY(-50%) scale(1.1);
  text-decoration: none;
  outline: none;
  transition-delay: 0s;
}

.close:active {
  transform: translateY(-50%) scale(0.9);
  background-color: rgba(255, 255, 255, 0.25);
}

/* Ensure no bootstrap interference */
.close:not(:disabled):not(.disabled):hover,
.close:not(:disabled):not(.disabled):focus {
  color: white !important;
  text-decoration: none;
}

.modal-body {
  padding: 25px;
  max-height: 70vh;
  overflow-y: auto;
}
    
    .info-section {
      margin-bottom: 25px;
      padding-bottom: 20px;
      border-bottom: 1px solid #eee;
    }
    
    .info-section:last-child {
      border-bottom: none;
      margin-bottom: 0;
      padding-bottom: 0;
    }
    
    .info-section h3 {
      font-size: 1.2rem;
      margin-bottom: 15px;
      color: #2c3e50;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .info-section h3 i {
      color: #667eea;
    }
    
    .info-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 15px;
    }
    
    .info-item {
      margin-bottom: 12px;
    }
    
    .info-label {
      display: block;
      font-weight: 600;
      color: #555;
      font-size: 0.9rem;
      margin-bottom: 5px;
    }
    
    .info-value {
      display: block;
      padding: 8px 0;
      color: #2c3e50;
      font-size: 0.95rem;
      line-height: 1.4;
    }
    
    .skills-container {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-top: 10px;
    }
    
    .skill-tag {
      background: #e9ecef;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      color: #495057;
    }
    
    .resume-preview {
      border: 1px solid #dee2e6;
      border-radius: 8px;
      padding: 15px;
      margin-top: 15px;
      background: #f8f9fa;
    }
    
    .resume-info {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
    }
    
    .file-icon {
      font-size: 48px;
      color: #6c757d;
      margin-right: 15px;
    }
    
    .resume-details h6 {
      word-break: break-all;
      white-space: normal;
      overflow-wrap: anywhere;
      font-size: 1rem;
      margin-bottom: 4px;
      max-width: 100%;
    }
    
    .resume-details {
      flex-grow: 1;
    }
    
    .resume-actions {
      margin-top: 15px;
      display: flex;
      gap: 10px;
    }
    
    .view-resume-btn {
      background: var(--primary-gradient);
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 6px;
      font-weight: 500;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      transition: all 0.2s;
    }
    
    .view-resume-btn:hover {
      background: var(--hover-primary-gradient);
      color: white;
      text-decoration: none;
    }
    
    .submit-application-btn {
      width: 100%;
      height: 50px;
      margin-top: 20px;
      background: var(--primary-gradient);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }
    
    .submit-application-btn:hover {
      background: var(--hover-primary-gradient);
      transform: translateY(-2px);
      box-shadow: 0 6px 15px -3px rgba(22, 42, 129, 0.4);
    }
    
    .alert {
      padding: 12px 15px;
      border-radius: 6px;
      margin: 15px 0;
      font-size: 0.9rem;
    }
    
    .alert-warning {
      background-color: #fff3cd;
      border: 1px solid #ffeaa7;
      color: #856404;
    }
    
    .alert-danger {
      background-color: #f8d7da;
      border: 1px solid #f5c6cb;
      color: #721c24;
    }
    
    @media (max-width: 768px) {
      .info-grid {
        grid-template-columns: 1fr;
      }
      
      .modal-content {
        width: 95%;
        margin: 10% auto;
      }
    }

    /* Add loading spinner animation */
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    .fa-spinner {
      animation: spin 1s linear infinite;
    }
    
    .btn-loading {
      position: relative;
      color: transparent !important;
    }
    
    .btn-loading::after {
      content: '';
      position: absolute;
      width: 20px;
      height: 20px;
      top: 50%;
      left: 50%;
      margin: -10px 0 0 -10px;
      border: 3px solid rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      border-top-color: #fff;
      animation: spin 1s linear infinite;
    }
  </style>
</head>
<body>
<?php include '../includes/user_header.php'; ?>

<div class="hero-area"></div>

<div class="container py-4">
  <a href="javascript:history.back()" class="back-btn">
    <i class="fa fa-arrow-left"></i> Back to Jobs
  </a>
  
  <div class="job-hero">
    <div class="job-hero-content text-center">
      <img src="../assets/<?php echo htmlspecialchars($job['image_url']); ?>" alt="Company Logo" class="company-logo">
      <h1 class="job-title"><?php echo htmlspecialchars($job['job_title']); ?></h1>
      <div class="company-name"><?php echo htmlspecialchars($job['company_name']); ?></div>
      
      <div class="quick-info">
        <span class="info-pill">
          <i class="fa fa-map-marker"></i> <?php echo htmlspecialchars($job['location']); ?>
        </span>
        <span class="info-pill">
          <i class="fa fa-briefcase"></i> <?php echo htmlspecialchars($job['job_type']); ?>
        </span>
        <span class="info-pill">
          <i class="fa fa-users"></i> <?php echo htmlspecialchars($job['slots_available']); ?> slots
        </span>
      </div>
    </div>
  </div>

  <div class="main-content">
    <div class="row">
      <div class="col-md-6">
        <div class="info-card">
          <h5 class="card-title">Job Details</h5>
          <div class="info-row">
            <span class="info-label">Position</span>
            <span class="info-value"><?php echo htmlspecialchars($job['job_position']); ?></span>
          </div>
          <div class="info-row">
            <span class="info-label">Type</span>
            <span class="info-value">
              <span class="badge-custom"><?php echo htmlspecialchars($job['job_type']); ?></span>
              <span class="badge-custom"><?php echo htmlspecialchars($job['job_type_shift']); ?></span>
            </span>
          </div>
          <div class="info-row">
            <span class="info-label">Location</span>
            <span class="info-value"><?php echo htmlspecialchars($job['location']); ?></span>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="info-card">
          <h5 class="card-title">Compensation & Timeline</h5>
          <div class="info-row">
            <span class="info-label">Salary</span>
            <span class="info-value">
              <span class="badge-custom salary-badge"><?php echo htmlspecialchars($job['salary_range']); ?></span>
            </span>
          </div>
          <div class="info-row">
            <span class="info-label">Deadline</span>
            <span class="info-value">
              <span class="badge-custom deadline-badge"><?php echo htmlspecialchars(date('M j, Y', strtotime($job['application_deadline']))); ?></span>
            </span>
          </div>
          <div class="info-row">
            <span class="info-label">Contact</span>
            <span class="info-value">
              <a href="mailto:<?php echo htmlspecialchars($job['contact_email']); ?>" style="color: #667eea; text-decoration: none;">
                <?php echo htmlspecialchars($job['contact_email']); ?>
              </a>
            </span>
          </div>
        </div>
      </div>
    </div>

    <div class="info-card description-card">
      <h5 class="card-title">About This Job</h5>
      <div class="description-content">
        <?php echo nl2br(htmlspecialchars($job['job_description'])); ?>
      </div>
    </div>

    <div class="info-card">
      <h5 class="card-title">What We're Looking For</h5>
      <div class="description-content">
        <?php echo nl2br(htmlspecialchars($job['qualifications'])); ?>
      </div>
    </div>

    <div class="apply-section">
      <button onclick="openApplyModal()" class="apply-btns">
        <i class="fa fa-paper-plane"></i> Apply Now
      </button>
      <p style="margin-top: 15px; color: #666; font-size: 0.9rem;">
        Ready to take the next step in your career?
      </p>
    </div>
  </div>
</div>

<!-- Application Modal -->
<div id="applyModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <span class="close" onclick="closeApplyModal()">&times;</span>
      <h2>Apply for <?php echo htmlspecialchars($job['job_title']); ?></h2>
      <p style="margin: 10px 0 0 0; opacity: 0.9;">Review your information before submitting</p>
    </div>
    <div class="modal-body">
      <div class="info-section">
        <h3><i class="fa fa-user"></i> Personal Information</h3>
        <div class="info-grid">
          <div class="info-item">
            <span class="info-label">First Name</span>
            <span class="info-value"><?php echo htmlspecialchars($user['first_name']); ?></span>
          </div>
          <div class="info-item">
            <span class="info-label">Last Name</span>
            <span class="info-value"><?php echo htmlspecialchars($user['last_name']); ?></span>
          </div>
          <div class="info-item">
            <span class="info-label">Middle Name</span>
            <span class="info-value"><?php echo !empty($user['middle_name']) ? htmlspecialchars($user['middle_name']) : 'N/A'; ?></span>
          </div>
          <div class="info-item">
            <span class="info-label">Email</span>
            <span class="info-value"><?php echo htmlspecialchars($user['email_address']); ?></span>
          </div>
          <div class="info-item">
            <span class="info-label">Phone Number</span>
            <span class="info-value"><?php echo !empty($user['phone_number']) ? htmlspecialchars($user['phone_number']) : 'N/A'; ?></span>
          </div>
          <div class="info-item">
            <span class="info-label">User ID</span>
            <span class="info-value"><?php echo htmlspecialchars($user['user_id']); ?></span>
          </div>
        </div>
      </div>
      
      <div class="info-section">
        <h3><i class="fa fa-graduation-cap"></i> Education</h3>
        <div class="info-grid">
          <div class="info-item">
            <span class="info-label">Course</span>
            <span class="info-value"><?php echo !empty($user['course']) ? htmlspecialchars($user['course']) : 'N/A'; ?></span>
          </div>
          <div class="info-item">
            <span class="info-label">Year Graduated</span>
            <span class="info-value"><?php echo !empty($user['year_graduated']) ? htmlspecialchars($user['year_graduated']) : 'N/A'; ?></span>
          </div>
        </div>
      </div>
      
      <div class="info-section">
        <h3><i class="fa fa-star"></i> Skills</h3>
        <div class="skills-container">
          <?php 
          if (!empty($user['skills'])) {
              $skills_array = explode(',', $user['skills']);
              foreach ($skills_array as $skill) {
                  if (!empty(trim($skill))) {
                      echo '<span class="skill-tag">' . htmlspecialchars(trim($skill)) . '</span>';
                  }
              }
          } else {
              echo '<span class="info-value">No skills added yet</span>';
          }
          ?>
        </div>
      </div>
      
      <div class="info-section">
        <h3><i class="fa fa-file-text"></i> Resume</h3>
        <?php if (!empty($user['resume'])): 
          $file_ext = pathinfo($user['resume'], PATHINFO_EXTENSION);
          $full_file_path = "../files/" . $user['resume'];
        ?>
        <div class="resume-preview">
          <div class="resume-info">
            <div class="file-icon">
              <?php if ($file_ext === 'pdf'): ?>
                <i class="fa fa-file-pdf-o"></i>
              <?php else: ?>
                <i class="fa fa-file-word-o"></i>
              <?php endif; ?>
            </div>
            <div class="resume-details">
              <h6><?php echo htmlspecialchars($user['resume']); ?></h6>
              <p class="text-muted mb-1">Uploaded: 
                <?php 
                if (file_exists($full_file_path)) {
                  echo date("F d, Y", filemtime($full_file_path));
                } else {
                  echo "Date unavailable";
                }
                ?>
              </p>
              <p class="text-muted">File type: .<?php echo $file_ext; ?></p>
            </div>
          </div>
          <div class="resume-actions">
            <?php if (file_exists($full_file_path)): ?>
              <a href="<?php echo htmlspecialchars($full_file_path); ?>" target="_blank" class="view-resume-btn">
                <i class="fa fa-eye"></i> View Resume
              </a>
              <a href="<?php echo htmlspecialchars($full_file_path); ?>" download class="view-resume-btn" style="background: #6c757d;">
                <i class="fa fa-download"></i> Download
              </a>
            <?php else: ?>
              <span class="text-danger">File not found. Please update your resume in your profile.</span>
            <?php endif; ?>
          </div>
        </div>
        <?php else: ?>
          <div class="alert alert-warning">
            <i class="fa fa-exclamation-triangle"></i> You haven't uploaded a resume yet. Please update your profile to add one.
          </div>
        <?php endif; ?>
      </div>
      
      <div class="info-section">
        <h3><i class="fa fa-linkedin"></i> LinkedIn</h3>
        <div class="info-item">
          <span class="info-label">Profile URL</span>
          <span class="info-value">
            <?php if (!empty($user['linkedin_profile'])): ?>
              <a href="<?php echo htmlspecialchars($user['linkedin_profile']); ?>" target="_blank">
                <?php echo htmlspecialchars($user['linkedin_profile']); ?>
              </a>
            <?php else: ?>
              N/A
            <?php endif; ?>
          </span>
        </div>
      </div>
      
      <form id="applicationForm" action="process_application.php" method="POST">
        <input type="hidden" name="job_id" value="<?php echo $job['job_id']; ?>">
        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
        
        <div class="info-section">
          <h3><i class="fa fa-comment"></i> Additional Information</h3>
          <div class="info-item">
            <span class="info-label">Cover Letter (Optional)</span>
            <textarea name="cover_letter" class="form-control" rows="4" placeholder="Tell us why you're interested in this position..."></textarea>
          </div>
        </div>
        
        <button type="submit" class="submit-application-btn" <?php echo empty($user['resume']) ? 'disabled' : ''; ?>>
          <i class="fa fa-paper-plane"></i> Submit Application
        </button>
        
        <?php if (empty($user['resume'])): ?>
          <div class="alert alert-danger mt-3">
            <i class="fa fa-exclamation-circle"></i> You cannot apply without a resume. Please update your profile first.
          </div>
        <?php endif; ?>
      </form>
    </div>
  </div>
</div>

<?php include '../includes/user_footer.php'; ?>

<script src="../assets/js/jquery-3.4.1.min.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/custom.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function openApplyModal() {
    const modal = document.getElementById('applyModal');
    
    // Show modal and trigger animation
    modal.style.display = 'block';
    
    // Small delay to ensure display: block is applied before animation
    requestAnimationFrame(() => {
        modal.classList.add('show');
    });
    
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
}

function closeApplyModal() {
    const modal = document.getElementById('applyModal');
    
    // Remove show class to trigger close animation
    modal.classList.remove('show');
    
    // Wait for animation to complete before hiding
    setTimeout(() => {
        modal.style.display = 'none';
        // Restore body scroll
        document.body.style.overflow = '';
    }, 400); // Match the CSS transition duration
}

// Enhanced click outside modal functionality
window.addEventListener('click', function(event) {
    const modal = document.getElementById('applyModal');
    if (event.target === modal) {
        closeApplyModal();
    }
});

// Enhanced keyboard support
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('applyModal');
        if (modal.classList.contains('show')) {
            closeApplyModal();
        }
    }
});

// Enhanced Form submission with SweetAlert notifications
document.getElementById('applicationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = document.querySelector('.submit-application-btn');
    const originalText = submitBtn.innerHTML;
    const originalBg = submitBtn.style.background;
    
    // Show loading state with animation
    submitBtn.classList.add('btn-loading');
    submitBtn.innerHTML = '<i class="fa fa-spinner"></i> Submitting Application...';
    submitBtn.disabled = true;
    
    fetch('action/process_application.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message with SweetAlert
            Swal.fire({
                icon: 'success',
                title: 'Application Submitted!',
                text: data.message,
                confirmButtonColor: '#0077ff',
                confirmButtonText: 'OK'
            }).then((result) => {
                // Close modal after success
                closeApplyModal();
                // Reset button
                submitBtn.classList.remove('btn-loading');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                submitBtn.style.background = originalBg;
            });
        } else {
            // Show error message with SweetAlert
            Swal.fire({
                icon: 'error',
                title: 'Application Failed',
                text: data.message,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Try Again'
            });
            
            // Reset button
            submitBtn.classList.remove('btn-loading');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            submitBtn.style.background = originalBg;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Show network error with SweetAlert
        Swal.fire({
            icon: 'error',
            title: 'Network Error',
            text: 'Please check your connection and try again.',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'OK'
        });
        
        // Reset button
        submitBtn.classList.remove('btn-loading');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        submitBtn.style.background = originalBg;
    });
});
</script>
</body>
</html>