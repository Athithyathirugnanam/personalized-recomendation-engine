-- ===================================
-- Personalized Recommendation Engine
-- Database Schema for MySQL
-- ===================================

-- Create Database
CREATE DATABASE IF NOT EXISTS recommendation_engine;
USE recommendation_engine;

-- ===================================
-- TABLE: users
-- Stores user account information
-- ===================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    google_id VARCHAR(255) DEFAULT NULL UNIQUE,
    facebook_id VARCHAR(255) DEFAULT NULL UNIQUE,
    role ENUM('buyer', 'seller') DEFAULT 'buyer',
    seller_business_name VARCHAR(100) DEFAULT NULL,
    seller_phone VARCHAR(20) DEFAULT NULL,
    seller_address TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_google_id (google_id),
    INDEX idx_facebook_id (facebook_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================
-- TABLE: interests
-- Master list of available interests
-- ===================================
CREATE TABLE IF NOT EXISTS interests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    interest_name VARCHAR(50) NOT NULL UNIQUE,
    icon VARCHAR(50) DEFAULT 'fa-tag',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================
-- TABLE: user_interests
-- Links users to their selected interests
-- ===================================
CREATE TABLE IF NOT EXISTS user_interests (
    user_id INT NOT NULL,
    interest_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, interest_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (interest_id) REFERENCES interests(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================
-- TABLE: products
-- Stores all products/content items
-- ===================================
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT DEFAULT NULL,
    title VARCHAR(200) NOT NULL,
    category VARCHAR(50) NOT NULL,
    image VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) DEFAULT 0.00,
    rating DECIMAL(2,1) DEFAULT 0.0,
    description TEXT,
    stock_quantity INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_rating (rating),
    INDEX idx_seller (seller_id),
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================
-- TABLE: user_favorites (Optional)
-- Track user favorite products
-- ===================================
CREATE TABLE IF NOT EXISTS user_favorites (
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================
-- INSERT SAMPLE DATA
-- ===================================

-- Insert Interests
INSERT IGNORE INTO interests (interest_name, icon) VALUES
('Action', 'fa-fist-raised'),
('Comedy', 'fa-smile'),
('Technology', 'fa-laptop-code'),
('Fashion', 'fa-tshirt'),
('Sports', 'fa-football-ball'),
('Music', 'fa-music'),
('Gaming', 'fa-gamepad'),
('Travel', 'fa-plane'),
('Food', 'fa-utensils'),
('Books', 'fa-book'),
('Science', 'fa-flask'),
('Art', 'fa-palette');

-- Insert Sample Products (Action Category)
INSERT INTO products (title, category, image, rating, description) VALUES
('The Matrix', 'Action', 'https://via.placeholder.com/300x400/667eea/ffffff?text=The+Matrix', 4.5, 'A mind-bending sci-fi action thriller that questions reality itself.'),
('Avengers: Endgame', 'Action', 'https://via.placeholder.com/300x400/667eea/ffffff?text=Avengers', 4.7, 'The epic conclusion to the Infinity Saga with all your favorite heroes.'),
('The Dark Knight', 'Action', '/static/images/products/The_Dark_Knight.jpeg', 5.0, 'Batman faces his greatest challenge yet with the Joker.'),
('Inception', 'Action', 'https://via.placeholder.com/300x400/fa709a/ffffff?text=Inception', 4.9, 'A thief who steals corporate secrets through dream-sharing technology.');

-- Insert Sample Products (Technology Category)
INSERT INTO products (title, category, image, rating, description) VALUES
('Gaming Pro Laptop', 'Technology', 'https://via.placeholder.com/300x400/764ba2/ffffff?text=Gaming+Pro', 4.0, 'High-performance gaming laptop with latest graphics card.'),
('iPhone 15 Pro', 'Technology', 'https://img.clevup.in/301826/1-1708521332360.jpeg?width=600&format=webp', 4.9, 'Latest iPhone with advanced camera system and A17 chip.'),
('MacBook Pro M3', 'Technology', '/static/images/products/MacBook_Pro_M3.webp', 4.9, 'Powerful laptop for professionals with M3 chip.'),
('Wireless Headphones', 'Technology', 'https://via.placeholder.com/300x400/fee140/333333?text=Headphones', 4.6, 'Premium noise-canceling wireless headphones.');

-- Insert Sample Products (Books Category)
INSERT INTO products (title, category, image, rating, description) VALUES
('Clean Code', 'Books', '/static/images/products/Clean_Code.webp', 5.0, 'A handbook of agile software craftsmanship by Robert C. Martin.'),
('Atomic Habits', 'Books', '/static/images/products/Atomic_Habits.webp', 5.0, 'An easy and proven way to build good habits and break bad ones.'),
('Python Crash Course', 'Books', 'https://via.placeholder.com/300x400/764ba2/ffffff?text=Python+Guide', 4.7, 'A hands-on, project-based introduction to programming.');

-- Insert Sample Products (Gaming Category)
INSERT INTO products (title, category, image, rating, description) VALUES
('PS5 Console', 'Gaming', 'https://via.placeholder.com/300x400/4facfe/ffffff?text=Gaming+Zone', 4.8, 'Next-generation gaming console with stunning graphics.'),
('Xbox Series X', 'Gaming', 'https://via.placeholder.com/300x400/4facfe/ffffff?text=Xbox+Series+X', 4.6, 'Powerful gaming console with 4K gaming capabilities.'),
('Spider-Man 2', 'Gaming', '/static/images/products/Spider_Man_2.jpeg', 5.0, 'Play as both Peter Parker and Miles Morales in this epic adventure.'),
('FIFA 24', 'Gaming', 'https://via.placeholder.com/300x400/4facfe/ffffff?text=FIFA+24', 4.4, 'The latest football gaming experience with HyperMotion technology.'),
('Game Strategy Guide', 'Gaming', 'https://via.placeholder.com/300x400/30cfd0/ffffff?text=Strategy+Guide', 4.1, 'Complete guide to master your favorite games.');

-- Insert Sample Products (Music Category)
INSERT INTO products (title, category, image, rating, description) VALUES
('Greatest Hits 2026', 'Music', 'https://via.placeholder.com/300x400/43e97b/ffffff?text=Top+Album', 4.2, 'Compilation of the best songs from 2026.'),
('AirPods Pro 2', 'Music', 'https://via.placeholder.com/300x400/43e97b/ffffff?text=AirPods+Pro', 4.8, 'Premium wireless earbuds with active noise cancellation.'),
('Spotify Premium', 'Music', 'https://via.placeholder.com/300x400/f093fb/ffffff?text=Spotify+Premium', 4.8, 'Unlimited music streaming with no ads.');

-- Insert Sample Products (Fashion Category)
INSERT INTO products (title, category, image, rating, description) VALUES
('Nike Air Max', 'Fashion', 'https://via.placeholder.com/300x400/fa709a/ffffff?text=Nike+Air', 4.3, 'Classic sneakers with modern comfort and style.');

-- Insert Sample Products (Comedy Category)
INSERT INTO products (title, category, image, rating, description) VALUES
('The Office Complete Series', 'Comedy', 'https://via.placeholder.com/300x400/667eea/ffffff?text=The+Office', 4.9, 'Complete collection of the beloved comedy series.'),
('Stand-Up Special 2026', 'Comedy', 'https://via.placeholder.com/300x400/43e97b/ffffff?text=Comedy+Special', 4.5, 'Latest stand-up comedy special from top comedians.');

-- Insert Sample Products (Science Category)
INSERT INTO products (title, category, image, rating, description) VALUES
('Introduction to AI', 'Science', 'https://via.placeholder.com/300x400/764ba2/ffffff?text=AI+Course', 4.7, 'Learn the fundamentals of Artificial Intelligence.'),
('Quantum Physics Explained', 'Science', 'https://via.placeholder.com/300x400/f093fb/ffffff?text=Quantum+Physics', 4.8, 'Understanding the mysteries of quantum mechanics.');

-- Insert Sample Products (Travel Category)
INSERT INTO products (title, category, image, rating, description) VALUES
('Paris Travel Guide', 'Travel', 'https://via.placeholder.com/300x400/fa709a/ffffff?text=Paris+Guide', 4.6, 'Complete guide to exploring the city of lights.'),
('Adventure Backpack', 'Travel', 'https://via.placeholder.com/300x400/43e97b/ffffff?text=Backpack', 4.4, 'Durable backpack perfect for all your adventures.');

-- Insert Sample Products (Art Category)
INSERT INTO products (title, category, image, rating, description) VALUES
('Digital Art Masterclass', 'Art', 'https://via.placeholder.com/300x400/fa709a/ffffff?text=Art+Class', 4.7, 'Learn digital art from professional artists.'),
('Premium Paint Set', 'Art', 'https://via.placeholder.com/300x400/f093fb/ffffff?text=Paint+Set', 4.5, 'Professional quality paints for artists.');

-- Insert Sample Products (Food Category)
INSERT INTO products (title, category, image, rating, description) VALUES
('Gourmet Cooking Course', 'Food', 'https://via.placeholder.com/300x400/fee140/333333?text=Cooking', 4.8, 'Learn to cook like a professional chef.'),
('International Recipes Book', 'Food', 'https://via.placeholder.com/300x400/43e97b/ffffff?text=Recipes', 4.6, 'Explore cuisines from around the world.');

-- Insert Sample Products (Sports Category)
INSERT INTO products (title, category, image, rating, description) VALUES
('Premium Running Shoes', 'Sports', 'https://via.placeholder.com/300x400/4facfe/ffffff?text=Running+Shoes', 4.7, 'High-performance running shoes for athletes.'),
('Fitness Tracker Pro', 'Sports', 'https://via.placeholder.com/300x400/43e97b/ffffff?text=Fitness+Tracker', 4.5, 'Track your workouts and health metrics.');

-- Add realistic demo prices and stock quantities so the UI shows meaningful values
UPDATE products SET price = 24.99, stock_quantity = 40 WHERE category = 'Books';
UPDATE products SET price = 59.99, stock_quantity = 35 WHERE category = 'Gaming';
UPDATE products SET price = 14.99, stock_quantity = 60 WHERE category = 'Music';
UPDATE products SET price = 79.99, stock_quantity = 30 WHERE category = 'Fashion';
UPDATE products SET price = 19.99, stock_quantity = 45 WHERE category = 'Comedy';
UPDATE products SET price = 29.99, stock_quantity = 25 WHERE category = 'Science';
UPDATE products SET price = 39.99, stock_quantity = 20 WHERE category = 'Travel';
UPDATE products SET price = 34.99, stock_quantity = 20 WHERE category = 'Art';
UPDATE products SET price = 19.99, stock_quantity = 50 WHERE category = 'Food';
UPDATE products SET price = 49.99, stock_quantity = 35 WHERE category = 'Sports';
UPDATE products SET price = 9.99, stock_quantity = 60 WHERE category = 'Action';
UPDATE products SET price = 999.99, stock_quantity = 15 WHERE category = 'Technology';

-- Fill missing values in existing data
UPDATE products
SET price = CASE
    WHEN LOWER(category) = 'technology' THEN 999.99
    WHEN LOWER(category) = 'gaming' THEN 59.99
    WHEN LOWER(category) = 'books' THEN 24.99
    WHEN LOWER(category) = 'music' THEN 14.99
    WHEN LOWER(category) = 'fashion' THEN 79.99
    WHEN LOWER(category) = 'travel' THEN 39.99
    ELSE 19.99
END
WHERE price IS NULL OR price <= 0;

UPDATE products
SET image = '/static/images/products/default-product.svg'
WHERE image IS NULL OR TRIM(image) = '';

-- Force local images for all products to avoid external image loading issues
UPDATE products
SET image = '/static/images/products/default-product.svg';

-- ===================================
-- Create a sample user (Optional - for testing)
-- Password: test123 (hashed)
-- ===================================
-- INSERT INTO users (name, email, password) VALUES
-- ('Test User', 'test@example.com', '$2y$10$example_hash_here');

-- ===================================
-- USEFUL QUERIES FOR TESTING
-- ===================================

-- View all users with their interests
-- SELECT u.name, u.email, GROUP_CONCAT(i.interest_name) as interests
-- FROM users u
-- LEFT JOIN user_interests ui ON u.id = ui.user_id
-- LEFT JOIN interests i ON ui.interest_id = i.id
-- GROUP BY u.id;

-- Get recommended products for a user based on interests
-- SELECT DISTINCT p.*, i.interest_name
-- FROM products p
-- JOIN interests i ON LOWER(p.category) = LOWER(i.interest_name)
-- JOIN user_interests ui ON i.id = ui.interest_id
-- WHERE ui.user_id = 1
-- ORDER BY p.rating DESC;

-- ===================================
-- END OF DATABASE SCHEMA
-- ===================================
