-- Create database
CREATE DATABASE IF NOT EXISTS mlm_binary;
USE mlm_binary;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    referral_code VARCHAR(10) UNIQUE NOT NULL,
    sponsor_id INT,
    package_type ENUM('basic', 'silver', 'gold', 'platinum') NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sponsor_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Create binary positions table
CREATE TABLE IF NOT EXISTS binary_positions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    parent_id INT,
    left_child_id INT,
    right_child_id INT,
    position ENUM('left', 'right') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (left_child_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (right_child_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Create transactions table
CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('bonus_sponsor', 'bonus_level', 'bonus_pairing') NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create settings table
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    value TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create admins table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default settings
INSERT INTO settings (name, value) VALUES
('package_basic', '300000'),
('package_silver', '600000'),
('package_gold', '1000000'),
('package_platinum', '1500000'),
('bonus_sponsor', '10'),
('bonus_level_1', '7'),
('bonus_level_2', '5'),
('bonus_level_3', '3'),
('bonus_level_4', '2'),
('bonus_level_5', '1'),
('bonus_pairing', '5'),
('daily_pairing_cap', '10000000');

-- Insert default admin account
-- Default credentials: admin / admin123
INSERT INTO admins (username, password) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');