<?php
/**
 * Edit Product Page
 * Allows sellers to edit their products
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

$seller_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
$stmt->bind_param("ii", $product_id, $seller_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error_message'] = "Product not found or you don't have permission to edit it.";
    redirect('seller_dashboard.php');
}

$product = $result->fetch_assoc();
$stmt->close();

// Fetch categories
$stmt = $conn->query("SELECT interest_name FROM interests ORDER BY interest_name");
$categories = $stmt->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - RecommendMe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-gradient-primary">
        <div class="container">
            <a class="navbar-brand" href="index.html">
                <i class="fas fa-magic"></i> RecommendMe
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="seller_dashboard.php">
                            <i class="fas fa-store"></i> My Store
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="recommendations.php">
                            <i class="fas fa-shopping-bag"></i> Browse
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="settings.php">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Edit Product Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-gradient-primary text-white">
                            <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Product</h4>
                        </div>
                        <div class="card-body p-4">
                            <?php if (isset($_SESSION['error_message'])): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['error_message']; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                                <?php unset($_SESSION['error_message']); ?>
                            <?php endif; ?>

                            <form action="edit_product_process.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="title" class="form-label">Product Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="title" name="title" 
                                               value="<?php echo htmlspecialchars($product['title']); ?>" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                        <select class="form-select" id="category" name="category" required>
                                            <?php foreach ($categories as $cat): ?>
                                            <option value="<?php echo htmlspecialchars($cat['interest_name']); ?>"
                                                    <?php echo ($cat['interest_name'] === $product['category']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($cat['interest_name']); ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="price" class="form-label">Price ($) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="price" name="price" 
                                               step="0.01" min="0" value="<?php echo $product['price']; ?>" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="stock" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="stock" name="stock" 
                                               min="0" value="<?php echo $product['stock_quantity']; ?>" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="rating" class="form-label">Rating (0-5)</label>
                                        <input type="number" class="form-control" id="rating" name="rating" 
                                               step="0.1" min="0" max="5" value="<?php echo $product['rating']; ?>">
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="image" class="form-label">Image URL <span class="text-danger">*</span></label>
                                        <input type="url" class="form-control" id="image" name="image" 
                                               value="<?php echo htmlspecialchars($product['image']); ?>" required>
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Enter a valid image URL
                                        </small>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="description" name="description" 
                                                  rows="4" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                                    </div>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-between mt-4">
                                    <a href="seller_dashboard.php" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-2"></i>Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer py-4 bg-dark text-white mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2026 Personalized Recommendation Engine. DBMS Mini Project.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
