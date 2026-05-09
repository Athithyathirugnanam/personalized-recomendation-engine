<?php
/**
 * Add Product Process
 * Handles new product creation by sellers
 */

require_once 'config/db.php';

// Check if user is logged in and is a seller
if (!is_logged_in()) {
    redirect('login.html');
}

if ($_SESSION['user_role'] !== 'seller') {
    $_SESSION['error_message'] = "Only sellers can add products.";
    redirect('settings.php');
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $seller_id = $_SESSION['user_id'];
    
    // Get and sanitize form data
    $title = sanitize_input($_POST['title']);
    $category = sanitize_input($_POST['category']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $rating = floatval($_POST['rating'] ?? 0.0);
    $image = sanitize_input($_POST['image']);
    $description = sanitize_input($_POST['description']);
    
    // Validation
    $errors = array();
    
    if (empty($title)) {
        $errors[] = "Product title is required";
    } elseif (strlen($title) < 3) {
        $errors[] = "Product title must be at least 3 characters";
    }
    
    if (empty($category)) {
        $errors[] = "Category is required";
    }
    
    if ($price < 0) {
        $errors[] = "Price cannot be negative";
    }
    
    if ($stock < 0) {
        $errors[] = "Stock quantity cannot be negative";
    }
    
    if ($rating < 0 || $rating > 5) {
        $errors[] = "Rating must be between 0 and 5";
    }
    
    if (empty($image)) {
        $errors[] = "Image URL is required";
    } elseif (!filter_var($image, FILTER_VALIDATE_URL)) {
        $errors[] = "Invalid image URL format";
    }
    
    if (empty($description)) {
        $errors[] = "Description is required";
    } elseif (strlen($description) < 10) {
        $errors[] = "Description must be at least 10 characters";
    }
    
    // If no errors, insert product
    if (empty($errors)) {
        
        $stmt = $conn->prepare("INSERT INTO products (seller_id, title, category, image, price, rating, description, stock_quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssddsi", $seller_id, $title, $category, $image, $price, $rating, $description, $stock);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Product added successfully!";
            $stmt->close();
            $conn->close();
            redirect('seller_dashboard.php');
        } else {
            $errors[] = "Error adding product. Please try again.";
        }
        
        $stmt->close();
    }
    
    // If there are errors, redirect back with errors
    if (!empty($errors)) {
        $_SESSION['error_message'] = implode("<br>", $errors);
        $conn->close();
        redirect('add_product.php');
    }
    
} else {
    redirect('add_product.php');
}

$conn->close();
?>
