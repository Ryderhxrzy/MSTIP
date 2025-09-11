<?php
  session_Start();
  include_once '../includes/db_config.php';
  $fullname = $_SESSION['first_name'] . ' ' . ($_SESSION['middle_name'] ? $_SESSION['middle_name'] . ' ' : '') . $_SESSION['last_name'];                            
  
  // Initialize search variables
  $searchLocation = '';
  $searchPosition = '';
  $searchJobType = '';
  $searchResults = [];
  $hasSearch = false;
  
  // Check if search form was submitted
  if ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['location']) || isset($_GET['position']) || isset($_GET['job_type']))) {
    $hasSearch = true;
    $searchLocation = isset($_GET['location']) ? trim($_GET['location']) : '';
    $searchPosition = isset($_GET['position']) ? trim($_GET['position']) : '';
    $searchJobType = isset($_GET['job_type']) ? trim($_GET['job_type']) : '';
    
    // Build the search query with OR conditions
    $searchQuery = "
      SELECT jl.*, r.region_code, r.region_name 
      FROM job_listings jl
      JOIN regions r ON jl.region_id = r.region_id
      WHERE 1=0
    ";
    
    $params = [];
    $types = '';
    $conditions = [];
    
    // Search by location - match with region_id
    if (!empty($searchLocation)) {
      $conditions[] = "r.region_id = ?";
      $params[] = $searchLocation;
      $types .= 'i';
    }
    
    // Search by position - match job_title and job_position
    if (!empty($searchPosition)) {
      $conditions[] = "(jl.job_title LIKE ? OR jl.job_position LIKE ?)";
      $positionParam = "%$searchPosition%";
      $params[] = $positionParam;
      $params[] = $positionParam;
      $types .= 'ss';
    }
    
    // Search by job type - match job_type_shift
    if (!empty($searchJobType)) {
      $conditions[] = "jl.job_type_shift LIKE ?";
      $jobTypeParam = "%$searchJobType%";
      $params[] = $jobTypeParam;
      $types .= 's';
    }
    
    // If we have conditions, join them with OR
    if (!empty($conditions)) {
      $searchQuery = "
        SELECT jl.*, r.region_code, r.region_name 
        FROM job_listings jl
        JOIN regions r ON jl.region_id = r.region_id
        WHERE " . implode(' OR ', $conditions) . "
        ORDER BY jl.posted_date DESC
      ";
    }
    
    // Debug: Add this temporarily to see what's happening
    // echo "Search Query: " . $searchQuery . "<br>";
    // echo "Params: " . print_r($params, true) . "<br>";
    // echo "Types: " . $types . "<br>";
    
    // Prepare and execute the search query
    $stmt = $conn->prepare($searchQuery);
    
    if ($stmt && !empty($conditions)) {
      if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
      }
      
      $stmt->execute();
      $result = $stmt->get_result();
      
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $searchResults[] = $row;
        }
      }
      
      $stmt->close();
    }
  }
  
  // Get featured jobs (only if not searching)
  $featuredIds = [];
  $featuredJobs = [];
  $normalJobs = [];
  
  if (!$hasSearch) {
    // Query featured jobs
    $featuredQuery = "
      SELECT jl.*, r.region_code, r.region_name
      FROM job_listings jl
      JOIN regions r ON jl.region_id = r.region_id
      ORDER BY jl.posted_date DESC
      LIMIT 2
    ";
    $featuredResult = $conn->query($featuredQuery);

    if ($featuredResult->num_rows > 0) {
      while ($row = $featuredResult->fetch_assoc()) {
        $featuredIds[] = $row['job_id'];
        $featuredJobs[] = $row;
      }
    }

    // Query normal jobs excluding featured
    $notInClause = "";
    if (!empty($featuredIds)) {
      $notInClause = "WHERE jl.job_id NOT IN (" . implode(',', $featuredIds) . ")";
    }

    $jobQuery = "
      SELECT jl.*, r.region_code, r.region_name
      FROM job_listings jl
      JOIN regions r ON jl.region_id = r.region_id
      $notInClause
      ORDER BY jl.posted_date DESC
      LIMIT 4
    ";
    $jobResult = $conn->query($jobQuery);

    if ($jobResult->num_rows > 0) {
      while ($row = $jobResult->fetch_assoc()) {
        $normalJobs[] = $row;
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home - MSTIP Job Search</title>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="MSTIP, graduate, job search, Philippines, employment" />
  <meta name="description" content="MSTIP Graduate Job Search platform connecting graduates with employment opportunities" />
  <meta name="author" content="MSTIP Team" />
  <title>MSTIP Graduate Job Search</title>
  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css" />
  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" rel="stylesheet">
  <!-- font awesome style -->
  <link href="../assets/css/font-awesome.min.css" rel="stylesheet" />
  <!-- nice select -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha256-mLBIhmBvigTFWPSCtvdu6a76T+3Xyt+K571hupeFLg4=" crossorigin="anonymous" />
  <!-- Custom styles for this template -->
  <link href="../assets/css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="../assets/css/responsive.css" rel="stylesheet" />
</head>

<body>

  <div class="hero_area">
    <?php include '../includes/user_header.php'; ?>
    <!-- end header section -->
    <!-- slider section -->
    <section class="slider_section">
      <div class="container">
        <div class="row">
          <div class="col-lg-7 col-md-8 mx-auto">
            <div class="detail-box">
              <h1>MSTIP Graduate Job Search</h1>
            </div>
          </div>
        </div>
        <div class="find_container">
          <div class="container">
            <div class="row">
              <div class="col">
                <form method="GET" action="">
                  <div class="form-row">
                    <div class="form-group col-lg-2">
                      <input type="text" class="form-control" id="inputPatientName" value="<?= $fullname ?>" disabled>
                    </div>
                    <div class="form-group col-lg-3">
                      <select name="location" class="form-control wide" id="inputLocation">
                        <option value="">Select Location</option>
                        <?php
                        $sql = "SELECT region_id, region_code, region_name FROM regions ORDER BY region_name ASC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $selected = ($searchLocation == $row['region_id']) ? 'selected' : '';
                                echo '<option value="' . $row['region_id'] . '" ' . $selected . '>' . $row['region_name'] . '</option>';
                            }
                        }
                        ?>
                      </select>
                    </div>
                    <div class="form-group col-lg-2">
                      <input type="text" class="form-control" name="position" placeholder="Position / Job Title" value="<?= htmlspecialchars($searchPosition) ?>">
                    </div>
                    <div class="form-group col-lg-2">
                      <select name="job_type" class="form-control wide" id="inputJobType">
                        <option value="">Job Type</option>
                        <option value="Full-Time" <?= ($searchJobType == 'Full-Time') ? 'selected' : '' ?>>Full Time</option>
                        <option value="Part-Time" <?= ($searchJobType == 'Part-Time') ? 'selected' : '' ?>>Part Time</option>
                      </select>
                    </div>
                    <div class="form-group col-lg-3">
                      <div class="btn-box">
                        <button type="submit" class="btn">Search Now</button>
                        <a href="?" class="btn btn-secondary ml-2">Clear</a>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- end slider section -->
  </div>

  <?php if ($hasSearch): ?>
  <!-- Search Results Section -->
  <section class="job_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>SEARCH RESULTS</h2>
        <p><?= count($searchResults) ?> job(s) found</p>
      </div>

      <div class="job_container">
        <div class="row">
          <?php
          if (!empty($searchResults)) {
            foreach ($searchResults as $job) {
              echo '
              <div class="col-lg-6">
                <div class="box">
                  <div class="job_content-box">
                    <div class="img-box">
                      <img src="../assets/'.htmlspecialchars($job['image_url']).'" alt="'.htmlspecialchars($job['company_name']).'">
                    </div>
                    <div class="detail-box">
                      <h5>'.htmlspecialchars($job['company_name']).'</h5>
                      <p>'.htmlspecialchars($job['job_title']).' / '.htmlspecialchars($job['slots_available']).' slot(s) only</p>
                      <div class="detail-info">
                        <h6>
                          <i class="fa fa-map-marker" aria-hidden="true"></i>
                          <span>'.htmlspecialchars($job['region_code']).'</span>
                        </h6>
                        <h6>
                          <i class="fa fa-money" aria-hidden="true"></i>
                          <span>'.htmlspecialchars($job['salary_range']).'</span>
                        </h6>
                        <h6>
                          <i class="fa fa-clock-o" aria-hidden="true"></i>
                          <span>'.htmlspecialchars($job['job_type_shift']).'</span>
                        </h6>
                      </div>
                    </div>
                  </div>
                  <div class="option-box">
                    <a href="job_detail.php?id='.$job['job_id'].'" class="apply-btn">View Details</a>
                  </div>
                </div>
              </div>';
            }
          } else {
            echo '<div class="col-12"><p class="text-center">No jobs found matching your search criteria.</p></div>';
          }
          ?>
        </div>
      </div>

      <div class="btn-box">
        <a href="job.php">View All Jobs</a>
      </div>
    </div>
  </section>
  <?php else: ?>
  <!-- job section -->
  <section class="job_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>FEATURED JOBS</h2>
      </div>
      
      <!-- Featured Jobs -->
      <div class="job_container">
        <h4 class="job_heading">Featured Jobs</h4>
        <div class="row">
          <?php
          if (!empty($featuredJobs)) {
            foreach ($featuredJobs as $job) {
              echo '
              <div class="col-lg-6">
                <div class="box">
                  <div class="job_content-box">
                    <div class="img-box">
                      <img src="../assets/'.htmlspecialchars($job['image_url']).'" alt="'.htmlspecialchars($job['company_name']).'">
                    </div>
                    <div class="detail-box">
                      <h5>'.htmlspecialchars($job['company_name']).'</h5>
                      <p>'.htmlspecialchars($job['job_title']).' / '.htmlspecialchars($job['slots_available']).' slot(s) only</p>
                      <div class="detail-info">
                        <h6>
                          <i class="fa fa-map-marker" aria-hidden="true"></i>
                          <span>'.htmlspecialchars($job['region_code']).'</span>
                        </h6>
                        <h6>
                          <i class="fa fa-money" aria-hidden="true"></i>
                          <span>'.htmlspecialchars($job['salary_range']).'</span>
                        </h6>
                        <h6>
                          <i class="fa fa-clock-o" aria-hidden="true"></i>
                          <span>'.htmlspecialchars($job['job_type_shift']).'</span>
                        </h6>
                      </div>
                    </div>
                  </div>
                  <div class="option-box">
                    <a href="job_detail.php?id='.$job['job_id'].'" class="apply-btn">View Details</a>
                  </div>
                </div>
              </div>';
            }
          } else {
            echo '<p>No featured jobs available.</p>';
          }
          ?>
        </div>
      </div>

      <!-- Normal Jobs -->
      <div class="job_container">
        <h4 class="job_heading">Jobs</h4>
        <div class="row">
          <?php
          if (!empty($normalJobs)) {
            foreach ($normalJobs as $job) {
              echo '
              <div class="col-lg-6">
                <div class="box">
                  <div class="job_content-box">
                    <div class="img-box">
                      <img src="../assets/'.htmlspecialchars($job['image_url']).'" alt="'.htmlspecialchars($job['company_name']).'">
                    </div>
                    <div class="detail-box">
                      <h5>'.htmlspecialchars($job['company_name']).'</h5>
                      <p>'.htmlspecialchars($job['job_title']).' / '.htmlspecialchars($job['slots_available']).' slot(s) only</p>
                      <div class="detail-info">
                        <h6>
                          <i class="fa fa-map-marker" aria-hidden="true"></i>
                          <span>'.htmlspecialchars($job['region_code']).'</span>
                        </h6>
                        <h6>
                          <i class="fa fa-money" aria-hidden="true"></i>
                          <span>'.htmlspecialchars($job['salary_range']).'</span>
                        </h6>
                        <h6>
                          <i class="fa fa-clock-o" aria-hidden="true"></i>
                          <span>'.htmlspecialchars($job['job_type_shift']).'</span>
                        </h6>
                      </div>
                    </div>
                  </div>
                  <div class="option-box">
                    <a href="job_detail.php?id='.$job['job_id'].'" class="apply-btn">View Details</a>
                  </div>
                </div>
              </div>';
            }
          } else {
            echo '<p>No jobs available.</p>';
          }
          ?>
        </div>
      </div>

      <div class="btn-box">
        <a href="job.php">View All</a>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- end job section -->

  <!-- expert section -->
  <section class="expert_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>Our Team</h2>
        <br>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="our-team">
              <div class="picture">
                <img class="img-fluid" src="../assets/images/MAANDREA.png" alt="Ma. Andrea">
              </div>
              <div class="team-content">
                <h3 class="name">Ma. Andrea</h3>
                <h4 class="title">Programmer (Leader)</h4>
              </div>
              <ul class="social">
                <li><a href="https://www.facebook.com/maz.andrea.2024" class="fa fa-facebook" aria-hidden="true" target="_blank" rel="noopener"></a></li>
                <li><a href="https://www.instagram.com/mmxder" class="fa fa-instagram" aria-hidden="true" target="_blank" rel="noopener"></a></li>
              </ul>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="our-team">
              <div class="picture">
                <img class="img-fluid" src="../assets/images/VINCEP.png" alt="Vince Peter">
              </div>
              <div class="team-content">
                <h3 class="name">Vince Peter</h3>
                <h4 class="title">Researcher</h4>
              </div>
              <ul class="social">
                <li><a href="https://www.facebook.com/vincepeter.ventivingo.1" class="fa fa-facebook" aria-hidden="true" target="_blank" rel="noopener"></a></li>
                <li><a href="https://www.instagram.com/vincepeterventivingo" class="fa fa-instagram" aria-hidden="true" target="_blank" rel="noopener"></a></li>
              </ul>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="our-team">
              <div class="picture">
                <img class="img-fluid" src="../assets/images/FAITHANN.png" alt="Faith Ann">
              </div>
              <div class="team-content">
                <h3 class="name">Faith Ann</h3>
                <h4 class="title">Designer</h4>
              </div>
              <ul class="social">
                <li><a href="https://www.facebook.com/faithann.torres.94" class="fa fa-facebook" aria-hidden="true" target="_blank" rel="noopener"></a></li>
                <li><a href="https://www.instagram.com/faithanntorres" class="fa fa-instagram" aria-hidden="true" target="_blank" rel="noopener"></a></li>
              </ul>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="our-team">
              <div class="picture">
                <img class="img-fluid" src="../assets/images/ADRIAN1.png" alt="Adrian">
              </div>
              <div class="team-content">
                <h3 class="name">Adrian</h3>
                <h4 class="title">Support</h4>
              </div>
              <ul class="social">
                <li><a href="https://www.facebook.com/adrian.dumlao.3386" class="fa fa-facebook" aria-hidden="true" target="_blank" rel="noopener"></a></li>
                <li><a href="https://www.instagram.com/adriandumlao2002" class="fa fa-instagram" aria-hidden="true" target="_blank" rel="noopener"></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end expert section -->

    <?php include_once '../includes/user_footer.php' ?>

    <script src="../assets/js/jquery-3.4.1.min.js"></script>
    <!-- bootstrap js -->
    <script src="../assets/js/bootstrap.js"></script>
    <!-- nice select -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js" integrity="sha256-Zr3vByTlMGQhvMfgkQ5BtWRSKBGa2QlspKYJnkjZTmo=" crossorigin="anonymous"></script>
    <!-- custom js -->
    <script src="../assets/js/custom.js"></script>
</body>
</html>