-- ===================================
-- Database Migration Script
-- Upgrade existing recommendation_engine database 
-- to support buyer/seller marketplace features
-- ===================================

USE recommendation_engine;

-- Step 1: Add role and seller columns to users table
ALTER TABLE users 
    ADD COLUMN IF NOT EXISTS role ENUM('buyer', 'seller') DEFAULT 'buyer' AFTER password,
    ADD COLUMN IF NOT EXISTS seller_business_name VARCHAR(100) DEFAULT NULL AFTER role,
    ADD COLUMN IF NOT EXISTS seller_phone VARCHAR(20) DEFAULT NULL AFTER seller_business_name,
    ADD COLUMN IF NOT EXISTS seller_address TEXT DEFAULT NULL AFTER seller_phone,
    ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at,
    ADD INDEX IF NOT EXISTS idx_role (role);

-- Step 2: Add seller_id, price, and stock to products table
ALTER TABLE products 
    ADD COLUMN IF NOT EXISTS seller_id INT DEFAULT NULL AFTER id,
    ADD COLUMN IF NOT EXISTS price DECIMAL(10,2) DEFAULT 0.00 AFTER image,
    ADD COLUMN IF NOT EXISTS stock_quantity INT DEFAULT 0 AFTER description,
    ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at,
    ADD INDEX IF NOT EXISTS idx_seller (seller_id);

-- Step 3: Add foreign key constraint for seller_id
-- Note: This might fail if data already exists - run only on clean databases
ALTER TABLE products 
    ADD CONSTRAINT fk_products_seller 
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE SET NULL;

-- Step 4: Update existing products with default prices (random prices between $10-$500)
UPDATE products 
SET price = FLOOR(10 + (RAND() * 490)),
    stock_quantity = FLOOR(1 + (RAND() * 100))
WHERE price = 0.00;

-- ===================================
-- Migration Complete!
-- ===================================
-- All users are now set to 'buyer' role by default
-- They can upgrade to 'seller' via settings page
-- Existing products have random prices assigned
-- Sellers can now add their own products
-- ===================================
