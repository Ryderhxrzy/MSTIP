    <?php
        session_start();

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
            <a class="navbar-brand" href="index.html">
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
                <li class="nav-item">
                    <a class="nav-link" href="#">
                    <i class="fa fa-user" aria-hidden="true"></i>
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
                </li>
                </ul>
            </div>
            </nav>
        </div>
        </header>