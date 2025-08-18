-- sql/pos_schema.sql
CREATE DATABASE IF NOT EXISTS restaurant_pos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE restaurant_pos;

-- users
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','cashier','kitchen') DEFAULT 'cashier',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- menu_items
CREATE TABLE menu_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  category VARCHAR(100),
  image VARCHAR(255) DEFAULT NULL,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- orders
CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  table_no VARCHAR(30) DEFAULT NULL,
  total_amount DECIMAL(10,2) DEFAULT 0.00,
  status ENUM('pending','paid','canceled') DEFAULT 'pending',
  created_by INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- order_items
CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  menu_id INT NOT NULL,
  quantity INT NOT NULL DEFAULT 1,
  price DECIMAL(10,2) DEFAULT 0.00,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (menu_id) REFERENCES menu_items(id) ON DELETE SET NULL
);

-- kot tickets
CREATE TABLE kot_tickets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  item_id INT NOT NULL,
  qty INT NOT NULL DEFAULT 1,
  status ENUM('pending','cooking','ready','served') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- seed: create admin user (use PHP to generate password_hash)
-- Run this PHP snippet locally to print a bcrypt hash:
-- <?php echo password_hash('123456', PASSWORD_DEFAULT); ?>

INSERT INTO users (username, password_hash, role) VALUES
('admin', '$2y$10$EXAMPLEPLACEHOLDERHASHCHANGEIT', 'admin');

INSERT INTO menu_items (name, price, category) VALUES
('Chicken Biryani', 250.00, 'Main Course'),
('Beef Burger', 180.00, 'Fast Food'),
('French Fries', 80.00, 'Snacks'),
('Coca Cola', 30.00, 'Drinks');
