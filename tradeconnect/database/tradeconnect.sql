CREATE DATABASE IF NOT EXISTS tradeconnect;
USE tradeconnect;

DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS tradesman_profiles;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('client','tradesman','admin') DEFAULT 'client',
    city VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tradesman_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    trade_category ENUM('plumber','electrician','carpenter','painter','other') NOT NULL,
    experience_years INT DEFAULT 0,
    hourly_rate DECIMAL(8,2) DEFAULT 500,
    bio TEXT,
    is_available TINYINT(1) DEFAULT 1,
    is_verified TINYINT(1) DEFAULT 0,
    rating_avg DECIMAL(3,2) DEFAULT 0.00,
    total_jobs INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    tradesman_id INT NOT NULL,
    service_type VARCHAR(50) NOT NULL,
    description TEXT,
    booking_date DATE NOT NULL,
    booking_time VARCHAR(10),
    status ENUM('pending','accepted','completed','cancelled') DEFAULT 'pending',
    address TEXT,
    city VARCHAR(100),
    hours INT DEFAULT 1,
    total_amount DECIMAL(10,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tradesman_id) REFERENCES tradesman_profiles(id) ON DELETE CASCADE
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    client_id INT NOT NULL,
    tradesman_id INT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);

-- Passwords will be fixed by fix_passwords.php
INSERT INTO users (name,email,password,phone,role,city) VALUES
('Admin','admin@tradeconnect.pk','PLACEHOLDER','0300-0000000','admin','Karachi'),
('Muhammad Fasih','client@demo.com','PLACEHOLDER','0300-1234567','client','Karachi'),
('Ahmed Khan','tradesman@demo.com','PLACEHOLDER','0321-9876543','tradesman','Karachi'),
('Muhammad Raza','raza@demo.com','PLACEHOLDER','0345-9988776','tradesman','Lahore'),
('Salman Akhtar','salman@demo.com','PLACEHOLDER','0312-5544332','tradesman','Karachi'),
('Bilal Hussain','bilal@demo.com','PLACEHOLDER','0333-1122334','tradesman','Islamabad');

INSERT INTO tradesman_profiles (user_id,trade_category,experience_years,hourly_rate,bio,is_available,is_verified,rating_avg,total_jobs) VALUES
(3,'electrician',12,500,'Expert home wiring, MCB panel, UPS & solar.',1,1,5.00,140),
(4,'plumber',8,400,'Pipe leaks, bathroom fittings, water tank install.',1,1,4.70,95),
(5,'carpenter',15,600,'Custom furniture, wardrobes, kitchen cabinets.',0,1,4.90,200),
(6,'painter',6,350,'Interior & exterior painting, texture work.',1,0,4.60,70);

INSERT INTO bookings (client_id,tradesman_id,service_type,description,booking_date,booking_time,status,address,city,hours,total_amount) VALUES
(2,1,'electrician','Fix home wiring in 3 rooms','2026-05-12','10:00','accepted','House #5, Block B, Gulshan','Karachi',2,1000),
(2,2,'plumber','Bathroom pipe leaking','2026-04-28','14:00','completed','House #5, Block B, Gulshan','Karachi',2,800),
(2,3,'carpenter','Custom wardrobe needed','2026-04-15','11:00','completed','House #5, Block B, Gulshan','Karachi',5,3000);

-- Add profile_image column (run if not exists)
ALTER TABLE users ADD COLUMN IF NOT EXISTS profile_image VARCHAR(255) DEFAULT NULL;

-- Add profile_image column (run if not exists)
ALTER TABLE users ADD COLUMN IF NOT EXISTS profile_image VARCHAR(255) DEFAULT NULL;
