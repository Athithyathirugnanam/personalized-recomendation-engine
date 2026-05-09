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
                        <a class="nav-link" href="index.html">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="recommendations.php">Recommendations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.html#about">About</a>
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
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                                <div class="product-badge"><?php echo htmlspecialchars($product['category']); ?></div>
                            </div>
                            <div class="product-content">
                                <h5 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h5>
                                <p class="product-category">
                                    <i class="fas fa-tag me-1"></i><?php echo htmlspecialchars($product['category']); ?>
                                </p>
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
                                    <span class="ms-2"><?php echo number_format($rating, 1); ?></span>
                                </div>
                                <button class="btn btn-primary btn-sm w-100" onclick="alert('<?php echo addslashes($product['description']); ?>')">
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
