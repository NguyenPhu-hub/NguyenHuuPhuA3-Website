CREATE DATABASE IF NOT EXISTS shop_online CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shop_online;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(30) DEFAULT NULL,
    address VARCHAR(255) DEFAULT NULL,
    role ENUM('admin','staff','customer') NOT NULL DEFAULT 'customer',
    status ENUM('active','locked') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tài khoản mẫu theo tài liệu Buổi 2
INSERT IGNORE INTO users (full_name, email, password, role, status) VALUES
('Quản trị viên', 'admin@shop.local', '$2y$10$3bcAYXYYxv7TuWI9KH3zOOSDGTT8Q7P/ptvLmboC/WyXdJRWkVYku', 'admin', 'active'),
('Nhân viên bán hàng', 'staff@shop.local', '$2y$10$Q2u2cILb1athiBr5Nz9njOJSvQwfjB333zsvP4omlakUD9rCTpBO2', 'staff', 'active');
