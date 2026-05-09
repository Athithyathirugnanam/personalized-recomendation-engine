<?php
/**
 * User Settings Page
 * Allows users to upgrade to seller role and manage account
 */

require_once 'config/db.php';

// Check if user is logged in
if (!is_logged_in()) {
    redirect('login.html');
}

// Get user data
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
$user_role = $_SESSION['user_role'] ?? 'buyer';

// Fetch full user details
$stmt = $conn->prepare("SELECT role, seller_business_name, seller_phone, seller_address FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$stmt->close();

$current_role = $user_data['role'] ?? 'buyer';
$business_name = $user_data['seller_business_name'] ?? '';
$phone = $user_data['seller_phone'] ?? '';
$address = $user_data['seller_address'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Personalized Recommendation Engine</title>
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
                        <a class="nav-link" href="<?php echo ($current_role === 'seller') ? 'seller_dashboard.php' : 'buyer_dashboard.php'; ?>">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="recommendations.php">
                            <i class="fas fa-shopping-bag"></i> Browse
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="settings.php">
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

    <!-- Settings Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="mb-4">
                        <i class="fas fa-cog me-2"></i>Account Settings
                    </h2>
                    
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success_message']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['success_message']); ?>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['error_message']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error_message']); ?>
                    <?php endif; ?>
                    
                    <!-- Account Info Card -->
                    <div class="card mb-4">
                        <div class="card-header bg-gradient-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-user me-2"></i>Account Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Name</label>
                                    <p class="text-muted"><?php echo htmlspecialchars($user_name); ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Email</label>
                                    <p class="text-muted"><?php echo htmlspecialchars($user_email); ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Current Role</label>
                                    <p>
                                        <?php if ($current_role === 'seller'): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-store me-1"></i>Seller
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-primary">
                                                <i class="fas fa-shopping-cart me-1"></i>Buyer
                                            </span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upgrade to Seller -->
                    <?php if ($current_role === 'buyer'): ?>
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-store me-2"></i>Become a Seller</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-4">Upgrade your account to start selling products on our platform!</p>
                            <form action="upgrade_to_seller_process.php" method="POST">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="business_name" class="form-label">Business Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="business_name" name="business_name" 
                                               placeholder="Enter your business name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               placeholder="Enter your phone number" required>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="address" class="form-label">Business Address</label>
                                        <textarea class="form-control" id="address" name="address" rows="3" 
                                                  placeholder="Enter your business address"></textarea>
                                    </div>
                                </div>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Benefits of becoming a seller:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>List your products</li>
                                        <li>Manage inventory</li>
                                        <li>View sales analytics</li>
                                        <li>Reach more customers</li>
                                    </ul>
                                </div>
                                <button type="submit" class="btn btn-warning btn-lg">
                                    <i class="fas fa-arrow-up me-2"></i>Upgrade to Seller Account
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php else: ?>
                    <!-- Seller Information -->
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-building me-2"></i>Seller Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Business Name</label>
                                    <p class="text-muted"><?php echo htmlspecialchars($business_name ?? 'N/A'); ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Phone Number</label>
                                    <p class="text-muted"><?php echo htmlspecialchars($phone ?? 'N/A'); ?></p>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">Business Address</label>
                                    <p class="text-muted"><?php echo htmlspecialchars($address ?? 'N/A'); ?></p>
                                </div>
                            </div>
                            <a href="seller_dashboard.php" class="btn btn-success">
                                <i class="fas fa-store-alt me-2"></i>Go to Seller Dashboard
                            </a>
                        </div>
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
