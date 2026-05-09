"""
AI-Powered Recommendation Engine
Uses collaborative filtering and content-based recommendations
"""

import pandas as pd
from database import get_db_connection

class RecommendationEngine:
    """
    AI recommendation engine for personalized product suggestions
    """
    
    def __init__(self):
        self.products_df = None
        self.user_interests = {}
        self.initialize()
    
    def initialize(self):
        """Initialize the recommendation engine with data from database"""
        print("🤖 Initializing AI Recommendation Engine...")
        self.load_products()
        print("✅ AI Engine Ready!")
    
    def load_products(self):
        """Load products from database"""
        conn = get_db_connection()
        if conn:
            cursor = conn.cursor(dictionary=True)
            try:
                query = """
                    SELECT p.id, p.title, p.category, p.price, p.rating, p.description, p.image
                    FROM products p
                    INNER JOIN (
                        SELECT MIN(id) AS id
                        FROM products
                        GROUP BY LOWER(TRIM(title))
                    ) unique_products ON unique_products.id = p.id
                    ORDER BY p.rating DESC, p.created_at DESC
                """
                cursor.execute(query)
                products = cursor.fetchall()
                
                if products:
                    self.products_df = pd.DataFrame(products)
                    
                    # Convert rating to numeric (in case it's stored as string)
                    self.products_df['rating'] = pd.to_numeric(self.products_df['rating'], errors='coerce').fillna(0)
                    self.products_df['price'] = pd.to_numeric(self.products_df['price'], errors='coerce').fillna(0)
                    self.products_df['id'] = pd.to_numeric(self.products_df['id'], errors='coerce').fillna(0)
                    
                    # Normalize ratings (convert to 0-5 scale)
                    if len(self.products_df) > 0 and self.products_df['rating'].max() > 0:
                        max_rating = self.products_df['rating'].max()
                        if max_rating > 5:
                            self.products_df['rating'] = (self.products_df['rating'] / max_rating) * 5
                    
                    print(f"📦 Loaded {len(products)} products")
                
            except Exception as e:
                print(f"❌ Error loading products: {e}")
            finally:
                cursor.close()
                conn.close()
    
    def get_user_interests(self, user_id):
        """Get user's selected interests from database"""
        conn = get_db_connection()
        interests = []
        
        if conn:
            cursor = conn.cursor(dictionary=True)
            try:
                query = """
                    SELECT i.interest_name 
                    FROM interests i
                    INNER JOIN user_interests ui ON i.id = ui.interest_id
                    WHERE ui.user_id = %s
                """
                cursor.execute(query, (user_id,))
                results = cursor.fetchall()
                interests = [r['interest_name'] for r in results]
                
            except Exception as e:
                print(f"❌ Error loading interests: {e}")
            finally:
                cursor.close()
                conn.close()
        
        return interests
    
    def recommend_by_interests(self, user_id, num_recommendations=12):
        """
        Recommend products based on user's selected interests
        """
        if self.products_df is None or len(self.products_df) == 0:
            return []
        
        interests = self.get_user_interests(user_id)
        if not interests:
            # If no interests, recommend top-rated products
            return self._recommend_top_rated(num_recommendations)
        
        # Filter products by user interests (case-insensitive)
        try:
            matching_products = self.products_df[
                self.products_df['category'].str.lower().isin([i.lower() for i in interests])
            ]
            
            if len(matching_products) == 0:
                # Fallback to top-rated if no matches
                return self._recommend_top_rated(num_recommendations)
            
            # Sort by rating and return top recommendations
            recommendations = matching_products.nlargest(num_recommendations, 'rating')
            result = recommendations.to_dict('records')
            
            # Convert numpy types to Python types for JSON serialization
            return [{k: (float(v) if isinstance(v, (int, float)) else v) for k, v in rec.items()} for rec in result]
        except Exception as e:
            print(f"❌ Error in recommend_by_interests: {e}")
            return self._recommend_top_rated(num_recommendations)
    
    def recommend_similar_products(self, product_id, num_recommendations=5):
        """
        Recommend products similar to a given product
        Based on category and rating similarity
        """
        if self.products_df is None or len(self.products_df) == 0:
            return []
        
        # Find the product
        product = self.products_df[self.products_df['id'] == product_id]
        if len(product) == 0:
            return []
        
        product_category = product.iloc[0]['category']
        
        # Find similar products in same category
        similar = self.products_df[
            (self.products_df['category'] == product_category) &
            (self.products_df['id'] != product_id)
        ]
        
        if len(similar) == 0:
            return []
        
        # Sort by rating
        recommendations = similar.nlargest(num_recommendations, 'rating')
        return recommendations.to_dict('records')
    
    def _recommend_top_rated(self, num_recommendations=12):
        """Get top-rated products"""
        if self.products_df is None or len(self.products_df) == 0:
            return []
        
        try:
            top_products = self.products_df.nlargest(num_recommendations, 'rating')
            result = top_products.to_dict('records')
            # Convert numpy types to Python types for JSON serialization
            return [{k: (float(v) if isinstance(v, (int, float)) else v) for k, v in rec.items()} for rec in result]
        except Exception as e:
            print(f"❌ Error in _recommend_top_rated: {e}")
            return []
    
    def get_personalized_recommendations(self, user_id, num_recommendations=12):
        """
        Get personalized recommendations for a user
        Combines interest-based and rating-based recommendations
        """
        # Get interest-based recommendations
        interest_recs = self.recommend_by_interests(user_id, num_recommendations)
        
        # If we have enough recommendations, return them
        if len(interest_recs) >= num_recommendations:
            return interest_recs[:num_recommendations]
        
        # Fill remaining with top-rated products
        top_recs = self._recommend_top_rated(num_recommendations)
        
        # Remove duplicates
        rec_ids = set([r['id'] for r in interest_recs])
        for rec in top_recs:
            if rec['id'] not in rec_ids and len(interest_recs) < num_recommendations:
                interest_recs.append(rec)
                rec_ids.add(rec['id'])
        
        return interest_recs[:num_recommendations]
    
    def search_products(self, query, num_results=10):
        """
        AI-powered product search
        Searches in title and description
        """
        if self.products_df is None or len(self.products_df) == 0:
            return []
        
        try:
            query = query.lower()
            
            # Search in title and description
            mask = (
                self.products_df['title'].str.lower().str.contains(query, na=False) |
                self.products_df['description'].str.lower().str.contains(query, na=False)
            )
            
            results = self.products_df[mask].nlargest(num_results, 'rating')
            result = results.to_dict('records')
            # Convert numpy types to Python types for JSON serialization
            return [{k: (float(v) if isinstance(v, (int, float)) else v) for k, v in rec.items()} for rec in result]
        except Exception as e:
            print(f"❌ Error in search_products: {e}")
            return []
    
    def get_trending_products(self, num_products=10):
        """Get trending/top-rated products"""
        if self.products_df is None or len(self.products_df) == 0:
            return []
        
        try:
            trending = self.products_df.nlargest(num_products, 'rating')
            result = trending.to_dict('records')
            # Convert numpy types to Python types for JSON serialization
            return [{k: (float(v) if isinstance(v, (int, float)) else v) for k, v in rec.items()} for rec in result]
        except Exception as e:
            print(f"❌ Error in get_trending_products: {e}")
            return []
    
    def refresh_data(self):
        """Refresh product data from database"""
        print("🔄 Refreshing recommendation engine data...")
        self.load_products()
        print("✅ Data refreshed!")

# Initialize recommendation engine
recommendation_engine = RecommendationEngine()
