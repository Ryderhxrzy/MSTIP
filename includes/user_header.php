    <?php
        include_once '../includes/db_config.php';

        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header("Location: ../login.php");
            exit();
        }
    ?>
    
    <!-- header section strats -->
    <header class="header_section">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg custom_nav-container ">
            <a class="navbar-brand" href="home.php">
                <span>
                <img src="../assets/images/mstip_logo.png" class="mstip_logo" alt="logo" width="110" height="90">
                </span>
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class=""> </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav  ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="job.php">Jobs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about-us.php">About Us</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="profile-avatar-small mr-2">
                                <?php 
                                    $firstInitial = isset($_SESSION['first_name']) ? substr($_SESSION['first_name'], 0, 1) : 'G';
                                    $lastInitial = isset($_SESSION['last_name']) ? substr($_SESSION['last_name'], 0, 1) : '';
                                    echo strtoupper($firstInitial . $lastInitial); 
                                ?>
                            </div>
                            <span id="user-display">
                                <?php 
                                    if (isset($_SESSION['first_name'], $_SESSION['last_name'])) {
                                        $fullname = $_SESSION['first_name'] . ' ' . ($_SESSION['middle_name'] ? $_SESSION['middle_name'] . ' ' : '') . $_SESSION['last_name'];
                                        echo htmlspecialchars($fullname);
                                    } else {
                                        echo "Guest";
                                    }
                                ?>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown" style="min-width:220px; box-shadow:0 4px 12px rgba(0,0,0,0.15); border-radius:10px;">
                            <div class="dropdown-header text-center" style="background:#f5f5f5; padding:15px 10px;">
                                <div class="profile-avatar-medium mx-auto mb-2">
                                    <?php 
                                        $firstInitial = isset($_SESSION['first_name']) ? substr($_SESSION['first_name'], 0, 1) : 'G';
                                        $lastInitial = isset($_SESSION['last_name']) ? substr($_SESSION['last_name'], 0, 1) : '';
                                        echo strtoupper($firstInitial . $lastInitial); 
                                    ?>
                                </div>
                                <div style="font-weight:bold; margin-top:8px;">
                                    <?php echo isset($fullname) ? htmlspecialchars($fullname) : "Guest"; ?>
                                </div>
                                <div style="font-size:12px; color:#888;">
                                    <?php echo isset($_SESSION['email_address']) ? htmlspecialchars($_SESSION['email_address']) : ""; ?>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="profile.php" style="font-weight:500;">
                                <i class="fa fa-id-card mr-2"></i> Profile
                            </a>
                            <a class="dropdown-item" href="../logout.php" style="font-weight:500;">
                                <i class="fa fa-sign-out mr-2"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
            </nav>
        </div>
        </header>