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

-- Create the job_listings table
CREATE TABLE job_listings (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    job_title VARCHAR(150) NOT NULL,
    company_name VARCHAR(150) NOT NULL,
    job_type ENUM('Government', 'Private') NOT NULL,
    government_agency VARCHAR(150) NULL,
    location VARCHAR(150) NOT NULL,
    region ENUM('NCR', 'CAR', 'Region I', 'Region II', 'Region III', 'Region IV-A', 'Region IV-B', 
                'Region V', 'Region VI', 'Region VII', 'Region VIII', 'Region IX', 'Region X', 
                'Region XI', 'Region XII', 'Region XIII', 'BARMM') NOT NULL,
    slots_available INT NOT NULL DEFAULT 1,
    salary_range VARCHAR(100),
    job_description TEXT,
    qualifications TEXT,
    job_type_shift ENUM('Full-Time', 'Part-Time') DEFAULT 'Full-Time',
    application_deadline DATE,
    contact_email VARCHAR(150),
    image_url VARCHAR(255),
    posted_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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

INSERT INTO job_listings (
    job_title, company_name, job_type, government_agency, location, region, slots_available, salary_range, 
    job_description, qualifications, job_type_shift, application_deadline, contact_email, image_url
) VALUES
-- NCR
('Administrative Assistant II', 'Department of Education', 'Government', 'DepEd', 
 'Pasig City, Metro Manila', 'NCR', 10, '₱20,000 - ₱30,000',
 'Provide clerical and administrative support to the office of the school division.',
 'Graduate of any 4-year course; Proficient in MS Office; Strong communication skills.',
 'Full-Time', '2025-12-30', 'careers@deped.gov.ph', 'images/deped_admin.jpg'),

('Software Developer', 'Accenture Philippines', 'Private', NULL,
 'Quezon City, Metro Manila', 'NCR', 15, '₱30,000 - ₱50,000',
 'Develop, test, and maintain software applications for clients worldwide.',
 'Graduate of IT/CS or related field; Experience in Java, Python, or PHP preferred.',
 'Full-Time', '2025-11-20', 'jobs@accenture.com', 'images/software_dev.jpg'),

-- CAR
('Nurse I', 'Department of Health', 'Government', 'DOH', 
 'Baguio City, Benguet', 'CAR', 20, '₱25,000 - ₱35,000',
 'Provide nursing care and assist in public health programs under the DOH regional office.',
 'BS Nursing graduate; Must be a licensed nurse; With or without experience.',
 'Full-Time', '2025-10-31', 'careers@doh.gov.ph', 'images/doh_nurse.jpg'),

('Field Sales Representative', 'Globe Telecom', 'Private', NULL,
 'La Trinidad, Benguet', 'CAR', 12, '₱18,000 - ₱28,000',
 'Promote and sell Globe products to clients within the CAR region.',
 'Graduate of any 4-year course; Good communication skills; Sales experience preferred.',
 'Full-Time', '2025-12-05', 'hr@globe.com.ph', 'images/globe_sales.jpg'),

-- Region I
('Agricultural Technologist', 'Department of Agriculture', 'Government', 'DA', 
 'San Fernando City, La Union', 'Region I', 7, '₱22,000 - ₱32,000',
 'Assist farmers with modern agricultural techniques and monitor crop yields.',
 'Graduate of Agriculture or related course; Licensed agriculturist preferred.',
 'Full-Time', '2025-11-30', 'careers@da.gov.ph', 'images/da_agri.jpg'),

('Hotel Receptionist', 'Thunderbird Resorts', 'Private', NULL,
 'San Fernando City, La Union', 'Region I', 5, '₱15,000 - ₱20,000',
 'Greet guests, handle check-in/check-out, and assist with hotel services.',
 'At least 2-year vocational course; Good English communication skills.',
 'Full-Time', '2025-10-20', 'jobs@thunderbird.ph', 'images/hotel_receptionist.jpg'),

-- Region II
('Science Research Specialist I', 'Department of Science and Technology', 'Government', 'DOST',
 'Tuguegarao City, Cagayan', 'Region II', 6, '₱30,000 - ₱40,000',
 'Assist in implementing science and technology research projects.',
 'Graduate of Science/Engineering course; With or without experience.',
 'Full-Time', '2025-12-15', 'careers@dost.gov.ph', 'images/dost_research.jpg'),

('Customer Support Specialist', 'Ibex Global', 'Private', NULL,
 'Tuguegarao City, Cagayan', 'Region II', 25, '₱18,000 - ₱25,000',
 'Handle customer queries and provide technical support via calls and chats.',
 'At least 2 years college; Computer literate; Good communication skills.',
 'Full-Time', '2025-11-10', 'hr@ibex.ph', 'images/ibex_support.jpg'),

-- Region III
('Police Officer I', 'Philippine National Police', 'Government', 'PNP',
 'San Fernando City, Pampanga', 'Region III', 50, '₱29,668',
 'Maintain peace and order, enforce laws, and ensure public safety.',
 'Graduate of any 4-year course; Must pass NAPOLCOM and medical exams.',
 'Full-Time', '2025-12-01', 'recruitment@pnp.gov.ph', 'images/pnp_officer.jpg'),

('Warehouse Staff', 'San Miguel Corporation', 'Private', NULL,
 'San Fernando City, Pampanga', 'Region III', 30, '₱15,000 - ₱22,000',
 'Assist in receiving, organizing, and releasing warehouse goods.',
 'High school graduate; Physically fit; Willing to work shifting schedules.',
 'Full-Time', '2025-10-25', 'careers@smc.ph', 'images/smc_warehouse.jpg'),

-- Region VII
('Public Health Officer', 'Department of Health', 'Government', 'DOH',
 'Cebu City, Cebu', 'Region VII', 10, '₱28,000 - ₱38,000',
 'Oversee public health programs in the Cebu regional office.',
 'Medical degree or Nursing graduate with MPH; At least 1 year of experience.',
 'Full-Time', '2025-11-30', 'cebu_health@doh.gov.ph', 'images/doh_health.jpg'),

('Customer Service Associate', 'Concentrix', 'Private', NULL,
 'Cebu City, Cebu', 'Region VII', 50, '₱18,000 - ₱25,000',
 'Handle customer concerns through calls and emails, ensuring customer satisfaction.',
 'At least 2 years college; Good English communication skills.',
 'Full-Time', '2025-12-10', 'hr@concentrix.com', 'images/customer_service.jpg'),

-- Region XI
('Teacher I', 'Department of Education', 'Government', 'DepEd',
 'Davao City, Davao del Sur', 'Region XI', 40, '₱25,000 - ₱32,000',
 'Teach students under the K-12 curriculum following DepEd standards.',
 'Education graduate; LET passer; With or without experience.',
 'Full-Time', '2025-11-25', 'deped_davao@deped.gov.ph', 'images/deped_teacher.jpg'),

('Part-time Tutor', 'Kumon Philippines', 'Private', NULL,
 'Davao City, Davao del Sur', 'Region XI', 8, '₱150/hour',
 'Assist students in Math and English subjects through Kumon learning methods.',
 'At least 2nd-year college student; Strong Math and English skills.',
 'Part-Time', '2025-10-25', 'kumonjobs@kumon.ph', 'images/tutor.jpg'),

-- BARMM
('Community Development Officer', 'Bangsamoro Autonomous Region in Muslim Mindanao Government', 'Government', 'BARMM',
 'Cotabato City, Maguindanao', 'BARMM', 12, '₱23,000 - ₱33,000',
 'Coordinate and implement community development projects under the BARMM Ministry.',
 'Graduate of Social Work, Development Studies, or related courses.',
 'Full-Time', '2025-12-20', 'careers@barmm.gov.ph', 'images/barmm_dev.jpg'),

('Sales Associate', 'Puregold Price Club', 'Private', NULL,
 'Cotabato City, Maguindanao', 'BARMM', 20, '₱12,000 - ₱16,000',
 'Assist customers, manage sales transactions, and maintain product displays.',
 'High school graduate; Customer service oriented.',
 'Full-Time', '2025-10-30', 'jobs@puregold.com.ph', 'images/puregold_sales.jpg');