<?php
/**
 * Recommendations Page with Backend Integration
 * Displays all products with filtering capabilities
 */

// Include database connection
require_once 'config/db.php';

// Get filter parameters frominput (GET or POST)
$category_filter = isset($_GET['category']) ? sanitize_input($_GET['category']) : 'all';
$rating_filter = isset($_GET['rating']) ? sanitize_input($_GET['rating']) : 'all';
$sort_by = isset($_GET['sort']) ? sanitize_input($_GET['sort']) : 'rating';

// Build SQL query based on filters
$query = "SELECT * FROM products WHERE 1=1";
$params = array();
$types = "";

// Apply category filter
if ($category_filter != 'all') {
    $query .= " AND category = ?";
    $params[] = $category_filter;
    $types .= "s";
}

// Apply rating filter
if ($rating_filter != 'all') {
    $rating_value = floatval($rating_filter);
    $query .= " AND rating >= ?";
    $params[] = $rating_value;
    $types .= "d";
}

// Apply sorting
switch ($sort_by) {
    case 'rating':
        $query .= " ORDER BY rating DESC";
        break;
    case 'popular':
        $query .= " ORDER BY rating DESC, title ASC";
        break;
    case 'newest':
        $query .= " ORDER BY created_at DESC";
        break;
    default:
        $query .= " ORDER BY rating DESC";
}

// Execute query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$all_products = array();
while ($row = $result->fetch_assoc()) {
    $all_products[] = $row;
}
$stmt->close();

// Get unique categories for filter dropdown
$categories_query = "SELECT DISTINCT category FROM products ORDER BY category";
$categories_result = $conn->query($categories_query);
$categories = array();
while ($row = $categories_result->fetch_assoc()) {
    $categories[] = $row['category'];
}

$total_products = count($all_products);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recommendations - Personalized Recommendation Engine</title>
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
                    <?php if (is_logged_in()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo ($_SESSION['user_role'] === 'seller') ? 'seller_dashboard.php' : 'buyer_dashboard.php'; ?>">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="recommendations.php">
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
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.html">
                                <i class="fas fa-home"></i> Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="recommendations.php">
                                <i class="fas fa-shopping-bag"></i> Browse
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.html">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.html">
                                <i class="fas fa-user-plus"></i> Register
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header bg-gradient-primary text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold mb-3">
                        <i class="fas fa-magic me-3"></i>Browse Recommendations
                    </h1>
                    <p class="lead mb-0">Discover amazing content curated just for you</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="filter-section py-4 bg-light">
        <div class="container">
            <form method="GET" action="recommendations.php">
                <div class="row align-items-center">
                    <div class="col-md-3 mb-3 mb-md-0">
                        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter By:</h5>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <select class="form-select" name="category" id="categoryFilter" onchange="this.form.submit()">
                            <option value="all" <?php echo ($category_filter == 'all') ? 'selected' : ''; ?>>All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo ($category_filter == $cat) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars(ucfirst($cat)); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <select class="form-select" name="rating" id="ratingFilter" onchange="this.form.submit()">
                            <option value="all" <?php echo ($rating_filter == 'all') ? 'selected' : ''; ?>>All Ratings</option>
                            <option value="5" <?php echo ($rating_filter == '5') ? 'selected' : ''; ?>>5 Stars</option>
                            <option value="4" <?php echo ($rating_filter == '4') ? 'selected' : ''; ?>>4+ Stars</option>
                            <option value="3" <?php echo ($rating_filter == '3') ? 'selected' : ''; ?>>3+ Stars</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="sort" id="sortBy" onchange="this.form.submit()">
                            <option value="rating" <?php echo ($sort_by == 'rating') ? 'selected' : ''; ?>>Highest Rated</option>
                            <option value="popular" <?php echo ($sort_by == 'popular') ? 'selected' : ''; ?>>Most Popular</option>
                            <option value="newest" <?php echo ($sort_by == 'newest') ? 'selected' : ''; ?>>Newest</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Recommendations Grid -->
    <section class="recommendations-section py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h4 class="fw-bold">
                        <span id="resultsCount"><?php echo $total_products; ?></span> Recommendations Found
                    </h4>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="row g-4" id="productsGrid">
                <?php if (!empty($all_products)): ?>
                    <?php foreach ($all_products as $product): ?>
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
                                <?php if (isset($product['stock_quantity']) && $product['stock_quantity'] > 0): ?>
                                    <small class="text-muted d-block mb-2"><?php echo $product['stock_quantity']; ?> in stock</small>
                                <?php else: ?>
                                    <small class="text-danger d-block mb-2">Out of stock</small>
                                <?php endif; ?>
                                <button class="btn btn-primary btn-sm w-100" 
                                        onclick='showProductDetails(<?php echo json_encode($product); ?>)'>
                                    <i class="fas fa-shopping-cart me-1"></i>View Details
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No products found matching your criteria. Try adjusting the filters.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
                    </h4>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="row g-4" id="productsGrid">
                <?php if (!empty($all_products)): ?>
                    <?php foreach ($all_products as $product): ?>
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
                                    $half_star = ($rating - $full_stars) >= 0.5 ? 1 : 0;
                                    $empty_stars = 5 - $full_stars - $half_star;
                                    
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
                                    <i class="fas fa-shopping-cart me-1"></i>View Details
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>No products found matching your filters. Try adjusting your search criteria.
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <div class="row mt-5">
                <div class="col-12">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>

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
