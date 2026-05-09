<?php
/**
 * Database Connection Test Script
 * This script tests the database connection and displays the status
 */

echo "<h2>Database Connection Test</h2>";
echo "<hr>";

// Test 1: Check if we can connect to MySQL server
echo "<h3>Test 1: MySQL Server Connection</h3>";
$conn_test = new mysqli('localhost', 'root', '');
if ($conn_test->connect_error) {
    echo "❌ <strong style='color: red;'>FAILED:</strong> Cannot connect to MySQL server<br>";
    echo "Error: " . $conn_test->connect_error . "<br>";
    echo "<br><strong>Solution:</strong> Make sure XAMPP/WAMP is running and MySQL service is started.<br>";
} else {
    echo "✅ <strong style='color: green;'>SUCCESS:</strong> Connected to MySQL server<br>";
    $conn_test->close();
}

echo "<hr>";

// Test 2: Check if database exists
echo "<h3>Test 2: Database Existence</h3>";
$conn_test = new mysqli('localhost', 'root', '');
$db_exists = $conn_test->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'recommendation_engine'");

if ($db_exists && $db_exists->num_rows > 0) {
    echo "✅ <strong style='color: green;'>SUCCESS:</strong> Database 'recommendation_engine' exists<br>";
} else {
    echo "❌ <strong style='color: red;'>FAILED:</strong> Database 'recommendation_engine' does not exist<br>";
    echo "<br><strong>Solution:</strong> Run the database.sql file to create the database.<br>";
    echo "You can do this by:<br>";
    echo "1. Opening phpMyAdmin (http://localhost/phpmyadmin)<br>";
    echo "2. Click on 'Import' tab<br>";
    echo "3. Choose 'database.sql' file and import it<br>";
}
$conn_test->close();

echo "<hr>";

// Test 3: Check tables if database exists
if ($db_exists && $db_exists->num_rows > 0) {
    echo "<h3>Test 3: Database Tables</h3>";
    $conn_test = new mysqli('localhost', 'root', '', 'recommendation_engine');
    
    $tables = ['users', 'interests', 'user_interests', 'products', 'user_favorites'];
    $all_tables_exist = true;
    
    foreach ($tables as $table) {
        $result = $conn_test->query("SHOW TABLES LIKE '$table'");
        if ($result && $result->num_rows > 0) {
            echo "✅ Table '<strong>$table</strong>' exists<br>";
        } else {
            echo "❌ Table '<strong>$table</strong>' is missing<br>";
            $all_tables_exist = false;
        }
    }
    
    if (!$all_tables_exist) {
        echo "<br><strong>Solution:</strong> Import the database.sql file to create missing tables.<br>";
    }
    
    $conn_test->close();
}

echo "<hr>";

// Test 4: Full connection test using db.php
echo "<h3>Test 4: Application Database Connection</h3>";
try {
    require_once 'config/db.php';
    echo "✅ <strong style='color: green;'>SUCCESS:</strong> Database connection established via db.php<br>";
    echo "Database: " . DB_NAME . "<br>";
    echo "Host: " . DB_HOST . "<br>";
    echo "User: " . DB_USER . "<br>";
} catch (Exception $e) {
    echo "❌ <strong style='color: red;'>FAILED:</strong> " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li>If any tests failed, follow the solutions provided above</li>";
echo "<li>Once all tests pass, you can delete this test_db.php file</li>";
echo "<li>Access your application at <a href='index.html'>index.html</a></li>";
echo "</ol>";
?>
