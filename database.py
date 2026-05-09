import mysql.connector
from mysql.connector import Error

# Database configuration
DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': '',  # Default XAMPP MySQL password is empty
    'database': 'recommendation_engine',
    'charset': 'utf8mb4',
    'collation': 'utf8mb4_general_ci'
}

def get_db_connection():
    """
    Create and return a database connection
    Returns None if connection fails
    """
    try:
        connection = mysql.connector.connect(**DB_CONFIG)
        if connection.is_connected():
            return connection
    except Error as e:
        print(f"Error connecting to MySQL database: {e}")
        return None

def test_connection():
    """
    Test the database connection
    """
    conn = get_db_connection()
    if conn:
        print("✅ Database connection successful!")
        cursor = conn.cursor()
        cursor.execute("SELECT DATABASE();")
        db_name = cursor.fetchone()
        print(f"Connected to database: {db_name[0]}")
        cursor.close()
        conn.close()
        return True
    else:
        print("❌ Database connection failed!")
        return False

if __name__ == "__main__":
    # Test the connection when running this file directly
    test_connection()
