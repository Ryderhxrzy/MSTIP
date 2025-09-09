<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config/db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        if (empty($_POST['email']) || empty($_POST['password'])) {
            throw new Exception('Please fill in all required fields');
        }

        // Validate email format
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }

        // Escape input to prevent SQL injection
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];

        // Check if user exists and get user data
        $query = "SELECT u.user_id, u.email_address, u.password, u.user_type, u.status,
                         ui.first_name, ui.last_name, ui.middle_name
                  FROM user u 
                  LEFT JOIN user_information ui ON u.user_id = ui.user_id 
                  WHERE u.email_address = '$email'";
        
        $result = mysqli_query($conn, $query);
        
        if (!$result) {
            throw new Exception('Database error: ' . mysqli_error($conn));
        }

        $user = mysqli_fetch_assoc($result);

        if (!$user) {
            throw new Exception('Invalid email or password');
        }

        // Check if account is active
        if ($user['status'] !== 'Active') {
            throw new Exception('Your account has been deactivated. Please contact support.');
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            throw new Exception('Invalid email or password');
        }

        // Set session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email_address'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['middle_name'] = $user['middle_name'];
        $_SESSION['logged_in'] = true;

        // Handle "Remember Me" functionality
        if (isset($_POST['remember_me']) && $_POST['remember_me'] == '1') {
            // Create a secure remember token
            $remember_token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
            
            // Store token in database (you might want to create a remember_tokens table)
            $user_id_escaped = mysqli_real_escape_string($conn, $user['user_id']);
            $token_escaped = mysqli_real_escape_string($conn, $remember_token);
            
            // For now, we'll just set a long-lasting cookie
            setcookie('remember_token', $remember_token, time() + (30 * 24 * 60 * 60), '/');
            setcookie('user_id', $user['user_id'], time() + (30 * 24 * 60 * 60), '/');
        }

        // Determine redirect based on user type
        $redirect_url = 'dashboard.php';
        switch ($user['user_type']) {
            case 'Admin':
                $redirect_url = 'admin/dashboard.php';
                break;
            case 'Employer':
                $redirect_url = 'employer/dashboard.php';
                break;
            default:
                $redirect_url = 'student/dashboard.php';
                break;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Login successful! Welcome back.',
            'user' => [
                'user_id' => $user['user_id'],
                'name' => trim($user['first_name'] . ' ' . $user['last_name']),
                'email' => $user['email_address'],
                'user_type' => $user['user_type']
            ],
            'redirect_url' => $redirect_url
        ]);

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>