# 🔐 OAuth Social Login Setup Guide

## Overview

This guide explains how to set up **Google Sign-In** and **Facebook Login** for your Personalized Recommendation Engine.

---

## 🎯 What You'll Get

### Google Sign-In:
- ✅ One-click login with Google account
- ✅ Shows device accounts (Chrome saved accounts)
- ✅ Auto-fills user name and email
- ✅ Secure OAuth 2.0 authentication

### Facebook Login:
- ✅ Popup window with Facebook login page
- ✅ Auto-fills saved Facebook credentials
- ✅ Access to profile picture and email
- ✅ Real Facebook authentication experience

---

## 📋 Prerequisites

Before you begin, you'll need:
- A Google account (for Google OAuth)
- A Facebook account (for Facebook OAuth)
- Your website URL (for localhost: `http://localhost`)
- Your project running on a web server (XAMPP)

---

## 🔧 Part 1: Database Setup

### Step 1: Update Database Schema

Run the OAuth migration to add social login columns:

1. Open **phpMyAdmin** → Select `recommendation_engine` database
2. Go to **SQL** tab
3. Import or paste: `oauth_migration.sql`
4. Click **Go**

**OR** for fresh installs:
- Just import the updated `database.sql` (already includes OAuth columns)

### What This Does:
- Adds `google_id` column to store Google user IDs
- Adds `facebook_id` column to store Facebook user IDs
- Creates indexes for faster OAuth lookups

---

## 🌐 Part 2: Google OAuth Setup

### Step 1: Create Google Cloud Project

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Click **"Select a project"** → **"New Project"**
3. Enter project name: `Recommendation Engine`
4. Click **"Create"**

### Step 2: Enable Google Sign-In API

1. In the left sidebar, go to **"APIs & Services"** → **"Library"**
2. Search for: `Google+ API` or `Google Identity`
3. Click **"Enable"**

### Step 3: Configure OAuth Consent Screen

1. Go to **"APIs & Services"** → **"OAuth consent screen"**
2. Choose **"External"** (unless you have Google Workspace)
3. Click **"Create"**
4. Fill in the form:
   - **App name**: `Personalized Recommendation Engine`
   - **User support email**: Your email
   - **Developer contact**: Your email
5. Click **"Save and Continue"**
6. **Scopes**: Click **"Add or Remove Scopes"**
   - Select: `./auth/userinfo.email`
   - Select: `./auth/userinfo.profile`
   - Select: `openid`
7. Click **"Save and Continue"**
8. **Test users** (optional): Add your email for testing
9. Click **"Save and Continue"**

### Step 4: Create OAuth Credentials

1. Go to **"APIs & Services"** → **"Credentials"**
2. Click **"+ Create Credentials"** → **"OAuth client ID"**
3. Choose application type: **"Web application"**
4. Name: `Web Client`
5. **Authorized JavaScript origins**:
   - `http://localhost`
   - `http://localhost:80`
   - `http://127.0.0.1`
   - Your production URL (when deployed)
6. **Authorized redirect URIs**:
   - `http://localhost/dbms project fin/`
   - `http://localhost/dbms project fin/login.html`
   - Your production URL
7. Click **"Create"**

### Step 5: Copy Your Client ID

1. You'll see a dialog with:
   - **Client ID**: `123456789-abcdefg.apps.googleusercontent.com`
   - **Client Secret**: (not needed for frontend OAuth)
2. **Copy the Client ID**

### Step 6: Update Your Code

Open `login.html` and find this line (around line 175):

```javascript
client_id: 'YOUR_GOOGLE_CLIENT_ID.apps.googleusercontent.com',
```

Replace with your actual Client ID:

```javascript
client_id: '123456789-abcdefg.apps.googleusercontent.com',
```

---

## 📘 Part 3: Facebook OAuth Setup

### Step 1: Create Facebook App

1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Click **"My Apps"** → **"Create App"**
3. Select use case: **"Consumer"** or **"Other"**
4. Click **"Next"**
5. App name: `Recommendation Engine`
6. App contact email: Your email
7. Click **"Create App"**

### Step 2: Add Facebook Login Product

1. In your app dashboard, find **"Facebook Login"**
2. Click **"Set Up"**
3. Choose platform: **"Web"**
4. Enter your site URL: `http://localhost/dbms project fin/`
5. Click **"Save"** and **"Continue"**

### Step 3: Configure Facebook Login Settings

1. In left sidebar: **"Facebook Login"** → **"Settings"**
2. **Valid OAuth Redirect URIs**:
   ```
   http://localhost/dbms project fin/
   http://localhost/dbms project fin/login.html
   http://localhost/dbms project fin/facebook_oauth.php
   ```
3. **Allowed Domains for the JavaScript SDK**:
   ```
   localhost
   ```
4. Click **"Save Changes"**

### Step 4: Get App ID and App Secret

1. In left sidebar: **"Settings"** → **"Basic"**
2. You'll see:
   - **App ID**: `1234567890123456`
   - **App Secret**: Click **"Show"** → Enter password → Copy it
3. **Copy both values**

### Step 5: Update Your Code

#### Update login.html:

Find this line (around line 207):

```javascript
appId      : 'YOUR_FACEBOOK_APP_ID',
```

Replace with your App ID:

```javascript
appId      : '1234567890123456',
```

#### Update facebook_oauth.php:

Find these lines (around line 23-24):

```php
$app_id = 'YOUR_FACEBOOK_APP_ID';
$app_secret = 'YOUR_FACEBOOK_APP_SECRET';
```

Replace with your credentials:

```php
$app_id = '1234567890123456';
$app_secret = 'your_app_secret_here';
```

### Step 6: Make App Live (Important!)

1. In app dashboard, toggle **"In development"** to **"Live"** mode
2. OR add test users: **"Roles"** → **"Test Users"** → **"Add"**

---

## 🧪 Testing Your OAuth Setup

### Test Google Sign-In:

1. Open `http://localhost/dbms project fin/login.html`
2. Click **"Continue with Google"** button
3. You should see:
   - Google account picker (if logged in to Chrome)
   - OR Google login page
4. Select account or login
5. Grant permissions
6. Should redirect to dashboard

### Test Facebook Login:

1. On the same login page
2. Click **"Continue with Facebook"** button
3. You should see:
   - Facebook login popup window
   - Auto-filled credentials if you're logged in
4. Login and click **"Continue as [Your Name]"**
5. Should redirect to dashboard

---

## 🔍 Troubleshooting

### Google Sign-In Issues:

#### Error: "Invalid Client ID"
- **Fix**: Double-check the Client ID in `login.html`
- Make sure it ends with `.apps.googleusercontent.com`

#### Error: "redirect_uri_mismatch"
- **Fix**: Add your exact URL to "Authorized JavaScript origins"
- Include both `http://localhost` and `http://127.0.0.1`

#### Button doesn't appear
- **Fix**: Check browser console for errors
- Make sure Google SDK script is loading
- Verify Client ID is correct

#### "Access blocked: This app's request is invalid"
- **Fix**: Complete the OAuth consent screen setup
- Add required scopes (email, profile, openid)

---

### Facebook Login Issues:

#### Error: "App Not Setup: This app is still in development mode"
- **Fix**: Add yourself as a test user
- OR switch app to Live mode

#### Error: "URL Blocked: This redirect failed"
- **Fix**: Add exact URL to "Valid OAuth Redirect URIs"
- Must include `http://` and full path

#### Popup blocked by browser
- **Fix**: Allow popups for localhost
- Chrome: Click popup icon in address bar → Always allow

#### "Given URL is not allowed by the Application configuration"
- **Fix**: Add domain to "App Domains"
- Add URL to redirect URIs

#### Email not returned
- **Fix**: Request `email` permission in scope
- Check Facebook app has email permission enabled
- User must grant email access

---

### General Issues:

#### Database errors
- **Fix**: Run `oauth_migration.sql`
- Check `google_id` and `facebook_id` columns exist

#### Session not created
- **Fix**: Check `session_start()` in PHP files
- Verify `db_connection.php` exists

#### Redirect loops
- **Fix**: Clear browser cookies
- Check role is being set correctly in database

---

## 🔒 Security Best Practices

### For Production:

1. **Use HTTPS**:
   - OAuth requires HTTPS in production
   - Get SSL certificate (Let's Encrypt is free)

2. **Verify Tokens**:
   - Google: Verify JWT signature (optional but recommended)
   - Facebook: Verify access token with Facebook API

3. **Environment Variables**:
   - Don't hardcode API keys in code
   - Use `.env` file or server environment variables

4. **Rate Limiting**:
   - Limit OAuth attempts per IP
   - Prevent brute force attacks

5. **Token Storage**:
   - Never store access tokens in database
   - Only store user IDs from OAuth providers

---

## 📊 How It Works

### Google Sign-In Flow:

```
User clicks "Continue with Google"
    ↓
Google shows account picker
    ↓
User selects account
    ↓
Google returns JWT token
    ↓
Frontend sends token to google_oauth.php
    ↓
Backend verifies token and extracts user data
    ↓
Check if user exists in database
    ↓
If yes: Login → Set session → Redirect to dashboard
If no: Create account → Set session → Redirect to dashboard
```

### Facebook Login Flow:

```
User clicks "Continue with Facebook"
    ↓
Facebook popup window appears
    ↓
User logs in (or already logged in)
    ↓
Facebook returns access token & user data
    ↓
Frontend sends data to facebook_oauth.php
    ↓
Backend verifies with Facebook API (optional)
    ↓
Check if user exists in database
    ↓
If yes: Login → Set session → Redirect to dashboard
If no: Create account → Set session → Redirect to dashboard
```

---

## 📝 Database Schema

### Users table with OAuth columns:

```sql
CREATE TABLE users (
    id INT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),        -- Random hash for OAuth users
    google_id VARCHAR(255) UNIQUE,   -- Google user ID
    facebook_id VARCHAR(255) UNIQUE, -- Facebook user ID
    role ENUM('buyer', 'seller'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### OAuth User Characteristics:
- `password`: Random secure hash (OAuth users don't use it)
- `google_id` or `facebook_id`: Populated on first login
- `role`: Defaults to 'buyer', can upgrade to seller
- Account linked by `email` address

---

## ✅ Quick Setup Checklist

### Google OAuth:
- [ ] Create Google Cloud project
- [ ] Enable Google+ API or Google Identity
- [ ] Configure OAuth consent screen
- [ ] Create OAuth credentials (Web application)
- [ ] Add authorized JavaScript origins
- [ ] Copy Client ID
- [ ] Update `login.html` with Client ID
- [ ] Test login

### Facebook OAuth:
- [ ] Create Facebook Developer account
- [ ] Create new Facebook App
- [ ] Add Facebook Login product
- [ ] Configure Valid OAuth Redirect URIs
- [ ] Add allowed domains
- [ ] Copy App ID and App Secret
- [ ] Update `login.html` with App ID
- [ ] Update `facebook_oauth.php` with App ID & Secret
- [ ] Set app to Live or add test users
- [ ] Test login

### Database:
- [ ] Run `oauth_migration.sql` (existing DB)
- [ ] OR import updated `database.sql` (new DB)
- [ ] Verify `google_id` and `facebook_id` columns exist

---

## 🎓 Educational Notes

### Why OAuth is Better:
- ✅ No password to remember (for users)
- ✅ No password to store (for you)
- ✅ Verified email addresses
- ✅ Faster registration process
- ✅ Built-in security by Google/Facebook
- ✅ One-click login experience

### OAuth vs Regular Login:
- Regular: User creates password → You store hash → User must remember
- OAuth: User clicks button → Provider handles auth → User already logged in

### Security Advantages:
- No plaintext passwords transmitted
- Tokens expire automatically
- User can revoke access anytime
- Provider (Google/Facebook) handles security updates
- Reduces attack surface on your application

---

## 🚀 Going Live

When deploying to production:

1. **Update OAuth Origins**:
   - Add your production domain to Google authorized origins
   - Add production URL to Facebook redirect URIs

2. **Use HTTPS**:
   - Required by both Google and Facebook
   - Get free SSL from Let's Encrypt

3. **Environment Variables**:
   ```php
   // Example: Use environment variables
   $app_id = getenv('FACEBOOK_APP_ID');
   $app_secret = getenv('FACEBOOK_APP_SECRET');
   ```

4. **Test Thoroughly**:
   - Test all OAuth flows
   - Test existing vs new users
   - Test buyer → seller upgrade
   - Test logout and re-login

---

## 📞 Support Resources

### Official Documentation:
- [Google Sign-In Guide](https://developers.google.com/identity/gsi/web/guides/overview)
- [Facebook Login Docs](https://developers.facebook.com/docs/facebook-login/web)

### Common URLs:
- Google Cloud Console: https://console.cloud.google.com/
- Facebook Developers: https://developers.facebook.com/
- Google API Library: https://console.cloud.google.com/apis/library

---

## 🎉 Success!

Once configured, users can:
- ✅ Login with Google account (shows device accounts)
- ✅ Login with Facebook (real Facebook login page)
- ✅ Auto-register on first OAuth login
- ✅ Link OAuth accounts to existing emails
- ✅ Switch between regular and OAuth login

**Your OAuth setup is complete!** 🚀

---

**Last Updated**: February 23, 2026  
**Version**: 2.1.0  
**New Feature**: Social Login with Google & Facebook
