"""
Update product images with real images from Unsplash
"""
from database import get_db_connection

# Mapping of categories to better Unsplash image URLs
IMAGE_MAPPING = {
    'action': 'https://images.unsplash.com/photo-1485846234645-a62644f84728?w=500&h=600&fit=crop',  # Action movie
    'comedy': 'https://images.unsplash.com/photo-1485846234645-a62644f84728?w=500&h=600&fit=crop',  # Comedy
    'technology': 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=500&h=600&fit=crop',  # Tech laptop
    'electronics': 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=500&h=600&fit=crop',  # Electronics
    'fashion': 'https://images.unsplash.com/photo-1495521821757-a1efb6729352?w=500&h=600&fit=crop',  # Fashion
    'sports': 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=500&h=600&fit=crop',  # Sports
    'music': 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=500&h=600&fit=crop',  # Music
    'gaming': 'https://images.unsplash.com/photo-1538481143235-5d630a6a4fc1?w=500&h=600&fit=crop',  # Gaming
    'travel': 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=500&h=600&fit=crop',  # Travel
    'food': 'https://images.unsplash.com/photo-1495521821757-a1efb6729352?w=500&h=600&fit=crop',  # Food
    'books': 'https://images.unsplash.com/photo-1507842217343-583f7270bfed?w=500&h=600&fit=crop',  # Books
    'science': 'https://images.unsplash.com/photo-1532187863486-abbb4d42558f?w=500&h=600&fit=crop',  # Science
    'art': 'https://images.unsplash.com/photo-1561214115-6d2f1b0609fa?w=500&h=600&fit=crop',  # Art
}

def update_images():
    conn = get_db_connection()
    if not conn:
        print("❌ Failed to connect to database")
        return
    
    cursor = conn.cursor(dictionary=True)
    
    try:
        # Get all products
        cursor.execute('SELECT id, title, category FROM products')
        products = cursor.fetchall()
        
        print(f"Updating images for {len(products)} products...\n")
        
        updated_count = 0
        for product in products:
            product_id = product['id']
            category = product['category'].lower()
            title = product['title']
            
            # Get image URL based on category
            image_url = IMAGE_MAPPING.get(category, 'https://via.placeholder.com/300x400/667eea/ffffff?text=' + title.replace(' ', '+'))
            
            # Update database
            cursor.execute('UPDATE products SET image = %s WHERE id = %s', (image_url, product_id))
            updated_count += 1
            print(f"✅ Updated: {title:<30} (Category: {category})")
        
        conn.commit()
        print(f"\n✨ Successfully updated {updated_count} products!")
        
    except Exception as e:
        print(f"❌ Error: {str(e)}")
    finally:
        cursor.close()
        conn.close()

if __name__ == "__main__":
    print("🖼️  Product Image Updater\n")
    update_images()
