# Personalized Recommendation Engine

## DBMS Mini Project - Frontend Design

A modern, responsive frontend design for a Personalized Recommendation Engine system using HTML, CSS, Bootstrap, and JavaScript.

---

## 📁 Project Structure

```
dbms project fin/
│
├── css/
│   └── style.css           # Main stylesheet with modern design
│
├── js/
│   └── script.js           # JavaScript for UI interactions
│
├── images/                 # Folder for images (currently using placeholder images)
│
├── index.html              # Home/Landing Page
├── login.html              # User Login Page
├── register.html           # User Registration Page
├── dashboard.html          # User Dashboard with Recommendations
├── recommendations.html    # Browse All Recommendations Page
└── README.md              # Project Documentation
```

---

## 🎯 Features

### 1. **Home Page (index.html)**
- Attractive landing page with gradient background
- Responsive navigation bar
- Hero section with call-to-action buttons
- Floating animated cards showcasing categories
- Features section highlighting key benefits
- About section with statistics
- Professional footer

### 2. **Registration Page (register.html)**
- User-friendly registration form
- Fields: Name, Email, Password
- Interest selection with 12+ categories:
  - Action, Comedy, Technology, Fashion
  - Sports, Music, Gaming, Travel
  - Food, Books, Science, Art
- Split layout with form and promotional content
- Responsive design

### 3. **Login Page (login.html)**
- Clean login interface
- Email and password fields
- "Remember Me" option
- Social login buttons (Google, Facebook)
- Link to registration page
- Split layout design

### 4. **Dashboard Page (dashboard.html)**
- Personalized welcome message
- User interests displayed as tags
- Quick statistics (Recommendations, Favorites, Viewed, Categories)
- Recommended items in card format (8 cards)
- Each card includes:
  - Product image
  - Title
  - Category badge
  - Star rating
  - "View Details" button
- "View All" button to recommendations page

### 5. **Recommendations Page (recommendations.html)**
- Advanced filter section:
  - Category filter
  - Rating filter
  - Sort options
- Grid layout with 12 product cards
- Favorite/heart icon on each card
- Pagination controls
- Responsive card grid

---

## 🎨 Design Features

### Color Scheme
- **Primary Gradient:** Purple to Blue (#667eea to #764ba2)
- **Success:** Green (#43e97b)
- **Warning:** Yellow (#fee140)
- **Danger:** Pink (#fa709a)
- **Soft backgrounds:** Light grays for better readability

### UI Elements
- **Card-based design** for products
- **Smooth hover effects** on all interactive elements
- **Gradient backgrounds** for headers and buttons
- **Floating animations** on hero section
- **Box shadows** for depth
- **Rounded corners** for modern look
- **Icon integration** using Font Awesome

### Responsive Design
- Mobile-first approach
- Bootstrap grid system
- Breakpoints for tablets and phones
- Collapsible navigation on mobile
- Stacked layouts for smaller screens

---

## 🚀 How to Run

### Option 1: Direct Browser
1. Navigate to the project folder
2. Double-click `index.html`
3. The website will open in your default browser

### Option 2: Live Server (Recommended)
1. Install VS Code extension "Live Server"
2. Right-click on `index.html`
3. Select "Open with Live Server"
4. Website will open with auto-reload feature

### Option 3: XAMPP (For future backend integration)
1. Copy project folder to `C:\xampp\htdocs\`
2. Start Apache server in XAMPP
3. Open browser and go to `http://localhost/dbms project fin/`

---

## 🔄 User Flow

1. **Landing** → User visits `index.html`
2. **Register** → New users click "Get Started" → `register.html`
3. **Login** → Existing users → `login.html`
4. **Dashboard** → After login → `dashboard.html` (shows personalized recommendations)
5. **Browse** → View all recommendations → `recommendations.html` (with filters)

---

## 💡 Interactive Features (JavaScript)

### Current Functionality:
- ✅ Form submission handling
- ✅ Registration with interest selection
- ✅ Login validation
- ✅ LocalStorage for user data persistence
- ✅ Dynamic dashboard population
- ✅ Filter functionality (dropdown handlers)
- ✅ Favorite/heart toggle on products
- ✅ Alert notifications
- ✅ Scroll animations
- ✅ Smooth navigation

### Simulated Features:
- User session management
- Product filtering by category/rating
- View details button click handling
- Add/remove favorites

---

## 📝 Future Backend Integration (Phase 2)

When connecting to MySQL database via PHP:

### Required Files to Add:
- `config.php` - Database connection
- `register_process.php` - Handle registration
- `login_process.php` - Handle login
- `get_recommendations.php` - Fetch recommendations based on user interests
- `filter_products.php` - Apply filters to products

### Database Tables Needed:
1. **users** - Store user information
2. **interests** - Store available interests
3. **user_interests** - Link users to their interests
4. **products** - Store product/content details
5. **recommendations** - Store recommendation data
6. **favorites** - Store user favorites

---

## 🎓 College Project Review Points

### Highlights for Presentation:
1. **Modern UI/UX Design** - Professional gradient-based theme
2. **Responsive Layout** - Works on all devices
3. **User-Centric** - Easy navigation and intuitive interface
4. **Interactive Elements** - JavaScript-powered interactions
5. **Scalable Structure** - Ready for backend integration
6. **Code Quality** - Clean, commented, organized code
7. **Best Practices** - Semantic HTML, CSS variables, modular JS

---

## 🛠️ Technologies Used

- **HTML5** - Structure and semantics
- **CSS3** - Styling, animations, gradients
- **Bootstrap 5.3** - Responsive grid and components
- **JavaScript (ES6)** - Client-side interactions
- **Font Awesome 6.4** - Icons
- **Google Fonts** - Typography (via Bootstrap)

---

## 📱 Browser Compatibility

Tested and working on:
- ✅ Google Chrome (Latest)
- ✅ Mozilla Firefox (Latest)
- ✅ Microsoft Edge (Latest)
- ✅ Safari (Latest)

---

## 👨‍💻 Development Notes

### Current Status:
- ✅ Frontend design complete
- ✅ All 5 pages functional
- ✅ Responsive design implemented
- ✅ Basic JavaScript interactions working
- ⏳ Backend integration pending (Phase 2)
- ⏳ Database design pending (Phase 2)

### Known Limitations:
- Images are placeholders (via placeholder.com)
- No actual backend processing
- User data stored in browser localStorage only
- Filters show alerts but don't actually filter content

---

## 📄 License

This is a college mini project for educational purposes.

---

## 👤 Author

Created for DBMS Mini Project - First Review

**Date:** February 2026

---

## 📞 Contact

For questions or feedback about this project, please contact your project supervisor.

---

**Note:** This is ONLY the frontend design. Backend logic, PHP database connection, and recommendation algorithms will be implemented in Phase 2 of the project.
