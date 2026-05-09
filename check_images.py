from database import get_db_connection

conn = get_db_connection()
if conn:
    cursor = conn.cursor(dictionary=True)
    # Get table structure
    cursor.execute('DESCRIBE products')
    columns = cursor.fetchall()
    print('Products table columns:')
    for col in columns:
        print(f'  {col["Field"]}: {col["Type"]}')
    
    # Get sample products
    cursor.execute('SELECT id, title, image FROM products LIMIT 5')
    products = cursor.fetchall()
    print(f'\nSample products with images:')
    for p in products:
        print(f'  ID: {p["id"]}, Title: {p["title"]}, Image: {p["image"]}')
    
    cursor.close()
    conn.close()
