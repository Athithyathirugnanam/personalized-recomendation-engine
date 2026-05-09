# Python Flask + MySQL Recommendation Engine - Setup Guide

## Prerequisites
- Python 3.8 or higher
- MySQL (XAMPP/WAMP)
- pip (Python package manager)

## Step-by-Step Setup

### 1. Install Python Dependencies
Open PowerShell in your project directory and run:

```powershell
pip install -r requirements.txt
```

This will install:
- Flask (web framework)
- mysql-connector-python (database driver)
- Werkzeug (password hashing)

### 2. Setup MySQL Database

**Option A: Using phpMyAdmin**
1. Start XAMPP and start MySQL service
2. Open http://localhost/phpmyadmin
3. Click "Import" tab
4. Choose `database.sql` file
5. Click "Go"

**Option B: Using Command Line**
```powershell
# Navigate to MySQL bin directory (adjust path as needed)
cd C:\xampp\mysql\bin
.\mysql.exe -u root -e "source path\to\database.sql"
```

### 3. Test Database Connection
```powershell
python database.py
```

You should see: ✅ Database connection successful!

### 4. Run the Flask Application
```powershell
python app.py
```

The server will start at: **http://127.0.0.1:5000/**

### 5. Access the Application
Open your browser and go to:
- **Home:** http://127.0.0.1:5000/
- **Register:** http://127.0.0.1:5000/register
- **Login:** http://127.0.0.1:5000/login

### 6. Test User Credentials
After importing database.sql, you can login with:
- **Email:** test@example.com
- **Password:** Test123!

## Project Structure
```
dbms project fin/
├── app.py                  # Main Flask application
├── database.py             # Database connection
├── requirements.txt        # Python dependencies
├── database.sql            # Database schema
├── templates/              # HTML templates
│   ├── base.html
│   ├── index.html
│   ├── login.html
│   ├── register.html
│   ├── buyer_dashboard.html
│   ├── seller_dashboard.html
│   ├── add_product.html
│   └── recommendations.html
└── static/                 # Static files
    └── css/
        └── style.css
```

## Routes

### Public Routes
- `/` - Homepage
- `/login` - User login
- `/register` - User registration
- `/recommendations` - Browse all products

### Protected Routes (Requires Login)
- `/dashboard` - Buyer dashboard (personalized recommendations)
- `/seller/dashboard` - Seller dashboard (manage products)
- `/seller/add-product` - Add new product
- `/seller/delete-product/<id>` - Delete product
- `/logout` - Logout

## Database Tables

**1. users** - Store user accounts
- Buyer accounts (view recommendations)
- Seller accounts (manage products)

**2. interests** - Available interest categories

**3. user_interests** - Links users to their interests

**4. products** - Product catalog

**5. user_favorites** - User's favorite products

## Configuration

To change database settings, edit `database.py`:

```python
DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': '',  # Your MySQL password
    'database': 'recommendation_engine'
}
```

To change Flask secret key, edit `app.py`:

```python
app.secret_key = 'your-secret-key-change-this'
```

## Troubleshooting

**Database connection failed:**
- Ensure MySQL service is running in XAMPP
- Check database credentials in `database.py`
- Verify database exists: `recommendation_engine`

**Module not found errors:**
- Run: `pip install -r requirements.txt`

**Port 5000 already in use:**
- Edit `app.py` and change port:
```python
app.run(debug=True, port=5001)
```

**Templates not found:**
- Ensure `templates/` folder exists
- Flask looks for templates in `templates/` by default

## Development Mode

The app runs in debug mode by default, which means:
- Auto-reload on code changes
- Detailed error messages
- Debug toolbar enabled

For production, change in `app.py`:
```python
app.run(debug=False)
```

## Next Steps

1. Register a new account (buyer or seller)
2. If buyer: Select interests and view recommendations
3. If seller: Add products to the catalog
4. Customize the recommendation algorithm
5. Add more features (favorites, ratings, search, etc.)

## Support

For issues, check:
- Console output for error messages
- Browser console (F12) for JavaScript errors
- MySQL error log in XAMPP

Happy coding! 🚀
