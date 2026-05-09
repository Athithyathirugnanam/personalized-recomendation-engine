<?php
session_start();
require_once 'db_connection.php';

// Set JSON header
header('Content-Type: application/json');

// Get the JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['credential'])) {
    echo json_encode(['success' => false, 'message' => 'No credential provided']);
    exit;
}

// Verify the Google ID token
$credential = $data['credential'];

// Split the JWT token
$parts = explode('.', $credential);
if (count($parts) !== 3) {
    echo json_encode(['success' => false, 'message' => 'Invalid token format']);
    exit;
}

// Decode the payload (middle part)
$payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1])), true);

// Extract user information
$google_id = $payload['sub'] ?? null;
$email = $payload['email'] ?? null;
$name = $payload['name'] ?? null;
$picture = $payload['picture'] ?? null;
$email_verified = $payload['email_verified'] ?? false;

// Validate required fields
if (!$google_id || !$email || !$name) {
    echo json_encode(['success' => false, 'message' => 'Invalid user data from Google']);
    exit;
}

// Check if email is verified
if (!$email_verified) {
    echo json_encode(['success' => false, 'message' => 'Email not verified by Google']);
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
        
        // Update last login and Google ID if not set
        $update_stmt = $conn->prepare("UPDATE users SET google_id = ?, updated_at = NOW() WHERE id = ?");
        $update_stmt->bind_param("si", $google_id, $user['id']);
        $update_stmt->execute();
        
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['login_method'] = 'google';
        
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
        // New user - register with Google
        // Default password for OAuth users (they won't use it)
        $oauth_password = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
        
        $insert_stmt = $conn->prepare("INSERT INTO users (name, email, password, google_id, role, created_at) VALUES (?, ?, ?, ?, 'buyer', NOW())");
        $insert_stmt->bind_param("ssss", $name, $email, $oauth_password, $google_id);
        
        if ($insert_stmt->execute()) {
            $user_id = $conn->insert_id;
            
            // Set session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = 'buyer';
            $_SESSION['login_method'] = 'google';
            
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
    error_log("Google OAuth Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error occurred']);
}

$conn->close();
?>
