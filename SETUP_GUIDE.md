# 🚀 Personalized Recommendation Engine - Setup Guide

## DBMS Mini Project Complete Setup Instructions

---

## 📋 Prerequisites

- **XAMPP** (Apache + MySQL + PHP)
- Modern web browser (Chrome, Firefox, Edge)
- Text editor (VS Code, Notepad++, etc.)

---

## 🛠️ Installation Steps

### Step 1: Install XAMPP

1. Download XAMPP from: https://www.apachefriends.org/
2. Install XAMPP (recommended location: `C:\xampp\`)
3. After installation, open **XAMPP Control Panel**

### Step 2: Start Required Services

1. Open **XAMPP Control Panel**
2. Click **Start** button next to **Apache**
3. Click **Start** button next to **MySQL**
4. Wait until both show **green background** (running status)

![XAMPP Control Panel](https://via.placeholder.com/600x200/28a745/ffffff?text=Apache+and+MySQL+Running)

### Step 3: Setup Project Files

1. Navigate to `C:\xampp\htdocs\`
2. Create a new folder: `recommendation_engine`
3. Copy ALL project files into this folder:
   ```
   C:\xampp\htdocs\recommendation_engine\
   ├── index.html
   ├── register.html
   ├── login.html
   ├── dashboard.php
   ├── recommendations.php
   ├── register_process.php
   ├── login_process.php
   ├── logout.php
   ├── database.sql
   ├── config/
   │   └── db.php
   ├── css/
   │   └── style.css
   └── js/
       └── script.js
   ```

### Step 4: Create Database

#### Method 1: Using phpMyAdmin (Recommended)

1. Open your browser and go to: `http://localhost/phpmyadmin/`
2. Click on **"New"** in the left sidebar
3. Enter database name: `recommendation_engine`
4. Click **"Create"**
5. Click on the newly created `recommendation_engine` database
6. Click on **"Import"** tab at the top
7. Click **"Choose File"** and select `database.sql` from your project folder
8. Scroll down and click **"Go"**
9. Wait for success message: ✅ **"Import has been successfully finished"**

#### Method 2: Using SQL Tab

1. Open phpMyAdmin: `http://localhost/phpmyadmin/`
2. Click on **"SQL"** tab
3. Open `database.sql` file in a text editor
4. Copy ALL content from `database.sql`
5. Paste into the SQL tab
6. Click **"Go"**

### Step 5: Verify Database Setup

1. In phpMyAdmin, click on `recommendation_engine` database in left sidebar
2. You should see **5 tables**:
   - ✅ `users` (stores user accounts)
   - ✅ `interests` (12 predefined interests)
   - ✅ `user_interests` (links users to their interests)
   - ✅ `products` (40+ sample products)
   - ✅ `user_favorites` (stores user's favorite products)
3. Click on `interests` table → **Browse**
4. Verify you see 12 rows (Action, Comedy, Technology, Fashion, etc.)
5. Click on `products` table → **Browse**
6. Verify you see 40+ products with different categories

---

## 🌐 Accessing the Application

### Open in Browser

1. **Homepage**: `http://localhost/recommendation_engine/index.html`
2. **Register**: `http://localhost/recommendation_engine/register.html`
3. **Login**: `http://localhost/recommendation_engine/login.html`
4. **Dashboard**: `http://localhost/recommendation_engine/dashboard.php` (after login)
5. **Browse Products**: `http://localhost/recommendation_engine/recommendations.php`

---

## 🧪 Testing the Complete Workflow

### Test 1: User Registration

1. Go to: `http://localhost/recommendation_engine/register.html`
2. Fill in the form:
   - **Name**: Test User
   - **Email**: test@example.com
   - **Password**: test123
   - **Interests**: Select at least 3 checkboxes (e.g., Gaming, Technology, Music)
3. Click **"Create Account"**
4. ✅ You should be redirected to `dashboard.php`
5. ✅ Welcome message should display your name: "Welcome back, Test User!"
6. ✅ Your selected interests should appear as colored badges
7. ✅ Recommended products matching your interests should appear

### Test 2: User Login

1. Click **"Logout"** from dashboard
2. Go to: `http://localhost/recommendation_engine/login.html`
3. Enter credentials:
   - **Email**: test@example.com
   - **Password**: test123
4. (Optional) Check **"Remember me"**
5. Click **"Login"**
6. ✅ You should be redirected to dashboard
7. ✅ Your data should load correctly

### Test 3: Dashboard Features

1. Open dashboard after login
2. **Verify Stats**:
   - ✅ "X Recommendations" count displays
   - ✅ "Y Interests" count matches selected interests
3. **Verify Interests Section**:
   - ✅ Each interest has correct icon
   - ✅ Badges show different colors
4. **Verify Product Recommendations**:
   - ✅ Up to 8 products display
   - ✅ Products match your interest categories
   - ✅ Star ratings display correctly
   - ✅ Click "View Details" shows product description in alert

### Test 4: Browse & Filter Products

1. Click **"Recommendations"** in navigation menu
2. Go to: `http://localhost/recommendation_engine/recommendations.php`
3. **Test Category Filter**:
   - Select a category from dropdown (e.g., "Gaming")
   - ✅ Page auto-refreshes
   - ✅ Only Gaming products display
   - ✅ Product count updates
4. **Test Rating Filter**:
   - Select "4.0+ Stars"
   - ✅ Only products with rating ≥ 4.0 display
5. **Test Sort Options**:
   - Select "Highest Rated"
   - ✅ Products sort by rating (5.0 first)
   - Select "Latest First"
   - ✅ Products sort by created date
6. **Test Combinations**:
   - Category: Technology + Rating: 4.5+ + Sort: Highest Rated
   - ✅ Filters work together correctly

### Test 5: Session Management

1. From dashboard, click **"Logout"**
2. ✅ You should be redirected to `index.html`
3. Try to access: `http://localhost/recommendation_engine/dashboard.php` directly
4. ✅ You should be redirected to `login.html` (session protection working)

---

## 🎯 Expected Results

### Database Content

After importing `database.sql`, you should have:

- **12 Interests**: Action, Comedy, Technology, Fashion, Sports, Music, Gaming, Travel, Food, Books, Science, Art
- **40+ Products** across all categories:
  - Action: Avengers Endgame, The Dark Knight, etc.
  - Gaming: FIFA 24, Spider-Man 2, Xbox Series X, etc.
  - Technology: iPhone 15 Pro, MacBook Pro M3, etc.
  - Books: Atomic Habits, Python Crash Course, etc.
  - Music: Spotify Premium, AirPods Pro 2, etc.
  - Fashion: Nike Air Max, etc.

### User Journey

1. **New User** → Register → Select Interests → Auto-login → Dashboard with personalized products
2. **Returning User** → Login → Dashboard loads → View personalized recommendations
3. **Browse All** → Recommendations page → Filter by category/rating → Sort results
4. **Logout** → Session cleared → Protected pages redirect to login

---

## 🔧 Troubleshooting

### ❌ Error: "Access denied for user 'root'@'localhost'"

**Fix**: Check database credentials in `config/db.php`
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Empty for default XAMPP
define('DB_NAME', 'recommendation_engine');
```

### ❌ Error: "Unknown database 'recommendation_engine'"

**Fix**: 
1. Go to `http://localhost/phpmyadmin/`
2. Manually create database named: `recommendation_engine`
3. Import `database.sql` again

### ❌ Error: "Cannot access dashboard.php"

**Fix**:
1. Ensure Apache is running in XAMPP
2. Check URL: `http://localhost/recommendation_engine/dashboard.php`
3. Ensure you are logged in first

### ❌ Error: "Headers already sent"

**Fix**:
1. Open PHP file showing error
2. Ensure NO whitespace before `<?php` tag
3. Ensure NO text/HTML before session_start()

### ❌ Blank page or nothing displays

**Fix**:
1. Check Apache error logs: `C:\xampp\apache\logs\error.log`
2. Enable PHP error display: In `config/db.php`, add at top:
   ```php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```
3. Check browser console for JavaScript errors (F12)

### ❌ Products not showing after filtering

**Fix**:
1. Verify products exist in database:
   - phpMyAdmin → `products` table → Browse
2. Try "All Categories" filter
3. Check if products have the selected category name (exact match, case-sensitive)

### ❌ Images not loading (broken image icons)

**Expected**: This is normal! Products use placeholder images from `via.placeholder.com`
- If you have no internet, images won't load
- Replace image URLs in `products` table with local images if needed

---

## 📊 Database Structure

### Table: users
- `id` - Primary key (auto increment)
- `name` - User's full name
- `email` - Unique email address
- `password` - Hashed password (password_hash)
- `created_at` - Registration timestamp

### Table: interests
- `id` - Primary key
- `name` - Interest name
- `icon` - Font Awesome icon class

### Table: user_interests
- `user_id` - Foreign key to users
- `interest_id` - Foreign key to interests
- Composite primary key (user_id, interest_id)

### Table: products
- `id` - Primary key
- `title` - Product name
- `description` - Product details
- `category` - Product category (matches interest name)
- `image_url` - Product image URL
- `rating` - Decimal rating (0.0 to 5.0)
- `created_at` - Product creation date

### Table: user_favorites
- `user_id` - Foreign key to users
- `product_id` - Foreign key to products
- `created_at` - Favorited timestamp
- Composite primary key (user_id, product_id)

---

## 🎓 Project Features Demonstrated

### Frontend Technologies
✅ HTML5 semantic structure  
✅ CSS3 with modern gradients & animations  
✅ Bootstrap 5.3 responsive framework  
✅ Font Awesome 6.4 icons  
✅ JavaScript for UI interactions  

### Backend Technologies
✅ Pure PHP (no frameworks) - core PHP only  
✅ MySQL database with normalized schema  
✅ MySQLi with prepared statements (SQL injection prevention)  
✅ Password hashing (password_hash/password_verify)  
✅ Session management & authentication  
✅ Input sanitization (htmlspecialchars)  

### Database Concepts
✅ 5 normalized tables (proper schema design)  
✅ Primary keys & foreign keys  
✅ One-to-Many relationships (users → favorites)  
✅ Many-to-Many relationships (users ↔ interests via junction table)  
✅ SQL JOIN queries  
✅ Dynamic filtering & sorting  

### Security Features
✅ Prepared statements prevent SQL injection  
✅ Password hashing prevents password theft  
✅ Input sanitization prevents XSS attacks  
✅ Session validation prevents unauthorized access  
✅ Output escaping prevents code injection  

---

## 📝 College Demo Checklist

Before presenting to your professor:

- [ ] XAMPP running (Apache green, MySQL green)
- [ ] Database created with all 5 tables
- [ ] 12 interests populated
- [ ] 40+ products populated
- [ ] Test registration works
- [ ] Test login works
- [ ] Dashboard shows personalized products
- [ ] Recommendations page filters work
- [ ] Logout redirects correctly
- [ ] All navigation links working
- [ ] No PHP errors displaying
- [ ] Responsive design works on different screen sizes

---

## 🎉 Success Indicators

Your setup is complete when:

1. ✅ You can register a new user with interests
2. ✅ Dashboard shows personalized recommendations based on interests
3. ✅ Recommendations page filters update dynamically
4. ✅ Login/logout flow works smoothly
5. ✅ No errors appear in browser console or on page

---

## 📞 Quick Reference

| Component | Location/URL |
|-----------|-------------|
| XAMPP Control | `C:\xampp\xampp-control.exe` |
| phpMyAdmin | `http://localhost/phpmyadmin/` |
| Project Folder | `C:\xampp\htdocs\recommendation_engine\` |
| Homepage | `http://localhost/recommendation_engine/` |
| Register | `http://localhost/recommendation_engine/register.html` |
| Login | `http://localhost/recommendation_engine/login.html` |
| Dashboard | `http://localhost/recommendation_engine/dashboard.php` |
| Browse Products | `http://localhost/recommendation_engine/recommendations.php` |

---

## 🏆 Project Highlights for Review

When demonstrating to professors/reviewers:

1. **Show Database Schema**: Open phpMyAdmin → Show 5 tables with relationships
2. **Explain Security**: Point out prepared statements in PHP files, password hashing
3. **Demonstrate User Flow**: Register → Dashboard → Personalized recommendations
4. **Show Filtering**: Use recommendations page to demonstrate dynamic SQL queries
5. **Code Walkthrough**: 
   - `config/db.php` - Database connection & helper functions
   - `register_process.php` - User registration with validation
   - `login_process.php` - Authentication logic
   - `dashboard.php` - Personalized recommendations query
   - `recommendations.php` - Dynamic filtering & sorting

---

## ✅ You're All Set!

Your **Personalized Recommendation Engine** is now ready for demonstration.

**Test Account for Quick Demo**:
- Email: `test@example.com`
- Password: `test123`

(Create this account first via registration page)

---

**Created by**: [Your Name]  
**Course**: Database Management Systems  
**Project Type**: Mini Project  
**Technologies**: HTML, CSS, Bootstrap, JavaScript, PHP, MySQL

---

**Good Luck with your Demo! 🚀**
