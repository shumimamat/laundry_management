CREATE DATABASE IF NOT EXISTS laundry;

USE laundry;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default Admin (Optional)
INSERT INTO users (name, email, password, role) 
VALUES ('Admin User', 'admin@laundry.com', 'adminpassword', 'admin'); 

-- Insert a default Customer (Optional)
INSERT INTO users (name, email, password, role) 
VALUES ('Customer User', 'customer@laundry.com', 'customerpassword', 'customer'); 

-- Services Table
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    price_per_kg DECIMAL(10,2) NOT NULL,
    description TEXT,
    image VARCHAR(255) NULL,
    availability ENUM('Available', 'Not Available') DEFAULT 'Available' -- Added availability field
);

-- Insert services into the services table with consistent naming
INSERT INTO services (service_name, price_per_kg, description, image, availability) 
VALUES 
    ('Laundry', 10.00, 'Regular laundry service, washing and folding.', 'service1.jpg', 'Available'),
    ('Shirt / Blouse', 1.50, 'Shirt and blouse washing and pressing service.', 'service8.jpg', 'Available'),
    ('Trousers', 3.00, 'Laundry service for trousers.', 'service10.jpg', 'Available'),
    ('Jacket', 15.00, 'Dry cleaning for jackets.', 'service12.jpg', 'Available'),
    ('Coat', 20.00, 'Dry cleaning for coats.', 'service6.jpg', 'Available'),
    ('Shirt Ironing', 2.00, 'Ironing service for shirts.', 'service4.jpeg', 'Available'),
    ('Pants Ironing', 3.00, 'Ironing service for pants.', 'service13.jpg', 'Available'),
    ('Suit Dry Cleaning', 25.00, 'Dry cleaning service for suits.', 'service15.jpg', 'Available'),
    ('Dress Washing', 18.00, 'Specialized washing for dresses.', 'service9.jpg', 'Available'),
    ('Delicate Fabrics', 30.00, 'Cleaning service for delicate fabrics.', 'service14.jpg', 'Available'),
    ('Heavy Laundry', 35.00, 'Laundry service for heavy items such as blankets and curtains.', 'service2.jpg', 'Available');

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    service_id INT NOT NULL,
    weight DECIMAL(10,2) NOT NULL,
    status ENUM('Pending', 'In Progress', 'Completed') DEFAULT 'Pending',
    payment_status ENUM('Pending', 'Paid') DEFAULT 'Pending',
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    delivery_date DATETIME NULL,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

-- Payments Table
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    payment_method VARCHAR(255) NOT NULL,
    payment_status ENUM('Pending', 'Paid') DEFAULT 'Pending',
    payment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Cart Table
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
