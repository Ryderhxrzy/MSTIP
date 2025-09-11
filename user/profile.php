<?php
session_start();
include '../includes/db_config.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login.php");
    exit();
}

// Make sure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$sql = "SELECT * FROM user_information WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die('User not found.');
}
$user = $result->fetch_assoc();

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $course = $_POST['course'];
    $year_graduated = $_POST['year_graduated'];
    $skills = $_POST['skills'];
    $linked_in = $_POST['linkedin_profile'];
    
    // Handle resume upload
    // Handle resume upload
    $resume_path = $user['resume']; // Keep existing if no new file uploaded

    if (!empty($_FILES['resume']['name'])) {
        $target_dir = "../files/";
        
        // Create directory if it doesn't exist
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_name = time() . '_' . basename($_FILES["resume"]["name"]);
        $target_file = $target_dir . $file_name;
        
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Allow both PDF and Word documents
        $allowed_types = array('pdf', 'doc', 'docx');
        if (!in_array($fileType, $allowed_types)) {
            $upload_error = "Only PDF and Word documents are allowed.";
        } else {
            // Check file size (optional - limit to 5MB)
            if ($_FILES["resume"]["size"] > 5000000) {
                $upload_error = "File is too large. Maximum size is 5MB.";
            } else {
                if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
                    // Delete old resume file if exists and file path is valid
                    if (!empty($user['resume']) && file_exists($user['resume'])) {
                        unlink($user['resume']);
                    }
                    $resume_path = $target_file;
                    $_SESSION['new_resume'] = $resume_path; // Store for potential preview
                } else {
                    $upload_error = "Sorry, there was an error uploading your file.";
                }
            }
        }
    }
    
    if (!isset($upload_error)) {
        $update = $conn->prepare("UPDATE user_information 
            SET first_name=?, middle_name=?, last_name=?, phone_number=?, course=?, year_graduated=?, skills=?, resume=?, linked_in=?
            WHERE user_id=?");
        $update->bind_param("ssssssssss", $first_name, $middle_name, $last_name, $phone_number, $course, $year_graduated, $skills, $resume_path, $linked_in, $user_id);
        
        if ($update->execute()) {
            $_SESSION['success_message'] = "Profile updated successfully!";
            header("Location: profile.php");
            exit;
        } else {
            $update_error = "Error updating profile: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="stylesheet" href="../assets/css/bootstrap.css" />
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" rel="stylesheet">
  <link href="../assets/css/font-awesome.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <link rel="stylesheet" href="../assets/css/responsive.css" />
  <title>My Profile</title>
  <style>
    .profile-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      padding: 25px;
      margin-bottom: 25px;
    }
    .profile-header {
      display: flex;
      align-items: center;
      margin-bottom: 25px;
      padding-bottom: 20px;
      border-bottom: 1px solid #eee;
    }
    .profile-avatar {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background: linear-gradient(45deg, #4e73df, #224abe);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 40px;
      font-weight: bold;
      margin-right: 20px;
    }
    .profile-title {
      font-weight: 700;
      color: #343a40;
      margin-bottom: 5px;
    }
    .profile-subtitle {
      color: #6c757d;
      font-size: 0.9rem;
    }
    .section-title {
      font-weight: 600;
      color: #4e73df;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid #f0f0f0;
    }
    .skill-tag {
      display: inline-block;
      background: #e9ecef;
      padding: 5px 12px;
      border-radius: 20px;
      margin: 5px 5px 5px 0;
      font-size: 0.85rem;
      color: #495057;
    }
    .file-upload {
      position: relative;
      overflow: hidden;
    }
    .file-upload-input {
      position: absolute;
      top: 0;
      right: 0;
      margin: 0;
      padding: 0;
      font-size: 20px;
      cursor: pointer;
      opacity: 0;
      height: 100%;
    }
    .social-link {
      color: #4e73df;
      text-decoration: none;
      transition: color 0.2s;
    }
    .social-link:hover {
      color: #224abe;
      text-decoration: underline;
    }
    .edit-toggle {
      cursor: pointer;
      color: #6c757d;
      transition: color 0.2s;
    }
    .edit-toggle:hover {
      color: #4e73df;
    }
    .btn-save {
      background: linear-gradient(45deg, #4e73df, #224abe);
      border: none;
      padding: 10px 25px;
      font-weight: 600;
      border-radius: 6px;
    }
    .btn-save:hover {
      background: linear-gradient(45deg, #224abe, #4e73df);
    }
    .resume-preview {
      border: 1px solid #dee2e6;
      border-radius: 8px;
      padding: 15px;
      margin-top: 20px;
      background: #f8f9fa;
    }
    .resume-actions {
      margin-top: 15px;
    }
    .file-icon {
      font-size: 48px;
      color: #6c757d;
      margin-right: 15px;
    }
    .resume-info {
      display: flex;
      align-items: center;
    }
    .resume-details {
      flex-grow: 1;
    }
  </style>
</head>
<body>
<?php include '../includes/user_header.php'; ?>

<div class="hero-area"></div>

<div class="container py-5">
  <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>
  
  <?php if (isset($upload_error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo $upload_error; ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>
  
  <?php if (isset($update_error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo $update_error; ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>

  <div class="row">
    <div class="col-lg-4 mb-4">
      <div class="profile-card text-center">
        <div class="profile-header justify-content-center">
          <div class="profile-avatar">
            <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
          </div>
        </div>
        <h3 class="profile-title"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h3>
        <p class="profile-subtitle"><?php echo htmlspecialchars($user['course']); ?></p>
        <p class="profile-subtitle">Class of <?php echo htmlspecialchars($user['year_graduated']); ?></p>
        
        <?php if (!empty($user['linked_in'])): ?>
          <div class="mt-4">
            <a href="<?php echo htmlspecialchars($user['linked_in']); ?>" target="_blank" class="social-link">
              <i class="fa fa-linkedin-square mr-2"></i> LinkedIn Profile
            </a>
          </div>
        <?php endif; ?>
      </div>
      
      <div class="profile-card">
        <h5 class="section-title">Skills</h5>
        <div id="skillsDisplay">
          <?php 
          if (!empty($user['skills'])) {
              $skills_array = explode(',', $user['skills']);
              foreach ($skills_array as $skill) {
                  echo '<span class="skill-tag">' . htmlspecialchars(trim($skill)) . '</span>';
              }
          } else {
              echo '<p class="text-muted">No skills added yet.</p>';
          }
          ?>
        </div>
      </div>
      
      <!-- Resume Preview Section -->
      <!-- Resume Preview Section -->
<?php if (!empty($user['resume'])): ?>
<div class="profile-card">
  <h5 class="section-title">Resume Preview</h5>
  <div class="resume-preview">
    <div class="resume-info">
      <div class="file-icon">
        <?php
        $file_ext = pathinfo($user['resume'], PATHINFO_EXTENSION);
        $full_file_path = "../files/" . $user['resume']; // Construct full path from filename
        
        if ($file_ext === 'pdf') {
            echo '<i class="fa fa-file-pdf-o"></i>';
        } else {
            echo '<i class="fa fa-file-word-o"></i>';
        }
        ?>
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
        <a href="<?php echo htmlspecialchars($full_file_path); ?>" target="_blank" class="btn btn-primary btn-sm">
          <i class="fa fa-eye mr-1"></i> View Resume
        </a>
        <a href="<?php echo htmlspecialchars($full_file_path); ?>" download class="btn btn-outline-secondary btn-sm ml-2">
          <i class="fa fa-download mr-1"></i> Download
        </a>
      <?php else: ?>
        <span class="text-danger">File not found</span>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php endif; ?>
    </div>
    
    <div class="col-lg-8">
      <div class="profile-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h5 class="section-title mb-0">Personal Information</h5>
        </div>
        
        <form method="post" enctype="multipart/form-data" id="profileForm">
          <div class="row">
            <div class="form-group col-md-6">
              <label for="user_id" class="font-weight-bold">User ID</label>
              <input type="text" class="form-control" id="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>" disabled>
            </div>
            <div class="form-group col-md-6">
              <label for="email_address" class="font-weight-bold">Email Address</label>
              <input type="email" class="form-control" id="email_address" value="<?php echo htmlspecialchars($user['email_address']); ?>" disabled>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-4">
              <label for="first_name" class="font-weight-bold">First Name</label>
              <input type="text" class="form-control" name="first_name" id="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            </div>
            <div class="form-group col-md-4">
              <label for="middle_name" class="font-weight-bold">Middle Name</label>
              <input type="text" class="form-control" name="middle_name" id="middle_name" value="<?php echo htmlspecialchars($user['middle_name']); ?>">
            </div>
            <div class="form-group col-md-4">
              <label for="last_name" class="font-weight-bold">Last Name</label>
              <input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-6">
              <label for="phone_number" class="font-weight-bold">Phone Number</label>
              <input type="tel" class="form-control" name="phone_number" id="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>">
            </div>
            <div class="form-group col-md-6">
              <label for="course" class="font-weight-bold">Course</label>
              <input type="text" class="form-control" name="course" id="course" value="<?php echo htmlspecialchars($user['course']); ?>">
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-6">
              <label for="year_graduated" class="font-weight-bold">Year Graduated</label>
              <input type="number" min="1900" max="2099" step="1" class="form-control" name="year_graduated" id="year_graduated" value="<?php echo htmlspecialchars($user['year_graduated']); ?>">
            </div>
            <div class="form-group col-md-6">
              <label for="linked_in" class="font-weight-bold">LinkedIn URL</label>
              <input type="url" class="form-control" name="linkedin_profile" id="linked_in" value="<?php echo htmlspecialchars($user['linkedin_profile']); ?>" placeholder="https://linkedin.com/in/yourprofile">
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-12">
              <label for="skills" class="font-weight-bold">Skills (comma separated)</label>
              <textarea class="form-control" name="skills" id="skills" rows="2" placeholder="e.g., PHP, JavaScript, Project Management"><?php echo htmlspecialchars($user['skills']); ?></textarea>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-12">
              <label for="resume" class="font-weight-bold">Resume (PDF or Word Document)</label>
              <div class="file-upload btn btn-outline-secondary btn-block">
                <span><i class="fa fa-upload mr-2"></i> Choose File</span>
                <input type="file" class="file-upload-input" name="resume" id="resume" accept=".pdf,.doc,.docx">
              </div>
              <small class="form-text text-muted" id="file-name">
                <?php 
                if (!empty($user['resume'])) {
                    echo basename($user['resume']) . ' (currently uploaded)';
                } else {
                    echo 'No file chosen (PDF or Word documents accepted)';
                }
                ?>
              </small>
            </div>
          </div>

          <div class="text-right mt-4">
            <button type="submit" class="btn apply-btns">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/user_footer.php'; ?>

<script src="../assets/js/jquery-3.4.1.min.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/custom.js"></script>
<script>
  $(document).ready(function() {
    // File upload name display
    $('#resume').change(function() {
      var fileName = $(this).val().split('\\').pop();
      $('#file-name').text(fileName || 'No file chosen (PDF or Word documents accepted)');
    });
    
    // Skills preview
    $('#skills').on('input', function() {
      var skills = $(this).val();
      if (skills.trim() === '') {
        $('#skillsDisplay').html('<p class="text-muted">No skills added yet.</p>');
        return;
      }
      
      var skillsArray = skills.split(',');
      var skillsHtml = '';
      
      skillsArray.forEach(function(skill) {
        if (skill.trim() !== '') {
          skillsHtml += '<span class="skill-tag">' + skill.trim() + '</span>';
        }
      });
      
      $('#skillsDisplay').html(skillsHtml);
    });
  });
</script>
</body>
</html>