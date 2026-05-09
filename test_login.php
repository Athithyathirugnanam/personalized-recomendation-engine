<?php
/**
 * Create Test User and Test Login
 */
session_start();
require_once 'config/db.php';

echo "<h1>Login Test & Debug Page</h1>";
echo "<style>body{font-family:Arial;padding:20px;} .success{color:green;} .error{color:red;} pre{background:#f4f4f4;padding:10px;border-radius:5px;}</style>";

// Test 1: Create a test user
echo "<h2>Step 1: Create Test User</h2>";
$test_email = "test@example.com";
$test_password = "Test123!";
$test_name = "Test User";

// Check if test user exists
$check_stmt = $conn->prepare("SELECT id, email FROM users WHERE email = ?");
$check_stmt->bind_param("s", $test_email);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo "<p class='success'>✅ Test user already exists: $test_email</p>";
} else {
    // Create test user
    $hashed_password = password_hash($test_password, PASSWORD_DEFAULT);
    $insert_stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'buyer')");
    $insert_stmt->bind_param("sss", $test_name, $test_email, $hashed_password);
    
    if ($insert_stmt->execute()) {
        echo "<p class='success'>✅ Test user created successfully!</p>";
    } else {
        echo "<p class='error'>❌ Failed to create test user: " . $insert_stmt->error . "</p>";
    }
    $insert_stmt->close();
}
$check_stmt->close();

echo "<div style='background:#e3f2fd;padding:15px;margin:20px 0;border-radius:5px;'>";
echo "<strong>Test Credentials:</strong><br>";
echo "Email: <code>test@example.com</code><br>";
echo "Password: <code>Test123!</code>";
echo "</div>";

// Test 2: Test the login process
echo "<h2>Step 2: Test Login Process</h2>";

if (isset($_POST['test_login'])) {
    $login_email = $_POST['login_email'];
    $login_password = $_POST['login_password'];
    
    echo "<h3>Testing login with:</h3>";
    echo "<pre>Email: $login_email\nPassword: $login_password</pre>";
    
    // Get user from database
    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $login_email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        echo "<p class='success'>✅ User found in database</p>";
        $user = $result->fetch_assoc();
        
        echo "<h4>User Data:</h4>";
        echo "<pre>";
        echo "ID: " . $user['id'] . "\n";
        echo "Name: " . $user['name'] . "\n";
        echo "Email: " . $user['email'] . "\n";
        echo "Role: " . $user['role'] . "\n";
        echo "Password Hash: " . substr($user['password'], 0, 30) . "...\n";
        echo "</pre>";
        
        // Verify password
        if (password_verify($login_password, $user['password'])) {
            echo "<p class='success'>✅ Password verification SUCCESSFUL!</p>";
            
            // Create session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            
            echo "<h4>Session Created:</h4>";
            echo "<pre>";
            print_r($_SESSION);
            echo "</pre>";
            
            echo "<p class='success' style='font-size:1.2em;'>🎉 <strong>LOGIN SUCCESSFUL!</strong></p>";
            echo "<p><a href='buyer_dashboard.php' class='btn btn-primary'>Go to Dashboard</a></p>";
            
        } else {
            echo "<p class='error'>❌ Password verification FAILED</p>";
            echo "<p>The password you entered doesn't match the hashed password in the database.</p>";
        }
        
    } else {
        echo "<p class='error'>❌ User NOT found in database with email: $login_email</p>";
    }
    $stmt->close();
}

// Test login form
echo "<h2>Step 3: Test Login Form</h2>";
echo "<form method='POST' style='background:#f5f5f5;padding:20px;border-radius:5px;max-width:400px;'>";
echo "<div style='margin-bottom:15px;'>";
echo "<label><strong>Email:</strong></label><br>";
echo "<input type='email' name='login_email' value='test@example.com' required style='width:100%;padding:8px;'>";
echo "</div>";
echo "<div style='margin-bottom:15px;'>";
echo "<label><strong>Password:</strong></label><br>";
echo "<input type='password' name='login_password' value='Test123!' required style='width:100%;padding:8px;'>";
echo "</div>";
echo "<button type='submit' name='test_login' style='background:#007bff;color:white;border:none;padding:10px 20px;border-radius:5px;cursor:pointer;'>Test Login</button>";
echo "</form>";

// Show all users in database
echo "<h2>Step 4: All Users in Database</h2>";
$all_users = $conn->query("SELECT id, name, email, role, created_at FROM users ORDER BY id");
if ($all_users && $all_users->num_rows > 0) {
    echo "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse:collapse;width:100%;'>";
    echo "<tr style='background:#333;color:white;'><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Created</th></tr>";
    while ($user = $all_users->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . $user['name'] . "</td>";
        echo "<td>" . $user['email'] . "</td>";
        echo "<td>" . $user['role'] . "</td>";
        echo "<td>" . $user['created_at'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p class='error'>No users found in database</p>";
}

echo "<hr>";
echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li>Use the test login form above to verify authentication works</li>";
echo "<li>Go to <a href='login.html'>login.html</a> and try logging in</li>";
echo "<li>If login.html doesn't work, check browser console for errors</li>";
echo "</ol>";

$conn->close();
?> 

<style>
    .btn-primary {
        display: inline-block;
        background: #007bff;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 10px;
    }
    .btn-primary:hover {
        background: #0056b3;
    }
</style>
