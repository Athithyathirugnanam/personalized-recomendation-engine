-- Sample Products with Working Image URLs
-- Run this to add sample products to your database for testing

-- Make sure you're logged in as a seller first!
-- These products use real Unsplash images that will work

INSERT INTO products (seller_id, title, category, image, price, rating, description, stock_quantity) VALUES
(1, 'Wireless Bluetooth Headphones', 'Electronics', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop', 79.99, 4.5, 'Premium wireless headphones with noise cancellation, 30-hour battery life, and superior sound quality.', 50),
(1, 'Smart Watch Series 5', 'Electronics', 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=400&fit=crop', 299.99, 4.8, 'Advanced fitness tracking, heart rate monitor, GPS, and smartphone notifications on your wrist.', 30),
(1, 'Vintage Leather Backpack', 'Fashion', 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&h=400&fit=crop', 89.99, 4.3, 'Handcrafted genuine leather backpack with laptop compartment and vintage styling.', 25),
(1, 'Running Shoes Pro', 'Sports', 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=400&fit=crop', 129.99, 4.7, 'Lightweight performance running shoes with enhanced cushioning and breathable mesh upper.', 100),
(1, 'Bestselling Novel Collection', 'Books', 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400&h=400&fit=crop', 34.99, 4.9, 'Collection of 5 award-winning novels from renowned contemporary authors.', 75),
(1, 'Minimalist Table Lamp', 'Home', 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=400&h=400&fit=crop', 45.99, 4.4, 'Modern LED desk lamp with adjustable brightness and sleek minimalist design.', 60),
(1, 'Yoga Mat Premium', 'Sports', 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=400&h=400&fit=crop', 39.99, 4.6, 'Eco-friendly non-slip yoga mat with extra cushioning and carrying strap included.', 120),
(1, 'Gourmet Coffee Beans', 'Food', 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=400&h=400&fit=crop', 24.99, 4.8, 'Premium single-origin Arabica coffee beans, medium roast. 1lb bag.', 200);

-- If you need to update existing products with working images:
-- UPDATE products SET image = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop' WHERE id = 1;
-- UPDATE products SET image = 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=400&fit=crop' WHERE id = 2;

-- Sample placeholder if you want to test fallback:
-- UPDATE products SET image = 'broken-url.jpg' WHERE id = 1;
