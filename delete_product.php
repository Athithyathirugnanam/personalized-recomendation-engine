<?php
/**
 * Delete Product
 * Handles product deletion by sellers
 */

require_once 'config/db.php';

// Check if user is logged in and is a seller
if (!is_logged_in()) {
    redirect('login.html');
}

if ($_SESSION['user_role'] !== 'seller') {
    $_SESSION['error_message'] = "Only sellers can delete products.";
    redirect('settings.php');
}

$seller_id = $_SESSION['user_id'];
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id > 0) {
    
    // Verify ownership before deleting
    $check_stmt = $conn->prepare("SELECT id FROM products WHERE id = ? AND seller_id = ?");
    $check_stmt->bind_param("ii", $product_id, $seller_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        // Delete the product
        $delete_stmt = $conn->prepare("DELETE FROM products WHERE id = ? AND seller_id = ?");
        $delete_stmt->bind_param("ii", $product_id, $seller_id);
        
        if ($delete_stmt->execute()) {
            $_SESSION['success_message'] = "Product deleted successfully!";
        } else {
            $_SESSION['error_message'] = "Error deleting product. Please try again.";
        }
        
        $delete_stmt->close();
    } else {
        $_SESSION['error_message'] = "Product not found or you don't have permission to delete it.";
    }
    
    $check_stmt->close();
    
} else {
    $_SESSION['error_message'] = "Invalid product ID.";
}

$conn->close();
redirect('seller_dashboard.php');
?>
