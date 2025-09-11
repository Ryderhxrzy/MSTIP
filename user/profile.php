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

$id_number = $_SESSION['user_id'];

// Fetch user info
$sql = "SELECT * FROM user_information WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id_number);
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
  $contact_number = $_POST['contact_number'];
  $course = $_POST['course'];
  $year = $_POST['year'];

  $update = $conn->prepare("UPDATE student_info 
    SET first_name=?, middle_name=?, last_name=?, contact_number=?, course=?, year=? 
    WHERE id_number=?");
  $update->bind_param("sssssss", $first_name, $middle_name, $last_name, $contact_number, $course, $year, $id_number);
  $update->execute();

  header("Location: profile.php?updated=1");
  exit;
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
    .profile-section {
      background: #f8f9fa;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 20px;
    }
    .profile-section h4 {
      font-weight: 600;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
<?php include '../includes/user_header.php'; ?>

<div class="hero-area"></div>

<div class="container py-5">
  <h2 class="mb-4">My Profile</h2>
  <?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success">Profile updated successfully.</div>
  <?php endif; ?>

  <form method="post" class="profile-section">
    <div class="row">
      <div class="form-group col-md-4">
        <label for="id_number">ID Number</label>
        <input type="text" class="form-control" id="id_number" value="<?php echo htmlspecialchars($user['id_number']); ?>" disabled>
      </div>
      <div class="form-group col-md-4">
        <label for="email">Email</label>
        <input type="text" class="form-control" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
      </div>
    </div>

    <div class="row">
      <div class="form-group col-md-4">
        <label for="first_name">First Name</label>
        <input type="text" class="form-control" name="first_name" id="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
      </div>
      <div class="form-group col-md-4">
        <label for="middle_name">Middle Name</label>
        <input type="text" class="form-control" name="middle_name" id="middle_name" value="<?php echo htmlspecialchars($user['middle_name']); ?>">
      </div>
      <div class="form-group col-md-4">
        <label for="last_name">Last Name</label>
        <input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
      </div>
    </div>

    <div class="row">
      <div class="form-group col-md-4">
        <label for="contact_number">Contact Number</label>
        <input type="text" class="form-control" name="contact_number" id="contact_number" value="<?php echo htmlspecialchars($user['contact_number']); ?>">
      </div>
      <div class="form-group col-md-4">
        <label for="course">Course</label>
        <input type="text" class="form-control" name="course" id="course" value="<?php echo htmlspecialchars($user['course']); ?>">
      </div>
      <div class="form-group col-md-4">
        <label for="year">Year</label>
        <input type="text" class="form-control" name="year" id="year" value="<?php echo htmlspecialchars($user['year']); ?>">
      </div>
    </div>

    <button type="submit" class="btn btn-primary">Update Profile</button>
  </form>
</div>

<?php include '../includes/user_footer.php'; ?>

<script src="../assets/js/jquery-3.4.1.min.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/custom.js"></script>
</body>
</html>
