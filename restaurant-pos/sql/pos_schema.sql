CREATE DATABASE restaurant_pos;
USE restaurant_pos;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','cashier','kitchen') DEFAULT 'cashier',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Menu Table
CREATE TABLE menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50)
);

-- Orders Table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    table_no INT,
    total_amount DECIMAL(10,2),
    status ENUM('pending','completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Order Items Table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    menu_id INT,
    quantity INT,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (menu_id) REFERENCES menu(id)
);

-- Sample User (admin / password: 123456)
INSERT INTO users (username, password, role)
VALUES ('admin', PASSWORD('123456'), 'admin');

-- Sample Menu
INSERT INTO menu (name, price, category) VALUES
('Chicken Curry', 150.00, 'Main Course'),
('Fried Rice', 120.00, 'Main Course'),
('Soft Drink', 30.00, 'Beverage');
