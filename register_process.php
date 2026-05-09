<?php
/**
 * User Registration Process
 * Handles user registration form submission
 * Validates input, checks for duplicates, and stores user data
 */

// Include database connection
require_once 'config/db.php';

// Initialize response array
$response = array('success' => false, 'message' => '');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get and sanitize form data
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $interests = isset($_POST['interests']) ? $_POST['interests'] : array();
    
    // Validation
    $errors = array();
    
    // Validate name
    if (empty($name)) {
        $errors[] = "Name is required";
    } elseif (strlen($name) < 3) {
        $errors[] = "Name must be at least 3 characters long";
    }
    
    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!validate_email($email)) {
        $errors[] = "Invalid email format";
    }
    
    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long";
    }
    
    // Validate interests
    if (empty($interests)) {
        $errors[] = "Please select at least one interest";
    }
    
    // Check if email already exists
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $errors[] = "Email already registered. Please use a different email or login.";
        }
        $stmt->close();
    }
    
    // If no errors, proceed with registration
    if (empty($errors)) {
        
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Begin transaction
        $conn->begin_transaction();
        
        try {
            // Insert user into database
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $user_id = $conn->insert_id;
                $stmt->close();
                
                // Insert user interests
                $stmt = $conn->prepare("INSERT INTO user_interests (user_id, interest_id) VALUES (?, ?)");
                
                foreach ($interests as $interest_name) {
                    // Get interest ID from interest name
                    $interest_stmt = $conn->prepare("SELECT id FROM interests WHERE interest_name = ?");
                    $interest_stmt->bind_param("s", $interest_name);
                    $interest_stmt->execute();
                    $interest_result = $interest_stmt->get_result();
                    
                    if ($interest_row = $interest_result->fetch_assoc()) {
                        $interest_id = $interest_row['id'];
                        $stmt->bind_param("ii", $user_id, $interest_id);
                        $stmt->execute();
                    }
                    $interest_stmt->close();
                }
                
                $stmt->close();
                
                // Commit transaction
                $conn->commit();
                
                // Set success response
                $response['success'] = true;
                $response['message'] = "Registration successful! Redirecting to login...";
                
                // Optional: Auto-login the user
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                
            } else {
                throw new Exception("Error inserting user data");
            }
            
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            $errors[] = "Registration failed. Please try again.";
        }
        
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
    redirect('dashboard.php');
} else {
    $_SESSION['error_message'] = $response['message'];
    redirect('register.html');
}

?>
