-- ================================================
-- OAuth Social Login Migration
-- Add Google and Facebook ID columns to users table
-- Run this if you already have an existing database
-- ================================================

USE recommendation_engine;

-- Add Google and Facebook ID columns to users table
ALTER TABLE users 
    ADD COLUMN google_id VARCHAR(255) NULL UNIQUE AFTER password,
    ADD COLUMN facebook_id VARCHAR(255) NULL UNIQUE AFTER google_id;

-- Add index for faster OAuth lookups
CREATE INDEX idx_google_id ON users(google_id);
CREATE INDEX idx_facebook_id ON users(facebook_id);

-- Show success message
SELECT 'OAuth migration completed successfully!' AS Status;
SELECT 'Users table now supports Google and Facebook login' AS Info;
