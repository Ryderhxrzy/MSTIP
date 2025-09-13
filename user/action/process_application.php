<?php
session_start();
include '../../includes/db_config.php';

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php'; // Adjust path as needed

// Set content type for JSON response
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

// Check if required data is provided
if (!isset($_POST['job_id']) || !isset($_POST['user_id']) || empty($_POST['job_id']) || empty($_POST['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required information']);
    exit();
}

$job_id = intval($_POST['job_id']);
$user_id = $_POST['user_id'];
$cover_letter = isset($_POST['cover_letter']) ? trim($_POST['cover_letter']) : null;

try {
    // Check if user has already applied for this job
    $check_sql = "SELECT user_id FROM applications WHERE job_id = ? AND user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("is", $job_id, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'You have already applied for this job']);
        exit();
    }
    
    // Get user information for email
    $user_sql = "SELECT first_name, last_name, email_address FROM user_information WHERE user_id = ?";
    $user_stmt = $conn->prepare($user_sql);
    $user_stmt->bind_param("s", $user_id);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    $user_data = $user_result->fetch_assoc();
    
    if (!$user_data) {
        echo json_encode(['success' => false, 'message' => 'User information not found']);
        exit();
    }
    
    // Get job information for email
    $job_sql = "SELECT job_title, company_name FROM job_listings WHERE job_id = ?";
    $job_stmt = $conn->prepare($job_sql);
    $job_stmt->bind_param("i", $job_id);
    $job_stmt->execute();
    $job_result = $job_stmt->get_result();
    $job_data = $job_result->fetch_assoc();
    
    if (!$job_data) {
        echo json_encode(['success' => false, 'message' => 'Job information not found']);
        exit();
    }
    
    // Insert application into applications table
    $insert_sql = "INSERT INTO applications (user_id, job_id, application_date, status) VALUES (?, ?, NOW(), 'Pending')";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("si", $user_id, $job_id);
    
    if ($insert_stmt->execute()) {
        // SMTP Configuration
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'mstipjobsearch@gmail.com';
            $mail->Password = 'ltvaaptdhgpvjqja';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            // include '../../includes/mail_config.php';
            
            // Recipients
            $mail->setFrom('noreply@mstipjobsearch.com', 'MSTIP Job Search');
            $mail->addAddress($user_data['email_address'], $user_data['first_name'] . ' ' . $user_data['last_name']);
            $mail->addReplyTo('hr@mstipjobsearch.com', 'HR Department');
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = "Application Confirmation - " . $job_data['job_title'];
            
            $message = "
            <html>
            <head>
                <title>Application Confirmation</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: linear-gradient(135deg, #08135c, #0077ff); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
                    .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 8px 8px; }
                    .highlight { background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 20px 0; }
                    .footer { text-align: center; margin-top: 30px; padding: 20px; color: #666; font-size: 0.9em; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>Application Submitted Successfully!</h1>
                    </div>
                    <div class='content'>
                        <h2>Dear " . htmlspecialchars($user_data['first_name']) . " " . htmlspecialchars($user_data['last_name']) . ",</h2>
                        
                        <p>Thank you for your interest in joining our team! We're excited to let you know that your application has been successfully submitted.</p>
                        
                        <div class='highlight'>
                            <h3>Application Details:</h3>
                            <p><strong>Position:</strong> " . htmlspecialchars($job_data['job_title']) . "</p>
                            <p><strong>Company:</strong> " . htmlspecialchars($job_data['company_name']) . "</p>
                            <p><strong>Application Date:</strong> " . date('F j, Y g:i A') . "</p>
                            <p><strong>Status:</strong> Pending Review</p>
                        </div>
                        
                        <h3>What Happens Next?</h3>
                        <ul>
                            <li><strong>Review Process:</strong> Our hiring team will carefully review your application and qualifications</li>
                            <li><strong>Initial Screening:</strong> If your profile matches our requirements, we'll contact you within 3-5 business days</li>
                            <li><strong>Interview Process:</strong> Qualified candidates will be invited for an interview</li>
                            <li><strong>Final Decision:</strong> We'll notify you of our decision regardless of the outcome</li>
                        </ul>
                        
                        <div class='highlight'>
                            <h3>Stay Connected:</h3>
                            <p>We'll keep you updated on your application status via this email address. Please ensure you check your inbox regularly, including your spam folder.</p>
                        </div>
                        
                        <p><strong>Questions?</strong> If you have any questions about your application or the position, feel free to reply to this email.</p>
                        
                        <p>We appreciate your interest in " . htmlspecialchars($job_data['company_name']) . " and look forward to potentially working with you!</p>
                        
                        <p>Best regards,<br>
                        <strong>The Hiring Team</strong><br>
                        " . htmlspecialchars($job_data['company_name']) . "</p>
                    </div>
                    
                    <div class='footer'>
                        <p>This is an automated message. Please do not reply directly to this email unless you have specific questions about your application.</p>
                        <p>&copy; " . date('Y') . " MSTIP Job Search. All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>
            ";
            
            $mail->Body = $message;
            
            // Send email
            if ($mail->send()) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Application submitted successfully! A confirmation email has been sent to your email address.'
                ]);
            } else {
                // Application was saved but email failed - still consider it successful
                echo json_encode([
                    'success' => true, 
                    'message' => 'Application submitted successfully! However, we could not send a confirmation email. Please check your application status in your profile.'
                ]);
            }
        } catch (Exception $e) {
            // Email sending failed but application was saved
            error_log("Email sending failed: " . $mail->ErrorInfo);
            echo json_encode([
                'success' => true, 
                'message' => 'Application submitted successfully! However, we could not send a confirmation email. Please check your application status in your profile.'
            ]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to submit application. Please try again.']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
} finally {
    // Close all prepared statements
    if (isset($check_stmt)) $check_stmt->close();
    if (isset($user_stmt)) $user_stmt->close();
    if (isset($job_stmt)) $job_stmt->close();
    if (isset($insert_stmt)) $insert_stmt->close();
    $conn->close();
}
?>