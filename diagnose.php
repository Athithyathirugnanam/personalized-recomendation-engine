<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Database Diagnostic Report</h1>";
echo "<style>body{font-family:Arial;padding:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;} pre{background:#f4f4f4;padding:10px;border-radius:5px;}</style>";

// Test 1: Check PHP MySQL Extension
echo "<h2>1. PHP MySQL Extension</h2>";
if (extension_loaded('mysqli')) {
    echo "<p class='success'>✅ MySQLi extension is loaded</p>";
} else {
    echo "<p class='error'>❌ MySQLi extension is NOT loaded</p>";
    echo "<p><strong>Fix:</strong> Enable mysqli in php.ini</p>";
}

// Test 2: Try to connect to MySQL
echo "<h2>2. MySQL Server Connection</h2>";
$host = 'localhost';
$user = 'root';
$pass = '';

echo "<p>Attempting to connect with:</p>";
echo "<pre>Host: $host\nUser: $user\nPassword: " . (empty($pass) ? '(empty)' : '***') . "</pre>";

$conn = @new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    echo "<p class='error'>❌ Connection FAILED</p>";
    echo "<p><strong>Error Code:</strong> " . $conn->connect_errno . "</p>";
    echo "<p><strong>Error Message:</strong> " . $conn->connect_error . "</p>";
    
    echo "<h3>Common Solutions:</h3>";
    echo "<ul>";
    echo "<li><strong>Error 2002 (XAMPP not running):</strong> Start XAMPP Control Panel and start MySQL service</li>";
    echo "<li><strong>Error 1045 (Access denied):</strong> Wrong username/password. Default is root with no password.</li>";
    echo "<li><strong>Error 2054:</strong> Update MySQL server or change authentication method</li>";
    echo "</ul>";
} else {
    echo "<p class='success'>✅ Connected to MySQL Server successfully!</p>";
    echo "<p><strong>Server Version:</strong> " . $conn->server_info . "</p>";
    echo "<p><strong>Host Info:</strong> " . $conn->host_info . "</p>";
    
    // Test 3: Check if database exists
    echo "<h2>3. Database Check</h2>";
    $db_name = 'recommendation_engine';
    $result = $conn->query("SHOW DATABASES LIKE '$db_name'");
    
    if ($result && $result->num_rows > 0) {
        echo "<p class='success'>✅ Database '$db_name' exists</p>";
        
        // Select the database
        if ($conn->select_db($db_name)) {
            echo "<p class='success'>✅ Successfully selected database</p>";
            
            // Test 4: Check tables
            echo "<h2>4. Database Tables</h2>";
            $tables_result = $conn->query("SHOW TABLES");
            
            if ($tables_result && $tables_result->num_rows > 0) {
                echo "<p class='success'>✅ Found " . $tables_result->num_rows . " tables:</p>";
                echo "<ul>";
                while ($row = $tables_result->fetch_array()) {
                    echo "<li>" . $row[0] . "</li>";
                }
                echo "</ul>";
                
                // Check users table
                $users_count = $conn->query("SELECT COUNT(*) as count FROM users");
                if ($users_count) {
                    $count = $users_count->fetch_assoc()['count'];
                    echo "<p><strong>Users:</strong> $count records</p>";
                }
                
                // Check products table
                $products_count = $conn->query("SELECT COUNT(*) as count FROM products");
                if ($products_count) {
                    $count = $products_count->fetch_assoc()['count'];
                    echo "<p><strong>Products:</strong> $count records</p>";
                }
                
            } else {
                echo "<p class='error'>❌ No tables found in database</p>";
                echo "<p><strong>Action:</strong> Import database.sql in phpMyAdmin</p>";
            }
        }
    } else {
        echo "<p class='error'>❌ Database '$db_name' does NOT exist</p>";
        echo "<h3>How to create the database:</h3>";
        echo "<ol>";
        echo "<li>Open phpMyAdmin: <a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a></li>";
        echo "<li>Click 'Import' tab</li>";
        echo "<li>Choose file: database.sql</li>";
        echo "<li>Click 'Go' button</li>";
        echo "</ol>";
        
        echo "<h3>OR create manually:</h3>";
        echo "<ol>";
        echo "<li>Click 'New' in phpMyAdmin</li>";
        echo "<li>Database name: recommendation_engine</li>";
        echo "<li>Collation: utf8mb4_general_ci</li>";
        echo "<li>Click 'Create'</li>";
        echo "<li>Then import database.sql</li>";
        echo "</ol>";
    }
    
    $conn->close();
}

// Test 5: Check db.php file
echo "<h2>5. Configuration File Check</h2>";
if (file_exists('config/db.php')) {
    echo "<p class='success'>✅ config/db.php exists</p>";
    
    // Try to include it
    echo "<p>Testing config/db.php...</p>";
    try {
        ob_start();
        include 'config/db.php';
        $output = ob_get_clean();
        
        if (empty($output)) {
            echo "<p class='success'>✅ config/db.php loaded without errors</p>";
        } else {
            echo "<p class='warning'>⚠ Output from config/db.php:</p>";
            echo "<pre>$output</pre>";
        }
        
        if (isset($conn) && $conn instanceof mysqli) {
            echo "<p class='success'>✅ Database connection object created successfully</p>";
            if (!$conn->connect_error) {
                echo "<p class='success'>✅ Connection is active and working!</p>";
                echo "<h2 class='success'>🎉 YOUR DATABASE IS WORKING! 🎉</h2>";
            }
        }
    } catch (Exception $e) {
        echo "<p class='error'>❌ Error loading config/db.php: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p class='error'>❌ config/db.php NOT found</p>";
}

echo "<hr>";
echo "<h2>Summary</h2>";
echo "<p>If all checks above show ✅, your database is connected correctly.</p>";
echo "<p>If you see ❌, follow the solutions provided above.</p>";
echo "<hr>";
echo "<p><a href='index.html'>← Back to Homepage</a> | <a href='login.html'>Login Page</a></p>";
?>
