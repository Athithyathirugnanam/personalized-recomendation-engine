<?php
/**
 * Seller Dashboard
 * Shows seller's products, analytics, and management options
 */

require_once 'config/db.php';

// Check if user is logged in
if (!is_logged_in()) {
    redirect('login.html');
}

// Check if user is a seller
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'] ?? 'buyer';

if ($user_role !== 'seller') {
    $_SESSION['error_message'] = "You need to upgrade to a seller account to access this page.";
    redirect('settings.php');
}

// Fetch seller's products
$stmt = $conn->prepare("SELECT * FROM products WHERE seller_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$seller_products = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Calculate analytics
$total_products = count($seller_products);
$total_value = array_sum(array_column($seller_products, 'price'));
$avg_rating = $total_products > 0 ? array_sum(array_column($seller_products, 'rating')) / $total_products : 0;
$total_stock = array_sum(array_column($seller_products, 'stock_quantity'));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard - RecommendMe</title>
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
                        <a class="nav-link active" href="seller_dashboard.php">
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

    <!-- Dashboard Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <!-- Welcome Header -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <h2 class="mb-2">
                        <i class="fas fa-store me-2"></i>Welcome back, <?php echo htmlspecialchars($user_name); ?>!
                    </h2>
                    <p class="text-muted">Manage your products and view your store analytics</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="add_product.php" class="btn btn-success btn-lg">
                        <i class="fas fa-plus me-2"></i>Add New Product
                    </a>
                </div>
            </div>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success_message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <!-- Analytics Cards -->
            <div class="row g-4 mb-5">
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon bg-primary">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $total_products; ?></h3>
                            <p>Total Products</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon bg-success">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stats-content">
                            <h3>$<?php echo number_format($total_value, 2); ?></h3>
                            <p>Total Value</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon bg-warning">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo number_format($avg_rating, 1); ?></h3>
                            <p>Avg Rating</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon bg-info">
                            <i class="fas fa-warehouse"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $total_stock; ?></h3>
                            <p>Total Stock</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Table -->
            <div class="card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>My Products</h5>
                </div>
                <div class="card-body">
                    <?php if ($total_products > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Rating</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($seller_products as $product): ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($product['title']); ?>" 
                                             class="img-thumbnail" 
                                             style="width: 50px; height: 50px; object-fit: contain;"
                                             onerror="this.src='images/placeholder.svg'">
                                    </td>
                                    <td><?php echo htmlspecialchars($product['title']); ?></td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($product['category']); ?></span>
                                    </td>
                                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                                    <td>
                                        <?php if ($product['stock_quantity'] > 0): ?>
                                            <span class="badge bg-success"><?php echo $product['stock_quantity']; ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Out of Stock</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-star text-warning"></i> 
                                        <?php echo number_format($product['rating'], 1); ?>
                                    </td>
                                    <td>
                                        <a href="edit_product.php?id=<?php echo $product['id']; ?>" 
                                           class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="delete_product.php?id=<?php echo $product['id']; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Are you sure you want to delete this product?')" 
                                           title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-box-open fa-5x text-muted mb-3"></i>
                        <h4>No Products Yet</h4>
                        <p class="text-muted">Start adding products to your store!</p>
                        <a href="add_product.php" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Add Your First Product
                        </a>
                    </div>
                    <?php endif; ?>
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
