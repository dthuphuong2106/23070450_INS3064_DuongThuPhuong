-- Optional database setup for Contact Manager.
-- If your contact_manager database and contacts table already exist,
-- do not import this file because it may conflict with existing data.

CREATE DATABASE IF NOT EXISTS contact_manager
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE contact_manager;

CREATE TABLE IF NOT EXISTS contacts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    group_name VARCHAR(100) DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_contact_email (email)
);
