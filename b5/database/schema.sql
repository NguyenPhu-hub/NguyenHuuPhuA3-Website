CREATE DATABASE IF NOT EXISTS shop_online
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shop_online;

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active'
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NULL,
    name VARCHAR(200) NOT NULL,
    sku VARCHAR(60) NOT NULL UNIQUE,
    description TEXT,
    brand VARCHAR(120),
    price DECIMAL(12,2) NOT NULL DEFAULT 0,
    sold_count INT NOT NULL DEFAULT 0,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_products_name (name),
    INDEX idx_products_brand (brand),
    INDEX idx_products_price (price),
    INDEX idx_products_sold_count (sold_count),
    CONSTRAINT fk_products_category FOREIGN KEY (category_id)
        REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    is_primary TINYINT(1) NOT NULL DEFAULT 0,
    CONSTRAINT fk_images_product FOREIGN KEY (product_id)
        REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_images_product_primary (product_id, is_primary)
);

INSERT INTO categories (name) VALUES
('Điện thoại'), ('Laptop'), ('Phụ kiện');

INSERT INTO products (category_id, name, sku, description, brand, price, sold_count, created_at) VALUES
(1, 'iPhone 15 128GB', 'IP15-128', 'Điện thoại Apple iPhone 15 bộ nhớ 128GB', 'Apple', 18990000, 120, '2026-01-10 10:00:00'),
(1, 'Samsung Galaxy S24', 'SS-S24', 'Điện thoại Samsung Galaxy S24', 'Samsung', 15990000, 95, '2026-02-10 10:00:00'),
(1, 'Xiaomi Redmi Note 13', 'XM-RN13', 'Điện thoại Xiaomi màn hình đẹp', 'Xiaomi', 4990000, 80, '2026-03-10 10:00:00'),
(2, 'MacBook Air M2', 'MB-AIRM2', 'Laptop Apple MacBook Air chip M2', 'Apple', 22990000, 65, '2026-04-10 10:00:00'),
(2, 'Dell Inspiron 15', 'DELL-IN15', 'Laptop Dell phục vụ học tập và làm việc', 'Dell', 14990000, 55, '2026-05-10 10:00:00'),
(2, 'ASUS Vivobook 15', 'ASUS-VB15', 'Laptop ASUS Vivobook 15', 'ASUS', 12990000, 42, '2026-06-10 10:00:00'),
(3, 'Chuột Logitech M331', 'LOG-M331', 'Chuột không dây Logitech', 'Logitech', 390000, 200, '2026-07-10 10:00:00'),
(3, 'Tai nghe Sony WH-CH520', 'SONY-CH520', 'Tai nghe bluetooth Sony', 'Sony', 990000, 150, '2026-08-10 10:00:00'),
(3, 'Bàn phím DareU EK87', 'DR-EK87', 'Bàn phím cơ DareU', 'DareU', 650000, 90, '2026-09-10 10:00:00'),
(3, 'Sạc Anker 20W', 'ANK-20W', 'Củ sạc nhanh Anker 20W', 'Anker', 350000, 75, '2026-10-10 10:00:00'),
(1, 'Samsung Galaxy A55', 'SS-A55', 'Điện thoại Samsung Galaxy A55', 'Samsung', 8990000, 60, '2026-10-20 10:00:00'),
(2, 'Lenovo IdeaPad Slim 3', 'LEN-SLIM3', 'Laptop Lenovo IdeaPad Slim 3', 'Lenovo', 11990000, 35, '2026-11-01 10:00:00'),
(1, 'iPhone 14 128GB', 'IP14-128', 'Điện thoại Apple iPhone 14', 'Apple', 13990000, 110, '2026-11-05 10:00:00'),
(3, 'Tai nghe JBL Tune 510BT', 'JBL-510BT', 'Tai nghe bluetooth JBL', 'JBL', 790000, 88, '2026-11-08 10:00:00');

-- Chưa có ảnh mẫu nên giao diện sẽ tự dùng ảnh mặc định.
