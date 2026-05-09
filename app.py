from flask import Flask, render_template, request, redirect, url_for, session, flash, jsonify, send_from_directory
from werkzeug.security import generate_password_hash, check_password_hash
from werkzeug.utils import secure_filename
from database import get_db_connection
from recommendation_engine import recommendation_engine
from datetime import datetime
import os
from pathlib import Path

app = Flask(__name__)
app.secret_key = os.getenv('SECRET_KEY', 'your-secret-key-change-this-to-something-secure')
app.config['SESSION_COOKIE_HTTPONLY'] = True
app.config['SESSION_COOKIE_SAMESITE'] = 'Lax'

# Image Upload Configuration
UPLOAD_FOLDER = Path(__file__).parent / 'static' / 'uploads' / 'products'
ALLOWED_EXTENSIONS = {'png', 'jpg', 'jpeg', 'gif', 'webp'}
MAX_FILE_SIZE = 5 * 1024 * 1024  # 5MB

UPLOAD_FOLDER.mkdir(parents=True, exist_ok=True)
app.config['UPLOAD_FOLDER'] = str(UPLOAD_FOLDER)
app.config['MAX_CONTENT_LENGTH'] = MAX_FILE_SIZE

def allowed_file(filename):
    """Check if file extension is allowed"""
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

def get_safe_filename(product_name, extension):
    """Create safe filename from product name"""
    # Replace spaces and special chars with underscores
    safe_name = product_name.lower().replace(' ', '_').replace(':', '').replace('-', '_')
    # Keep only alphanumeric and underscores
    safe_name = ''.join(c if c.isalnum() or c == '_' else '' for c in safe_name)
    return f"{safe_name}.{extension}"


def sanitize_text(value):
    """Trim user input and safely handle empty values."""
    return value.strip() if value else ''


def validate_email_address(email):
    """Basic email validation for registration and login forms."""
    return bool(email) and '@' in email and '.' in email


def get_available_interests():
    """Fetch all selectable interests from the database."""
    conn = get_db_connection()
    interests = []

    if conn:
        cursor = None
        try:
            cursor = conn.cursor(dictionary=True)
            cursor.execute("SELECT id, interest_name, icon FROM interests ORDER BY interest_name")
            interests = cursor.fetchall()
        finally:
            if cursor is not None:
                cursor.close()
            conn.close()

    return interests


def get_personalized_products(user_id, limit=12):
    """Return products whose category matches the user's saved interests."""
    conn = get_db_connection()
    products = []

    if conn:
        cursor = None
        try:
            cursor = conn.cursor(dictionary=True)
            cursor.execute(
                """
                SELECT p.id, p.title, p.category, p.image, p.price, p.rating, p.description, p.stock_quantity
                FROM products p
                INNER JOIN (
                    SELECT MIN(id) AS id
                    FROM products
                    GROUP BY LOWER(TRIM(title))
                ) unique_products ON unique_products.id = p.id
                INNER JOIN user_interests ui ON ui.user_id = %s
                INNER JOIN interests i ON i.id = ui.interest_id
                WHERE LOWER(p.category) = LOWER(i.interest_name)
                ORDER BY p.rating DESC, p.created_at DESC
                LIMIT %s
                """,
                (user_id, limit)
            )
            products = cursor.fetchall()

            if not products:
                cursor.execute(
                    """
                    SELECT id, title, category, image, price, rating, description, stock_quantity
                    FROM products
                    WHERE id IN (
                        SELECT MIN(id)
                        FROM products
                        GROUP BY LOWER(TRIM(title))
                    )
                    ORDER BY rating DESC, created_at DESC
                    LIMIT %s
                    """,
                    (limit,)
                )
                products = cursor.fetchall()
        finally:
            if cursor is not None:
                cursor.close()
            conn.close()

    return products

# ============================================
# HOME PAGE
# ============================================
@app.route('/')
def index():
    return render_template('index.html')

# ============================================
# LOGIN TEST DASHBOARD
# ============================================
@app.route('/login-test')
def login_test():
    return render_template('login_test.html')

# ============================================
# SYSTEM STATUS & DIAGNOSTICS
# ============================================
@app.route('/status')
def system_status():
    return render_template('status.html')

# ============================================
# QUICK ACCESS DASHBOARD
# ============================================
@app.route('/quick')
def quick_access():
    return render_template('quick_access.html')

# ============================================
# PRODUCT IMAGE MANAGER
# ============================================
@app.route('/image-manager')
def image_manager():
    return render_template('image_manager.html')

# ============================================
# REGISTER
# ============================================
@app.route('/register', methods=['GET', 'POST'])
def register():
    interests = get_available_interests()

    if request.method == 'POST':
        name = sanitize_text(request.form.get('name'))
        email = sanitize_text(request.form.get('email')).lower()
        password = request.form.get('password', '')
        role = request.form.get('role', 'buyer')
        selected_interests = request.form.getlist('interests[]')

        errors = []
        if len(name) < 3:
            errors.append('Name must be at least 3 characters long.')
        if not validate_email_address(email):
            errors.append('Please enter a valid email address.')
        if len(password) < 6:
            errors.append('Password must be at least 6 characters long.')
        if not selected_interests:
            errors.append('Please select at least one interest.')

        conn = get_db_connection()
        if conn:
            check_cursor = conn.cursor(dictionary=True)
            insert_cursor = None
            try:
                check_cursor.execute('SELECT id FROM users WHERE email = %s', (email,))
                if check_cursor.fetchone():
                    errors.append('An account with this email already exists.')

                if errors:
                    for message in errors:
                        flash(message, 'error')
                    return render_template('register.html', interests=interests, selected_interest_ids=selected_interests)

                hashed_password = generate_password_hash(password)

                insert_cursor = conn.cursor()
                insert_cursor.execute(
                    'INSERT INTO users (name, email, password, role) VALUES (%s, %s, %s, %s)',
                    (name, email, hashed_password, role)
                )
                user_id = insert_cursor.lastrowid

                for interest_id in selected_interests:
                    insert_cursor.execute(
                        'INSERT INTO user_interests (user_id, interest_id) VALUES (%s, %s)',
                        (user_id, interest_id)
                    )

                conn.commit()
                session['user_id'] = user_id
                session['user_name'] = name
                session['user_email'] = email
                session['user_role'] = role

                flash('Registration successful! Your interests have been saved.', 'success')
                if role == 'seller':
                    return redirect(url_for('seller_dashboard'))
                return redirect(url_for('buyer_dashboard'))

            except Exception as e:
                conn.rollback()
                flash(f'Registration failed: {str(e)}', 'error')
            finally:
                if insert_cursor is not None:
                    insert_cursor.close()
                check_cursor.close()
                conn.close()

    return render_template('register.html', interests=interests, selected_interest_ids=[])

# ============================================
# LOGIN
# ============================================
@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        email = sanitize_text(request.form.get('email')).lower()
        password = request.form.get('password')
        
        if not email or not password:
            flash('Email and password are required', 'error')
            return render_template('login.html')
        
        conn = get_db_connection()
        if conn:
            cursor = conn.cursor(dictionary=True)
            try:
                # Get user by email
                query = "SELECT * FROM users WHERE email = %s"
                cursor.execute(query, (email,))
                user = cursor.fetchone()
                
                if user and check_password_hash(user['password'], password):
                    # Create session
                    session['user_id'] = user['id']
                    session['user_name'] = user['name']
                    session['user_email'] = user['email']
                    session['user_role'] = user['role']
                    
                    flash('Login successful!', 'success')
                    print(f"✅ User {email} logged in as {user['role']}")
                    
                    # Redirect based on role
                    if user['role'] == 'seller':
                        return redirect(url_for('seller_dashboard'))
                    else:
                        return redirect(url_for('buyer_dashboard'))
                else:
                    flash('Invalid email or password', 'error')
                    print(f"❌ Failed login attempt for {email}")
                    
            except Exception as e:
                flash(f'Login failed: {str(e)}', 'error')
                print(f"❌ Error during login: {str(e)}")
            finally:
                cursor.close()
                conn.close()
        else:
            flash('Database connection failed', 'error')
            print("❌ Database connection failed during login")
    
    return render_template('login.html')

# ============================================
# LOGOUT
# ============================================
@app.route('/logout')
def logout():
    session.clear()
    flash('You have been logged out.', 'info')
    return redirect(url_for('index'))

# ============================================
# BUYER DASHBOARD
# ============================================
@app.route('/dashboard')
def buyer_dashboard():
    if 'user_id' not in session:
        return redirect(url_for('login'))
    
    conn = get_db_connection()
    user_interests = []
    
    if conn:
        cursor = conn.cursor(dictionary=True)
        try:
            cursor.execute(
                """
                SELECT i.id, i.interest_name, i.icon
                FROM interests i
                INNER JOIN user_interests ui ON i.id = ui.interest_id
                WHERE ui.user_id = %s
                ORDER BY i.interest_name
                """,
                (session['user_id'],)
            )
            user_interests = cursor.fetchall()
        finally:
            cursor.close()
            conn.close()
    
    products = get_personalized_products(session['user_id'], limit=12)

    if not products:
        products = recommendation_engine.get_personalized_recommendations(
            session['user_id'],
            num_recommendations=12
        )
    if not products:
        conn = get_db_connection()
        if conn:
            cursor = conn.cursor(dictionary=True)
            try:
                cursor.execute(
                    '''
                    SELECT *
                    FROM products
                    WHERE id IN (
                        SELECT MIN(id)
                        FROM products
                        GROUP BY LOWER(TRIM(title))
                    )
                    ORDER BY rating DESC, created_at DESC
                    LIMIT 12
                    '''
                )
                products = cursor.fetchall()
            finally:
                cursor.close()
                conn.close()
    
    return render_template('buyer_dashboard.html', 
                         products=products, 
                         user_interests=user_interests,
                         user_name=session.get('user_name'))

# ============================================
# SELLER DASHBOARD
# ============================================
@app.route('/seller/dashboard')
def seller_dashboard():
    if 'user_id' not in session or session.get('user_role') != 'seller':
        return redirect(url_for('login'))
    
    conn = get_db_connection()
    products = []
    
    if conn:
        cursor = conn.cursor(dictionary=True)
        try:
            query = "SELECT * FROM products WHERE seller_id = %s ORDER BY created_at DESC"
            cursor.execute(query, (session['user_id'],))
            products = cursor.fetchall()
        except Exception as e:
            print(f"Error: {e}")
        finally:
            cursor.close()
            conn.close()
    
    return render_template('seller_dashboard.html', 
                         products=products,
                         user_name=session.get('user_name'))

# ============================================
# ADD PRODUCT (Seller)
# ============================================
@app.route('/seller/add-product', methods=['GET', 'POST'])
def add_product():
    if 'user_id' not in session or session.get('user_role') != 'seller':
        return redirect(url_for('login'))
    
    if request.method == 'POST':
        title = request.form.get('title')
        category = request.form.get('category')
        price = request.form.get('price')
        rating = request.form.get('rating', 0.0)
        description = request.form.get('description')
        image = request.form.get('image')
        stock = request.form.get('stock', 0)
        
        conn = get_db_connection()
        if conn:
            cursor = conn.cursor()
            try:
                query = """
                    INSERT INTO products 
                    (seller_id, title, category, price, rating, description, image, stock_quantity) 
                    VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
                """
                cursor.execute(query, (session['user_id'], title, category, price, 
                                     rating, description, image, stock))
                conn.commit()
                flash('Product added successfully!', 'success')
                return redirect(url_for('seller_dashboard'))
            except Exception as e:
                flash(f'Failed to add product: {str(e)}', 'error')
            finally:
                cursor.close()
                conn.close()
    
    return render_template('add_product.html')

# ============================================
# DELETE PRODUCT (Seller)
# ============================================
@app.route('/seller/delete-product/<int:product_id>')
def delete_product(product_id):
    if 'user_id' not in session or session.get('user_role') != 'seller':
        return redirect(url_for('login'))
    
    conn = get_db_connection()
    if conn:
        cursor = conn.cursor()
        try:
            query = "DELETE FROM products WHERE id = %s AND seller_id = %s"
            cursor.execute(query, (product_id, session['user_id']))
            conn.commit()
            flash('Product deleted successfully!', 'success')
        except Exception as e:
            flash(f'Failed to delete product: {str(e)}', 'error')
        finally:
            cursor.close()
            conn.close()
    
    return redirect(url_for('seller_dashboard'))

# ============================================
# UPLOAD PRODUCT IMAGE
# ============================================
@app.route('/api/upload-product-image/<int:product_id>', methods=['POST'])
def upload_product_image(product_id):
    """Upload image for a product"""
    if 'user_id' not in session:
        return jsonify({'error': 'Not logged in'}), 401
    
    # Check if file is in request
    if 'file' not in request.files:
        return jsonify({'error': 'No file provided'}), 400
    
    file = request.files['file']
    if file.filename == '':
        return jsonify({'error': 'No file selected'}), 400
    
    if not allowed_file(file.filename):
        return jsonify({'error': f'Only {", ".join(ALLOWED_EXTENSIONS)} files allowed'}), 400
    
    try:
        conn = get_db_connection()
        if not conn:
            return jsonify({'error': 'Database connection failed'}), 500

        cursor = conn.cursor(dictionary=True)
        try:
            cursor.execute("SELECT title FROM products WHERE id = %s", (product_id,))
            product = cursor.fetchone()

            if not product:
                return jsonify({'error': 'Product not found'}), 404

            ext = file.filename.rsplit('.', 1)[1].lower()
            filename = get_safe_filename(product['title'], ext)
            filepath = Path(app.config['UPLOAD_FOLDER']) / filename

            file.save(str(filepath))

            image_path = f'/static/uploads/products/{filename}'
            cursor.execute("UPDATE products SET image = %s WHERE id = %s", (image_path, product_id))
            conn.commit()

            return jsonify({
                'success': True,
                'message': 'Image uploaded successfully',
                'image_path': image_path,
                'filename': filename
            }), 200
        finally:
            cursor.close()
            conn.close()

    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/api/products/<int:product_id>/image', methods=['PUT', 'POST'])
def update_product_image(product_id):
    """Alternative endpoint to update product image"""
    if 'user_id' not in session:
        return jsonify({'error': 'Not logged in'}), 401
    
    if 'file' not in request.files:
        return jsonify({'error': 'No file provided'}), 400
    
    return upload_product_image(product_id)

@app.route('/uploads/products/<filename>')
def serve_product_image(filename):
    """Serve uploaded product images"""
    try:
        return send_from_directory(str(app.config['UPLOAD_FOLDER']), filename)
    except Exception as e:
        return redirect(url_for('static', filename='images/products/default-product.svg')), 404

# ============================================
# RECOMMENDATIONS PAGE
# ============================================
@app.route('/recommendations')
def recommendations():
    conn = get_db_connection()
    products = []
    categories = []
    
    if conn:
        cursor = conn.cursor(dictionary=True)
        try:
            # Get all products
            query = "SELECT id, title, category, image, price, rating, description, stock_quantity FROM products ORDER BY rating DESC, created_at DESC"
            cursor.execute(query)
            products = cursor.fetchall()
            
            # Get distinct categories
            query = "SELECT DISTINCT category FROM products ORDER BY category"
            cursor.execute(query)
            categories = cursor.fetchall()
            
        except Exception as e:
            print(f"Error: {e}")
        finally:
            cursor.close()
            conn.close()
    
    return render_template('recommendations.html', 
                         products=products, 
                         categories=categories)

# ============================================
# INTERESTS SELECTION PAGE
# ============================================
@app.route('/interests', methods=['GET', 'POST'])
def select_interests():
    if 'user_id' not in session:
        return redirect(url_for('login'))

    interests = get_available_interests()
    selected_interest_ids = []
    
    if request.method == 'POST':
        selected_interests = request.form.getlist('interests[]')

        selected_interest_ids = [str(interest_id) for interest_id in selected_interests]

        if len(selected_interests) < 3:
            flash('Please select at least 3 interests.', 'error')
            return render_template(
                'interests.html',
                interests=interests,
                selected_interest_ids=selected_interest_ids
            )
        
        conn = get_db_connection()
        if conn:
            cursor = conn.cursor()
            try:
                # First, delete existing interests
                delete_query = "DELETE FROM user_interests WHERE user_id = %s"
                cursor.execute(delete_query, (session['user_id'],))
                
                # Insert new interests
                for interest_id in selected_interests:
                    insert_query = "INSERT INTO user_interests (user_id, interest_id) VALUES (%s, %s)"
                    cursor.execute(insert_query, (session['user_id'], interest_id))
                
                conn.commit()
                flash('Your interests have been saved!', 'success')
                return redirect(url_for('buyer_dashboard'))
                
            except Exception as e:
                flash(f'Error saving interests: {str(e)}', 'error')
            finally:
                cursor.close()
                conn.close()
    
    conn = get_db_connection()
    if conn:
        cursor = conn.cursor(dictionary=True)
        try:
            cursor.execute(
                """
                SELECT interest_id
                FROM user_interests
                WHERE user_id = %s
                ORDER BY interest_id
                """,
                (session['user_id'],)
            )
            selected_interest_ids = [str(row['interest_id']) for row in cursor.fetchall()]
        finally:
            cursor.close()
            conn.close()

    return render_template(
        'interests.html',
        interests=interests,
        selected_interest_ids=selected_interest_ids
    )

@app.route('/save-interests', methods=['POST'])
def save_interests():
    return select_interests()

# ============================================
# API ENDPOINTS FOR INTERACTIVITY
# ============================================
@app.route('/api/product/<int:product_id>')
def api_get_product(product_id):
    conn = get_db_connection()
    product = None
    
    if conn:
        cursor = conn.cursor(dictionary=True)
        try:
            query = "SELECT * FROM products WHERE id = %s"
            cursor.execute(query, (product_id,))
            product = cursor.fetchone()
        except Exception as e:
            print(f"Error: {e}")
        finally:
            cursor.close()
            conn.close()
    
    if product:
        return jsonify(product)
    return jsonify({'error': 'Product not found'}), 404

@app.route('/api/favorites/add', methods=['POST'])
def api_add_favorite():
    if 'user_id' not in session:
        return jsonify({'success': False, 'message': 'Please login first'}), 401
    
    data = request.get_json()
    product_id = data.get('product_id')
    
    conn = get_db_connection()
    if conn:
        cursor = conn.cursor()
        try:
            query = "INSERT IGNORE INTO user_favorites (user_id, product_id) VALUES (%s, %s)"
            cursor.execute(query, (session['user_id'], product_id))
            conn.commit()
            return jsonify({'success': True, 'message': 'Added to favorites!'})
        except Exception as e:
            return jsonify({'success': False, 'message': str(e)}), 500
        finally:
            cursor.close()
            conn.close()
    
    return jsonify({'success': False, 'message': 'Database error'}), 500

@app.route('/api/interests')
def api_get_interests():
    conn = get_db_connection()
    interests = []
    
    if conn:
        cursor = conn.cursor(dictionary=True)
        try:
            query = "SELECT * FROM interests ORDER BY interest_name"
            cursor.execute(query)
            interests = cursor.fetchall()
        except Exception as e:
            print(f"Error: {e}")
        finally:
            cursor.close()
            conn.close()
    
    return jsonify(interests)

# ============================================
# SHOPPING CART
# ============================================
@app.route('/search')
def search_products():
    """AI-powered product search"""
    query = request.args.get('q', '').strip()
    
    if not query:
        flash('Please enter a search query', 'warning')
        return redirect(url_for('buyer_dashboard'))
    
    # Use AI recommendation engine for intelligent search
    products = recommendation_engine.search_products(query, num_results=20)
    
    return render_template('search_results.html', 
                         products=products,
                         search_query=query,
                         user_name=session.get('user_name'))

@app.route('/trending')
def trending_products():
    """Get trending products"""
    if 'user_id' not in session:
        return redirect(url_for('login'))
    
    products = recommendation_engine.get_trending_products(num_products=20)
    
    return render_template('trending.html', 
                         products=products,
                         user_name=session.get('user_name'))

@app.route('/api/recommendations/refresh', methods=['POST'])
def refresh_recommendations():
    """Refresh recommendation engine data"""
    if 'user_id' not in session or session.get('user_role') != 'seller':
        return jsonify({'success': False, 'message': 'Unauthorized'}), 401
    
    try:
        recommendation_engine.refresh_data()
        return jsonify({
            'success': True, 
            'message': 'Recommendation engine refreshed successfully'
        })
    except Exception as e:
        return jsonify({
            'success': False,
            'message': f'Error: {str(e)}'
        }), 500

# ============================================
# SHOPPING CART
# ============================================
@app.route('/cart')
def view_cart():
    if 'user_id' not in session:
        return redirect(url_for('login'))
    
    cart_items = session.get('cart', [])
    total_price = sum(item['price'] * item['quantity'] for item in cart_items)
    
    return render_template('cart.html', cart_items=cart_items, total_price=total_price)

@app.route('/api/cart/add', methods=['POST'])
def add_to_cart():
    if 'user_id' not in session:
        return jsonify({'success': False, 'message': 'Please login first'}), 401
    
    data = request.get_json()
    product_id = data.get('product_id')
    product_title = data.get('title')
    product_price = data.get('price')
    product_image = data.get('image')
    
    if not all([product_id, product_title, product_price]):
        return jsonify({'success': False, 'message': 'Missing product data'}), 400
    
    # Initialize cart in session if not exists
    if 'cart' not in session:
        session['cart'] = []
    
    # Check if product already in cart
    existing_item = next((item for item in session['cart'] if item['id'] == product_id), None)
    
    if existing_item:
        existing_item['quantity'] += 1
    else:
        session['cart'].append({
            'id': product_id,
            'title': product_title,
            'price': float(product_price),
            'image': product_image,
            'quantity': 1
        })
    
    session.modified = True
    
    return jsonify({
        'success': True, 
        'message': f'{product_title} added to cart!',
        'cart_count': len(session['cart'])
    })

@app.route('/api/cart/remove/<int:product_id>', methods=['POST'])
def remove_from_cart(product_id):
    if 'cart' in session:
        session['cart'] = [item for item in session['cart'] if item['id'] != product_id]
        session.modified = True
    
    return jsonify({'success': True})

@app.route('/checkout', methods=['GET', 'POST'])
def checkout():
    if 'user_id' not in session:
        return redirect(url_for('login'))
    
    if request.method == 'POST':
        cart_items = session.get('cart', [])
        
        if not cart_items:
            flash('Your cart is empty', 'error')
            return redirect(url_for('view_cart'))
        
        try:
            conn = get_db_connection()
            if conn:
                cursor = conn.cursor()
                
                # Create order
                order_query = """
                    INSERT INTO orders (user_id, total_amount, status, created_at)
                    VALUES (%s, %s, %s, NOW())
                """
                total_amount = sum(item['price'] * item['quantity'] for item in cart_items)
                cursor.execute(order_query, (session['user_id'], total_amount, 'pending'))
                order_id = cursor.lastrowid
                
                # Add order items
                for item in cart_items:
                    item_query = """
                        INSERT INTO order_items (order_id, product_id, quantity, price)
                        VALUES (%s, %s, %s, %s)
                    """
                    cursor.execute(item_query, (order_id, item['id'], item['quantity'], item['price']))
                
                conn.commit()
                cursor.close()
                conn.close()
                
                # Clear cart
                session['cart'] = []
                session.modified = True
                
                flash(f'Order placed successfully! Order ID: {order_id}', 'success')
                return redirect(url_for('buyer_dashboard'))
                
        except Exception as e:
            flash(f'Error processing order: {str(e)}', 'error')
            print(f"Checkout error: {e}")
    
    cart_items = session.get('cart', [])
    total_price = sum(item['price'] * item['quantity'] for item in cart_items)
    
    return render_template('checkout.html', cart_items=cart_items, total_price=total_price)

# ============================================
# IMAGE UPLOADER PAGE
# ============================================
@app.route('/image-uploader')
def image_uploader():
    """Page for uploading product images"""
    return render_template('image_uploader.html')

@app.route('/api/all-products')
def get_all_products():
    """API endpoint to get all products"""
    conn = get_db_connection()
    products = []
    
    if conn:
        cursor = conn.cursor(dictionary=True)
        try:
            cursor.execute("SELECT id, title, category, image FROM products WHERE id IN (SELECT MIN(id) FROM products GROUP BY LOWER(TRIM(title))) ORDER BY title")
            products = cursor.fetchall()
        finally:
            cursor.close()
            conn.close()
    
    return jsonify({'products': products})

# ============================================
# RUN SERVER
# ============================================
if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)
