-- ===========================================
-- Update Products with Real Working Images
-- Run this to add actual product images
-- ===========================================

USE recommendation_engine;

-- Clear existing products
TRUNCATE TABLE products;

-- Technology Products with Real Images
INSERT INTO products (seller_id, title, category, image, price, rating, description, stock_quantity) VALUES
(NULL, 'Premium Wireless Headphones', 'Technology', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop', 299.99, 4.8, 'Premium over-ear wireless headphones with active noise cancellation, 30-hour battery life, and superior sound quality.', 45),
(NULL, 'Smart Watch Pro', 'Technology', 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=400&fit=crop', 399.99, 4.6, 'Advanced fitness tracking, heart rate monitoring, GPS, and smartphone notifications. Water-resistant up to 50m.', 67),
(NULL, 'Mechanical Gaming Keyboard', 'Technology', 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=400&h=400&fit=crop', 149.99, 4.7, 'RGB backlit mechanical gaming keyboard with customizable keys, anti-ghosting technology, and ergonomic design.', 89),
(NULL, 'Ultra HD Webcam', 'Technology', 'https://images.unsplash.com/photo-1587826080692-f439cd0b70da?w=400&h=400&fit=crop', 89.99, 4.5, '4K Ultra HD webcam with auto-focus, built-in microphone, and low-light correction for perfect video calls.', 120),
(NULL, 'Wireless Mouse', 'Technology', 'https://images.unsplash.com/photo-1527814050087-3793815479db?w=400&h=400&fit=crop', 49.99, 4.4, 'Ergonomic wireless mouse with precision tracking, 6 programmable buttons, and long battery life.', 200),

-- Fashion Products
(NULL, 'Classic White Sneakers', 'Fashion', 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400&h=400&fit=crop', 79.99, 4.6, 'Timeless white sneakers perfect for any casual outfit. Made with premium materials for comfort and durability.', 150),
(NULL, 'Leather Crossbody Bag', 'Fashion', 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=400&h=400&fit=crop', 129.99, 4.8, 'Elegant leather crossbody bag with adjustable strap. Multiple compartments for organization.', 75),
(NULL, 'Denim Jacket', 'Fashion', 'https://images.unsplash.com/photo-1576871337632-b9aef4c17ab9?w=400&h=400&fit=crop', 89.99, 4.5, 'Classic denim jacket that never goes out of style. Comfortable fit and versatile for any season.', 95),
(NULL, 'Aviator Sunglasses', 'Fashion', 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&h=400&fit=crop', 159.99, 4.7, 'Premium aviator sunglasses with UV protection and polarized lenses. Iconic style meets functionality.', 110),

-- Books
(NULL, 'The Art of Programming', 'Books', 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400&h=400&fit=crop', 45.99, 4.9, 'Comprehensive guide to modern programming practices. Perfect for beginners and experienced developers.', 200),
(NULL, 'Mindfulness & Meditation', 'Books', 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400&h=400&fit=crop', 24.99, 4.7, 'Discover inner peace through mindfulness and meditation techniques. Transform your daily life.', 180),
(NULL, 'World History Encyclopedia', 'Books', 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=400&h=400&fit=crop', 59.99, 4.8, 'Explore the fascinating journey of human civilization from ancient times to the modern era.', 95),

-- Sports & Fitness
(NULL, 'Yoga Mat Premium', 'Sports', 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=400&h=400&fit=crop', 39.99, 4.6, 'Non-slip premium yoga mat with extra thickness for comfort. Eco-friendly materials, includes carrying strap.', 250),
(NULL, 'Adjustable Dumbbells Set', 'Sports', 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=400&h=400&fit=crop', 199.99, 4.8, 'Space-saving adjustable dumbbell set, 5-52.5lbs per dumbbell. Perfect for home gym workouts.', 78),
(NULL, 'Running Shoes Pro', 'Sports', 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=400&fit=crop', 129.99, 4.7, 'Professional running shoes with responsive cushioning and breathable mesh upper. Built for performance.', 145),

-- Gaming
(NULL, 'Gaming Controller Elite', 'Gaming', 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?w=400&h=400&fit=crop', 179.99, 4.9, 'Elite wireless gaming controller with customizable buttons, hair-trigger locks, and interchangeable components.', 92),
(NULL, 'Gaming Headset RGB', 'Gaming', 'https://images.unsplash.com/photo-1599669454699-248893623440?w=400&h=400&fit=crop', 99.99, 4.6, 'Immersive 7.1 surround sound gaming headset with RGB lighting and crystal-clear microphone.', 156),
(NULL, 'Gaming Chair Ergonomic', 'Gaming', 'https://images.unsplash.com/photo-1598550476439-6847785fcea6?w=400&h=400&fit=crop', 299.99, 4.7, 'Professional ergonomic gaming chair with lumbar support, adjustable armrests, and premium materials.', 45),

-- Music
(NULL, 'Electric Guitar Stratocaster', 'Music', 'https://images.unsplash.com/photo-1510915361894-db8b60106cb1?w=400&h=400&fit=crop', 599.99, 4.8, 'Classic electric guitar with versatile tone options. Perfect for beginners and professionals alike.', 35),
(NULL, 'Studio Microphone Pro', 'Music', 'https://images.unsplash.com/photo-1589003077984-894e133dabab?w=400&h=400&fit=crop', 249.99, 4.9, 'Professional condenser microphone for studio recording, podcasting, and streaming. Crystal-clear audio.', 67),
(NULL, 'Bluetooth Speaker Portable', 'Music', 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=400&h=400&fit=crop', 79.99, 4.5, 'Waterproof portable Bluetooth speaker with 360° sound and 24-hour battery life. Perfect for outdoor adventures.', 198),

-- Home & Garden (Food/Art category)
(NULL, 'Stainless Steel Cookware Set', 'Food', 'https://images.unsplash.com/photo-1584990347449-39b0e17e6a3e?w=400&h=400&fit=crop', 299.99, 4.7, '10-piece professional cookware set. Non-stick, oven-safe, and dishwasher friendly.', 88),
(NULL, 'Coffee Maker Deluxe', 'Food', 'https://images.unsplash.com/photo-1517668808822-9ebb02f2a0e6?w=400&h=400&fit=crop', 149.99, 4.6, 'Programmable coffee maker with thermal carafe, brew strength control, and auto shut-off.', 125),

-- Art & Creative
(NULL, 'Professional Paint Brush Set', 'Art', 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=400&h=400&fit=crop', 69.99, 4.8, 'Premium artist brush set with 24 pieces. Perfect for watercolor, acrylic, and oil painting.', 145),
(NULL, 'Digital Drawing Tablet', 'Art', 'https://images.unsplash.com/photo-1611532736579-6b16e2b50449?w=400&h=400&fit=crop', 399.99, 4.9, 'Professional drawing tablet with 8192 pressure levels and tilt recognition. Includes stylus pen.', 56),

-- Travel
(NULL, 'Travel Backpack 40L', 'Travel', 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&h=400&fit=crop', 89.99, 4.7, 'Durable 40L travel backpack with laptop compartment, USB charging port, and water-resistant material.', 167),
(NULL, 'Luggage Set Premium', 'Travel', 'https://images.unsplash.com/photo-1565026057447-bc90a3dceb87?w=400&h=400&fit=crop', 249.99, 4.8, '3-piece hardshell luggage set with spinner wheels and TSA locks. Lightweight and durable.', 78),

-- Science/Technology Educational
(NULL, 'Arduino Starter Kit', 'Science', 'https://images.unsplash.com/photo-1553406830-ef2513450d76?w=400&h=400&fit=crop', 79.99, 4.9, 'Complete Arduino starter kit with sensors, components, and project guide. Perfect for learning electronics.', 145),
(NULL, 'Telescope Astronomical', 'Science', 'https://images.unsplash.com/photo-1614642288276-75c68b7f7d5e?w=400&h=400&fit=crop', 299.99, 4.7, 'High-powered astronomical telescope with tripod. Explore the moon, planets, and distant galaxies.', 42);

-- ===================================
-- Verification Query
-- ===================================
-- Run this to verify products were inserted:
-- SELECT id, title, category, SUBSTRING(image, 1, 50) as image_preview, price, stock_quantity FROM products;
