from database import get_db_connection
from werkzeug.security import generate_password_hash

conn = get_db_connection()
if conn:
    cursor = conn.cursor(dictionary=True)
    
    # Check users table
    cursor.execute('SELECT COUNT(*) as count FROM users')
    users_result = cursor.fetchone()
    print(f'✅ Total users in database: {users_result["count"]}')
    
    # List all users
    cursor.execute('SELECT id, name, email, role FROM users LIMIT 5')
    users = cursor.fetchall()
    if users:
        print(f'📋 Users in database:')
        for user in users:
            print(f'   ID: {user["id"]}, Name: {user["name"]}, Email: {user["email"]}, Role: {user["role"]}')
    else:
        print('❌ No users in database!')
        print('\n🔄 Creating test users for login...')
        
        # Create test buyer
        buyer_email = 'buyer@test.com'
        buyer_password = 'password123'
        hashed_password = generate_password_hash(buyer_password)
        
        cursor.execute(
            'INSERT INTO users (name, email, password, role) VALUES (%s, %s, %s, %s)',
            ('Test Buyer', buyer_email, hashed_password, 'buyer')
        )
        
        # Create test seller
        seller_email = 'seller@test.com'
        seller_password = 'password123'
        hashed_password = generate_password_hash(seller_password)
        
        cursor.execute(
            'INSERT INTO users (name, email, password, role) VALUES (%s, %s, %s, %s)',
            ('Test Seller', seller_email, hashed_password, 'seller')
        )
        
        conn.commit()
        print(f'✅ Test users created!')
        print(f'   Buyer: {buyer_email} / {buyer_password}')
        print(f'   Seller: {seller_email} / {seller_password}')
    
    cursor.close()
    conn.close()
else:
    print('❌ Failed to connect to database')
