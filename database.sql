-- Create the database
CREATE DATABASE IF NOT EXISTS MSTIP;
USE MSTIP;

-- Create the User table
CREATE TABLE User (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    User_ID VARCHAR(50) UNIQUE NOT NULL,
    Email_Address VARCHAR(100) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    User_type ENUM('User', 'Admin') NOT NULL,
    CONSTRAINT chk_email CHECK (Email_Address LIKE '%@mstip.edu.ph')
);

INSERT INTO User (User_ID, Email_Address, Password, User_type)
VALUES ('A123456', 'admin@mstip.edu.ph', 'test', 'Admin');

-- Create the ID table
CREATE TABLE User_Information (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    User_ID VARCHAR(50) NOT NULL,
    Email_Address VARCHAR(100) NOT NULL,
    First_Name VARCHAR(50) NOT NULL,
    Middle_Name VARCHAR(50),
    Last_Name VARCHAR(50) NOT NULL,
    Phone_Number VARCHAR(20) NOT NULL,
    Course VARCHAR(100) NOT NULL,
    Year_Graduated YEAR NOT NULL,
    Skills TEXT,
    Resume VARCHAR(255),
    LinkedIn_Profile VARCHAR(255),
    FOREIGN KEY (User_ID) REFERENCES User(User_ID)
);