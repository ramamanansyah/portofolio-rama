CREATE DATABASE portfolio_db;
USE portfolio_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    excerpt TEXT NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255),
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Add role column if it doesn't exist
ALTER TABLE users ADD COLUMN IF NOT EXISTS role ENUM('admin', 'user') DEFAULT 'user';

-- Update existing users
UPDATE users SET role = 'user' WHERE role IS NULL;

-- Make first user admin
UPDATE users SET role = 'admin' WHERE id = 1;