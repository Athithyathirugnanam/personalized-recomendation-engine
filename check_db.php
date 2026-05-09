<?php
/**
 * COMPLETE DATABASE DIAGNOSTIC
 * This will show exactly what's wrong
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><title>Database Diagnostic</title>";
echo "<style>
body{font-family:Arial;padding:20px;background:#f5f5f5;}
.container{max-width:1000px;margin:0 auto;background:white;padding:30px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.1);}
h1{color:#333;border-bottom:3px solid #007bff;padding-bottom:10px;}
h2{color:#007bff;margin-top:30px;}
.success{color:green;font-weight:bold;}
.error{color:red;font-weight:bold;}
.warning{color:orange;font-weight:bold;}
pre{background:#f4f4f4;padding:15px;border-radius:5px;overflow-x:auto;}
.box{background:#e3f2fd;padding:15px;margin:15px 0;border-left:4px solid #007bff;border-radius:5px;}
table{border-collapse:collapse;width:100%;margin:15px 0;}
table th{background:#333;color:white;padding:10px;text-align:left;}
table td{padding:10px;border:1px solid #ddd;}
table tr:nth-child(even){background:#f9f9f9;}
.btn{display:inline-block;padding:10px 20px;background:#007bff;color:white;text-decoration:none;border-radius:5px;margin:10px 5px 10px 0;}
.btn:hover{background:#0056b3;}
</style></head><body><div class='container'>";

echo "<h1>🔍 Complete Database Diagnostic</h1>";
echo "<p><em>Generated: " . date('Y-m-d H:i:s') . "</em></p>";

// TEST 1: PHP Configuration
echo "<h2>Test 1: PHP Configuration</h2>";
echo "<table>";
echo "<tr><th>Setting</th><th>Value</th></tr>";
echo "<tr><td>PHP Version</td><td>" . phpversion() . "</td></tr>";
echo "<tr><td>Display Errors</td><td>" . (ini_get('display_errors') ? 'ON' : 'OFF') . "</td></tr>";
echo "<tr><td>MySQLi Extension</td><td>" . (extension_loaded('mysqli') ? '<span class="success">✅ LOADED</span>' : '<span class="error">❌ NOT LOADED</span>') . "</td></tr>";
echo "</table>";

if (!extension_loaded('mysqli')) {
    echo "<div class='box error'>❌ <strong>CRITICAL:</strong> MySQLi extension is not loaded. Enable it in php.ini</div>";
    exit;
}

// TEST 2: Database Configuration File
echo "<h2>Test 2: Database Configuration File</h2>";
$config_file = 'config/db.php';

if (file_exists($config_file)) {
    echo "<p class='success'>✅ config/db.php exists</p>";
    
    // Read the config file to show settings
    $config_content = file_get_contents($config_file);
    if (preg_match("/define\('DB_HOST',\s*'([^']+)'/", $config_content, $matches)) {
        echo "<p>DB_HOST: <code>" . $matches[1] . "</code></p>";
    }
    if (preg_match("/define\('DB_USER',\s*'([^']+)'/", $config_content, $matches)) {
        echo "<p>DB_USER: <code>" . $matches[1] . "</code></p>";
    }
    if (preg_match("/define\('DB_NAME',\s*'([^']+)'/", $config_content, $matches)) {
        echo "<p>DB_NAME: <code>" . $matches[1] . "</code></p>";
    }
} else {
    echo "<p class='error'>❌ config/db.php NOT found</p>";
    echo "<p>Current directory: " . __DIR__ . "</p>";
    exit;
}

// TEST 3: MySQL Server Connection
echo "<h2>Test 3: MySQL Server Connection</h2>";
$host = 'localhost';
$user = 'root';
$pass = '';

echo "<p>Attempting to connect to MySQL server...</p>";
$test_conn = @new mysqli($host, $user, $pass);

if ($test_conn->connect_error) {
    echo "<p class='error'>❌ CANNOT CONNECT to MySQL Server</p>";
    echo "<div class='box error'>";
    echo "<strong>Error Code:</strong> " . $test_conn->connect_errno . "<br>";
    echo "<strong>Error Message:</strong> " . $test_conn->connect_error . "<br><br>";
    
    echo "<strong>Common Solutions:</strong><br>";
    echo "• <strong>Error 2002:</strong> MySQL service is not running. Start XAMPP and click 'Start' for MySQL<br>";
    echo "• <strong>Error 1045:</strong> Wrong username or password<br>";
    echo "• <strong>Error 2054:</strong> Authentication method issue<br>";
    echo "</div>";
    exit;
} else {
    echo "<p class='success'>✅ Successfully connected to MySQL Server</p>";
    echo "<p>Server Version: <code>" . $test_conn->server_info . "</code></p>";
    echo "<p>Protocol Version: <code>" . $test_conn->protocol_version . "</code></p>";
}

// TEST 4: Database Existence
echo "<h2>Test 4: Database 'recommendation_engine'</h2>";
$db_name = 'recommendation_engine';
$db_check = $test_conn->query("SHOW DATABASES LIKE '$db_name'");

if ($db_check && $db_check->num_rows > 0) {
    echo "<p class='success'>✅ Database '$db_name' exists</p>";
    
    // Try to select the database
    if ($test_conn->select_db($db_name)) {
        echo "<p class='success'>✅ Successfully selected database</p>";
    } else {
        echo "<p class='error'>❌ Cannot select database: " . $test_conn->error . "</p>";
    }
} else {
    echo "<p class='error'>❌ Database '$db_name' does NOT exist</p>";
    echo "<div class='box error'>";
    echo "<strong>Solution:</strong><br>";
    echo "1. Open phpMyAdmin: <a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a><br>";
    echo "2. Click 'Import' tab<br>";
    echo "3. Choose 'database.sql' file from your project<br>";
    echo "4. Click 'Go' to import<br>";
    echo "</div>";
    
    echo "<h3>All Databases on Server</h3>";
    $all_dbs = $test_conn->query("SHOW DATABASES");
    echo "<ul>";
    while ($db = $all_dbs->fetch_array()) {
        echo "<li>" . $db[0] . "</li>";
    }
    echo "</ul>";
    exit;
}

// TEST 5: Database Tables
echo "<h2>Test 5: Database Tables</h2>";
$tables = $test_conn->query("SHOW TABLES");

if ($tables && $tables->num_rows > 0) {
    echo "<p class='success'>✅ Found " . $tables->num_rows . " tables</p>";
    echo "<ul>";
    while ($table = $tables->fetch_array()) {
        echo "<li><strong>" . $table[0] . "</strong></li>";
    }
    echo "</ul>";
    
    // Check required tables
    $required_tables = ['users', 'products', 'interests', 'user_interests', 'user_favorites'];
    $missing_tables = [];
    
    foreach ($required_tables as $table) {
        $check = $test_conn->query("SHOW TABLES LIKE '$table'");
        if (!$check || $check->num_rows == 0) {
            $missing_tables[] = $table;
        }
    }
    
    if (count($missing_tables) > 0) {
        echo "<p class='warning'>⚠ Missing tables: " . implode(', ', $missing_tables) . "</p>";
    } else {
        echo "<p class='success'>✅ All required tables exist</p>";
    }
} else {
    echo "<p class='error'>❌ No tables found in database</p>";
    echo "<p>Import database.sql to create tables</p>";
}

// TEST 6: Table Data
echo "<h2>Test 6: Table Data</h2>";

// Check users table
$users_result = $test_conn->query("SELECT COUNT(*) as count FROM users");
if ($users_result) {
    $count = $users_result->fetch_assoc()['count'];
    echo "<p><strong>Users table:</strong> $count records</p>";
    
    if ($count > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr>";
        $users = $test_conn->query("SELECT id, name, email, role FROM users LIMIT 5");
        while ($user = $users->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . $user['name'] . "</td>";
            echo "<td>" . $user['email'] . "</td>";
            echo "<td>" . $user['role'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}

// Check products table
$products_result = $test_conn->query("SELECT COUNT(*) as count FROM products");
if ($products_result) {
    $count = $products_result->fetch_assoc()['count'];
    echo "<p><strong>Products table:</strong> $count records</p>";
}

// Check interests table
$interests_result = $test_conn->query("SELECT COUNT(*) as count FROM interests");
if ($interests_result) {
    $count = $interests_result->fetch_assoc()['count'];
    echo "<p><strong>Interests table:</strong> $count records</p>";
}

// TEST 7: Include db.php and test
echo "<h2>Test 7: Testing config/db.php Include</h2>";
try {
    ob_start();
    require_once $config_file;
    $output = ob_get_clean();
    
    echo "<p class='success'>✅ config/db.php loaded successfully</p>";
    
    if (!empty($output)) {
        echo "<div class='box warning'><strong>Output from db.php:</strong><pre>$output</pre></div>";
    }
    
    // Test the connection object
    if (isset($conn) && $conn instanceof mysqli) {
        echo "<p class='success'>✅ \$conn object created</p>";
        
        if ($conn->connect_error) {
            echo "<p class='error'>❌ Connection has error: " . $conn->connect_error . "</p>";
        } else {
            echo "<p class='success'>✅ Connection is active</p>";
            
            // Try a test query
            $test_query = $conn->query("SELECT 1 as test");
            if ($test_query) {
                echo "<p class='success'>✅ Can execute queries</p>";
                echo "<h2 style='color:green;'>🎉 DATABASE IS FULLY WORKING! 🎉</h2>";
            } else {
                echo "<p class='error'>❌ Cannot execute queries: " . $conn->error . "</p>";
            }
        }
    } else {
        echo "<p class='error'>❌ \$conn object not created properly</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Error loading config/db.php: " . $e->getMessage() . "</p>";
}

$test_conn->close();

// TEST 8: Session Test
echo "<h2>Test 8: Session Configuration</h2>";
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "<p class='success'>✅ Session is active</p>";
} else {
    echo "<p class='warning'>⚠ Session not started</p>";
    session_start();
    echo "<p class='success'>✅ Session started</p>";
}

echo "<hr>";
echo "<h2>📋 Summary & Next Steps</h2>";
echo "<div class='box'>";
echo "<strong>If all tests show ✅:</strong><br>";
echo "• Your database is working correctly<br>";
echo "• Try these pages:<br>";
echo "&nbsp;&nbsp;- <a href='index.html' class='btn'>Home Page</a>";
echo "<a href='register.html' class='btn'>Register</a>";
echo "<a href='login.html' class='btn'>Login</a>";
echo "<a href='test_login.php' class='btn'>Test Login</a>";
echo "<br><br>";
echo "<strong>If you see ❌:</strong><br>";
echo "• Follow the solutions shown above<br>";
echo "• Make sure XAMPP MySQL is running<br>";
echo "• Import database.sql if database/tables are missing<br>";
echo "</div>";

echo "</div></body></html>";
?>
