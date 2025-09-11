<?php
session_start();
// job_detail.php
include '../includes/db_config.php'; // your DB connection

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Invalid Job ID.');
}
$job_id = intval($_GET['id']);

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
  <style>
    * {
      transition: all 0.3s ease;
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
      <a href="apply.php?job_id=<?php echo $job['job_id']; ?>" class="apply-btns">
        <i class="fa fa-paper-plane"></i> Apply Now
      </a>
      <p style="margin-top: 15px; color: #666; font-size: 0.9rem;">
        Ready to take the next step in your career?
      </p>
    </div>
  </div>
</div>

<?php include '../includes/user_footer.php'; ?>

<script src="../assets/js/jquery-3.4.1.min.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/custom.js"></script>


</body>
</html>