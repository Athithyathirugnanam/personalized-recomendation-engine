<?php
/**
 * Edit Product Process
 * Handles product updates by sellers
 */

require_once 'config/db.php';

// Check if user is logged in and is a seller
if (!is_logged_in()) {
    redirect('login.html');
}

if ($_SESSION['user_role'] !== 'seller') {
    $_SESSION['error_message'] = "Only sellers can edit products.";
    redirect('settings.php');
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $seller_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    
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
    
    if (!filter_var($image, FILTER_VALIDATE_URL)) {
        $errors[] = "Invalid image URL";
    }
    
    // Verify ownership
    $check_stmt = $conn->prepare("SELECT id FROM products WHERE id = ? AND seller_id = ?");
    $check_stmt->bind_param("ii", $product_id, $seller_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows == 0) {
        $errors[] = "Product not found or you don't have permission to edit it.";
    }
    $check_stmt->close();
    
    // If no errors, update product
    if (empty($errors)) {
        
        $stmt = $conn->prepare("UPDATE products SET title = ?, category = ?, image = ?, price = ?, rating = ?, description = ?, stock_quantity = ?, updated_at = NOW() WHERE id = ? AND seller_id = ?");
        $stmt->bind_param("sssddsiii", $title, $category, $image, $price, $rating, $description, $stock, $product_id, $seller_id);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Product updated successfully!";
            $stmt->close();
            $conn->close();
            redirect('seller_dashboard.php');
        } else {
            $errors[] = "Error updating product. Please try again.";
        }
        
        $stmt->close();
    }
    
    // If there are errors, redirect back
    if (!empty($errors)) {
        $_SESSION['error_message'] = implode("<br>", $errors);
        $conn->close();
        redirect('edit_product.php?id=' . $product_id);
    }
    
} else {
    redirect('seller_dashboard.php');
}

$conn->close();
?>