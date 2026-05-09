<?php
/**
 * Dashboard Page with Backend Integration
 * Displays personalized recommendations based on user interests
 */

// Include database connection
require_once 'config/db.php';

// Check if user is logged in
if (!is_logged_in()) {
    redirect('login.html');
}

// Get user information
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];

// Fetch user interests
$user_interests = array();
$interests_query = "SELECT i.id, i.interest_name, i.icon 
                    FROM interests i 
                    INNER JOIN user_interests ui ON i.id = ui.interest_id 
                    WHERE ui.user_id = ?";
$stmt = $conn->prepare($interests_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$interests_result = $stmt->get_result();

while ($row = $interests_result->fetch_assoc()) {
    $user_interests[] = $row;
}
$stmt->close();

// Fetch recommended products based on user interests
$recommended_products = array();
if (!empty($user_interests)) {
    // Build query to get products matching user's interest categories
    $interest_names = array_column($user_interests, 'interest_name');
    $placeholders = str_repeat('?,', count($interest_names) - 1) . '?';
    
    $products_query = "SELECT DISTINCT p.* 
                       FROM products p 
                       WHERE p.category IN ($placeholders) 
                       ORDER BY p.rating DESC 
                       LIMIT 8";
    
    $stmt = $conn->prepare($products_query);
    $types = str_repeat('s', count($interest_names));
    $stmt->bind_param($types, ...$interest_names);
    $stmt->execute();
    $products_result = $stmt->get_result();
    
    while ($row = $products_result->fetch_assoc()) {
        $recommended_products[] = $row;
    }
    $stmt->close();
}

// Get statistics
$total_recommendations = count($recommended_products);
$total_interests = count($user_interests);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Personalized Recommendation Engine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/product-modal.css">
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
                        <a class="nav-link active" href="buyer_dashboard.php">
                            <i class="fas fa-home"></i> Dashboard
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

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <div class="container-fluid">
            <!-- Welcome Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="welcome-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="fw-bold mb-2">Welcome back, <span id="userName"><?php echo htmlspecialchars($user_name); ?></span>! 👋</h2>
                                <p class="text-muted mb-0">Here are your personalized recommendations based on your interests</p>
                            </div>
                            <div class="welcome-icon d-none d-md-block">
                                <i class="fas fa-user-circle fa-4x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Interests Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="interests-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0"><i class="fas fa-heart me-2"></i>Your Interests</h5>
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                        </div>
                        <div class="interests-tags" id="userInterests">
                            <?php foreach ($user_interests as $interest): ?>
                            <span class="interest-tag">
                                <i class="fas <?php echo htmlspecialchars($interest['icon']); ?>"></i> 
                                <?php echo htmlspecialchars($interest['interest_name']); ?>
                            </span>
                            <?php endforeach; ?>
                            <?php if (empty($user_interests)): ?>
                            <p class="text-muted">No interests selected yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row mb-4">
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-details">
                            <h3 class="mb-0"><?php echo $total_recommendations; ?></h3>
                            <p class="text-muted mb-0">Recommendations</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="stat-details">
                            <h3 class="mb-0">0</h3>
                            <p class="text-muted mb-0">Favorites</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="stat-details">
                            <h3 class="mb-0">0</h3>
                            <p class="text-muted mb-0">Viewed</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-danger">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div class="stat-details">
                            <h3 class="mb-0"><?php echo $total_interests; ?></h3>
                            <p class="text-muted mb-0">Categories</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommended Items Section -->
            <div class="row">
                <div class="col-12">
                    <div class="section-header mb-4">
                        <h4 class="fw-bold"><i class="fas fa-magic me-2"></i>Recommended For You</h4>
                    </div>
                </div>
            </div>

            <!-- Product Cards Grid -->
            <div class="row g-4">
                <?php if (!empty($recommended_products)): ?>
                    <?php foreach ($recommended_products as $product): ?>
                    <!-- Product Card -->
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="product-card">
                            <div class="product-image">
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['title']); ?>"
                                     onerror="this.src='images/placeholder.svg'">
                                <div class="product-badge"><?php echo htmlspecialchars($product['category']); ?></div>
                            </div>
                            <div class="product-content">
                                <h5 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h5>
                                <div class="product-rating mb-2">
                                    <?php
                                    $rating = floatval($product['rating']);
                                    $full_stars = floor($rating);
                                    $half_star = ($rating - $full_stars) >= 0.5;
                                    $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);
                                    
                                    for ($i = 0; $i < $full_stars; $i++) {
                                        echo '<i class="fas fa-star"></i>';
                                    }
                                    if ($half_star) {
                                        echo '<i class="fas fa-star-half-alt"></i>';
                                    }
                                    for ($i = 0; $i < $empty_stars; $i++) {
                                        echo '<i class="far fa-star"></i>';
                                    }
                                    ?>
                                    <span><?php echo number_format($rating, 1); ?></span>
                                </div>
                                <div class="product-price">
                                    <span class="product-price-symbol">$</span><?php echo number_format($product['price'], 2); ?>
                                </div>
                                <button class="btn btn-primary btn-sm w-100 mt-2" 
                                        onclick='showProductDetails(<?php echo json_encode($product); ?>)'>
                                    <i class="fas fa-info-circle me-1"></i>View Details
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No recommendations available yet. Try selecting more interests!
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- View More Button -->
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="recommendations.php" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>View All Recommendations
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Details Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5">
                            <img id="modalProductImage" src="" alt="Product" class="product-detail-image">
                        </div>
                        <div class="col-md-7">
                            <span id="modalProductBadge" class="product-detail-badge"></span>
                            <h3 id="modalProductTitle" class="product-detail-title"></h3>
                            <div id="modalProductRating" class="product-detail-rating"></div>
                            <div id="modalProductPrice" class="product-detail-price"></div>
                            <div id="modalProductStock" class="product-detail-stock"></div>
                            
                            <div class="product-detail-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Product Description</h6>
                                <p id="modalProductDescription" class="product-detail-description mb-0"></p>
                            </div>
                            
                            <div class="product-detail-info">
                                <h6><i class="fas fa-box me-2"></i>Product Details</h6>
                                <ul>
                                    <li><strong>Category:</strong> <span id="modalProductCategory"></span></li>
                                    <li><strong>Product ID:</strong> <span id="modalProductId"></span></li>
                                    <li><strong>Availability:</strong> <span id="modalProductAvailability"></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="quantity-selector">
                        <label for="productQuantity">Qty:</label>
                        <select id="productQuantity" class="form-select form-select-sm">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-add-to-cart" onclick="addToCart()">
                        <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                    </button>
                    <button type="button" class="btn btn-buy-now" onclick="buyNow()">
                        <i class="fas fa-bolt me-2"></i>Buy Now
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer py-4 bg-dark text-white mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2026 Personalized Recommendation Engine. DBMS Mini Project.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script>
    // Product Details Modal Functions
    function showProductDetails(product) {
        // Set image
        document.getElementById('modalProductImage').src = product.image;
        document.getElementById('modalProductImage').onerror = function() {
            this.src = 'images/placeholder.svg';
        };
        
        // Set badge
        document.getElementById('modalProductBadge').textContent = product.category;
        
        // Set title
        document.getElementById('modalProductTitle').textContent = product.title;
        
        // Set rating
        const rating = parseFloat(product.rating);
        const fullStars = Math.floor(rating);
        const halfStar = (rating - fullStars) >= 0.5 ? 1 : 0;
        const emptyStars = 5 - fullStars - halfStar;
        
        let ratingHTML = '';
        for (let i = 0; i < fullStars; i++) {
            ratingHTML += '<i class="fas fa-star"></i>';
        }
        if (halfStar) {
            ratingHTML += '<i class="fas fa-star-half-alt"></i>';
        }
        for (let i = 0; i < emptyStars; i++) {
            ratingHTML += '<i class="far fa-star"></i>';
        }
        ratingHTML += '<span>' + rating.toFixed(1) + ' out of 5</span>';
        document.getElementById('modalProductRating').innerHTML = ratingHTML;
        
        // Set price
        document.getElementById('modalProductPrice').innerHTML = 
            '<span class="product-price-symbol">$</span>' + parseFloat(product.price).toFixed(2);
        
        // Set stock
        const stockEl = document.getElementById('modalProductStock');
        const stock = parseInt(product.stock_quantity || 0);
        if (stock > 0) {
            stockEl.innerHTML = '<i class="fas fa-check-circle me-2"></i>In Stock (' + stock + ' available)';
            stockEl.className = 'product-detail-stock in-stock';
        } else {
            stockEl.innerHTML = '<i class="fas fa-times-circle me-2"></i>Currently Out of Stock';
            stockEl.className = 'product-detail-stock out-of-stock';
        }
        
        // Set description
        document.getElementById('modalProductDescription').textContent = product.description || 'No description available.';
        
        // Set category
        document.getElementById('modalProductCategory').textContent = product.category;
        
        // Set product ID
        document.getElementById('modalProductId').textContent = '#' + product.id;
        
        // Set availability
        document.getElementById('modalProductAvailability').textContent = stock > 0 ? 'In Stock' : 'Out of Stock';
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('productModal'));
        modal.show();
    }
    
    function addToCart() {
        const quantity = document.getElementById('productQuantity').value;
        alert('Added ' + quantity + ' item(s) to cart! (Cart functionality coming soon)');
    }
    
    function buyNow() {
        alert('Proceeding to checkout... (Checkout functionality coming soon)');
    }
    </script>
</body>
</html>
<?php
// Close database connection
$conn->close();
?>
