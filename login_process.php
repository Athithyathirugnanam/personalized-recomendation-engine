<?php
/**
 * User Login Process
 * Handles user login form submission
 * Validates credentials and creates session
 */

// Start session first
session_start();

// Include database connection
require_once 'config/db.php';

// Initialize response array
$response = array('success' => false, 'message' => '');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get and sanitize form data
    $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $remember_me = isset($_POST['remember_me']) ? true : false;
    
    // Validation
    $errors = array();
    
    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!validate_email($email)) {
        $errors[] = "Invalid email format";
    }
    
    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    
    // If no validation errors, proceed with login
    if (empty($errors)) {
        
        // Prepare SQL statement to get user data including role
        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Check if user exists
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                
                // Password is correct, create session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'] ?? 'buyer';
                
                // Set remember me cookie (optional)
                if ($remember_me) {
                    setcookie('remember_email', $email, time() + (86400 * 30), "/"); // 30 days
                }
                
                // Update last login time (optional - you can add this column to users table)
                // $update_stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                // $update_stmt->bind_param("i", $user['id']);
                // $update_stmt->execute();
                // $update_stmt->close();
                
                $response['success'] = true;
                $response['message'] = "Login successful! Redirecting to dashboard...";
                
            } else {
                $errors[] = "Invalid email or password";
            }
            
        } else {
            $errors[] = "Invalid email or password";
        }
        
        $stmt->close();
    }
    
    // If there are errors, set error response
    if (!empty($errors)) {
        $response['message'] = implode("<br>", $errors);
    }
    
} else {
    $response['message'] = "Invalid request method";
}

// Close database connection
$conn->close();

// Return JSON response for AJAX calls
if (isset($_POST['ajax']) && $_POST['ajax'] == '1') {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// For non-AJAX submissions, redirect with message
if ($response['success']) {
    $_SESSION['success_message'] = $response['message'];
    // Redirect based on user role
    $redirect_page = ($_SESSION['user_role'] === 'seller') ? 'seller_dashboard.php' : 'buyer_dashboard.php';
    redirect($redirect_page);
} else {
    $_SESSION['error_message'] = $response['message'];
    redirect('login.html');
}

?>
