# 🛒 Marketplace Feature - Buyer & Seller System

## 🎉 New Features Added!

Your Personalized Recommendation Engine now supports a complete **buyer/seller marketplace** system!

---

## 📋 What's New?

### 1. **User Roles** - Buyer & Seller
- All users start as **buyers** by default
- Buyers can upgrade to **sellers** via Settings page
- Different dashboards for buyers vs sellers

### 2. **Seller Capabilities**
- ✅ Add new products with images, prices, stock
- ✅ View all their listed products
- ✅ Edit product details (title, price, stock, etc.)
- ✅ Delete products
- ✅ View sales analytics (total products, value, avg rating, stock)

### 3. **Product Images**
- All products now display images
- Sellers provide image URLs when adding products
- Supports any public image URL (Unsplash, Pexels, via.placeholder.com, etc.)

### 4. **Price & Stock Management**
- Products now have prices displayed
- Stock quantity tracking
- "Out of stock" indicator

---

## 🚀 How to Use

### For Buyers:

1. **Register/Login** as usual
2. Browse products on **Recommendations** page
3. See product prices and stock availability
4. **Upgrade to Seller** via Settings page when ready

### For Sellers:

1. **Login** to your account
2. Go to **Settings** → Fill in business details → **Upgrade to Seller**
3. You'll be redirected to **Seller Dashboard**
4. Click **"Add New Product"** button
5. Fill in product details:
   - Product Title
   - Category
   - Price
   - Stock Quantity
   - Image URL
   - Description
6. Click **"Add Product"**
7. Manage products from Seller Dashboard

---

## 🗄️ Database Changes

### New Columns in `users` table:
- `role` - Either 'buyer' or 'seller'
- `seller_business_name` - Business name for sellers
- `seller_phone` - Contact phone for sellers
- `seller_address` - Business address
- `updated_at` - Last update timestamp

### New Columns in `products` table:
- `seller_id` - Links product to seller (NULL for sample products)
- `price` - Product price in dollars
- `stock_quantity` - Available stock
- `updated_at` - Last update timestamp

---

## 📁 New Files Created

### PHP Files:
1. **settings.php** - User settings & seller upgrade page
2. **upgrade_to_seller_process.php** - Handles seller upgrade
3. **buyer_dashboard.php** - Buyer's personalized dashboard
4. **seller_dashboard.php** - Seller's product management dashboard
5. **add_product.php** - Form to add new products
6. **add_product_process.php** - Process new product creation
7. **edit_product.php** - Form to edit product details
8. **edit_product_process.php** - Process product updates
9. **delete_product.php** - Delete products

### SQL Files:
1. **database_migration.sql** - Upgrade existing database to new schema

---

## 🔄 Database Setup Options

### Option A: Fresh Install (New Database)
1. Open phpMyAdmin
2. Drop old database (if exists): `DROP DATABASE recommendation_engine;`
3. Import updated `database.sql`

### Option B: Upgrade Existing Database
1. Open phpMyAdmin
2. Select `recommendation_engine` database
3. Go to **SQL** tab
4. Import or paste contents of `database_migration.sql`
5. Click **"Go"**

---

## 🎯 User Flow Diagrams

### Buyer Flow:
```
Register → Login (as buyer) → Buyer Dashboard → Browse Products
                                    ↓
                            (Optional) Settings → Upgrade to Seller
```

###Seller Flow:
```
Register → Login → Settings → Upgrade to Seller
    ↓
Seller Dashboard → Add Product → Edit Product → Delete Product
```

---

## 📸 Page Screenshots & Features

### 1. **Settings Page**
- View account information
- Display current role (Buyer/Seller)
- Upgrade to seller form:
  - Business Name (required)
  - Phone Number (required)
  - Business Address (optional)
- Benefits of becoming a seller listed

### 2. **Seller Dashboard**
- Welcome message with seller name
- **Analytics Cards**:
  - Total Products count
  - Total Value of inventory
  - Average Rating
  - Total Stock quantity
- **Products Table** with:
  - Product image thumbnail
  - Title, Category, Price
  - Stock quantity with color coding
  - Rating display
  - Edit & Delete buttons
- **Add New Product** button (top right)
- Empty state message if no products

### 3. **Add Product Page**
- Form fields:
  - Product Title (required)
  - Category dropdown (from interests)
  - Price in $ (required)
  - Stock Quantity (required)
  - Rating (0-5, optional)
  - Image URL (required)
  - Description (required)
- Form validation
- Back to Dashboard link

### 4. **Edit Product Page**
- Same fields as Add Product, pre-filled with existing data
- Save Changes button
- Cancel button

### 5. **Buyer Dashboard**
- Unchanged from before
- Shows personalized recommendations
- Updated navigation with Settings link

### 6. **Recommendations Page**
- Now shows product prices
- Stock availability display
- Dynamic navigation based on login status
- If logged in: Shows Dashboard, Settings, Logout
- If not logged in: Shows Home, Login, Register

---

## 🔐 Security Features

### Role-Based Access Control:
- Seller pages check for seller role
- Buyers cannot access seller dashboard
- Users can only edit/delete their own products
- Sellers cannot edit other sellers' products

### Input Validation:
- Business name: min 3 characters
- Phone: validates format
- Prices: must be non-negative
- Stock: must be non-negative
- Image URLs: must be valid URLs
- Product ownership verification before edit/delete

---

## 🧪 Testing the New Features

### Test Scenario 1: Become a Seller
1. Register new account (becomes buyer by default)
2. Go to Settings
3. Fill in seller details:
   - Business Name: "Test Store"
   - Phone: "123-456-7890"
   - Address: "123 Main St"
4. Click "Upgrade to Seller Account"
5. Verify redirect to Seller Dashboard
6. Check role in navigation

### Test Scenario 2: Add a Product
1. Login as seller
2. Click "Add New Product"
3. Fill in:
   - Title: "iPhone 15 Pro Max"
   - Category: "Technology"
   - Price: 1199.99
   - Stock: 50
   - Rating: 4.8
   - Image: `https://via.placeholder.com/400x300/764ba2/ffffff?text=iPhone+15`
   - Description: "Latest iPhone with A17 Pro chip"
4. Submit form
5. Verify product appears in Seller Dashboard
6. Check product on Recommendations page

### Test Scenario 3: Edit & Delete
1. From Seller Dashboard, click Edit on a product
2. Change price to $1099.99
3. Save changes
4. Verify update in dashboard and recommendations page
5. Click Delete on a product
6. Confirm deletion
7. Verify product removed

### Test Scenario 4: Recommendations as Buyer
1. Logout (or use another browser)
2. Login as buyer account
3. Browse Recommendations page
4. Verify you see all products with prices
5. Check stock availability display
6. Verify you cannot access seller dashboard directly

---

## 🌟 Feature Highlights

### For Buyers:
- ✅ View product prices
- ✅ See stock availability
- ✅ Get personalized recommendations
- ✅ Option to become seller anytime
- ✅ Modern, responsive UI

### For Sellers:
- ✅ Complete product management
- ✅ Real-time analytics dashboard
- ✅ Easy product creation
- ✅ Edit products anytime
- ✅ Stock management
- ✅ Category-based organization

---

## 📊 Sample Data

The database includes:
- 12 interest categories
- 40+ sample products (with random prices $10-$500)
- Sample products have seller_id = NULL (platform products)
- Seller-added products link to seller via seller_id

---

## 🔧 Troubleshooting

### Issue: "Only sellers can add products"
- **Fix**: Go to Settings → Upgrade to Seller

### Issue: Cannot see prices on products
- **Fix**: Run `database_migration.sql` to add price column

### Issue: "Product not found or you don't have permission"
- **Fix**: You can only edit/delete your own products

### Issue: Foreign key constraint error
- **Fix**: Comment out foreign key line in migration if upgrading existing database

---

## 📝 Database Queries for Testing

### Check user role:
```sql
SELECT id, name, email, role, seller_business_name 
FROM users WHERE id = 1;
```

### View seller's products:
```sql
SELECT p.id, p.title, p.price, p.stock_quantity, u.seller_business_name
FROM products p
LEFT JOIN users u ON p.seller_id = u.id
WHERE p.seller_id = 1;
```

### Count products by seller:
```sql
SELECT u.seller_business_name, COUNT(p.id) as product_count, 
       SUM(p.price) as total_value
FROM users u
LEFT JOIN products p ON u.id = p.seller_id
WHERE u.role = 'seller'
GROUP BY u.id;
```

---

## 🎓 Educational Value

This project now demonstrates:
- ✅ Multi-user role system
- ✅ CRUD operations (Create, Read, Update, Delete)
- ✅ Role-based access control
- ✅ One-to-Many relationship (Seller → Products)
- ✅ Data aggregation & analytics
- ✅ Form validation
- ✅ Conditional UI rendering
- ✅ Foreign key relationships
- ✅ Database migrations

---

## 🏆 Complete Feature List

### Authentication:
- User registration
- User login
- Session management
- Logout

### User Management:
- User profiles
- Role switching (buyer → seller)
- Seller information storage

### Product Management:
- Add products
- Edit products
- Delete products
- View product list
- Image support via URLs

### Shopping Features:
- Browse all products
- Filter by category
- Filter by rating
- Sort products
- View product details
- See prices & stock
- Personalized recommendations

### Analytics:
- Total products count
- Total inventory value
- Average product rating
- Total stock quantity

### UI/UX:
- Responsive design
- Role-based navigation
- Dynamic dashboards
- Alert messages
- Form validation
- Loading states

---

## ✅ Project Completion Status

- [x] User roles (buyer/seller)
- [x] Settings page for role upgrade
- [x] Seller dashboard with analytics
- [x] Add product functionality
- [x] Edit product functionality
- [x] Delete product functionality
- [x] Price display on products
- [x] Stock management
- [x] Image URLs for all products
- [x] Database migration script
- [x] Dynamic navigation
- [x] Role-based access control
- [x] Complete documentation

---

## 📞 Quick Reference

| User Type | Default Dashboard | Can Add Products? | Can Edit All Products? |
|-----------|------------------|-------------------|----------------------|
| Buyer | buyer_dashboard.php | ❌ No | ❌ No |
| Seller | seller_dashboard.php | ✅ Yes | ✅ Only own products |

| File | Purpose |
|------|---------|
| settings.php | Upgrade to seller |
| buyer_dashboard.php | Buyer's home page |
| seller_dashboard.php | Seller's home page |
| add_product.php | Add new products |
| edit_product.php | Edit products |
| delete_product.php | Remove products |
| database_migration.sql | Upgrade database |

---

**Congratulations! Your marketplace is ready! 🎉**

Users can now buy AND sell products on your platform!

---

**Last Updated**: February 23, 2026  
**Version**: 2.0.0  
**New Features**: Marketplace with Buyer/Seller System  
**Database Schema**: Updated with roles, prices, stock
