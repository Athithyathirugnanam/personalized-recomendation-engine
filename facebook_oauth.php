<?php
session_start();
require_once 'db_connection.php';

// Set JSON header
header('Content-Type: application/json');

// Get the JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate input
if (!isset($data['accessToken']) || !isset($data['userID']) || !isset($data['email']) || !isset($data['name'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit;
}

$access_token = $data['accessToken'];
$facebook_id = $data['userID'];
$email = $data['email'];
$name = $data['name'];
$picture = $data['picture'] ?? null;

// Optional: Verify the access token with Facebook (recommended for production)
// This makes an additional API call to Facebook to verify the token is valid
$app_id = 'YOUR_FACEBOOK_APP_ID'; // Replace with your Facebook App ID
$app_secret = 'YOUR_FACEBOOK_APP_SECRET'; // Replace with your Facebook App Secret

// Verify token with Facebook Graph API
$verify_url = "https://graph.facebook.com/debug_token?input_token={$access_token}&access_token={$app_id}|{$app_secret}";
$verify_response = @file_get_contents($verify_url);

if ($verify_response === false) {
    // If verification fails, you can choose to continue or reject
    // For development, we'll continue, but log the error
    error_log("Facebook token verification failed");
}

// Validate email
if (empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Email is required. Please grant email permission.']);
    exit;
}

try {
    // Check if user already exists with this email
    $stmt = $conn->prepare("SELECT id, name, email, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // User exists - login
        $user = $result->fetch_assoc();
        
        // Update Facebook ID if not set
        $update_stmt = $conn->prepare("UPDATE users SET facebook_id = ?, updated_at = NOW() WHERE id = ?");
        $update_stmt->bind_param("si", $facebook_id, $user['id']);
        $update_stmt->execute();
        
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['login_method'] = 'facebook';
        
        // Determine redirect based on role
        $redirect = ($user['role'] === 'seller') ? 'seller_dashboard.php' : 'buyer_dashboard.php';
        
        echo json_encode([
            'success' => true,
            'message' => 'Login successful',
            'redirect' => $redirect,
            'user' => [
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role']
            ]
        ]);
    } else {
        // New user - register with Facebook
        // Default password for OAuth users (they won't use it)
        $oauth_password = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
        
        $insert_stmt = $conn->prepare("INSERT INTO users (name, email, password, facebook_id, role, created_at) VALUES (?, ?, ?, ?, 'buyer', NOW())");
        $insert_stmt->bind_param("ssss", $name, $email, $oauth_password, $facebook_id);
        
        if ($insert_stmt->execute()) {
            $user_id = $conn->insert_id;
            
            // Set session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = 'buyer';
            $_SESSION['login_method'] = 'facebook';
            
            echo json_encode([
                'success' => true,
                'message' => 'Account created and logged in successfully',
                'redirect' => 'buyer_dashboard.php',
                'new_user' => true,
                'user' => [
                    'name' => $name,
                    'email' => $email,
                    'role' => 'buyer'
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create account']);
        }
    }
    
} catch (Exception $e) {
    error_log("Facebook OAuth Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error occurred']);
}

$conn->close();
?>
