<?php
session_start();
header('Content-Type: application/json');

include_once 'config/db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        $required_fields = ['email', 'password', 'first_name', 'last_name', 'phone', 'course', 'year_grad', 'linkedin_profile'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        // Validate email format
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }

        // Escape strings to prevent SQL injection
        $email = mysqli_real_escape_string($conn, $_POST['email']);

        // Check if email already exists
        $query = "SELECT COUNT(*) as count FROM user WHERE email_address = '$email'";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            throw new Exception('Database error: ' . mysqli_error($conn));
        }
        $row = mysqli_fetch_assoc($result);
        if ($row['count'] > 0) {
            throw new Exception('Email address already registered');
        }

        // Generate unique user_id
        $user_id = generateUserId($conn);
        
        // Hash password
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        // Handle file upload
        $resume_filename = null;
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
            $resume_filename = handleFileUpload($_FILES['resume'], $user_id);
        } else {
            throw new Exception('Resume file is required');
        }

        // Begin transaction
        mysqli_autocommit($conn, false);

        // Escape all input data
        $user_id_escaped = mysqli_real_escape_string($conn, $user_id);
        $email_escaped = mysqli_real_escape_string($conn, $_POST['email']);
        $password_escaped = mysqli_real_escape_string($conn, $hashed_password);
        $first_name_escaped = mysqli_real_escape_string($conn, $_POST['first_name']);
        $middle_name_escaped = !empty($_POST['middle_name']) ? mysqli_real_escape_string($conn, $_POST['middle_name']) : null;
        $last_name_escaped = mysqli_real_escape_string($conn, $_POST['last_name']);
        $phone_escaped = mysqli_real_escape_string($conn, $_POST['phone']);
        $course_escaped = mysqli_real_escape_string($conn, $_POST['course']);
        $year_grad_escaped = (int)$_POST['year_grad'];
        $skills_escaped = !empty($_POST['skills']) ? mysqli_real_escape_string($conn, $_POST['skills']) : null;
        $resume_escaped = mysqli_real_escape_string($conn, $resume_filename);
        $linkedin_escaped = mysqli_real_escape_string($conn, $_POST['linkedin_profile']);

        // Insert into user table
        $query = "INSERT INTO user (user_id, email_address, password, user_type) 
                  VALUES ('$user_id_escaped', '$email_escaped', '$password_escaped', 'User')";
        
        if (!mysqli_query($conn, $query)) {
            throw new Exception('Failed to insert user: ' . mysqli_error($conn));
        }

        // Insert into user_information table
        $middle_name_part = $middle_name_escaped ? "'$middle_name_escaped'" : "NULL";
        $skills_part = $skills_escaped ? "'$skills_escaped'" : "NULL";
        
        $query = "INSERT INTO user_information (
                    user_id, email_address, first_name, middle_name, last_name, 
                    phone_number, course, year_graduated, skills, resume, linkedin_profile
                  ) VALUES (
                    '$user_id_escaped', '$email_escaped', '$first_name_escaped', $middle_name_part, '$last_name_escaped',
                    '$phone_escaped', '$course_escaped', $year_grad_escaped, $skills_part, '$resume_escaped', '$linkedin_escaped'
                  )";
        
        if (!mysqli_query($conn, $query)) {
            throw new Exception('Failed to insert user information: ' . mysqli_error($conn));
        }

        // Commit transaction
        mysqli_commit($conn);
        mysqli_autocommit($conn, true);

        echo json_encode([
            'success' => true, 
            'message' => 'Registration successful! Welcome to MSTIP.',
            'user_id' => $user_id
        ]);

    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($conn);
        mysqli_autocommit($conn, true);
        
        // Clean up uploaded file if exists
        if (isset($resume_filename) && file_exists("./files/" . $resume_filename)) {
            unlink("./files/" . $resume_filename);
        }

        echo json_encode([
            'success' => false, 
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

function generateUserId($conn) {
    do {
        $number = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $user_id = 'U' . $number;
        
        $user_id_escaped = mysqli_real_escape_string($conn, $user_id);
        $query = "SELECT COUNT(*) as count FROM user WHERE user_id = '$user_id_escaped'";
        $result = mysqli_query($conn, $query);
        
        if (!$result) {
            throw new Exception('Database error: ' . mysqli_error($conn));
        }
        
        $row = mysqli_fetch_assoc($result);
        $exists = $row['count'] > 0;
    } while ($exists);
    
    return $user_id;
}

function handleFileUpload($file, $user_id) {
    $upload_dir = 'files/';
    
    // Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0777, true)) {
            throw new Exception('Failed to create upload directory');
        }
    }

    // Validate file
    $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    $max_size = 5 * 1024 * 1024; // 5MB

    if (!in_array($file['type'], $allowed_types)) {
        throw new Exception('Invalid file type. Only PDF, DOC, and DOCX files are allowed.');
    }

    if ($file['size'] > $max_size) {
        throw new Exception('File size exceeds 5MB limit');
    }

    // Generate unique filename
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = $user_id . '_resume_' . time() . '.' . $file_extension;
    $filepath = $upload_dir . $filename;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        throw new Exception('Failed to upload resume file');
    }

    return $filename;
}
?>