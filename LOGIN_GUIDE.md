# 🚀 QUICK START GUIDE - LOGIN & BACKEND

## ✅ Backend Status: WORKING

Your Flask backend is fully operational and connected to the MySQL database!

---

## 📝 Test Login Credentials

Use these credentials to test the login system:

### Option 1: Buyer Account
- **Email:** `test@example.com`
- **Password:** `password123`

### Option 2: Buyer Account 2
- **Email:** `athithyathiru@gmail.com`
- **Password:** `password123`

---

## 🔗 Important URLs

| Page | URL | Purpose |
|------|-----|---------|
| **Home** | http://127.0.0.1:5000/ | Landing page |
| **Login** | http://127.0.0.1:5000/login | User login |
| **Register** | http://127.0.0.1:5000/register | Create new account |
| **Backend Test** | http://127.0.0.1:5000/backend-test | Test database connectivity |
| **Login Test** | http://127.0.0.1:5000/login-test | Test login functionality |
| **Dashboard** | http://127.0.0.1:5000/dashboard | Buyer dashboard (after login) |
| **Seller Dashboard** | http://127.0.0.1:5000/seller/dashboard | Seller dashboard (after login) |

---

## 🎯 How to Login

1. **Go to:** http://127.0.0.1:5000/login
2. **Enter Email:** `test@example.com`
3. **Enter Password:** `password123`
4. **Click:** Login button
5. **You'll be redirected to:** `/dashboard` (Buyer Dashboard)

---

## 🧪 Test Login Directly

For testing, you can use the **Login Test Dashboard** at:
- http://127.0.0.1:5000/login-test

This page lets you:
- ✅ Check if routes are working
- ✅ Test login directly
- ✅ Verify API connectivity
- ✅ See test credentials

---

## 🔧 Backend Features (All Working)

### ✅ User Management
- User Registration
- User Login (with password hashing)
- Session Management
- Role-based Access (Buyer/Seller)

### ✅ Product Management
- Add Products (Sellers)
- View Products (Buyers)
- Delete Products (Sellers)
- Product Search & Filtering

### ✅ Recommendation System
- Interest-based Recommendations
- Product Categories
- User Interests Management

### ✅ API Endpoints
- `/api/interests` - Get all interests
- `/api/product/<id>` - Get product details
- `/api/favorites/add` - Add to favorites

---

## 📱 Troubleshooting

### "Page not found" error (404)
- **Solution:** Make sure you're using `http://127.0.0.1:5000/login` (NOT `/login.html`)
- Check that the Flask server is running

### "Login failed" error
- **Solution:** Verify you're using the correct credentials
- Email: `test@example.com`
- Password: `password123`

### "Database connection error"
- **Solution:** Make sure MySQL/XAMPP is running
- Run: `python database.py` to test connection

### Flask server not starting
- **Solution:** Check if port 5000 is already in use
- Run: `python app.py`

---

## 📊 Backend Architecture

```
┌─────────────────┐
│   HTML Forms    │
└────────┬────────┘
         │ (HTTP POST/GET)
┌────────▼──────────────┐
│   Flask App.py        │  ← Backend Server
│ (Running on :5000)    │
└────────┬──────────────┘
         │ (MySQL Commands)
┌────────▼──────────────┐
│   MySQL Database      │
│ (recommendation_eng)  │
└──────────────────────┘
```

---

## 🎯 Next Steps

1. **Test Login:** Visit http://127.0.0.1:5000/login-test
2. **Try Login:** Use `test@example.com` / `password123`
3. **Explore Dashboard:** Visit `/dashboard` after login
4. **Register New User:** Go to `/register` to create account

---

## ✨ All Systems Operational

- ✅ Python Flask Backend
- ✅ MySQL Database Connected
- ✅ Session Management
- ✅ Password Hashing
- ✅ API Endpoints
- ✅ Template Rendering

**Your system is ready to use!**
