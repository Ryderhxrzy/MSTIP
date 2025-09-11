<?php
session_start();
  include_once '../includes/db_config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Job - MSTIP Job Search</title>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
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

    <?php include '../includes/user_header.php'; ?>
    <div class="hero-area">
      <section class="job_section layout_padding">
        <div class="container">
          <div class="heading_container heading_center">
            <h2>JOBS</h2>
          </div>

          <div class="job_container">
            <h4 class="job_heading">Available Jobs</h4>
            <div class="row">
              <?php
              // Fixed query to join with regions table and get region_code
              $query = "SELECT jl.*, r.region_code 
                        FROM job_listings jl 
                        JOIN regions r ON jl.region_id = r.region_id 
                        ORDER BY jl.posted_date DESC";
              $result = mysqli_query($conn, $query);

              if(mysqli_num_rows($result) > 0){
                  while($row = mysqli_fetch_assoc($result)){
                      ?>
                      <div class="col-lg-6">
                        <div class="box">
                          <div class="job_content-box">
                            <div class="img-box">
                              <img src="<?php echo !empty('../assets/' . $row['image_url']) ? '../assets/' . $row['image_url'] : '../assets/images/default.png'; ?>" alt="Job Image">
                            </div>
                            <div class="detail-box">
                              <h5><?php echo htmlspecialchars($row['company_name']); ?></h5>
                              <p><?php echo htmlspecialchars($row['job_title'] . ' / ' . $row['slots_available'] . ' slot(s) only'); ?></p>
                              <div class="detail-info">
                                <h6>
                                  <i class="fa fa-map-marker" aria-hidden="true"></i>
                                  <span><?php echo htmlspecialchars($row['region_code']); ?></span>
                                </h6>
                                <h6>
                                  <i class="fa fa-money" aria-hidden="true"></i>
                                  <span><?php echo htmlspecialchars($row['salary_range']); ?></span>
                                </h6>
                                <h6>
                                  <i class="fa fa-clock-o" aria-hidden="true"></i>
                                  <span><?php echo htmlspecialchars($row['job_type_shift']); ?></span>
                                </h6>
                              </div>
                            </div>
                          </div>
                          <div class="option-box">
                            <a href="job_detail.php?id=<?php echo $row['job_id']; ?>" class="apply-btn">View Details</a>
                          </div>
                        </div>
                      </div>
                      <?php
                  }
              } else {
                  echo "<p class='text-center'>No job listings available right now.</p>";
              }
              ?>
            </div>
          </div>
        </div>
      </section>
    </div>
    
    <?php include_once '../includes/user_footer.php' ?>

    <!-- jQery -->
    <script src="../assets/js/jquery-3.4.1.min.js"></script>
    <!-- bootstrap js -->
    <script src="../assets/js/bootstrap.js"></script>
    <!-- nice select -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js" integrity="sha256-Zr3vByTlMGQhvMfgkQ5BtWRSKBGa2QlspKYJnkjZTmo=" crossorigin="anonymous"></script>
    <!-- custom js -->
    <script src="../assets/js/custom.js"></script>
</body>
</html>