-- Create the database
CREATE DATABASE IF NOT EXISTS MSTIP;
USE MSTIP;

-- Create the User table
CREATE TABLE User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(10) UNIQUE NOT NULL,
    email_address VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('User', 'Admin') DEFAULT 'User',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    CONSTRAINT chk_email CHECK (email_address LIKE '%@mstip.edu.ph')
);

INSERT INTO User (user_id, email_address, password, user_type)
VALUES ('A123456', 'admin@mstip.edu.ph', 'test', 'Admin');

-- Create the User_Information table
CREATE TABLE User_Information (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(10) NOT NULL,
    email_address VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100) NULL,
    last_name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    course VARCHAR(200) NOT NULL,
    year_graduated INT NOT NULL,
    skills TEXT NULL,
    resume VARCHAR(255) NOT NULL,
    linkedin_profile TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (email_address) REFERENCES User(email_address) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_email (email_address)
);