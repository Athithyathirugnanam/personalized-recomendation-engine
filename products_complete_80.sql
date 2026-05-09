-- ===========================================
-- Complete Product Database - 80+ Products
-- Covers all interest categories with variety
-- ===========================================

USE recommendation_engine;

-- ===========================================
-- TECHNOLOGY PRODUCTS (15 items)
-- ===========================================
INSERT INTO products (seller_id, title, category, image, price, rating, description, stock_quantity) VALUES
-- Computers & Laptops
(NULL, 'MacBook Pro 16" M3 Max', 'Technology', 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400&h=400&fit=crop', 2499.99, 4.9, 'Powerful laptop with M3 Max chip, 36GB RAM, 1TB SSD. Perfect for professionals and creators.', 25),
(NULL, 'Dell XPS 15 Developer Edition', 'Technology', 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=400&h=400&fit=crop', 1899.99, 4.7, 'Premium ultrabook with Intel i9, 32GB RAM, NVIDIA RTX graphics. Ubuntu pre-installed.', 40),
(NULL, 'Gaming Desktop RTX 4090', 'Technology', 'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=400&h=400&fit=crop', 3299.99, 4.8, 'Ultimate gaming PC with RTX 4090, AMD Ryzen 9, 64GB RAM, liquid cooling.', 15),

-- Audio & Headphones
(NULL, 'Sony WH-1000XM5 Headphones', 'Technology', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop', 399.99, 4.8, 'Industry-leading noise cancellation, 30-hour battery, premium comfort.', 120),
(NULL, 'Apple AirPods Pro 2nd Gen', 'Technology', 'https://images.unsplash.com/photo-1606220945770-b5b6c2c55bf1?w=400&h=400&fit=crop', 249.99, 4.7, 'Advanced H2 chip, adaptive audio, personalized spatial audio with USB-C.', 200),
(NULL, 'Bose QuietComfort Earbuds', 'Technology', 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=400&h=400&fit=crop', 299.99, 4.6, 'Comfortable earbuds with world-class noise cancellation and rich sound.', 85),

-- Wearables
(NULL, 'Apple Watch Ultra 2', 'Technology', '/static/images/products/Apple_Watch_Ultra_2.jpeg', 799.99, 4.9, 'Rugged titanium design, precision GPS, ocean-ready, extreme battery life.', 60),
(NULL, 'Samsung Galaxy Watch 6', 'Technology', 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=400&fit=crop', 349.99, 4.6, 'Advanced health tracking, ECG, sleep monitoring, sleek design.', 95),
(NULL, 'Fitbit Sense 2', 'Technology', 'https://images.unsplash.com/photo-1608889825205-eebdb9fc5806?w=400&h=400&fit=crop', 249.99, 4.5, 'Comprehensive health smartwatch with stress management tools.', 110),

-- Cameras & Photography
(NULL, 'Sony A7 IV Mirrorless Camera', 'Technology', '/static/images/products/Sony_A7_IV_Mirrorless_Camera.webp', 2499.99, 4.9, 'Professional full-frame camera with 33MP sensor, 4K 60fps video.', 30),
(NULL, 'Canon EOS R6 Mark II', 'Technology', 'https://images.unsplash.com/photo-1606800052052-a08af7148866?w=400&h=400&fit=crop', 2399.99, 4.8, '24MP full-frame sensor, advanced autofocus, 40fps continuous shooting.', 28),

-- Peripherals
(NULL, 'Logitech MX Master 3S Mouse', 'Technology', 'https://images.unsplash.com/photo-1527814050087-3793815479db?w=400&h=400&fit=crop', 99.99, 4.8, 'Flagship mouse with 8K DPI sensor, quiet clicks, ergonomic design.', 180),
(NULL, 'Mechanical RGB Keyboard Pro', 'Technology', 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=400&h=400&fit=crop', 179.99, 4.7, 'Cherry MX switches, per-key RGB, aluminum frame, programmable macros.', 145),
(NULL, 'Webcam 4K Pro StreamCam', 'Technology', 'https://images.unsplash.com/photo-1587826080692-f439cd0b70da?w=400&h=400&fit=crop', 129.99, 4.6, '4K at 30fps, auto-framing, dual microphones, works in low light.', 90),
(NULL, 'USB-C Docking Station Dual 4K', 'Technology', 'https://images.unsplash.com/photo-1625948515291-69613efd103f?w=400&h=400&fit=crop', 249.99, 4.7, 'Powers two 4K monitors, 100W charging, 11 ports, single cable setup.', 75),

-- ===========================================
-- FASHION PRODUCTS (12 items)
-- ===========================================
-- Shoes
(NULL, 'Nike Air Max 270 React', 'Fashion', 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=400&fit=crop', 159.99, 4.7, 'Comfortable cushioning, modern design, perfect for everyday wear.', 200),
(NULL, 'Adidas Ultraboost 22', 'Fashion', 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?w=400&h=400&fit=crop', 189.99, 4.8, 'Energy-returning cushioning, breathable knit upper, iconic style.', 175),
(NULL, 'Classic White Leather Sneakers', 'Fashion', 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400&h=400&fit=crop', 89.99, 4.6, 'Minimalist design, premium leather, versatile for any outfit.', 250),
(NULL, 'Chelsea Boots Premium Leather', 'Fashion', 'https://images.unsplash.com/photo-1638247025967-b4e38f787b76?w=400&h=400&fit=crop', 199.99, 4.7, 'Handcrafted leather boots, elastic side panels, timeless style.', 80),

-- Bags & Accessories
(NULL, 'Leather Messenger Bag', 'Fashion', 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=400&h=400&fit=crop', 149.99, 4.8, 'Genuine leather, laptop compartment, professional look for work.', 120),
(NULL, 'Designer Crossbody Bag', 'Fashion', 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=400&h=400&fit=crop', 179.99, 4.7, 'Premium leather, adjustable strap, multiple compartments.', 95),
(NULL, 'Canvas Backpack Vintage', 'Fashion', 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&h=400&fit=crop', 79.99, 4.5, 'Durable canvas, leather accents, perfect for daily commute.', 160),

-- Clothing
(NULL, 'Classic Denim Jacket', 'Fashion', 'https://images.unsplash.com/photo-1576871337632-b9aef4c17ab9?w=400&h=400&fit=crop', 119.99, 4.6, 'Timeless design, premium denim, fitted cut for modern look.', 140),
(NULL, 'Wool Blend Winter Coat', 'Fashion', 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=400&h=400&fit=crop', 249.99, 4.8, 'Warm wool blend, water-resistant, elegant design for cold weather.', 65),
(NULL, 'Athletic Hoodie Premium', 'Fashion', 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=400&h=400&fit=crop', 69.99, 4.5, 'Soft fleece interior, moisture-wicking, perfect for workouts.', 220),

-- Sunglasses & Watches
(NULL, 'Ray-Ban Aviator Classic', 'Fashion', 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&h=400&fit=crop', 169.99, 4.8, 'Iconic aviator style, UV protection, polarized lenses.', 150),
(NULL, 'Luxury Automatic Watch', 'Fashion', '/static/images/products/Luxury_Automatic_Watch.jpeg', 899.99, 4.9, 'Swiss movement, sapphire crystal, stainless steel, 50m water resistant.', 35),

-- ===========================================
-- BOOKS PRODUCTS (10 items)
-- ===========================================
(NULL, 'Clean Code: Software Craftsmanship', 'Books', '/static/images/products/Clean_Code_Software_Craftsmanship.jpeg', 44.99, 4.9, 'Essential guide to writing clean, maintainable code. Must-read for developers.', 300),
(NULL, 'Atomic Habits by James Clear', 'Books', '/static/images/products/Atomic_Habits.webp', 27.99, 4.9, 'Transform your life with tiny changes. Build good habits, break bad ones.', 450),
(NULL, 'The Psychology of Money', 'Books', 'https://images.unsplash.com/photo-1592496431122-2349e0fbc666?w=400&h=400&fit=crop', 24.99, 4.8, 'Timeless lessons on wealth, greed, and happiness with money.', 380),
(NULL, 'Sapiens: A Brief History', 'Books', 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400&h=400&fit=crop', 29.99, 4.8, 'Journey through human history from Stone Age to modern age.', 290),
(NULL, 'The Lean Startup', 'Books', 'https://images.unsplash.com/photo-1553729459-efe14ef6055d?w=400&h=400&fit=crop', 32.99, 4.7, 'How to build successful startups using continuous innovation.', 250),
(NULL, 'Deep Work by Cal Newport', 'Books', 'https://images.unsplash.com/photo-1524578271613-d550eacf6090?w=400&h=400&fit=crop', 26.99, 4.8, 'Rules for focused success in a distracted world. Master concentration.', 320),
(NULL, 'Designing Data-Intensive Apps', 'Books', '/static/images/products/Designing_Data_Intensive_Apps.jpeg', 54.99, 4.9, 'The big ideas behind reliable, scalable systems. For software engineers.', 180),
(NULL, 'Thinking, Fast and Slow', 'Books', 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400&h=400&fit=crop', 28.99, 4.8, 'How we make decisions. Two systems that drive the way we think.', 270),
(NULL, 'The Pragmatic Programmer', 'Books', 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=400&h=400&fit=crop', 49.99, 4.9, 'From journeyman to master. Essential programming wisdom.', 210),
(NULL, 'Zero to One by Peter Thiel', 'Books', 'https://images.unsplash.com/photo-1519682337058-a94d519337bc?w=400&h=400&fit=crop', 25.99, 4.7, 'Notes on startups and how to build the future. Silicon Valley insights.', 340),

-- ===========================================
-- SPORTS & FITNESS PRODUCTS (12 items)
-- ===========================================
(NULL, 'Premium Yoga Mat 6mm', 'Sports', 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=400&h=400&fit=crop', 49.99, 4.7, 'Non-slip, eco-friendly TPE material, extra thick for comfort.', 280),
(NULL, 'Adjustable Dumbbell Set 52.5lb', 'Sports', 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=400&h=400&fit=crop', 349.99, 4.8, 'Space-saving design, 15 weight settings per dumbbell, quick adjustment.', 85),
(NULL, 'Nike Air Zoom Pegasus Running', 'Sports', 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=400&fit=crop', 139.99, 4.7, 'Responsive cushioning, breathable mesh, perfect for daily runs.', 195),
(NULL, 'Resistance Bands Set of 5', 'Sports', 'https://images.unsplash.com/photo-1598289431512-b97b0917affc?w=400&h=400&fit=crop', 29.99, 4.6, 'Five resistance levels, portable workout solution, includes door anchor.', 420),
(NULL, 'Foam Roller Deep Tissue', 'Sports', 'https://images.unsplash.com/photo-1611016186353-9af58c69a533?w=400&h=400&fit=crop', 34.99, 4.7, 'High-density EVA foam, grid design for muscle recovery and flexibility.', 310),
(NULL, 'Jump Rope Speed Training', 'Sports', 'https://images.unsplash.com/photo-1621167177420-27a36f19b98b?w=400&h=400&fit=crop', 24.99, 4.5, 'Ball-bearing system, adjustable length, perfect for cardio workouts.', 385),
(NULL, 'Kettlebell Cast Iron 35lb', 'Sports', 'https://images.unsplash.com/photo-1598977123118-4e30ba3c4f5b?w=400&h=400&fit=crop', 59.99, 4.8, 'Solid cast iron, powder-coated finish, wide handle for comfort.', 125),
(NULL, 'Exercise Bike Indoor Cycling', 'Sports', 'https://images.unsplash.com/photo-1576678927484-cc907957dd8a?w=400&h=400&fit=crop', 399.99, 4.7, 'Magnetic resistance, LCD monitor, adjustable seat and handlebars.', 45),
(NULL, 'Pull-Up Bar Doorway Mount', 'Sports', 'https://images.unsplash.com/photo-1599058917212-d750089bc07e?w=400&h=400&fit=crop', 39.99, 4.6, 'No-screw installation, supports 300lbs, multiple grip positions.', 265),
(NULL, 'Gym Bag Duffel Large', 'Sports', 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&h=400&fit=crop', 54.99, 4.6, 'Waterproof material, shoe compartment, adjustable shoulder strap.', 175),
(NULL, 'Boxing Gloves Professional', 'Sports', 'https://images.unsplash.com/photo-1605894337883-f4e63bf5c425?w=400&h=400&fit=crop', 79.99, 4.7, 'Premium leather, multi-layer foam padding, secure velcro closure.', 140),
(NULL, 'Treadmill Folding Electric', 'Sports', 'https://images.unsplash.com/photo-1576678927484-cc907957dd8a?w=400&h=400&fit=crop', 599.99, 4.6, '12 preset programs, 3HP motor, foldable design, heart rate monitor.', 38),

-- ===========================================
-- GAMING PRODUCTS (10 items)
-- ===========================================
(NULL, 'PlayStation 5 Slim Console', 'Gaming', 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?w=400&h=400&fit=crop', 499.99, 4.9, 'Latest PS5 with 1TB SSD, ray tracing, 4K gaming at 120fps.', 65),
(NULL, 'Xbox Series X Console', 'Gaming', 'https://images.unsplash.com/photo-1621259182978-fbf93132d53d?w=400&h=400&fit=crop', 499.99, 4.8, 'Most powerful Xbox ever, 4K at 120fps, 1TB storage, Game Pass ready.', 58),
(NULL, 'Nintendo Switch OLED', 'Gaming', 'https://images.unsplash.com/photo-1578303512597-81e6cc155b3e?w=400&h=400&fit=crop', 349.99, 4.8, '7-inch OLED screen, enhanced audio, 64GB storage, versatile gaming.', 95),
(NULL, 'Elite Gaming Controller Pro', 'Gaming', 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?w=400&h=400&fit=crop', 199.99, 4.8, 'Customizable buttons, hair-trigger locks, rechargeable battery pack.', 120),
(NULL, 'RGB Gaming Headset 7.1', 'Gaming', 'https://images.unsplash.com/photo-1599669454699-248893623440?w=400&h=400&fit=crop', 119.99, 4.7, 'Virtual 7.1 surround sound, noise-cancelling mic, RGB lighting.', 185),
(NULL, 'Gaming Chair Ergonomic Pro', 'Gaming', 'https://images.unsplash.com/photo-1598550476439-6847785fcea6?w=400&h=400&fit=crop', 329.99, 4.7, 'Premium PU leather, lumbar support, 4D armrests, tilt mechanism.', 72),
(NULL, 'Gaming Monitor 27" 165Hz', 'Gaming', 'https://images.unsplash.com/photo-1593640408182-31c70c8268f5?w=400&h=400&fit=crop', 399.99, 4.8, '1440p QHD, 165Hz refresh rate, 1ms response, G-Sync compatible.', 45),
(NULL, 'Streaming Microphone USB', 'Gaming', 'https://images.unsplash.com/photo-1589003077984-894e133dabab?w=400&h=400&fit=crop', 129.99, 4.7, 'Studio-quality sound, pop filter included, RGB lighting, mute button.', 165),
(NULL, 'Webcam 1080p Streaming', 'Gaming', 'https://images.unsplash.com/photo-1587826080692-f439cd0b70da?w=400&h=400&fit=crop', 79.99, 4.6, 'Full HD 1080p at 60fps, autofocus, built-in dual microphones.', 210),
(NULL, 'Gaming Desk Large L-Shape', 'Gaming', 'https://images.unsplash.com/photo-1595515106969-1ce29566ff1c?w=400&h=400&fit=crop', 249.99, 4.6, 'L-shaped design, cable management, carbon fiber surface, LED strip.', 55),

-- ===========================================
-- MUSIC PRODUCTS (8 items)
-- ===========================================
(NULL, 'Fender Stratocaster Electric', 'Music', 'https://images.unsplash.com/photo-1510915361894-db8b60106cb1?w=400&h=400&fit=crop', 799.99, 4.9, 'Classic American Strat, alder body, maple neck, vintage tone.', 42),
(NULL, 'Yamaha Digital Piano 88-Key', 'Music', 'https://images.unsplash.com/photo-1520523839897-bd0b52f945a0?w=400&h=400&fit=crop', 599.99, 4.8, 'Weighted keys, 10 voices, built-in speakers, MIDI connectivity.', 38),
(NULL, 'Studio Condenser Mic XLR', 'Music', 'https://images.unsplash.com/photo-1589003077984-894e133dabab?w=400&h=400&fit=crop', 299.99, 4.8, 'Large diaphragm, cardioid pattern, perfect for vocals and instruments.', 95),
(NULL, 'JBL Charge 5 Bluetooth Speaker', 'Music', 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=400&h=400&fit=crop', 179.99, 4.7, 'Powerful sound, 20-hour battery, waterproof IP67, PartyBoost.', 220),
(NULL, 'Audio Interface USB-C 2x2', 'Music', 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=400&h=400&fit=crop', 199.99, 4.7, '24-bit/192kHz, 2 combo inputs, studio-quality preamps, bus-powered.', 115),
(NULL, 'Studio Monitor Speakers Pair', 'Music', 'https://images.unsplash.com/photo-1545454675-3531b543be5d?w=400&h=400&fit=crop', 349.99, 4.8, '5-inch woofer, accurate sound reproduction, bi-amplified design.', 68),
(NULL, 'MIDI Keyboard Controller 61-Key', 'Music', 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?w=400&h=400&fit=crop', 249.99, 4.7, 'Semi-weighted keys, drum pads, faders, knobs, USB-powered.', 85),
(NULL, 'Acoustic Guitar Solid Top', 'Music', 'https://images.unsplash.com/photo-1510915228340-29c85a43dcfe?w=400&h=400&fit=crop', 299.99, 4.8, 'Solid spruce top, mahogany back/sides, warm rich tone.', 75),

-- ===========================================
-- TRAVEL PRODUCTS (8 items)
-- ===========================================
(NULL, 'Samsonite Hardside Luggage 3pc', 'Travel', 'https://images.unsplash.com/photo-1565026057447-bc90a3dceb87?w=400&h=400&fit=crop', 349.99, 4.8, 'Spinner wheels, TSA locks, scratch-resistant shell, 20"/24"/28".', 95),
(NULL, 'Travel Backpack 40L Carry-On', 'Travel', 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&h=400&fit=crop', 89.99, 4.7, 'Flight-approved size, laptop compartment, USB port, water-resistant.', 185),
(NULL, 'Packing Cubes Set of 6', 'Travel', 'https://images.unsplash.com/photo-1544816155-12df9643f363?w=400&h=400&fit=crop', 34.99, 4.6, 'Organize clothes efficiently, compression design, mesh top panels.', 420),
(NULL, 'Travel Neck Pillow Memory Foam', 'Travel', 'https://images.unsplash.com/photo-1612528443702-f6741f70a049?w=400&h=400&fit=crop', 29.99, 4.6, 'Ergonomic design, machine-washable cover, comes with eye mask.', 310),
(NULL, 'Universal Travel Adapter', 'Travel', 'https://images.unsplash.com/photo-1591290619762-d2c2d7fc7c06?w=400&h=400&fit=crop', 24.99, 4.7, 'Works in 150+ countries, 4 USB ports, dual voltage, compact design.', 275),
(NULL, 'Portable Luggage Scale Digital', 'Travel', 'https://images.unsplash.com/photo-1607006021144-a596e6dd8118?w=400&h=400&fit=crop', 14.99, 4.5, 'Weighs up to 110lb, LCD display, auto-lock, battery included.', 485),
(NULL, 'Travel Toiletry Bag Hanging', 'Travel', 'https://images.unsplash.com/photo-1544816155-12df9643f363?w=400&h=400&fit=crop', 39.99, 4.7, 'Multiple compartments, hook for hanging, water-resistant material.', 225),
(NULL, 'Camera Bag Travel Backpack', 'Travel', 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&h=400&fit=crop', 129.99, 4.8, 'Padded camera compartment, laptop sleeve, weather-resistant.', 95),

-- ===========================================
-- FOOD & COOKING PRODUCTS (6 items)
-- ===========================================
(NULL, 'All-Clad Stainless Cookware 10pc', 'Food', 'https://images.unsplash.com/photo-1584990347449-39b0e17e6a3e?w=400&h=400&fit=crop', 599.99, 4.9, 'Professional-grade stainless steel, tri-ply construction, oven-safe.', 65),
(NULL, 'Breville Espresso Machine', 'Food', 'https://images.unsplash.com/photo-1517668808822-9ebb02f2a0e6?w=400&h=400&fit=crop', 699.99, 4.8, 'Built-in grinder, precise temperature control, microfoam milk wand.', 48),
(NULL, 'KitchenAid Stand Mixer 5-Qt', 'Food', 'https://images.unsplash.com/photo-1578916171728-46686eac8d58?w=400&h=400&fit=crop', 449.99, 4.9, 'Powerful motor, 10 speeds, includes 3 attachments, tilt-head design.', 85),
(NULL, 'Chef Knife Set 15-Piece', 'Food', 'https://images.unsplash.com/photo-1593618998160-e34014e67546?w=400&h=400&fit=crop', 149.99, 4.7, 'German stainless steel, ergonomic handles, comes with wooden block.', 125),
(NULL, 'Cast Iron Skillet Pre-Seasoned', 'Food', 'https://images.unsplash.com/photo-1626775238053-4315516eedc9?w=400&h=400&fit=crop', 79.99, 4.8, '12-inch diameter, pre-seasoned, oven-safe, lifetime durability.', 195),
(NULL, 'Air Fryer XL 8-Quart', 'Food', 'https://images.unsplash.com/photo-1607623488235-f190ea694ad8?w=400&h=400&fit=crop', 129.99, 4.7, 'Large capacity, 8 cooking presets, digital touchscreen, dishwasher-safe.', 145),

-- ===========================================
-- ART & CREATIVE PRODUCTS (8 items)
-- ===========================================
(NULL, 'Wacom Drawing Tablet Pro', 'Art', 'https://images.unsplash.com/photo-1611532736579-6b16e2b50449?w=400&h=400&fit=crop', 499.99, 4.9, 'Pen pressure 8192 levels, tilt recognition, wireless kit included.', 68),
(NULL, 'Professional Paint Set 48 Colors', 'Art', 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=400&h=400&fit=crop', 89.99, 4.8, 'Acrylic paints, high pigment, artist-grade quality, vibrant colors.', 175),
(NULL, 'Artist Easel Studio Adjustable', 'Art', 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?w=400&h=400&fit=crop', 129.99, 4.7, 'Beechwood construction, adjustable height, holds large canvases.', 85),
(NULL, 'Sketchbook Hardbound 200 Pages', 'Art', 'https://images.unsplash.com/photo-1615799998082-4097fb418702?w=400&h=400&fit=crop', 24.99, 4.7, 'Acid-free paper, hardbound cover, 9x12 inches, 100gsm weight.', 340),
(NULL, 'Colored Pencils Set 120 Colors', 'Art', 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=400&h=400&fit=crop', 69.99, 4.8, 'Soft core, vibrant pigments, pre-sharpened, metal tin case.', 215),
(NULL, 'Watercolor Paper Pad A3 Size', 'Art', 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?w=400&h=400&fit=crop', 34.99, 4.6, 'Cold-pressed texture, 300gsm, 20 sheets, acid-free.', 265),
(NULL, 'Art Markers Dual Tip 120 Pack', 'Art', 'https://images.unsplash.com/photo-1563698073-2b07e4decb19?w=400&h=400&fit=crop', 79.99, 4.7, 'Alcohol-based, brush and chisel tips, blendable, vibrant ink.', 185),
(NULL, 'Calligraphy Pen Set Starter Kit', 'Art', 'https://images.unsplash.com/photo-1579762715459-5a068c289fda?w=400&h=400&fit=crop', 44.99, 4.6, 'Multiple nibs, black ink, practice sheets, instruction guide.', 225),

-- ===========================================
-- SCIENCE & EDUCATION PRODUCTS (6 items)
-- ===========================================
(NULL, 'Arduino Ultimate Starter Kit', 'Science', 'https://images.unsplash.com/photo-1553406830-ef2513450d76?w=400&h=400&fit=crop', 89.99, 4.9, '200+ components, sensors, motors, LCD, project guide book.', 165),
(NULL, 'Telescope Computerized 8-inch', 'Science', 'https://images.unsplash.com/photo-1614642288276-75c68b7f7d5e?w=400&h=400&fit=crop', 899.99, 4.8, 'GoTo mount, 40,000+ object database, smartphone adapter included.', 35),
(NULL, 'Microscope Digital 1000x', 'Science', 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?w=400&h=400&fit=crop', 149.99, 4.7, 'LED illumination, USB camera, comes with 50 prepared slides.', 95),
(NULL, 'Raspberry Pi 5 Starter Kit', 'Science', 'https://images.unsplash.com/photo-1553406830-ef2513450d76?w=400&h=400&fit=crop', 129.99, 4.8, 'Latest Pi 5, power supply, SD card, case, HDMI cable, guide.', 125),
(NULL, '3D Printer FDM Large Build', 'Science', 'https://images.unsplash.com/photo-1605647540924-852290f6b0d5?w=400&h=400&fit=crop', 399.99, 4.7, '300x300x400mm build volume, auto bed leveling, filament sensor.', 48),
(NULL, 'Lab Equipment Chemistry Set', 'Science', 'https://images.unsplash.com/photo-1532187643603-ba119ca4109e?w=400&h=400&fit=crop', 79.99, 4.6, 'Safety goggles, beakers, test tubes, experiment guide for students.', 145);

-- ===========================================
-- Verification
-- ===========================================
-- Run this to verify all products were inserted:
SELECT 
    category, 
    COUNT(*) as product_count,
    MIN(price) as min_price,
    MAX(price) as max_price,
    AVG(rating) as avg_rating
FROM products 
GROUP BY category 
ORDER BY product_count DESC;
