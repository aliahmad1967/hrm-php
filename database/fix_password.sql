-- Fix admin password
-- Run this SQL query in phpMyAdmin

-- First, let's see the current admin password hash
SELECT id, username, password FROM users WHERE username = 'admin';

-- Update admin password to 'admin123'
-- This uses the correct password_hash from PHP
UPDATE users SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE username = 'admin';

-- Verify the update
SELECT id, username, password FROM users WHERE username = 'admin';