-- Update existing users table to add role column if it doesn't exist
ALTER TABLE users ADD COLUMN IF NOT EXISTS role ENUM('admin', 'user') DEFAULT 'user';

-- Set the first user as admin (you can change this to set a specific user as admin)
UPDATE users SET role = 'admin' WHERE id = 1;

-- Or set admin by email (replace with your email)
-- UPDATE users SET role = 'admin' WHERE email = 'your-email@example.com';

-- Insert a default admin user (optional - remove if not needed)
-- INSERT INTO users (fullname, email, password, role) 
-- VALUES ('Admin', 'admin@rama.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- Note: The password above is 'password' hashed with bcrypt
