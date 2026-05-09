<?php
/**
 * Upgrade to Seller Process
 * Handles upgrading buyer account to seller
 */

require_once 'config/db.php';

// Check if user is logged in
if (!is_logged_in()) {
    redirect('login.html');
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $user_id = $_SESSION['user_id'];
    
    // Get and sanitize form data
    $business_name = sanitize_input($_POST['business_name']);
    $phone = sanitize_input($_POST['phone']);
    $address = sanitize_input($_POST['address'] ?? '');
    
    // Validation
    $errors = array();
    
    if (empty($business_name)) {
        $errors[] = "Business name is required";
    } elseif (strlen($business_name) < 3) {
        $errors[] = "Business name must be at least 3 characters";
    }
    
    if (empty($phone)) {
        $errors[] = "Phone number is required";
    } elseif (!preg_match("/^[0-9\-\+\(\) ]{10,20}$/", $phone)) {
        $errors[] = "Invalid phone number format";
    }
    
    // If no errors, update user to seller
    if (empty($errors)) {
        
        // Update user role and seller details
        $stmt = $conn->prepare("UPDATE users SET role = 'seller', seller_business_name = ?, seller_phone = ?, seller_address = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("sssi", $business_name, $phone, $address, $user_id);
        
        if ($stmt->execute()) {
            // Update session
            $_SESSION['user_role'] = 'seller';
            $_SESSION['success_message'] = "Congratulations! You are now a seller. You can start adding products.";
            
            $stmt->close();
            $conn->close();
            
            redirect('seller_dashboard.php');
        } else {
            $errors[] = "Error upgrading account. Please try again.";
        }
        
        $stmt->close();
    }
    
    // If there are errors, redirect back with errors
    if (!empty($errors)) {
        $_SESSION['error_message'] = implode("<br>", $errors);
        $conn->close();
        redirect('settings.php');
    }
    
} else {
    redirect('settings.php');
}

$conn->close();
?>
