<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Applications - MSTIP Job Search</title>
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

    <link rel="shortcut icon" href="../assets/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="../assets/images/favicon.ico" type="image/x-icon">
</head>
<body>
    <?php 
    include '../includes/user_header.php'; 
    
    // Get user ID from session
    $user_id = $_SESSION['user_id'];
    
    // Query to get application count by status
    $status_count_query = "SELECT status, COUNT(*) as count FROM applications WHERE user_id = '$user_id' GROUP BY status";
    $status_count_result = $conn->query($status_count_query);
    
    $status_counts = [
        'Pending' => 0,
        'Reviewed' => 0,
        'Accepted' => 0,
        'Rejected' => 0
    ];
    
    $total_applications = 0;
    
    if ($status_count_result && $status_count_result->num_rows > 0) {
        while($row = $status_count_result->fetch_assoc()) {
            $status_counts[$row['status']] = $row['count'];
            $total_applications += $row['count'];
        }
    }
    
    // Query to get all applications for the user with job details
    $applications_query = "
        SELECT a.*, j.job_title, j.company_name, j.location, j.image_url, j.application_deadline
        FROM applications a
        JOIN job_listings j ON a.job_id = j.job_id
        WHERE a.user_id = '$user_id'
        ORDER BY a.application_date DESC
    ";
    $applications_result = $conn->query($applications_query);
    ?>

    <div class="hero-area">
        <section class="expert_section layout_padding">
           <div class="container">
            <div class="heading_container heading_center">
                <h2>
                    My Application
                </h2>
                <p class="page-subtitle">Track and manage all your job applications in one place</p>
            </div>
        </section>
    </div>
    
    <div class="container mb-5">
        <!-- Stats Overview -->
        <div class="row">
            <div class="col-md-3">
                <div class="stats-box">
                    <div class="stats-label">Total Applications</div>
                    <div class="stats-number"><?php echo $total_applications; ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-box">
                    <div class="stats-label">Pending</div>
                    <div class="stats-number"><?php echo $status_counts['Pending']; ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-box">
                    <div class="stats-label">Under Review</div>
                    <div class="stats-number"><?php echo $status_counts['Reviewed']; ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-box">
                    <div class="stats-label">Accepted</div>
                    <div class="stats-number"><?php echo $status_counts['Accepted']; ?></div>
                </div>
            </div>
        </div>
        
        <!-- Filters and Sorting -->
        <div class="filter-section">
            <div class="row">
                <div class="col-md-6">
                    <h5>Filter by Status</h5>
                    <div class="d-flex flex-wrap mt-3">
                        <button class="filter-btn active" data-filter="all">All</button>
                        <button class="filter-btn" data-filter="Pending">Pending</button>
                        <button class="filter-btn" data-filter="Reviewed">Under Review</button>
                        <button class="filter-btn" data-filter="Accepted">Accepted</button>
                        <button class="filter-btn" data-filter="Rejected">Rejected</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5>Sort By</h5>
                    <select class="sort-select mt-3 w-100" id="sortSelect">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="company">Company Name</option>
                        <option value="status">Application Status</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="applications-list">
            <?php if ($applications_result && $applications_result->num_rows > 0): ?>
                <?php while($application = $applications_result->fetch_assoc()): ?>
                    <div class="application-card" data-status="<?php echo $application['status']; ?>">
                        <div class="application-header">
                            <div class="d-flex align-items-center">
                                <img src="../assets/<?php echo $application['image_url']; ?>" alt="<?php echo $application['company_name']; ?>" class="company-logo me-3">
                                <div style="margin-left: 10px;">
                                    <h4 class="mb-1"><?php echo $application['job_title']; ?></h4>
                                    <p class="mb-0"><?php echo $application['company_name']; ?> • <?php echo $application['location']; ?></p>
                                </div>
                            </div>
                            <span class="status-badge status-<?php echo strtolower($application['status']); ?>">
                                <?php echo $application['status']; ?>
                            </span>
                        </div>
                        
                        <div class="application-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Application Date:</strong> <?php echo date('F j, Y', strtotime($application['application_date'])); ?></p>
                                    <p><strong>Deadline:</strong> <?php echo date('F j, Y', strtotime($application['application_deadline'])); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Application ID:</strong> #<?php echo $application['application_id']; ?></p>
                                    <p><strong>Job ID:</strong> #<?php echo $application['job_id']; ?></p>
                                </div>
                            </div>
                            
                            <!-- Application Status Timeline -->
                            <div class="application-status-timeline">
                                <div class="status-step <?php echo $application['status'] != 'Pending' ? 'completed' : 'active'; ?>">
                                    <div class="status-indicator">
                                        <i class="fa fa-check"></i>
                                    </div>
                                    <div class="status-label">Applied</div>
                                </div>
                                
                                <div class="status-step <?php echo $application['status'] == 'Reviewed' || $application['status'] == 'Accepted' ? 'completed' : ($application['status'] == 'Pending' ? '' : 'active'); ?>">
                                    <div class="status-indicator">
                                        <i class="fa <?php echo $application['status'] == 'Reviewed' || $application['status'] == 'Accepted' ? 'fa-check' : 'fa-eye'; ?>"></i>
                                    </div>
                                    <div class="status-label">Under Review</div>
                                </div>
                                
                                <div class="status-step <?php echo $application['status'] == 'Accepted' ? 'completed' : ($application['status'] == 'Rejected' ? '' : 'active'); ?>">
                                    <div class="status-indicator">
                                        <i class="fa <?php echo $application['status'] == 'Accepted' ? 'fa-check' : 'fa-user'; ?>"></i>
                                    </div>
                                    <div class="status-label">Decision</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="application-footer">
                            <div class="application-date">
                                Applied <?php echo date('F j, Y', strtotime($application['application_date'])); ?>
                            </div>
                            <div>
                                <button class="view-details-btn me-2" onclick="window.location.href='job_detail.php?id=<?php echo $application['job_id']; ?>'">
                                    <i class="fa fa-eye me-1"></i> View Job Details
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fa fa-file-text-o"></i>
                    <h3>No Applications Yet</h3>
                    <p>You haven't applied to any jobs yet. Start browsing available positions to apply.</p>
                    <a href="job_listings.php" class="btn btn-primary mt-3">
                        <i class="fa fa-briefcase me-2"></i> Browse Jobs
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include_once '../includes/user_footer.php' ?>

    <!-- jQery -->
    <script src="../assets/js/jquery-3.4.1.min.js"></script>
    <!-- bootstrap js -->
    <script src="../assets/js/bootstrap.js"></script>
    <!-- nice select -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js" integrity="sha256-Zr3vByTlMGQhvMfgkQ5BtWRSKBGa2QlspKYJnkjZTmo=" crossorigin="anonymous"></script>
    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <!-- custom js -->
    <script src="../assets/js/custom.js"></script>
    
    <script>
    // Filter applications by status
    document.querySelectorAll('.filter-btn').forEach(button => {
        button.addEventListener('click', function() {
            // Update active button
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            const applications = document.querySelectorAll('.application-card');
            
            applications.forEach(card => {
                if (filter === 'all' || card.getAttribute('data-status') === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
    
    // Sort applications
    document.getElementById('sortSelect').addEventListener('change', function() {
        const sortBy = this.value;
        const applicationsContainer = document.querySelector('.applications-list');
        const applications = Array.from(document.querySelectorAll('.application-card'));
        
        applications.sort((a, b) => {
            if (sortBy === 'newest') {
                return new Date(b.querySelector('.application-date').textContent.replace('Applied ', '')) - 
                       new Date(a.querySelector('.application-date').textContent.replace('Applied ', ''));
            } else if (sortBy === 'oldest') {
                return new Date(a.querySelector('.application-date').textContent.replace('Applied ', '')) - 
                       new Date(b.querySelector('.application-date').textContent.replace('Applied ', ''));
            } else if (sortBy === 'company') {
                return a.querySelector('h4').textContent.localeCompare(b.querySelector('h4').textContent);
            } else if (sortBy === 'status') {
                return a.getAttribute('data-status').localeCompare(b.getAttribute('data-status'));
            }
            return 0;
        });
        
        // Remove all applications and re-add in sorted order
        applications.forEach(card => {
            applicationsContainer.appendChild(card);
        });
    });
    </script>
</body>
</html>