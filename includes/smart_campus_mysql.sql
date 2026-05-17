-- smart_campus MySQL Schema
-- Compatible with XAMPP / MySQL / MariaDB

CREATE DATABASE IF NOT EXISTS smart_campus;
USE smart_campus;

-- Table: person
CREATE TABLE IF NOT EXISTS person (
    person_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    password_hash VARCHAR(255),
    person_type VARCHAR(10) NOT NULL DEFAULT 'student',
    created_date DATE DEFAULT (CURDATE())
) ENGINE=InnoDB;

-- Table: student (subtype)
CREATE TABLE IF NOT EXISTS student (
    person_id INT PRIMARY KEY,
    student_number VARCHAR(20) NOT NULL UNIQUE,
    major VARCHAR(50) NOT NULL,
    enrollment_year INT,
    FOREIGN KEY (person_id) REFERENCES person(person_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table: employee (subtype)
CREATE TABLE IF NOT EXISTS employee (
    person_id INT PRIMARY KEY,
    hire_date DATE,
    employee_type VARCHAR(20) NOT NULL,
    FOREIGN KEY (person_id) REFERENCES person(person_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table: faculty
CREATE TABLE IF NOT EXISTS faculty (
    person_id INT PRIMARY KEY,
    academic_rank VARCHAR(30),
    department VARCHAR(50) NOT NULL,
    FOREIGN KEY (person_id) REFERENCES employee(person_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table: administrator
CREATE TABLE IF NOT EXISTS administrator (
    person_id INT PRIMARY KEY,
    admin_role VARCHAR(30) NOT NULL,
    department VARCHAR(50) NOT NULL,
    FOREIGN KEY (person_id) REFERENCES employee(person_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table: director
CREATE TABLE IF NOT EXISTS director (
    person_id INT PRIMARY KEY,
    title VARCHAR(50) NOT NULL,
    area_of_responsibility VARCHAR(100) NOT NULL,
    appointment_start_date DATE NOT NULL,
    FOREIGN KEY (person_id) REFERENCES employee(person_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table: service_category
CREATE TABLE IF NOT EXISTS service_category (
    cat_id INT AUTO_INCREMENT PRIMARY KEY,
    cat_name VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(200)
) ENGINE=InnoDB;

-- Table: service
CREATE TABLE IF NOT EXISTS service (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    description VARCHAR(200),
    cat_id INT NOT NULL,
    FOREIGN KEY (cat_id) REFERENCES service_category(cat_id)
) ENGINE=InnoDB;

-- Table: request
CREATE TABLE IF NOT EXISTS request (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description VARCHAR(500) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'Pending',
    created_date DATE NOT NULL DEFAULT (CURDATE()),
    resolution_date DATE,
    priority VARCHAR(10) DEFAULT 'Normal',
    requester_person_id INT NOT NULL,
    assignee_person_id INT,
    service_id INT NOT NULL,
    FOREIGN KEY (requester_person_id) REFERENCES person(person_id),
    FOREIGN KEY (assignee_person_id) REFERENCES person(person_id),
    FOREIGN KEY (service_id) REFERENCES service(service_id)
) ENGINE=InnoDB;

-- Table: appointment
CREATE TABLE IF NOT EXISTS appointment (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(100),
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    location VARCHAR(100) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'Scheduled',
    organizer_person_id INT NOT NULL,
    attendee_person_id INT NOT NULL,
    request_id INT NOT NULL,
    FOREIGN KEY (organizer_person_id) REFERENCES person(person_id),
    FOREIGN KEY (attendee_person_id) REFERENCES person(person_id),
    FOREIGN KEY (request_id) REFERENCES request(request_id)
) ENGINE=InnoDB;

-- Table: message
CREATE TABLE IF NOT EXISTS message (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(200),
    body TEXT NOT NULL,
    sent_datetime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    read_status VARCHAR(10) NOT NULL DEFAULT 'unread',
    sender_person_id INT NOT NULL,
    receiver_person_id INT NOT NULL,
    request_id INT,
    FOREIGN KEY (sender_person_id) REFERENCES person(person_id),
    FOREIGN KEY (receiver_person_id) REFERENCES person(person_id),
    FOREIGN KEY (request_id) REFERENCES request(request_id)
) ENGINE=InnoDB;

-- Table: feedback
CREATE TABLE IF NOT EXISTS feedback (
    feedback_id INT AUTO_INCREMENT PRIMARY KEY,
    rating TINYINT NOT NULL,
    comments VARCHAR(500) NOT NULL,
    submitted_date DATE NOT NULL DEFAULT (CURDATE()),
    request_id INT NOT NULL,
    author_person_id INT NOT NULL,
    FOREIGN KEY (request_id) REFERENCES request(request_id),
    FOREIGN KEY (author_person_id) REFERENCES person(person_id)
) ENGINE=InnoDB;

-- ============================================================
-- Seed Data
-- ============================================================

-- Service categories
INSERT IGNORE INTO service_category (cat_name, description) VALUES
('IT', 'Information Technology services'),
('Administrative', 'Administrative and registration services'),
('Academic', 'Academic and faculty services');

-- Services
INSERT IGNORE INTO service (service_name, description, cat_id) VALUES
('IT Support', 'Technical help and network issues', 1),
('Transcript Request', 'Official academic records', 2),
('Course Registration', 'Enroll or drop courses', 3),
('Library Access', 'Library card and resource access', 2),
('WiFi Access', 'Campus WiFi credentials', 1);

-- Persons (password_hash is bcrypt for "Password123")
INSERT IGNORE INTO person (first_name, last_name, email, phone, password_hash, person_type, created_date) VALUES
('Hachim',  'Abakar',  'hachim@campus.edu',  '0555111', '$2y$10$examplehashstudent1xxxxx', 'student',  '2022-09-01'),
('Saleh',   'Bakri',   'saleh@campus.edu',   '0555222', '$2y$10$examplehashstudent2xxxxx', 'student',  '2022-09-01'),
('Ahmet',   'Yilmaz',  'ahmet@campus.edu',   '0555333', '$2y$10$examplehashemployeexxxx', 'employee', '2020-01-01');

-- Students
INSERT IGNORE INTO student (person_id, student_number, major, enrollment_year) VALUES
(1, '2022001', 'Computer Science',     2022),
(2, '2022002', 'Information Technology', 2022);

-- Employee
INSERT IGNORE INTO employee (person_id, hire_date, employee_type) VALUES
(3, '2020-01-01', 'FACULTY');

-- Faculty
INSERT IGNORE INTO faculty (person_id, academic_rank, department) VALUES
(3, 'Professor', 'IT Department');

-- Sample request
INSERT IGNORE INTO request (title, description, status, created_date, priority, requester_person_id, service_id) VALUES
('Need Transcript', 'Please provide official transcript for graduation application.', 'Pending',  CURDATE(), 'High',   1, 2),
('WiFi Issue',      'Cannot connect to campus WiFi in Building B.',                   'Completed', CURDATE(), 'Normal', 2, 1);
