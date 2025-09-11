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

-- Sample Admin
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

CREATE TABLE regions (
    region_id INT AUTO_INCREMENT PRIMARY KEY,
    region_code VARCHAR(20) NOT NULL UNIQUE,
    region_name VARCHAR(255) NOT NULL
);

INSERT INTO regions (region_code, region_name) VALUES
('NCR', 'National Capital Region'),
('CAR', 'Cordillera Administrative Region'),
('REGION1', 'Ilocos Region (Region I)'),
('REGION2', 'Cagayan Valley (Region II)'),
('REGION3', 'Central Luzon (Region III)'),
('REGION4A', 'CALABARZON (Region IV-A)'),
('REGION4B', 'MIMAROPA (Region IV-B)'),
('REGION5', 'Bicol Region (Region V)'),
('REGION6', 'Western Visayas (Region VI)'),
('NIR', 'Negros Island Region'),
('REGION7', 'Central Visayas (Region VII)'),
('REGION8', 'Eastern Visayas (Region VIII)'),
('REGION9', 'Zamboanga Peninsula (Region IX)'),
('REGION10', 'Northern Mindanao (Region X)'),
('REGION11', 'Davao Region (Region XI)'),
('REGION12', 'SOCCSKSARGEN (Region XII)'),
('REGION13', 'Caraga (Region XIII)'),
('BARMM', 'Bangsamoro Autonomous Region in Muslim Mindanao');

-- Create the job_listings table
CREATE TABLE job_listings (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    job_title VARCHAR(150) NOT NULL,
    job_position ENUM('Entry Level', 'Junior', 'Mid-Level', 'Senior', 'Managerial') DEFAULT 'Entry Level',
    company_name VARCHAR(150) NOT NULL,
    job_type ENUM('Government', 'Private') NOT NULL,
    government_agency VARCHAR(150) NULL,
    location VARCHAR(150) NOT NULL,
    region_id INT NOT NULL,
    slots_available INT NOT NULL DEFAULT 1,
    salary_range VARCHAR(100),
    job_description TEXT,
    qualifications TEXT,
    job_type_shift ENUM('Full-Time', 'Part-Time') DEFAULT 'Full-Time',
    application_deadline DATE,
    contact_email VARCHAR(150),
    image_url VARCHAR(255),
    posted_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (region_id) REFERENCES regions(region_id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
);

-- Create the applications table
CREATE TABLE applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(10) NOT NULL,
    job_id INT NOT NULL,
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Pending', 'Reviewed', 'Accepted', 'Rejected') DEFAULT 'Pending',
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (job_id) REFERENCES job_listings(job_id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_job_id (job_id)
);

-- Complete INSERT for job_listings with region_id references

INSERT INTO job_listings (
    job_title, job_position, company_name, job_type, government_agency, location, region_id, slots_available, salary_range, 
    job_description, qualifications, job_type_shift, application_deadline, contact_email, image_url
) VALUES
-- NCR (region_id = 1)
('Administrative Assistant II', 'Entry Level', 'Department of Education', 'Government', 'DepEd', 
 'Pasig City, Metro Manila', 1, 10, '₱20,000 - ₱30,000',
 'Provide clerical and administrative support to the office of the school division.',
 'Graduate of any 4-year course; Proficient in MS Office; Strong communication skills.',
 'Full-Time', '2025-12-30', 'careers@deped.gov.ph', 'images/deped_admin.png'),

('Software Developer', 'Mid-Level', 'Accenture Philippines', 'Private', NULL,
 'Quezon City, Metro Manila', 1, 15, '₱30,000 - ₱50,000',
 'Develop, test, and maintain software applications for clients worldwide.',
 'Graduate of IT/CS or related field; Experience in Java, Python, or PHP preferred.',
 'Full-Time', '2025-11-20', 'jobs@accenture.com', 'images/software_dev.jpg'),

-- CAR (region_id = 2)
('Nurse I', 'Entry Level', 'Department of Health', 'Government', 'DOH', 
 'Baguio City, Benguet', 2, 20, '₱25,000 - ₱35,000',
 'Provide nursing care and assist in public health programs under the DOH regional office.',
 'BS Nursing graduate; Must be a licensed nurse; With or without experience.',
 'Full-Time', '2025-10-31', 'careers@doh.gov.ph', 'images/doh_nurse.svg'),

('Field Sales Representative', 'Junior', 'Globe Telecom', 'Private', NULL,
 'La Trinidad, Benguet', 2, 12, '₱18,000 - ₱28,000',
 'Promote and sell Globe products to clients within the CAR region.',
 'Graduate of any 4-year course; Good communication skills; Sales experience preferred.',
 'Full-Time', '2025-12-05', 'hr@globe.com.ph', 'images/globe_sales.jpg'),

-- Region I (region_id = 3)
('Agricultural Technologist', 'Entry Level', 'Department of Agriculture', 'Government', 'DA', 
 'San Fernando City, La Union', 3, 7, '₱22,000 - ₱32,000',
 'Assist farmers with modern agricultural techniques and monitor crop yields.',
 'Graduate of Agriculture or related course; Licensed agriculturist preferred.',
 'Full-Time', '2025-11-30', 'careers@da.gov.ph', 'images/da_agri.png'),

('Hotel Receptionist', 'Junior', 'Thunderbird Resorts', 'Private', NULL,
 'San Fernando City, La Union', 3, 5, '₱15,000 - ₱20,000',
 'Greet guests, handle check-in/check-out, and assist with hotel services.',
 'At least 2-year vocational course; Good English communication skills.',
 'Full-Time', '2025-10-20', 'jobs@thunderbird.ph', 'images/hotel_receptionist.jpg'),

-- Region II (region_id = 4)
('Science Research Specialist I', 'Mid-Level', 'Department of Science and Technology', 'Government', 'DOST',
 'Tuguegarao City, Cagayan', 4, 6, '₱30,000 - ₱40,000',
 'Assist in implementing science and technology research projects.',
 'Graduate of Science/Engineering course; With or without experience.',
 'Full-Time', '2025-12-15', 'careers@dost.gov.ph', 'images/dost_research.png'),

('Customer Support Specialist', 'Junior', 'Ibex Global', 'Private', NULL,
 'Tuguegarao City, Cagayan', 4, 25, '₱18,000 - ₱25,000',
 'Handle customer queries and provide technical support via calls and chats.',
 'At least 2 years college; Computer literate; Good communication skills.',
 'Full-Time', '2025-11-10', 'hr@ibex.ph', 'images/ibex_support.png'),

-- Region III (region_id = 5)
('Police Officer I', 'Entry Level', 'Philippine National Police', 'Government', 'PNP',
 'San Fernando City, Pampanga', 5, 50, '₱29,668',
 'Maintain peace and order, enforce laws, and ensure public safety.',
 'Graduate of any 4-year course; Must pass NAPOLCOM and medical exams.',
 'Full-Time', '2025-12-01', 'recruitment@pnp.gov.ph', 'images/pnp_officer.png'),

('Warehouse Staff', 'Entry Level', 'San Miguel Corporation', 'Private', NULL,
 'San Fernando City, Pampanga', 5, 30, '₱15,000 - ₱22,000',
 'Assist in receiving, organizing, and releasing warehouse goods.',
 'High school graduate; Physically fit; Willing to work shifting schedules.',
 'Full-Time', '2025-10-25', 'careers@smc.ph', 'images/smc_warehouse.png'),

-- Region VII (region_id = 11)
('Public Health Officer', 'Mid-Level', 'Department of Health', 'Government', 'DOH',
 'Cebu City, Cebu', 11, 10, '₱28,000 - ₱38,000',
 'Oversee public health programs in the Cebu regional office.',
 'Medical degree or Nursing graduate with MPH; At least 1 year of experience.',
 'Full-Time', '2025-11-30', 'cebu_health@doh.gov.ph', 'images/doh_nurse.svg'),

('Customer Service Associate', 'Junior', 'Concentrix', 'Private', NULL,
 'Cebu City, Cebu', 11, 50, '₱18,000 - ₱25,000',
 'Handle customer concerns through calls and emails, ensuring customer satisfaction.',
 'At least 2 years college; Good English communication skills.',
 'Full-Time', '2025-12-10', 'hr@concentrix.com', 'images/customer_service.png'),

-- Region XI (region_id = 15)
('Teacher I', 'Entry Level', 'Department of Education', 'Government', 'DepEd',
 'Davao City, Davao del Sur', 15, 40, '₱25,000 - ₱32,000',
 'Teach students under the K-12 curriculum following DepEd standards.',
 'Education graduate; LET passer; With or without experience.',
 'Full-Time', '2025-11-25', 'deped_davao@deped.gov.ph', 'images/deped_admin.png'),

('Part-time Tutor', 'Junior', 'Kumon Philippines', 'Private', NULL,
 'Davao City, Davao del Sur', 15, 8, '₱150/hour',
 'Assist students in Math and English subjects through Kumon learning methods.',
 'At least 2nd-year college student; Strong Math and English skills.',
 'Part-Time', '2025-10-25', 'kumonjobs@kumon.ph', 'images/tutor.jpg'),

-- BARMM (region_id = 18)
('Community Development Officer', 'Mid-Level', 'Bangsamoro Autonomous Region in Muslim Mindanao Government', 'Government', 'BARMM',
 'Cotabato City, Maguindanao', 18, 12, '₱23,000 - ₱33,000',
 'Coordinate and implement community development projects under the BARMM Ministry.',
 'Graduate of Social Work, Development Studies, or related courses.',
 'Full-Time', '2025-12-20', 'careers@barmm.gov.ph', 'images/barmm_dev.png'),

('Sales Associate', 'Entry Level', 'Puregold Price Club', 'Private', NULL,
 'Cotabato City, Maguindanao', 18, 20, '₱12,000 - ₱16,000',
 'Assist customers, manage sales transactions, and maintain product displays.',
 'High school graduate; Customer service oriented.',
 'Full-Time', '2025-10-30', 'jobs@puregold.com.ph', 'images/puregold_sales.png');
